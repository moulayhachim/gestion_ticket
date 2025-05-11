<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            ['name' => 'Urgent', 'color' => '#ef4444'],
            ['name' => 'Élevé', 'color' => '#f97316'],
            ['name' => 'Moyen', 'color' => '#eab308'],
            ['name' => 'Faible', 'color' => '#22c55e'],
        ];

        foreach ($priorities as $priority) {
            Priority::create($priority);
        }
    }
}