<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Seed contoh pelanggan laundry.
     */
    public function run(): void
    {
        $user = User::where('email', 'user@laundry.test')->first();

        $customers = [
            [
                'name' => 'Andi Pratama',
                'user_id' => $user?->id,
                'phone' => '081234567890',
                'address' => 'Jl. Melati No. 12',
                'gender' => Customer::GENDER_MALE,
                'notes' => 'Pelanggan reguler, preferensi parfum lembut.',
            ],
            [
                'name' => 'Siti Aminah',
                'user_id' => null,
                'phone' => '082112223333',
                'address' => 'Jl. Kenanga No. 5',
                'gender' => Customer::GENDER_FEMALE,
                'notes' => 'Sering menggunakan layanan express.',
            ],
            [
                'name' => 'Budi Santoso',
                'user_id' => null,
                'phone' => '085677889900',
                'address' => 'Perumahan Harmoni Blok C3',
                'gender' => Customer::GENDER_MALE,
                'notes' => null,
            ],
            [
                'name' => 'Rina Lestari',
                'user_id' => null,
                'phone' => null,
                'address' => 'Jl. Anggrek No. 18',
                'gender' => Customer::GENDER_FEMALE,
                'notes' => 'Minta pakaian dipisah warna terang dan gelap.',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['name' => $customer['name'], 'phone' => $customer['phone']],
                [
                    'user_id' => $customer['user_id'],
                    'address' => $customer['address'],
                    'gender' => $customer['gender'],
                    'notes' => $customer['notes'],
                ]
            );
        }
    }
}
