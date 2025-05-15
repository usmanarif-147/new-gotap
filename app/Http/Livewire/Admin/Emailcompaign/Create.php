<?php

namespace App\Http\Livewire\Admin\Emailcompaign;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomTemplateMail;

class Create extends Component
{
    public string $subject = '';
    public string $bodyText = '';
    public ?string $buttonText = null;
    public ?string $buttonUrl = null;
    public string $bgColor = '#ffffff';
    public string $textColor = '#000000';
    public string $textAlign = 'left';

    public array $selectedUsers = [];
    public bool $selectAll = false;
    public string $search = '';

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = ['all'];
        } else {
            $this->selectedUsers = [];
        }
    }

    public function getFilteredUsersProperty()
    {
        return User::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->get();
    }

    public function sendEmail()
    {
        $recipients = collect();

        if (in_array('all', $this->selectedUsers)) {
            $recipients = User::pluck('email');
        } else {
            $recipients = User::whereIn('id', $this->selectedUsers)->pluck('email');
        }

        foreach ($recipients as $email) {
            Mail::to($email)->queue(new CustomTemplateMail(
                $this->subject,
                $this->bodyText,
                $this->buttonText,
                $this->buttonUrl,
                $this->bgColor,
                $this->textColor,
                $this->textAlign
            ));
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Emails Send Successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.emailcompaign.create');
    }
}
