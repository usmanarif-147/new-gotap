<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profiles = Profile::where('type', 'social')->pluck('id')->toArray();
        $users = User::where('role', 'user')->pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            $profildId = $profiles[array_rand($profiles)];
            $userId = $users[array_rand($users)];
            $profile = Profile::where('id', $profildId)->first();
            $user = User::where('id', $userId)->first();

            if ($profile->user_id != $user->id) {
                $isConnectionExist = DB::table('connects')
                    ->where('connected_id', $profile->id)
                    ->where('connecting_id', $user->id)
                    ->exists();
                if (!$isConnectionExist) {
                    DB::table('connects')->insert([
                        'connected_id' => $profile->id,
                        'connecting_id' => $user->id,
                        'created_at' => now()
                    ]);
                }
            }
        }
    }
}
