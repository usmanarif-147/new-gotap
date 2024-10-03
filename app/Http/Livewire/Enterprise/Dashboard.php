<?php

namespace App\Http\Livewire\Enterprise;

use Livewire\Component;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $chartData, $totalProfiles, $activeCards;

    public function mount()
    {
        $profiles = Profile::where('enterprise_id', Auth::user()->id)->orderBy('tiks', 'DESC')->take(10)->get();
        $this->chartData = $this->prepareChartData($profiles);
        $this->totalProfiles = Profile::where('enterprise_id', Auth::user()->id)->count();
        $this->activeCards = Profile::join('profile_cards', 'profiles.id', 'profile_cards.profile_id')
            ->where('profiles.enterprise_id', Auth::user()->id)
            ->count();
    }

    private function prepareChartData($profiles)
    {
        $data = [];

        foreach ($profiles as $profile) {
            $data['labels'][] = $profile->username;
            $data['data'][] = $profile->tiks;
        }
        return $data;
    }
    public function render()
    {
        return view('livewire.enterprise.dashboard');
    }
}