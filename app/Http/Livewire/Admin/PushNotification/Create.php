<?php

namespace App\Http\Livewire\Admin\PushNotification;

use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public string $title = '';
    public string $message = '';
    public bool $schedule = false;

    /* ———————————————————
       Recipients state
    ——————————————————— */
    public string $search = '';      // what user is typing
    public array $suggestions = [];      // live query results (id, name, email)
    public array $recipients = [];      // selected user 

    protected $listeners = [
        'add-recipient' => 'addRecipient',
    ];

    public function getCanSendProperty()
    {
        return !empty($this->recipients) && !empty($this->message);
    }

    public function updatedSearch()
    {
        $query = trim($this->search);

        if ($query === '') {
            $this->suggestions = [];
            return;
        }

        $this->suggestions = User::where(function ($q) use ($query) {
            $q->where('name', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%");
        })
            ->whereNotIn('id', $this->recipients)
            ->where('role', 'user')
            ->limit(8)
            ->get(['id', 'name', 'email'])
            ->toArray();
    }

    public function addRecipient($userId = null)
    {
        if (!$userId)
            return;

        $this->recipients[$userId] = $userId;
        $this->reset(['search', 'suggestions']);

        $this->dispatchBrowserEvent('recipient-added');
    }

    public function removeRecipient($userId)
    {
        unset($this->recipients[$userId]);
    }

    public function selectAll()
    {
        $this->recipients = User::where('role', 'user')->pluck('id')->toArray(); // fix for "only one showing"
        $this->dispatchBrowserEvent('recipient-added');
    }

    public function clearAll()
    {
        $this->recipients = [];
        $this->dispatchBrowserEvent('recipient-added');
    }

    public function send()
    {
        $this->reset(['title', 'message', 'recipients', 'search', 'suggestions']);
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Notifications Sent Successfully!',
        ]);
    }
    public function render()
    {
        return view('livewire.admin.push-notification.create');
    }
}
