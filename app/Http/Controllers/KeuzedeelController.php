<?php

namespace App\Http\Controllers;

use App\Models\Keuzedeel;
use App\Models\Inschrijving;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuzedeelController extends Controller
{

    // Show all available keuzedelen
    public function index()
    {
        $keuzedelen = Keuzedeel::with('actieveInschrijvingen')->get();
        $user = Auth::user();

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

        // Validation 4: Already registered????
        $bestaandeInschrijving = Inschrijving::where('user_id', $user->id)
            ->where('keuzedeel_id', $keuzedeel->id)
            ->where('status', 'ingeschreven')
            ->first();

        if ($bestaandeInschrijving) {
            return back()->with('error', 'You are already registered for this keuzedeel.');
        }

        // Create registration
        Inschrijving::create([
            'user_id' => $user->id,
            'keuzedeel_id' => $keuzedeel->id,
            'status' => 'ingeschreven'
        ]);

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
        ]);

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
        ]);

        $keuzedeel->update($validated);

        return redirect()->route('keuzedelen.show', $keuzedeel)->with('success', 'Keuzedeel succesvol bijgewerkt!');
    }

    // Delete a keuzedeel (admin only)
    public function destroy(Keuzedeel $keuzedeel)
    {
        $keuzedeel->delete();
        return redirect()->route('keuzedelen.index')->with('success', 'Keuzedeel succesvol verwijderd!');
    }

    // Remove student from keuzedeel (admin only)
    public function verwijderLeerling(Inschrijving $inschrijving)
    {
        $keuzedeel = $inschrijving->keuzedeel;
        $inschrijving->delete();
        return back()->with('success', 'Leerling succesvol verwijderd!');
    }

    // Toggle active status of keuzedeel (admin only)
    public function toggleActive(Keuzedeel $keuzedeel)
    {
        $keuzedeel->update(['is_active' => !$keuzedeel->is_active]);
        $status = $keuzedeel->is_active ? 'geactiveerd' : 'gedeactiveerd';
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
