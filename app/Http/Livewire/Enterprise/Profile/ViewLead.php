<?php

namespace App\Http\Livewire\Enterprise\Profile;

use DB;
use Livewire\Component;

class ViewLead extends Component
{
    public $leadId;

    public function getLead($id)
    {
        $lead = DB::table('leads')->select(
            'leads.id',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.note',
            'leads.country',
            'leads.state',
            'leads.city',
            'leads.viewer_id',
            'leads.created_at',
            DB::raw('COALESCE(viewerProfile.username, "No Viewer") as viewer_username'),
            DB::raw('COALESCE(viewerProfile.photo, "photo") as viewer_photo'),
        )
            // Join profiles on viewer_id
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->where(function ($query) {
                // If viewer_id is null, just return the data; else, return profile data
                $query->whereNull('leads.viewer_id');
            })
            ->where('leads.id', $id)
            ->orderBy('leads.created_at', 'desc')->first();


        return $lead;
    }

    public function render()
    {
        $this->leadId = request()->id;
        $lead = $this->getLead($this->leadId);
        return view('livewire.enterprise.profile.view-lead', [
            'lead' => $lead,
        ]);
    }
}
