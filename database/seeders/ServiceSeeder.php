<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Seed contoh layanan laundry.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cuci Kering',
                'description' => 'Layanan cuci tanpa setrika, cocok untuk pakaian sehari-hari.',
                'price_per_kg' => 8000,
                'estimated_days' => 2,
            ],
            [
                'name' => 'Cuci Setrika',
                'description' => 'Layanan cuci lengkap dengan setrika rapi.',
                'price_per_kg' => 12000,
                'estimated_days' => 3,
            ],
            [
                'name' => 'Setrika Saja',
                'description' => 'Layanan setrika pakaian yang sudah dicuci sendiri.',
                'price_per_kg' => 5000,
                'estimated_days' => 1,
            ],
            [
                'name' => 'Laundry Express',
                'description' => 'Layanan cepat selesai dalam 1 hari.',
                'price_per_kg' => 18000,
                'estimated_days' => 1,
            ],
            [
                'name' => 'Laundry Sepatu',
                'description' => 'Perawatan dan pencucian sepatu.',
                'price_per_kg' => 25000,
                'estimated_days' => 3,
            ],
            [
                'name' => 'Laundry Bedcover',
                'description' => 'Cuci bedcover, sprei, dan linen besar.',
                'price_per_kg' => 15000,
                'estimated_days' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                [
                    'description' => $service['description'],
                    'price_per_kg' => $service['price_per_kg'],
                    'estimated_days' => $service['estimated_days'],
                    'is_active' => true,
                ]
            );
        }
    }
}
