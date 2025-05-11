<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Bug logiciel'],
            ['name' => 'Problème matériel'],
            ['name' => 'Demande d\'information'],
            ['name' => 'Assistance administrative'],
            ['name' => 'Autre'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}