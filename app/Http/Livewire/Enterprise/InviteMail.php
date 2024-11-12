<?php

namespace App\Http\Livewire\Enterprise;

use Livewire\Component;

class InviteMail extends Component
{
    public $subject = '';
    public $message = '';

    public $action = 'Join Now';

    public $hasChanges = false;


    public function updated($field)
    {
        $this->hasChanges = true;
    }
    public function render()
    {
        return view('livewire.enterprise.invite-mail');
    }
}
