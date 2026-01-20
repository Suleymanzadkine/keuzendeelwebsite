<?php

namespace App\Http\Controllers;

use App\Models\Elective;
use Illuminate\Http\Request;

class ElectiveController extends Controller
{
    /**
     * Show the electives selection page.
     */
    public function index()
    {
        $electives = Elective::all();
        $userElectives = auth()->user()->electives()->pluck('electives.id')->toArray();

        return view('electives.index', [
            'electives' => $electives,
            'userElectives' => $userElectives,
        ]);
    }

    /**
     * Store the selected electives.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'electives' => 'required|array|min:1',
            'electives.*' => 'required|exists:electives,id',
        ]);

        $user = auth()->user();
        
        // Sync the electives (this will add new ones and remove ones not selected)
        $user->electives()->sync($validated['electives']);

        return redirect()->route('dashboard')->with('success', 'Keuzedelen succesvol opgeslagen!');
    }
}
