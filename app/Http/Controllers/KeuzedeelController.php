<?php

namespace App\Http\Controllers;

use App\Models\Keuzedeel;
use App\Models\Inschrijving;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class KeuzedeelController extends Controller
{

    // Show all available keuzedelen
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            $keuzedelen = Keuzedeel::with('actieveInschrijvingen')->get();
        } else {
            $opleidingId = $user->opleiding_id ?? null;
            $keuzedelen = Keuzedeel::with('actieveInschrijvingen')
                ->when($opleidingId, function ($q) use ($opleidingId) {
                    $q->whereHas('opleidingen', function ($q2) use ($opleidingId) {
                        $q2->where('opleidingen.id', $opleidingId);
                    });
                })
                ->get();
        }

        return view('keuzedelen.index', compact('keuzedelen', 'user'));
    }

    // Show details of a keuzedeel
    public function show(Keuzedeel $keuzedeel)
    {
        $keuzedeel->load('actieveInschrijvingen.user');
        return view('keuzedelen.show', compact('keuzedeel'));
    }

    // Register for a keuzedeel
    public function inschrijven(Request $request, Keuzedeel $keuzedeel)
    {
        $user = $this->getAuthenticatedUser();

        if (!$keuzedeel->is_active) {
            return back()->with('error', 'This keuzedeel is currently not available for registration.');
        }
        // Validation 1: Is keuzedeel full?
        if ($keuzedeel->isVol()) {
            return back()->with('error', 'This keuzedeel is unfortunately full.');
        }

        // Validation 2: Already completed?
        if ($user->heeftKeuzedeelAfgerond($keuzedeel->id)) {
            return back()->with('error', 'You have already completed this keuzedeel.');
        }

        // Validation 3: Already have a keuzedeel in this period?
        if ($user->heeftInschrijvingVoorPeriode($keuzedeel->periode)) {
            return back()->with('error', 'You have already chosen a keuzedeel for this period.');
        }

        // Validation 4: Check for any existing registration (regardless of status)
        $bestaandeInschrijving = Inschrijving::where('user_id', $user->id)
            ->where('keuzedeel_id', $keuzedeel->id)
            ->first();

        if ($bestaandeInschrijving) {
            if ($bestaandeInschrijving->status === 'ingeschreven') {
                return back()->with('error', 'You are already registered for this keuzedeel.');
            }

            // Re-activate previous registration
            $bestaandeInschrijving->update(['status' => 'ingeschreven']);

            return back()->with('success', 'You have been successfully registered!');
        }

        // Try to create a new registration, handle race-condition unique constraint
        try {
            Inschrijving::create([
                'user_id' => $user->id,
                'keuzedeel_id' => $keuzedeel->id,
                'status' => 'ingeschreven'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // If unique constraint violation occurred, attempt to update existing record
            if (str_contains($e->getMessage(), 'UNIQUE') || $e->getCode() === '23000') {
                $ins = Inschrijving::where('user_id', $user->id)->where('keuzedeel_id', $keuzedeel->id)->first();
                if ($ins && $ins->status !== 'ingeschreven') {
                    $ins->update(['status' => 'ingeschreven']);
                    return back()->with('success', 'You have been successfully registered!');
                }
                return back()->with('error', 'You are already registered for this keuzedeel.');
            }

            throw $e;
        }

        return back()->with('success', 'You have been successfully registered!');
    }

    // Unregister
    public function uitschrijven(Keuzedeel $keuzedeel)
    {
        $user = Auth::user();

        $inschrijving = Inschrijving::where('user_id', $user->id)
            ->where('keuzedeel_id', $keuzedeel->id)
            ->where('status', 'ingeschreven')
            ->first();

        if ($inschrijving) {
            $inschrijving->update(['status' => 'geannuleerd']);

            // Notify the user they've been removed
            $user = $inschrijving->user;
            $user->notify(new \App\Notifications\StudentRemovedNotification($keuzedeel->naam, 1, $keuzedeel->id));

            return back()->with('success', 'You have been unregistered.');
        }

        return back()->with('error', 'No active registration found.');
    }

    // My registrations
    public function mijnInschrijvingen()
    {
        $user = $this->getAuthenticatedUser();
        $inschrijvingen = $user->inschrijvingen()
            ->with('keuzedeel')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('keuzedelen.mijn-inschrijvingen', compact('inschrijvingen'));
    }

    // Create a new keuzedeel (admin only)
    public function create()
    {
        return view('keuzedelen.create');
    }

    // Store a new keuzedeel (admin only)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'naam' => 'required|string|max:255',
            'beschrijving' => 'required|string',
            'min_deelnemers' => 'required|integer|min:1',
            'max_deelnemers' => 'required|integer|gte:min_deelnemers',
            'periode' => 'required|string|max:50',
            'allow_multiple' => 'sometimes|boolean',
        ]);

        // Ensure boolean value is set when checkbox is absent
        $validated['allow_multiple'] = $request->has('allow_multiple') ? (bool) $request->input('allow_multiple') : false;

        Keuzedeel::create($validated);

        return redirect()->route('keuzedelen.index')->with('success', 'Keuzedeel succesvol toegevoegd!');
    }

    // Edit a keuzedeel (admin only)
    public function edit(Keuzedeel $keuzedeel)
    {
        return view('keuzedelen.edit', compact('keuzedeel'));
    }

    // Update a keuzedeel (admin only)
    public function update(Request $request, Keuzedeel $keuzedeel)
    {
        $validated = $request->validate([
            'naam' => 'required|string|max:255',
            'beschrijving' => 'required|string',
            'min_deelnemers' => 'required|integer|min:1',
            'max_deelnemers' => 'required|integer|gte:min_deelnemers',
            'periode' => 'required|string|max:50',
            'allow_multiple' => 'sometimes|boolean',
        ]);

        // Normalize checkbox boolean
        $validated['allow_multiple'] = $request->has('allow_multiple') ? (bool) $request->input('allow_multiple') : false;

        $original = $keuzedeel->getOriginal();
        $keuzedeel->update($validated);

        // Determine simple changes for notification
        $changes = array_diff_assoc($keuzedeel->getAttributes(), $original);
        $users = \App\Models\User::whereHas('inschrijvingen', function ($q) use ($keuzedeel) {
            $q->where('keuzedeel_id', $keuzedeel->id)->where('status', 'ingeschreven');
        })->get();

        if ($users->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\KeuzedeelUpdatedNotification($keuzedeel, $changes));
        }

        return redirect()->route('keuzedelen.show', $keuzedeel)->with('success', 'Keuzedeel succesvol bijgewerkt!');
    }

    // Delete a keuzedeel (admin only)
    public function destroy(Keuzedeel $keuzedeel)
    {
        $keuzedeel->delete();
        return redirect()->route('keuzedelen.index')->with('success', 'Keuzedeel succesvol verwijderd!');
    }

    // Remove student from keuzedeel (admin only)
    public function verwijderLeerling($inschrijvingId)
    {
        $inschrijving = Inschrijving::find($inschrijvingId);

        if (! $inschrijving) {
            return back()->with('error', 'Inschrijving niet gevonden.');
        }

        $keuzedeel = $inschrijving->keuzedeel;
        $user = $inschrijving->user;
        $inschrijving->delete();

        // Notify the user
        $user->notify(new \App\Notifications\StudentRemovedNotification($keuzedeel->naam, 1, $keuzedeel->id));

        return back()->with('success', 'Leerling succesvol verwijderd!');
    }

    // Remove ALL inscriptions of a specific user for a keuzedeel (admin only)
    public function verwijderLeerlingVoorGebruiker(Keuzedeel $keuzedeel, User $user)
    {
        $count = Inschrijving::where('keuzedeel_id', $keuzedeel->id)
            ->where('user_id', $user->id)
            ->where('status', 'ingeschreven')
            ->count();

        Inschrijving::where('keuzedeel_id', $keuzedeel->id)
            ->where('user_id', $user->id)
            ->where('status', 'ingeschreven')
            ->delete();

        // Notify the user about removal
        $user->notify(new \App\Notifications\StudentRemovedNotification($keuzedeel->naam, $count ?: 0, $keuzedeel->id));

        // Check low enrollment for admins
        $this->checkLowEnrollment($keuzedeel);

        return back()->with('success', 'Alle inschrijvingen van leerling verwijderd!');
    }

    // Toggle active status of keuzedeel (admin only)
    public function toggleActive(Keuzedeel $keuzedeel)
    {
        $keuzedeel->update(['is_active' => !$keuzedeel->is_active]);
        $status = $keuzedeel->is_active ? 'geactiveerd' : 'gedeactiveerd';

        $users = \App\Models\User::whereHas('inschrijvingen', function ($q) use ($keuzedeel) {
            $q->where('keuzedeel_id', $keuzedeel->id)->where('status', 'ingeschreven');
        })->get();

        if ($users->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\KeuzedeelStatusChangedNotification($keuzedeel));
        }

        return back()->with('success', "Keuzedeel succesvol $status!");
    }

    private function getAuthenticatedUser(): User
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            throw new \RuntimeException('Authenticated user is not an instance of User model.');
        }
        return $user;
    }
}
