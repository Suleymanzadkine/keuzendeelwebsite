<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        $user = Role::firstOrCreate(['name' => 'user'], ['display_name' => 'User']);

        // Assign admin role to existing users with is_admin flag
        User::where('is_admin', 1)->get()->each(function ($u) use ($admin) {
            $u->assignRole($admin);
        });
    }
}
