<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin SIPUS',
            'email' => 'admin@sipus.com',
            'password' => Hash::make('password'),
            'instansi' => 'SIPUS Administrator',
            'role' => 'admin',
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}
