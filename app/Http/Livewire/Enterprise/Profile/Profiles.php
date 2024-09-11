<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Models\Card;
use App\Models\Profile;
use App\Models\ProfileCard;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Profiles extends Component
{

    use WithFileUploads, WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $profileId, $methodType, $modalTitle, $modalBody, $modalActionBtnColor, $modalActionBtnText;

    public $search = '', $filterByStatus, $filterByCategory, $sortBy;

    public $total, $heading, $statuses = [], $categories;

    protected $listeners = ['activateTag'];

    public function activateTag($card_uuid, $profile_id)
    {
        // dd($profile_id);
        $card = null;
        $check = 1;
        // check card exist
        if ($card_uuid) {
            $card = Card::where('uuid', $card_uuid)->first();
        }

        if (!$card) {
            $check = 0;
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',
                'message' => 'Card not found!',
            ]);
        }

        // check card is already activated
        if ($card->status) {
            $check = 0;
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',
                'message' => 'Card is already active!',
            ]);
        }

        if ($check) {
            // insert card in user cards table
            $profile_card = new ProfileCard();
            $profile_card->card_id = $card->id;
            $profile_card->profile_id = $profile_id;
            $profile_card->status = 1;
            $profile_card->save();

            // update card status to activated
            Card::whereId($card->id)->update([
                'status' => 1
            ]);

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'Card activated successfully!',
            ]);
            // $this->resetPage();
        }

    }

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
            ->where('profiles.enterprise_id', auth()->id())
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

        return view('livewire.enterprise.profile.profiles', [
            'profiles' => $profiles
        ]);
    }
}