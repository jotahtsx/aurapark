<?php

namespace Database\Seeders;

use App\Models\Pricing;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category'      => 'Moto',
                'hourly_price'  => 5.00,
                'monthly_price' => 80.00,
                'total_spots'   => 30,
                'is_active'     => true,
            ],
            [
                'category'      => 'Veículo Pequeno',
                'hourly_price'  => 10.00,
                'monthly_price' => 150.00,
                'total_spots'   => 30,
                'is_active'     => true,
            ],
            [
                'category'      => 'Veículo Médio',
                'hourly_price'  => 15.00,
                'monthly_price' => 220.00,
                'total_spots'   => 30,
                'is_active'     => true,
            ],
            [
                'category'      => 'Veículo Grande',
                'hourly_price'  => 20.00,
                'monthly_price' => 300.00,
                'total_spots'   => 30,
                'is_active'     => true,
            ],
        ];

        foreach ($categories as $category) {
            Pricing::create($category);
        }
    }
}
