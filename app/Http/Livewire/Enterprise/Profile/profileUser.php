<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Models\Card;
use App\Models\Profile;
use App\Models\ProfileCard;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class profileUser extends Component
{
    public $profile_id;

    public $tab_change;

    protected $listeners = ['refresh-profileUser' => 'linkedUser'];

    public function mount($id, $tab)
    {
        $this->profile_id = $id;
        $this->tab_change = $tab;
    }

    public function linkedUser()
    {
        $profileUser = Profile::where('id', $this->profile_id)->pluck('user_id')->first();
        if ($profileUser == null) {
            return $profileUser;
        } else {
            $profileUser = User::find($profileUser);
            return $profileUser;
        }
    }
    public function dLinkUser($user_id)
    {
        $profile = Profile::where('id', $this->profile_id)
            ->where('user_id', $user_id)
            ->first();
        if ($profile) {
            $profileCard = ProfileCard::where('profile_id', $this->profile_id)
                ->where('user_id', $user_id)->first();
            if ($profileCard) {
                $platforms = DB::table('profile_platforms')->where('profile_id', $this->profile_id)
                    ->where('user_id', $user_id)
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
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'danger',
                    'message' => 'Card not found!',
                ]);
            }
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'danger',
                'message' => 'Profile Not Found!',
            ]);
        }
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'User D Link Successfully from this profile!',
        ]);
        $this->emit('refresh-profileUser');
    }
    public function render()
    {
        $user = $this->linkedUser();
        return view('livewire.enterprise.profile.profile-user', [
            'user' => $user
        ]);
    }
}
