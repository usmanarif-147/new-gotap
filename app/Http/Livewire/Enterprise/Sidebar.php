<?php

namespace App\Http\Livewire\Enterprise;

use App\Models\UserRequestProfile;
use Livewire\Component;

class Sidebar extends Component
{
    public $pending = 0;
    public $isActive = false;

    protected $listeners = [
        'requestPending' => 'PendingStatus',
    ];

    public function mount()
    {
        $this->pending = UserRequestProfile::where('status', 0)->count();
    }

    public function PendingStatus()
    {
        $this->pending = UserRequestProfile::where('status', 0)->count();
        $this->isActive = true;
    }
    public function render()
    {
        return view('livewire.enterprise.sidebar');
    }
}
