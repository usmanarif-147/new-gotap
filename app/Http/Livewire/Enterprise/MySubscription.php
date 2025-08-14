<?php

namespace App\Http\Livewire\Enterprise;

use Livewire\Component;

class MySubscription extends Component
{
    public $user, $subscription;

    public function mount()
    {
        $this->user = auth()->user();
        $this->subscription = $this->user->userSubscription;
    }
    public function render()
    {
        return view('livewire.enterprise.my-subscription');
    }
}
