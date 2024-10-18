<?php

namespace App\Http\Livewire\Enterprise\Profile;

use DB;
use Livewire\Component;

class LeadsMap extends Component
{
    public $leads;

    public function getData()
    {
        $this->leads = DB::table('leads')->select(
            'leads.id',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.viewing_id',
            'leads.viewer_id',
            'leads.created_at',
            'leads.latitude',
            'leads.longitude',
            'leads.city',
            'leads.country',
            'leads.state',
            DB::raw('COALESCE(viewingProfile.username, "No Viewing") as viewing_username'),
            DB::raw('COALESCE(viewingProfile.photo, "null") as viewing_photo'),
            DB::raw('COALESCE(viewerProfile.username, "No Viewer") as viewer_username'),
            DB::raw('COALESCE(viewerProfile.photo, "null") as viewer_photo'),
        )
            // Join profiles on viewing_id
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            // Join profiles on viewer_id
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->where(function ($query) {
                // If viewer_id is null, just return the data; else, return profile data
                $query->whereNull('leads.viewer_id')
                    ->orWhere('viewingProfile.enterprise_id', auth()->id());
            })
            ->distinct('leads.latitude', 'leads.longitude')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->orderBy('leads.created_at', 'desc')->get();
    }

    public function render()
    {
        $this->getData();
        // dd($this->leads);
        return view('livewire.enterprise.profile.leads-map');
    }
}
