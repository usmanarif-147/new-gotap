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
    public function render()
    {
        $user = $this->linkedUser();
        return view('livewire.enterprise.profile.profile-user', [
            'user' => $user
        ]);
    }
}
