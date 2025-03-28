<?php

namespace App\Http\Livewire\Enterprise\Profile;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ProfileLeads extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $profile_id;

    public $tab_change;

    public $search = '';

    public $total;

    public function mount($id, $tab)
    {
        $this->profile_id = $id;
        $this->tab_change = $tab;
    }

    public function getProfileLeads()
    {
        $filteredData = DB::table('leads')->select(
            'leads.id',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.note',
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
                $query->whereNull('leads.viewer_id')
                    ->orWhere('viewingProfile.enterprise_id', auth()->id());
            })
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->where('leads.viewing_id', $this->profile_id)
            ->orderBy('leads.created_at', 'desc');


        return $filteredData;
    }
    public function render()
    {
        $data = $this->getProfileLeads();
        $leads = $data->paginate(10);
        $this->total = $leads->total();
        return view('livewire.enterprise.profile.profile-leads', [
            'leads' => $leads,
        ]);
    }
}
