<?php

namespace Database\Seeders;

use App\Models\Elective;
use Illuminate\Database\Seeder;

class ElectiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electives = [
            [
                'name' => 'Software Development',
                'description' => 'Learn to develop software applications',
            ],
            [
                'name' => 'IT Skills',
                'description' => 'Develop essential IT skills',
            ],
            [
                'name' => 'Programmeren',
                'description' => 'Master programming fundamentals',
            ],
            [
                'name' => 'Ontwerpen',
                'description' => 'Learn design principles and practices',
            ],
        ];

        foreach ($electives as $elective) {
            Elective::create($elective);
        }
    }
}
