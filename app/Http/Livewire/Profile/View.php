<?php

namespace App\Http\Livewire\Profile;

use App\Models\Card;
use App\Models\Platform;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class View extends Component
{
    public $identifier = null;

    public $redicretTo = null;
    public $profile, $platforms = [], $profileCheck = false;

    public $name, $email, $phone;

    public $latitude;
    public $longitude;

    public function mount()
    {
        $this->identifier = request()->username;
    }

    protected $listeners = ['setLocation'];

    public function setLocation($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getProfileData()
    {
        $this->profile = Profile::select(
            'id',
            'username',
            'type',
            'job_title',
            'work_position',
            'bio',
            'company',
            'photo',
            'cover_photo',
            'user_direct',
            'user_id',
            'enterprise_id',
            'is_leads_enabled',
            'private'
        )
            ->where('username', $this->identifier)
            ->first();
        if (!$this->profile) {
            return abort(404);
        }
        if ($this->profile->user_id != null) {
            User::where('id', $this->profile->user_id)->increment('tiks');
        }
        Profile::where('id', $this->profile->id)->increment('tiks');


        if ($this->profile->user_direct) {
            $this->isProfileDirect();
        }

        $platforms = DB::table('profile_platforms')
            ->select(
                'platforms.id as platform_id',
                'platforms.title',
                'platforms.icon',
                'platforms.input',
                'platforms.baseUrl as base_url',
                'profile_platforms.user_id as user_id',
                'profile_platforms.created_at',
                'profile_platforms.path',
                'profile_platforms.label',
                'profile_platforms.platform_order',
                'profile_platforms.direct',
            )
            ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
            ->join('profiles', 'profile_platforms.profile_id', 'profiles.id')
            ->where('profile_id', $this->profile->id)
            ->orderBy('profile_platforms.platform_order')
            ->get();

        $this->platforms = $platforms->chunk(4);
    }

    public function isProfileDirect()
    {
        $userPlatform = DB::table('profile_platforms')
            ->where('profile_id', $this->profile->id)
            ->orderBy('platform_order')
            ->first();

        if ($userPlatform) {
            $platform = Platform::where('id', $userPlatform->platform_id)->first();
            if ($platform) {
                DB::table('profile_platforms')
                    ->where('profile_id', $this->profile->id)
                    ->where('platform_id', $userPlatform->platform_id)
                    ->increment('clicks');
                if (!str_contains($userPlatform->path, 'https') && !str_contains($userPlatform->path, 'http')) {
                    $this->redicretTo = 'https://' . $userPlatform->path;
                } else {
                    $this->redicretTo = $userPlatform->path;
                }

                if ($platform->baseURL) {
                    $this->redicretTo = $platform->baseURL . '/' . $userPlatform->path;
                }
            }
        }
    }


    public function increment($platformId, $url)
    {
        // dd([$platformId, $url]);
        if (!$this->profile->private) {
            DB::table('profile_platforms')
                ->where('profile_id', $this->profile->id)
                ->where('platform_id', $platformId)
                ->increment('clicks');

            // Log the URL to verify it
            // Log::info('Redirecting to: ' . $url);
            $this->dispatchBrowserEvent('redirect', [
                'url' => $url
            ]);
        }
    }

    public function viewerDetail()
    {
        $ip = request()->ip();
        $locationData = $this->getUserLocation($ip);
        if ($locationData) {
            $country = $locationData['geoplugin_countryName'];
            $ip_address = $locationData['geoplugin_request'];
            $state = $locationData['geoplugin_region'];
            $city = $locationData['geoplugin_city'];
            $lat = $this->latitude;
            $long = $this->longitude;
        } else {
            $country = null;
            $ip_address = null;
            $state = null;
            $city = null;
            $lat = null;
            $long = null;
        }
        $data = ['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone];
        DB::table('leads')->insert([
            'enterprise_id' => $this->profile->enterprise_id,
            'employee_id' => $this->profile->user_id,
            'viewing_id' => $this->profile->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'ip_address' => $ip_address,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'latitude' => $lat,
            'longitude' => $long,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->dispatchBrowserEvent('closeModal');

    }

    private function getUserLocation($ip = null)
    {
        $client = new Client();

        try {
            // Make the API call to GeoPlugin
            $response = $client->get("http://www.geoplugin.net/json.gp?ip={$ip}");

            // Decode the JSON response
            $locationData = json_decode($response->getBody(), true);

            if (isset($locationData['geoplugin_countryName'])) {
                return $locationData; // Return the array data directly
            } else {
                return 0;
            }

        } catch (RequestException $e) {
            return 0;
        }
    }

    public function userdetail()
    {
        $user = auth()->user();
        if ($user) {
            $this->profileCheck = Profile::where('user_id', $user->id)->orwhere('enterprise_id', $user->id)->where('id', $this->profile->id)->exists();
        }
        return $this->profile;
    }



    public function render()
    {
        $this->getProfileData();
        $this->userdetail();
        return view('livewire.profile.view', [
            'profile' => $this->profile,
            'platforms' => $this->platforms,
            'profilecheck' => $this->profileCheck,
        ]);
    }
}