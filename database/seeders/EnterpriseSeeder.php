<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class EnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'email' => $faker->email,
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'enterpirse_type' => 'corp',
                'photo' => null,
                'role' => 'enterpriser',
                'password' => bcrypt('11223344'),
            ]);
        }
    }
}
