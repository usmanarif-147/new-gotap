<?php

namespace App\Http\Livewire\Enterprise\Profile;

use Livewire\Component;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class Manageprofile extends Component
{
    public $heading;

    public $profile_id;

    public $tab_change = 0;

    protected $listeners = ['refresh-profile' => 'profileData'];
    public function profileData($id)
    {
        if (request()->id) {
            $this->profile_id = request()->id;
        } else {
            $this->profile_id = $id;
        }
        $profile = Profile::where('id', $this->profile_id)->first();

        $total_platforms = DB::table('profile_platforms')->where('profile_id', $this->profile_id)->count();

        $cardStatus = DB::table('profile_cards')->where('profile_id', $this->profile_id)->select('status')->first();
        if ($cardStatus) {
            if ($cardStatus->status) {
                $card_status = 'active';
            } else {
                $card_status = 'inactive';
            }
        } else {
            $card_status = '--';
        }

        return ['profile' => $profile, 'total_platforms' => $total_platforms, 'card_status' => $card_status];
    }

    public function isDirect($value)
    {
        $string = '';
        $profile = Profile::where('id', $this->profile_id)->first();
        if ($value) {
            $profile->user_direct = 1;
            $string = 'Only first platform on top set to public';
        } else {
            $profile->user_direct = 0;
            $string = 'All platforms are set to public';
        }
        $profile->update();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => $string,
        ]);
    }

    public function isPrivate($value)
    {
        $string = '';
        $profile = Profile::where('id', $this->profile_id)->first();
        if ($value) {
            $profile->private = 1;
            $string = 'Profile is set to private';
        } else {
            $profile->private = 0;
            $string = 'Profile is set to public';
        }
        $profile->update();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => $string,
        ]);
    }

    public function viewProfile()
    {
        $this->tab_change = 0;
    }

    public function editProfile()
    {
        $this->tab_change = 1;
    }

    public function platformsLinks()
    {
        $this->tab_change = 2;
    }

    public function platformsProfile()
    {
        $this->tab_change = 3;
    }

    public function profileLinkedUser()
    {
        $this->tab_change = 4;
    }

    public function profileAnalytics()
    {
        $this->tab_change = 5;
    }

    public function profileLeads()
    {
        $this->tab_change = 6;
    }
    public function render()
    {
        $id = $this->profile_id;
        $profile = $this->profileData($id);
        $this->heading = "Manage Profile";
        return view('livewire.enterprise.profile.manageprofile', [
            'profile' => $profile['profile'],
            'total_platforms' => $profile['total_platforms'],
            'card_status' => $profile['card_status'],
        ]);
    }
}