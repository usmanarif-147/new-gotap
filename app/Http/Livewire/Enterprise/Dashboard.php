<?php

namespace App\Http\Livewire\Enterprise;

use DB;
use Livewire\Component;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Storage;

class Dashboard extends Component
{
    public $chartData, $totalProfiles, $activeCards, $leads;

    public function mount()
    {
        $profiles = Profile::where('enterprise_id', Auth::user()->id)->orderBy('tiks', 'DESC')->take(10)->get();
        $this->chartData = $this->prepareChartData($profiles);
        $this->totalProfiles = Profile::where('enterprise_id', Auth::user()->id)->count();
        $this->activeCards = Profile::join('profile_cards', 'profiles.id', 'profile_cards.profile_id')
            ->where('profiles.enterprise_id', Auth::user()->id)
            ->count();
        $this->leads = DB::table('leads')
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->count();
    }

    private function prepareChartData($profiles)
    {
        $fallbackImageUrl = asset('avatar.png');

        $data = [
            'labels' => [],
            'photos' => [],
            'data' => []
        ];

        foreach ($profiles as $profile) {
            $data['labels'][] = $profile->username;
            $data['photos'][] = $profile->photo ? asset(Storage::url($profile->photo)) : $fallbackImageUrl; // Fallback if no photo
            $data['data'][] = $profile->tiks;
        }
        return $data;
    }
    public function render()
    {
        return view('livewire.enterprise.dashboard');
    }
}