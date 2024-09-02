<?php

namespace App\Http\Livewire\Admin;

use App\Models\Application;
use Livewire\Component;

class Sidebar extends Component
{
    protected $listeners = ['pendingApplications' => 'getPendingApplications'];

    public $pendingApplications = 0;

    public function getPendingApplications()
    {
        $this->pendingApplications = Application::where('status', 0)->count();
    }

    public function render()
    {
        $this->getPendingApplications();
        return view('livewire.admin.sidebar');
    }
}
