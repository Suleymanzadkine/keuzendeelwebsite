<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List users for admin
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    // Edit user roles
    public function editRoles(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit-roles', compact('user', 'roles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->roles()->sync($validated['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Rollen bijgewerkt.');
    }
}
