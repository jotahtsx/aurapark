<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Sexta',
            'last_name' => 'Feira',
            'email' => 'friday@aurapark.com',
            'status' => 'active',
            'password' => Hash::make('151515'),
        ]);

        // Configurando diferentes localizações para o Faker
        $locales = [
            'pt_BR',
            'en_US',
            'es_MX',
        ];

        foreach ($locales as $locale) {
            // Instancia o Faker com a localização atual
            $faker = \Faker\Factory::create($locale);

            for ($i = 0; $i < 5; $i++) {
                // Apenas 50% dos usuários fictícios estarão 'active'
                $status = $faker->boolean(50) ? 'active' : 'inactive'; 

                User::create([
                    'name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'email' => $faker->unique()->safeEmail,
                    'status' => $status,
                    'password' => Hash::make('password'), 
                ]);
            }
        }
    }
}