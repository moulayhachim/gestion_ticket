<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un administrateur
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Créer des agents de support
        User::create([
            'name' => 'Agent Support',
            'email' => 'agent@example.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);

        User::create([
            'name' => 'Agent Support 2',
            'email' => 'agent2@example.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);

        // Créer des utilisateurs réguliers
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Regular User 2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}