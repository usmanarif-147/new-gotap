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
        // Assuming profiles have a relation with platforms
        $profiles = Profile::with('platforms')->where('enterprise_id', Auth::user()->id)->get();
        $this->chartData = $this->prepareChartData($profiles);
        $this->totalProfiles = Profile::where('enterprise_id', Auth::user()->id)->count();
        $this->activeCards = Profile::join('profile_cards', 'profiles.id', 'profile_cards.profile_id')->count();
    }

    private function prepareChartData($profiles)
    {
        $data = [];

        foreach ($profiles as $profile) {
            $data['labels'][] = $profile->username; // or another unique identifier
            $data['data'][] = $profile->platforms->count(); // Number of attached platforms
        }

        return $data;
    }
    public function render()
    {
        return view('livewire.enterprise.dashboard');
    }
}