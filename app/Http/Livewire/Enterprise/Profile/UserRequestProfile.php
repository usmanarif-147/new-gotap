<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Models\Profile;
use App\Models\ProfileCard;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserRequestProfile as ProfileRequestUser;

class UserRequestProfile extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $userId, $reqId;

    public $searchTerm = '', $sortBy, $filterByStatus = '', $statuses = [];
    public $c_modal_heading = '', $c_modal_body = '', $c_modal_btn_text = '', $c_modal_btn_color = '', $c_modal_method = '';
    public $profiles = [], $total;

    public function mount()
    {
        $this->statuses = [
            '1' => 'Pending',
            '2' => 'Active',
            '3' => 'D-Active',
        ];
    }

    public function showProfileModal($userid, $id)
    {
        $this->userId = $userid;
        $this->reqId = $id;
        $this->loadProfiles();
        $this->dispatchBrowserEvent('showProfilesModal');
    }

    public function loadProfiles()
    {
        $this->profiles = Profile::where('enterprise_id', auth()->id())->whereNull('user_id')->where('username', 'like', "%$this->searchTerm%")->get();
    }

    public function updatedSearchTerm()
    {
        $this->loadProfiles();
    }

    public function attachToUser($profileId)
    {
        Profile::where('id', $profileId)->update([
            'user_id' => $this->userId,
        ]);
        ProfileRequestUser::where('id', $this->reqId)->update([
            'status' => 1,
        ]);
        ProfileCard::where('profile_id', $profileId)->update([
            'user_id' => $this->userId,
        ]);
        $this->dispatchBrowserEvent('close-profile-modal');
        $this->emit('requestPending');
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Profile Attach with User successfully!',
        ]);
    }

    //delete Request

    public function confirmModal($id)
    {
        $this->reqId = $id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You want to delete this user Request!';
        $this->c_modal_btn_text = 'delete';
        $this->c_modal_btn_color = 'btn-danger';
        $this->c_modal_method = 'deleteRequest';
        $this->dispatchBrowserEvent('confirm-modal');
    }

    public function closeModal()
    {
        $this->c_modal_heading = '';
        $this->c_modal_body = '';
        $this->c_modal_btn_text = '';
        $this->c_modal_btn_color = '';
        $this->c_modal_method = '';
        $this->dispatchBrowserEvent('close-modal');
    }
    public function deleteRequest()
    {
        $data = ProfileRequestUser::find($this->reqId);
        $data->delete();
        $this->closeModal();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'User Request for Profile is Deleted Successfully!',
        ]);
    }

    public function getData()
    {
        $filteredData = ProfileRequestUser::select(
            'user_request_profiles.id',
            'user_request_profiles.user_id',
            'user_request_profiles.enterprise_id',
            'user_request_profiles.status',
            'users.username',
            'users.id as UserId',
            'users.email',
            'users.photo',
            'user_request_profiles.created_at'
        )
            ->leftJoin('users', 'user_request_profiles.user_id', '=', 'users.id')
            ->when($this->filterByStatus, function ($query) {
                if ($this->filterByStatus == 3) {
                    $query->where('user_request_profiles.status', 2);
                }
                if ($this->filterByStatus == 2) {
                    $query->where('user_request_profiles.status', 1);
                }
                if ($this->filterByStatus == 1) {
                    $query->where('user_request_profiles.status', 0);
                }
            })
            ->when($this->sortBy, function ($query) {
                if ($this->sortBy == 'created_asc') {
                    $query->orderBy('user_request_profiles.created_at', 'asc');
                }
                if ($this->sortBy == 'created_desc') {
                    $query->orderBy('user_request_profiles.created_at', 'desc');
                }
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('users.username', 'like', "%$this->search%")
                        ->orWhere('user_request_profiles.status', 'like', "%$this->search%");
                });
            })
            ->where('user_request_profiles.enterprise_id', auth()->id())
            ->orderBy('user_request_profiles.created_at', 'desc');

        return $filteredData;

    }
    public function render()
    {
        $data = $this->getData();
        $requestProfile = $data->paginate(10);

        $this->total = $requestProfile->total();
        return view('livewire.enterprise.profile.user-request-profile', [
            'requestProfile' => $requestProfile,
        ]);
    }
}
