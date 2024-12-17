<?php

namespace App\Http\Livewire\Enterprise;

use Livewire\Component;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Insights extends Component
{
    public $viewsData, $totalProfiles, $activeCards, $leads, $leadsData, $leadsGraphData;
    public $chartType = 'views';
    public $isActive = 'views';

    public function mount()
    {
        $profiles = Profile::where('enterprise_id', Auth::user()->id)->orderBy('tiks', 'DESC')->take(10)->get();
        $this->viewsData = $this->prepareChartData($profiles);
        $this->dispatchBrowserEvent('update-chart', ['data' => $this->viewsData]);
        $this->totalProfiles = Profile::where('enterprise_id', Auth::user()->id)->count();
        $this->activeCards = Profile::join('profile_cards', 'profiles.id', 'profile_cards.profile_id')
            ->where('profiles.enterprise_id', Auth::user()->id)
            ->count();
        $this->leads = DB::table('leads')
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->count();
        $leads = DB::table('leads')
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->select('viewingProfile.id', 'viewingProfile.username', 'viewingProfile.photo', DB::raw('COUNT(leads.id) as lead_count'))
            ->groupBy('viewingProfile.id', 'viewingProfile.username', 'viewingProfile.photo')
            ->orderByDesc('lead_count')
            ->take(10)
            ->get();
        $this->leadsData = $this->prepareChartDataLeads($leads);
        $this->leadsGraphData = $this->getLeadsGraphData();
    }

    private function getLeadsGraphData()
    {
        $leadsGraphData = DB::table('leads')
            ->join('profiles', 'leads.viewing_id', '=', 'profiles.id')
            ->select(
                DB::raw('DATE(leads.created_at) as date'),
                DB::raw('COUNT(leads.id) as leads_count')
            )
            ->where('profiles.enterprise_id', auth()->id())
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return $leadsGraphData;
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
            $data['photos'][] = $profile->photo && file_exists(public_path('storage/' . $profile->photo)) ? asset(Storage::url($profile->photo)) : $fallbackImageUrl; // Fallback if no photo
            $data['data'][] = $profile->tiks;
        }
        return $data;
    }

    private function prepareChartDataLeads($leads)
    {
        $fallbackImageUrl = asset('avatar.png');

        $data = [
            'labels' => [],
            'photos' => [],
            'data' => []
        ];

        foreach ($leads as $lead) {
            $data['labels'][] = $lead->username;
            $data['photos'][] = $lead->photo && file_exists(public_path('storage/' . $lead->photo)) ? asset(Storage::url($lead->photo)) : $fallbackImageUrl; // Fallback if no photo
            $data['data'][] = $lead->lead_count;
        }
        return $data;
    }

    public function switchToViews()
    {
        $this->chartType = 'views';
        $this->isActive = 'views';
        $this->dispatchBrowserEvent('update-chart', ['data' => $this->viewsData, 'isActive' => $this->isActive]);
    }

    public function switchToLeads()
    {
        $this->chartType = 'leads';
        $this->isActive = 'leads';
        $this->dispatchBrowserEvent('update-chart', ['data' => $this->leadsData, 'isActive' => $this->isActive]);
    }

    public function getProfiles()
    {
        $profiles = DB::table('profiles')
            ->select(
                'profiles.id',
                'profiles.username',
                'profiles.tiks',
                'profiles.taps',
                'profiles.photo',
                DB::raw('COUNT(leads.id) as leads_count')
            )
            ->leftJoin('leads', 'leads.viewing_id', '=', 'profiles.id')
            ->where('profiles.enterprise_id', auth()->id())
            ->groupBy('profiles.id', 'profiles.username', 'profiles.photo', 'profiles.tiks', 'profiles.taps')
            ->having('leads_count', '>=', 0)
            ->orderBy('profiles.tiks', 'desc')->take(5)
            ->get();

        return $profiles;
    }

    public function render()
    {
        $data = $this->getProfiles();
        return view('livewire.enterprise.insights', [
            'profiles' => $data,
        ]);
    }
}
