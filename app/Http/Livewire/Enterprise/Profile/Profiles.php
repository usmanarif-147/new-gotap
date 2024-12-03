<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Models\Card;
use App\Models\Group;
use App\Models\Profile;
use App\Models\ProfileCard;
use App\Models\UserRequestProfile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Profiles extends Component
{

    use WithFileUploads, WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $profileId, $userId;

    public $c_modal_heading = '', $c_modal_body = '', $c_modal_btn_text = '', $c_modal_btn_color = '', $c_modal_method = '';

    public $search = '', $filterByStatus, $filterByCategory, $sortBy;

    public $total, $heading, $statuses = [], $categories;

    protected $listeners = [
        'activateTag',
        'profiles-refresh-enterprisers' => 'getData'
    ];

    public function activateTag($card_uuid, $profile_id)
    {
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
            'profiles.user_id',
            'profile_cards.card_id',
            'profile_cards.status as cardStatus',
            'cards.uuid as card_uuid',
            'users.name as user_name',
            'users.username as user_username',
            'users.email as user_email',
            'users.photo as user_photo'
        )
            ->leftJoin('profile_cards', 'profiles.id', 'profile_cards.profile_id')
            ->leftJoin('cards', 'profile_cards.card_id', 'cards.id')
            ->leftJoin('users', 'profiles.user_id', '=', 'users.id')
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
                        ->orWhere('profiles.email', 'like', "%$this->search%")
                        ->orWhere('users.name', 'like', "%$this->search%")
                        ->orWhere('users.email', 'like', "%$this->search%");
                });
            })
            ->where('profiles.enterprise_id', auth()->id())
            ->orderBy('profiles.created_at', 'desc');


        return $filteredData;
    }

    public function confirmCardStatus($card_id, $profile_id)
    {
        $this->profileId = $profile_id;
        $this->cardId = $card_id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You Want To Change Status of card';
        $this->c_modal_btn_text = 'Accept';
        $this->c_modal_btn_color = 'btn-success';
        $this->c_modal_method = 'profileCardStatus';
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

    public function profileCardStatus()
    {
        $profileCard = ProfileCard::where('profile_id', $this->profileId)
            ->where('card_id', $this->cardId)->first();

        $profileCard->update(['status' => $profileCard->status ? 0 : 1]);
        $this->closeModal();

        $this->dispatchBrowserEvent('swal:modal', [
            'message' => 'Profile Card Status Changed Successfully!',
            'type' => 'success'
        ]);
        $this->emit('profiles-refresh-enterprisers');

    }

    public function confirmModal($id)
    {
        $this->profileId = $id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You want to delete this profile!';
        $this->c_modal_btn_text = 'Delete';
        $this->c_modal_btn_color = 'btn-danger';
        $this->c_modal_method = 'delete';
        $this->dispatchBrowserEvent('confirm-modal');
    }

    public function delete()
    {
        $profile = Profile::where('id', $this->profileId)->first();
        $userId = $profile->user_id;
        DB::transaction(function () use ($profile) {
            // Delete profile platforms from profile_platforms table
            $this->deleteProfilePlatforms($profile->id);

            // Delete all cards linked with the profile and set their status to inactive
            $this->deleteProfileCards($profile->id);

            // Remove the profile from all groups where it exists and decrement total_profiles
            $this->removeProfileFromGroups($profile->id);

            // Remove from connects
            $this->removeFromConnects($profile->id);

            $this->removeFromLeads($profile->id);

            // Delete profile cover photo
            if ($profile->cover_photo) {
                Storage::disk('public')->delete($profile->cover_photo);
            }

            // Delete profile photo
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }

            // Delete profile
            $profile->delete();
        });
        if ($userId != null) {
            $profile = Profile::where('user_id', auth()->id())->where('active', 1)->first();

            if (!$profile) {
                Profile::where('user_id', auth()->id())
                    ->where('is_default', 1)
                    ->update([
                        'active' => 1,
                    ]);
            }
        }
        $this->resetPage();
        $this->closeModal();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Profile deleted successfully!',
        ]);
    }

    private function deleteProfilePlatforms($profileId)
    {
        DB::table('profile_platforms')->where('profile_id', $profileId)->delete();
    }

    private function deleteProfileCards($profileId)
    {
        $profile_cards = DB::table('profile_cards')->where('profile_id', $profileId)->get();
        foreach ($profile_cards as $card) {
            Card::where('id', $card->card_id)->update(['status' => 0]);
        }
        DB::table('profile_cards')->where('profile_id', $profileId)->delete();
    }

    private function removeFromConnects($profileId)
    {
        DB::table('connects')->where('connected_id', $profileId)->delete();
    }

    private function removeProfileFromGroups($profileId)
    {
        $user_groups = DB::table('user_groups')->where('profile_id', $profileId)->get();
        foreach ($user_groups as $group) {
            Group::where('id', $group->group_id)->decrement('total_profiles');
        }
        DB::table('user_groups')->where('profile_id', $profileId)->delete();
    }

    private function removeFromLeads($profileId)
    {
        DB::table('leads')->where('viewing_id', $profileId)->delete();
    }

    public function confirmUserDactivate($id, $user_id)
    {
        $this->profileId = $id;
        $this->userId = $user_id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You want to Disconnect User from profile!';
        $this->c_modal_btn_text = 'Disconnect';
        $this->c_modal_btn_color = 'btn-danger';
        $this->c_modal_method = 'dLinkUser';
        $this->dispatchBrowserEvent('confirm-modal');
    }

    public function dLinkUser()
    {
        $profile = Profile::where('id', $this->profileId)
            ->where('user_id', $this->userId)
            ->first();
        if ($profile) {
            $profileCard = ProfileCard::where('profile_id', $this->profileId)
                ->where('user_id', $this->userId)->first();
            if ($profileCard) {
                $platforms = DB::table('profile_platforms')->where('profile_id', $this->profileId)
                    ->where('user_id', $this->userId)
                    ->get();
                foreach ($platforms as $k => $v) {
                    DB::table('profile_platforms')->whereId($v->id)->update([
                        'user_id' => null
                    ]);
                }
                $profileCard->update([
                    'user_id' => null
                ]);
                $profile->update([
                    'user_id' => null
                ]);
                UserRequestProfile::where('enterprise_id', auth()->id())->where('user_id', $this->userId)->where('status', 1)->update([
                    'status' => 2,
                ]);
                DB::table('leads')->where('employee_id', $this->userId)->where('enterprise_id', $profile->enterprise_id)->where('viewing_id', $this->profileId)
                    ->update(['employee_id' => null]);
            } else {
                $profile->update([
                    'user_id' => null
                ]);
                UserRequestProfile::where('enterprise_id', auth()->id())->where('user_id', $this->userId)->where('status', 1)->update([
                    'status' => 2,
                ]);
                DB::table('leads')->where('employee_id', $this->userId)->where('enterprise_id', $profile->enterprise_id)->where('viewing_id', $this->profileId)
                    ->update(['employee_id' => null]);
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'danger',
                    'message' => 'Card not found!',
                ]);
                $this->closeModal();
            }
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'danger',
                'message' => 'Profile Not Found!',
            ]);
            $this->closeModal();
        }
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'User Dactivate Successfully from this profile!',
        ]);
        $this->closeModal();
        $this->emit('profiles-refresh-enterprisers');
    }

    public function render()
    {

        $data = $this->getData();
        $profiles = $data->paginate(10);

        $this->total = $profiles->total();

        return view('livewire.enterprise.profile.profiles', [
            'profiles' => $profiles
        ]);
    }
}