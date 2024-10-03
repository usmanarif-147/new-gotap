<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Models\Profile;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProfileAnalyticsChart extends Component
{
    public $profile_id;

    public $tab_change;

    public $days = 7;

    public $analyticsData;

    public function mount($id, $tab)
    {
        $this->profile_id = $id;
        $this->tab_change = $tab;
        $this->analyticsData = $this->getAnalyticsData($this->days);
    }

    public function updatedDays()
    {
        $this->analyticsData = $this->getAnalyticsData($this->days);
        $this->dispatchBrowserEvent('refreshChart', [
            'analyticsData' => $this->analyticsData,
            'days' => $this->days
        ]);
    }

    public function getAnalyticsData($days)
    {
        $profile = Profile::find($this->profile_id);

        // Calculate date range based on selected days
        $startDate = now()->subDays($days);

        // Fetch profile views and other data for the given date range
        $profileViews = $profile->tiks;

        $platforms = DB::table('profile_platforms')
            ->select(
                'platforms.id',
                'profile_platforms.label',
                'profile_platforms.clicks',
            )
            ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
            ->where('profile_id', $profile->id)
            ->where('profile_platforms.created_at', '>=', $startDate)
            ->orderBy('profile_platforms.platform_order')
            ->get();

        $analyticsData = [
            [
                'label' => trans('backend.profile_views'),
                'profileViews' => $profileViews,
            ],
            [
                'label' => trans('backend.platform_clicks'),
                'total_clicks' => $platforms->sum('clicks'),
            ],
            [
                'label' => trans('backend.platforms'),
                'total_platforms' => $platforms->count(),
            ],
            [
                'label' => trans('backend.groups'),
                'total_groups' => DB::table('user_groups')
                    ->where('profile_id', $profile->id)
                    ->where('created_at', '>=', $startDate)
                    ->count(),
            ]
        ];
        return $analyticsData;
    }

    public function render()
    {
        return view('livewire.enterprise.profile.profile-analytics-chart');
    }
}
