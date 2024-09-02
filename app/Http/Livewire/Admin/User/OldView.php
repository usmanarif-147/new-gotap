<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;

class OldView extends Component
{

    public $userId = null;

    public function mount()
    {
        $this->userId = request()->id;
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
