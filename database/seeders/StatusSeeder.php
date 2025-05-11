<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Ouvert', 'color' => '#f97316'],
            ['name' => 'En cours', 'color' => '#3b82f6'],
            ['name' => 'Résolu', 'color' => '#22c55e'],
            ['name' => 'Fermé', 'color' => '#64748b'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}