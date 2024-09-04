<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 50; $i++) {

            $reason = null;
            $status = rand(0, 2);
            if ($status == 1) {
                $reason = 'Congratulation, Your Application Is Accepted';
            }
            if ($status == 2) {
                $reason = 'Sorry! Your Application Is Rejected';
            }

            Application::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'enterprise_type' => $faker->name,
                'reason' => $reason,
                'status' => $status,
            ]);
        }
    }
}
