<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sipus.com',
            'password' => Hash::make('admin123'),
            'instansi' => 'SIPUS Administrator',
            'role' => 'admin',
            'status' => 'approved',
            'approved_at' => now(),
            'email_verified_at' => now(),
        ]);

        // Create Regular User (Approved)
        User::create([
            'name' => 'Akhyar Nasrullah Adilian',
            'email' => 'akhyar@example.com',
            'password' => Hash::make('user123'),
            'instansi' => 'Universitas Lampung',
            'role' => 'user',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => 1, // Approved by admin
            'email_verified_at' => now(),
        ]);

        // Create Pending User
        User::create([
            'name' => 'User Pending',
            'email' => 'pending@example.com',
            'password' => Hash::make('user123'),
            'instansi' => 'Instansi Testing',
            'role' => 'user',
            'status' => 'pending',
            'email_verified_at' => now(),
        ]);
    }
}
