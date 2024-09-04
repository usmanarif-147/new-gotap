<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 25; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('11223344'),
                'role' => 'user'
            ]);

            Profile::create([
                'user_id' => $user->id,
                'username' => $faker->username,
                'phone' => $faker->phoneNumber,
                'type' => 'social',
                'is_default' => 1,
                'active' => 1,
                'private' => 0
            ]);

            Group::create([
                'user_id' => $user->id,
                'title' => 'favourites',
            ]);

            Group::create([
                'user_id' => $user->id,
                'title' => 'scanned card',
            ]);
        }
    }
}
