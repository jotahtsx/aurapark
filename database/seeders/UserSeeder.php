<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário fixo
        User::create([
            'name' => 'Sexta',
            'last_name' => 'Feira',
            'email' => 'friday@aurapark.com',
            'email_verified_at' => now(),
            'status' => 'active',
            'password' => Hash::make('151515'),
        ]);

        $locales = ['pt_BR', 'en_US', 'es_MX'];

        foreach ($locales as $locale) {
            $faker = FakerFactory::create($locale);

            for ($i = 0; $i < 5; $i++) {
                User::factory()->create([
                    'name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'email' => $faker->unique()->safeEmail(),
                    'status' => $faker->boolean(50) ? 'active' : 'inactive',
                ]);
            }
        }
    }
}
