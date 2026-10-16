<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat akun Admin
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Membuat akun Kasir
        User::create([
            'name' => 'Kasir Satu',
            'email' => 'kasir@toko.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);
    }
}