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
        User::firstOrCreate(
            ['email' => 'admin@digitalxpress.com'],
            [
                'name' => 'Daniel Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Usuario Cliente de Prueba
        User::firstOrCreate(
            ['email' => 'cliente@digitalxpress.com'],
            [
                'name' => 'María García',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Usuario Técnico
        User::firstOrCreate(
            ['email' => 'tecnico@digitalxpress.com'],
            [
                'name' => 'Carlos Técnico',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Usuario VIP
        User::firstOrCreate(
            ['email' => 'vip@digitalxpress.com'],
            [
                'name' => 'Ana VIP',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
