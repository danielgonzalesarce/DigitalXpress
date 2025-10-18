<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'name' => 'Daniel Admin',
            'email' => 'admin@digitalxpress.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Usuario Cliente de Prueba
        User::create([
            'name' => 'María García',
            'email' => 'cliente@digitalxpress.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Usuario Técnico
        User::create([
            'name' => 'Carlos Técnico',
            'email' => 'tecnico@digitalxpress.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Usuario VIP
        User::create([
            'name' => 'Ana VIP',
            'email' => 'vip@digitalxpress.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
