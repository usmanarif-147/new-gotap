<?php

namespace Database\Seeders;

use App\Models\Platform;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class ProfilePlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $profiles = Profile::pluck('id')->toArray();
        $platforms = Platform::pluck('id')->toArray();
        for ($i = 0; $i < Profile::count(); $i++) {

            $profile = Profile::where('id', $profiles[array_rand($profiles)])->first();

            $counter = rand(1, 10);
            for ($j = 0; $j < $counter; $j++) {

                $platform_id = $platforms[array_rand($platforms)];
                $platformExists = DB::table('profile_platforms')
                    ->where('user_id', $profile->user_id)
                    ->where('profile_id', $profile->id)
                    ->where('platform_id', $platform_id)
                    ->exists();

                if (!$platformExists) {

                    DB::table('profile_platforms')->insert([
                        'user_id' => $profile->user_id,
                        'profile_id' => $profile->id,
                        'platform_id' => $platform_id,
                        'direct' => rand(0, 1),
                        'label' => $faker->name,
                        'path' => $faker->url,
                        'platform_order' => 0,
                        'created_at' => now()
                    ]);
                }
            }
        }

        foreach ($profiles as $profile) {

            $platform = DB::table('profile_platforms')
                ->where('profile_id', $profile)
                ->get()
                ->pluck('id')
                ->toArray();
            $j = 0;
            for ($i = 0; $i < count($platform); $i++) {
                $j = $j + 1;
                DB::table('profile_platforms')->where('id', $platform[$i])->update([
                    'platform_order' => $j
                ]);
            }
        }
    }
}
