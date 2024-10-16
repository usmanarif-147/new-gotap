<?php

namespace App\Http\Livewire\Enterprise\Profile;

use DB;
use Livewire\Component;
use Livewire\WithPagination;

class Leads extends Component
{
    use WithPagination;

    public $search = '';

    public $total;

    public function getData()
    {
        $filteredData = DB::table('leads')->select(
            'leads.id',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.viewing_id',
            'leads.viewer_id',
            'leads.created_at',
            DB::raw('COALESCE(viewingProfile.username, "No Viewing") as viewing_username'),
            DB::raw('COALESCE(viewingProfile.photo, "photo") as viewing_photo'),
            DB::raw('COALESCE(viewerProfile.username, "No Viewer") as viewer_username'),
            DB::raw('COALESCE(viewerProfile.photo, "photo") as viewer_photo'),
        )
            // Join profiles on viewing_id
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            // Join profiles on viewer_id
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('leads.name', 'like', "%$this->search%")
                        ->orWhere('leads.email', 'like', "%$this->search%")
                        ->orWhere('leads.phone', 'like', "%$this->search%")
                        ->orWhere('viewingProfile.username', 'like', "%$this->search%");
                });
            })
            ->where(function ($query) {
                // If viewer_id is null, just return the data; else, return profile data
                $query->whereNull('leads.viewer_id')
                    ->orWhere('viewingProfile.enterprise_id', auth()->id());
            })
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->orderBy('leads.created_at', 'desc');


        return $filteredData;
    }

    public function render()
    {
        $data = $this->getData();
        $leads = $data->paginate(10);

        $this->total = $leads->total();
        return view('livewire.enterprise.profile.leads', [
            'leads' => $leads,
        ]);
    }
}
