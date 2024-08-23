<?php

namespace Database\Seeders;

use App\Models\Platform;
use App\Models\Profile;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $users = User::pluck('id')->toArray();
        $platforms = Platform::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            $profile = Profile::create([
                'user_id' => $users[array_rand($users)],
                'name' => $faker->name,
                'email' => $faker->email,
                'username' => $faker->username,
                'phone' => $faker->phoneNumber,
                'active' => 0,
                'private' => 0
            ]);
        }

        $profiles = Profile::pluck('id')->toArray();
        $counter = rand(1, 7);
        for ($i = 0; $i < $counter; $i++) {

            $profile = Profile::where('id', $profiles[array_rand($profiles)])->first();

            DB::table('user_platforms')->insert([
                'user_id' => $profile->user_id,
                'profile_id' => $profile->id,
                'platform_id' => $platforms[array_rand($platforms)],
                'direct' => rand(0, 1),
                'label' => $faker->name,
                'path' => $faker->url,
                'platform_order' => $i + 1,
                'created_at' => now()
            ]);
        }
    }
}
