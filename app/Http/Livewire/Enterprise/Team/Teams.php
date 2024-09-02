<?php

namespace App\Http\Livewire\Enterprise\Team;

use App\Models\Profile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Teams extends Component
{

    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $profileId, $methodType, $modalTitle, $modalBody, $modalActionBtnColor, $modalActionBtnText;

    public $search = '', $filterByStatus, $filterByCategory, $sortBy;

    public $total, $heading, $statuses = [], $categories;

    public function updatedFilterByStatus()
    {
        $this->resetPage();
    }
    public function updatedFilterByCategory()
    {
        $this->resetPage();
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function getData()
    {
        $filteredData = Profile::select(
            'profiles.id',
            'profiles.name',
            'profiles.email',
            'profiles.username',
            'profiles.photo',
            'profiles.phone',
            // 'profiles.status',
        )
            // ->when($this->filterByStatus, function ($query) {
            //     if ($this->filterByStatus == 2) {
            //         $query->where('profiles.status', 0);
            //     }
            //     if ($this->filterByStatus == 1) {
            //         $query->where('profiles.status', 1);
            //     }
            // })
            ->when($this->sortBy, function ($query) {
                if ($this->sortBy == 'created_asc') {
                    $query->orderBy('profiles.created_at', 'asc');
                }
                if ($this->sortBy == 'created_desc') {
                    $query->orderBy('profiles.created_at', 'desc');
                }
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('profiles.name', 'like', "%$this->search%")
                        ->orWhere('profiles.username', 'like', "%$this->search%")
                        ->orWhere('profiles.email', 'like', "%$this->search%");
                });
            })
            ->where('profiles.user_id', auth()->id())
            ->orderBy('profiles.created_at', 'desc');

        return $filteredData;
    }

    public function confirmModal($id)
    {
        $this->profileId = $id;
        $this->methodType = 'delete';
        $this->modalTitle = 'Are you sure';
        $this->modalBody = 'You want to deactivate this platform!';
        $this->modalActionBtnColor = 'btn-danger';
        $this->modalActionBtnText = 'Deactivate';
        $this->dispatchBrowserEvent('confirmModal');
    }

    public function delete()
    {

        Profile::where('id', $this->profileId)->update([
            'status' => 0
        ]);

        $this->resetPage();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Platform deactivated successfully!',
        ]);
    }

    public function render()
    {

        $data = $this->getData();

        $this->heading = "Profiles";
        $profiles = $data->paginate(10);

        $this->total = $profiles->total();

        return view('livewire.enterprise.team.teams', [
            'profiles' => $profiles
        ]);
    }
}
