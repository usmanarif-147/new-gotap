<?php

namespace App\Http\Livewire\Profile;

use App\Models\Card;
use App\Models\Platform;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class View extends Component
{
    public $identifier = null;

    public $redicretTo = null;
    public $profile, $platforms = [];

    public function mount()
    {
        $this->identifier = request()->segment(1) == 'card_id' ? 'uuid' : request()->username;
    }

    public function getProfileData()
    {

        if ($this->identifier == 'uuid') {
            $this->profile = Card::join('user_cards', 'cards.id', 'user_cards.card_id')
                ->join('profiles', 'profiles.id', 'user_cards.profile_id')
                ->where('cards.uuid', request()->segment(2))
                ->select('profiles.*', 'user_cards.status as card_status')
                ->first();

            if (!$this->profile || !$this->profile->card_status) {
                return abort(404);
            }
        } else {
            $this->profile = Profile::select(
                'id',
                'username',
                'job_title',
                'work_position',
                'bio',
                'company',
                'photo',
                'cover_photo',
                'private'
            )
                ->where('username', $this->identifier)
                ->first();

            if (!$this->profile) {
                return abort(404);
            }
        }

        User::where('id', $this->profile->user_id)->increment('tiks');
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
            ->orderBy(('profile_platforms.platform_order'))
            ->get();

        $this->platforms = $platforms->chunk(4);
    }

    public function isProfileDirect()
    {
        $userPlatform = DB::table('profile_platforms')
            ->where('profile_id', $this->profile->id)
            ->where('platform_order', 1)
            ->first();
        $platform = Platform::where('id', $userPlatform->platform_id)->first();

        if (!$platform->baseURL) {
            if (!str_contains($userPlatform->path, 'https') && !str_contains($userPlatform->path, 'http')) {
                $this->redicretTo = 'https://' . $userPlatform->path;
            }
        } else {
            $this->redicretTo = $platform->baseURL . '/' . $userPlatform->path;
        }
    }

    public function increment($platformId, $url)
    {
        if (!$this->profile->private) {

            DB::table('profile_platforms')
                ->where('profile_id', $this->profile->id)
                ->where('platform_id', $platformId)
                ->increment('clicks');

            $this->dispatchBrowserEvent('redirect', [
                'url' => $url
            ]);
        }
    }


    public function render()
    {
        $this->getProfileData();
        return view('livewire.profile.view', [
            'profile' => $this->profile,
            'platforms' => $this->platforms
        ]);
    }
}
