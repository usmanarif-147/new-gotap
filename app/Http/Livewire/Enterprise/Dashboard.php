<?php

namespace App\Http\Livewire\Enterprise;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $leadCaptured, $cardViews, $recentLeads;

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
            ->select('viewingProfile.username as viewing_name', 'leads.created_at')
            ->orderBy('leads.created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.enterprise.dashboard');
    }
}