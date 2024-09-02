<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;

class View extends Component
{
    public $tab = 1;

    public $userId = null;

    public function mount()
    {
        $this->userId = request()->id;
    }

    public function profilesTab()
    {
        $this->tab = 1;
    }

    public function updateAccountTab()
    {
        $this->tab = 2;
    }
    public function changePasswordTab()
    {
        $this->tab = 3;
    }

    public function getAccountDetails()
    {
        return User::with('profiles')->where('id', $this->userId)->first();
    }

    public function render()
    {
        $account = $this->getAccountDetails();
        return view('livewire.admin.user.view', [
            'account' => $account
        ]);
    }
}
