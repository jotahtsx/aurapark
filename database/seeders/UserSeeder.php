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
        User::create([
            'name' => 'Sexta',
            'last_name' => 'Feira',
            'avatar' => 'https://i.pravatar.cc/150?u=friday@aurapark.com',
            'email' => 'friday@aurapark.com',
            'email_verified_at' => now(),
            'status' => 'active',
            'password' => Hash::make('151515'),
        ]);

        $locales = ['pt_BR', 'en_US', 'es_MX'];

        foreach ($locales as $locale) {
            $faker = FakerFactory::create($locale);

            for ($i = 0; $i < 5; $i++) {
                $email = $faker->unique()->safeEmail();

                User::create([
                    'name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'avatar' => "https://i.pravatar.cc/150?u={$email}",
                    'email' => $email,
                    'email_verified_at' => now(),
                    'status' => $faker->boolean(80) ? 'active' : 'inactive',
                    'password' => Hash::make('password'),
                ]);
            }
        }
    }
}
