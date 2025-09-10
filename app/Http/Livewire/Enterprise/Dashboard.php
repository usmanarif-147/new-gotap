<?php

namespace App\Http\Livewire\Enterprise;

use App\Models\CompaignEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $leadCaptured, $cardViews, $recentLeads, $compaigns;
    public $virtualBackgroundStats;

    public function mount()
    {
        $this->leadCaptured = DB::table('leads')
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->whereBetween('leads.created_at', [Carbon::now()->subDays(60), Carbon::now()])
            ->count();
        $this->cardViews = DB::table('leads')
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->where('leads.type', 1) // Filter by type = 1
            ->whereBetween('leads.created_at', [Carbon::now()->subDays(60), Carbon::now()]) // Last 60 days
            ->count();
        $this->recentLeads = DB::table('leads')
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->whereBetween('leads.created_at', [Carbon::now()->subDays(60), Carbon::now()])
            ->select('viewingProfile.username as viewing_name', 'viewerProfile.username as viewer_name', 'leads.created_at', 'leads.name')
            ->orderBy('leads.created_at', 'desc')
            ->take(5)
            ->get();
        $this->compaigns = CompaignEmail::withCount('reads')
            ->where('enterprise_id', auth()->id())
            ->whereBetween('created_at', [Carbon::now()->subDays(360), Carbon::now()])
            ->latest()
            ->take(5)
            ->get();

        // Virtual Background Statistics
        $this->virtualBackgroundStats = [
            'total_profiles_with_backgrounds' => DB::table('profiles')
                ->where('enterprise_id', auth()->id())
                ->whereNotNull('virtual_background')
                ->count(),
            'enabled_backgrounds' => DB::table('profiles')
                ->where('enterprise_id', auth()->id())
                ->where('virtual_background_enabled', 1)
                ->count(),
            'generated_backgrounds' => DB::table('profiles')
                ->where('enterprise_id', auth()->id())
                ->whereNotNull('virtual_background')
                ->count(),
            'recent_generations' => DB::table('profiles')
                ->where('enterprise_id', auth()->id())
                ->whereNotNull('virtual_background')
                ->where('updated_at', '>=', Carbon::now()->subDays(7))
                ->count()
        ];
    }

    public function render()
    {
        return view('livewire.enterprise.dashboard');
    }
}