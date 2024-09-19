<?php

namespace App\Http\Livewire\Enterprise\Profile;

use Livewire\Component;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class Manageprofile extends Component
{
    public $heading;

    public $profile_id;

    // public $Isdirect, $Isprivate;

    public $tab_change = 1;

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

        return ['profile' => $profile, 'total_platforms' => $total_platforms];
    }

    public function Isdirect($value)
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

    public function Isprivate($value)
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

    public function edit_profile()
    {
        $this->tab_change = 1;
        // $this->profile_id = $profile_id;
    }

    public function platforms_links()
    {
        $this->tab_change = 2;
        // $this->profile_id = $id;
    }

    public function platforms_profile()
    {
        $this->tab_change = 3;
        // $this->profile_id = $id;
    }
    public function render()
    {
        $id = $this->profile_id;
        $profile = $this->profileData($id);
        $this->heading = "Manage Profile";
        return view('livewire.enterprise.profile.manageprofile', [
            'profile' => $profile['profile'],
            'total_platforms' => $profile['total_platforms']
        ]);
    }
}