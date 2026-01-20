<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'student',
                'display_name' => 'Student',
            ],
            [
                'name' => 'docent',
                'display_name' => 'Docent/SLB',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
