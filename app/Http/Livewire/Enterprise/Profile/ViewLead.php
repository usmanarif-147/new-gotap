<?php

namespace App\Http\Livewire\Enterprise\Profile;

use DB;
use Livewire\Component;

class ViewLead extends Component
{
    public $leadId;

    public function getLead($id)
    {
        $lead = DB::table('leads')->find($id);
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
