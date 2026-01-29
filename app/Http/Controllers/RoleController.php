<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'display_name' => 'nullable|string|max:255',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Rol aangemaakt.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'display_name' => 'nullable|string|max:255',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Rol bijgewerkt.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Rol verwijderd.');
    }
}
