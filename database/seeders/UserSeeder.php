<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buatkan seeder user

        User::insert([
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => bcrypt('admin'),
                'role' => 'admin',
                'status' => 'active',
                'avatar' => 'avatar.jpg',
                'letter_box' => null, // Harus ada agar jumlah kolom sama
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aldi',
                'username' => 'aldi',
                'password' => bcrypt('aldi'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => null,     // Harus ada
                'letter_box' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penghuni',
                'username' => 'Penghuni',
                'password' => bcrypt('penghuni'),
                'role' => 'user',
                'status' => 'active',
                'avatar' => null,     // Harus ada
                'letter_box' => null, // Harus ada
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
