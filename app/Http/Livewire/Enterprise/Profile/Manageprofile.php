<?php

namespace App\Http\Livewire\Enterprise\Profile;

use Livewire\Component;
use App\Models\Profile;

class Manageprofile extends Component
{
    public $heading;

    public $profile_id;

    // public $Isdirect, $Isprivate;

    public $edit_profile = true;

    public
    $name,
    $email,
    $username,
    $work_position,
    $job_title,
    $company,
    $address,
    $bio,
    $phone,
    $photo,
    $cover_photo,
    $user_direct,
    $private,
    $created_at;

    protected $listeners = ['refresh-profile' => 'profileData'];
    public function profileData($id)
    {
        if (request()->id) {
            $this->profile_id = request()->id;
        } else {
            $this->profile_id = $id;
        }
        $profile = Profile::where('id', $this->profile_id)->first();

        $this->name = $profile->name;
        $this->email = $profile->email;
        $this->username = $profile->username;
        $this->work_position = $profile->work_position;
        $this->job_title = $profile->job_title;
        $this->company = $profile->company;
        $this->address = $profile->address;
        $this->bio = $profile->bio;
        $this->phone = $profile->phone;
        $this->user_direct = $profile->user_direct;
        $this->private = $profile->private;
        $this->created_at = $profile->created_at->diffForHumans();
        $this->photo = $profile->photo;
        $this->cover_photo = $profile->cover_photo;
    }

    public function Isdirect($value)
    {
        $profile = Profile::where('id', $this->profile_id)->first();
        if ($value) {
            $profile->user_direct = 1;
        } else {
            $profile->user_direct = 0;
        }
        $profile->update();
    }

    public function Isprivate($value)
    {
        $profile = Profile::where('id', $this->profile_id)->first();
        if ($value) {
            $profile->private = 1;
        } else {
            $profile->private = 0;
        }
        $profile->update();
    }

    public function edit_profile($profile_id)
    {
        $this->edit_profile = true;
        $this->profile_id = $profile_id;
    }

    public function plateforms()
    {
        $this->edit_profile = false;
    }
    public function render()
    {
        $this->profileData($this->profile_id);
        $this->heading = "Manage Profile";
        return view('livewire.enterprise.profile.manageprofile');
    }
}