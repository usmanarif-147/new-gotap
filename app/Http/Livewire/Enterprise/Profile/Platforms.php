<?php

namespace App\Http\Livewire\Enterprise\Profile;

use Livewire\Component;
use App\Models\Platform;

class Platforms extends Component
{
    public $profile_id;

    // public $category;

    public function getData()
    {
        $data = Platform::get();
        return $data;
    }
    public function render()
    {
        $data = $this->getData();

        return view('livewire.enterprise.profile.platforms');
    }
}