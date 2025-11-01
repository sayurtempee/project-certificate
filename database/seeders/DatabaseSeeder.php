<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [
            [
                'name' => 'Admin Hilmi',
                'email' => 'gemoyy71jkt@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ],
            [
                'name' => 'Admin Zaenal',
                'email' => 'arifinaliza@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        ];

        foreach ($users as $data) {
            User::firstOrCreate([
                'email' => $data['email']
            ], $data);
        }
    }
}
