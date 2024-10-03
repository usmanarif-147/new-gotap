<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use App\Models\Card;
use App\Models\Platform;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ViewProfileByCard extends Component
{
    public $identifier = null;

    public $redicretTo = null;
    public $profile, $platforms = [], $profileCheck = false;

    public $name, $email, $phone;

    public $showModal = 1;

    protected $listeners = ['submitForm'];

    public function mount()
    {
        $this->identifier = request()->segment(2);
    }

    public function getProfileData()
    {
        $this->profile = Card::join('profile_cards', 'cards.id', '=', 'profile_cards.card_id')
            ->join('profiles', 'profiles.id', '=', 'profile_cards.profile_id')
            ->where('cards.uuid', $this->identifier)
            ->select('profiles.*', 'profile_cards.status as card_status')
            ->first();
        if (!$this->profile || !$this->profile->card_status) {
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
        if (!$this->profile->private) {
            DB::table('profile_platforms')
                ->where('profile_id', $this->profile->id)
                ->where('platform_id', $platformId)
                ->increment('clicks');

            // Log the URL to verify it
            Log::info('Redirecting to: ' . $url);

            $this->dispatchBrowserEvent('redirect', [
                'url' => $url
            ]);
        }
    }

    public function submitForm()
    {
        $this->profile = Card::join('profile_cards', 'cards.id', '=', 'profile_cards.card_id')
            ->join('profiles', 'profiles.id', '=', 'profile_cards.profile_id')
            ->where('cards.uuid', $this->identifier)
            ->select('profiles.*', 'profile_cards.status as card_status', 'profile_cards.card_id as card_id')
            ->first();
        $data = ['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone];
        DB::table('leads')->insert([
            'enterprise_id' => $this->profile->enterprise_id,
            'employee_id' => $this->profile->user_id,
            'viewing_id' => $this->profile->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->modalShow = 0;
    }

    public function userdetail()
    {
        $user = auth()->user();
        if ($user) {
            $this->profileCheck = Profile::where('user_id', $user->id)->orwhere('enterprise_id', $user->id)->exists();
        }
        return $this->profile;
    }

    public function render()
    {
        $this->getProfileData();
        $this->userdetail();
        return view('livewire.profile.view-profile-by-card', [
            'profile' => $this->profile,
            'platforms' => $this->platforms,
            'profilecheck' => $this->profileCheck,
        ]);
    }
}
