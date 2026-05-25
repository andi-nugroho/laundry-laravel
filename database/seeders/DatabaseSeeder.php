<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@laundry.test',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Kasir',
                'email' => 'kasir@laundry.test',
                'role' => User::ROLE_KASIR,
            ],
            [
                'name' => 'User',
                'email' => 'user@laundry.test',
                'role' => User::ROLE_USER,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => $user['role'],
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->call(ServiceSeeder::class);
    }
}
