<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'visionmoderneconstructionsarl@gmail.com'],
            [
                'name' => 'Administrateur Principal',
                'password' => Hash::make('ChangeMoiRapidement123!'),
                'role' => 'admin',
                'actif' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}