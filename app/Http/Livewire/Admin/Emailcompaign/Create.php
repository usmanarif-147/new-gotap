<?php

namespace App\Http\Livewire\Admin\Emailcompaign;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomTemplateMail;
use App\Models\EmailTemplate;

class Create extends Component
{
    public string $subject = '';
    public string $bodyText = '';
    public ?string $buttonText = null;
    public ?string $buttonUrl = null;
    public string $bgColor = '#ffffff';
    public string $textColor = '#000000';
    public string $textAlign = 'center';

    public array $selectedUsers = [];
    public bool $selectAll = false;
    public string $search = '';
    public int $step = 1;

    public $templates;
    public $selectedTemplateId;

    // Mount and preload templates
    public function mount(): void
    {
        $this->templates = EmailTemplate::all();
        $this->selectedTemplateId = $this->templates->first()?->id;

        if ($this->selectedTemplateId) {
            $this->bodyText = $this->templates->first()->html ?? '';
            $this->dispatchBrowserEvent('refreshEditor', $this->bodyText);
        }
    }

    // Update editor content when new template is selected
    public function updatedSelectedTemplateId($id): void
    {
        $template = EmailTemplate::find($id);

        if ($template) {
            $this->bodyText = $template->html;
            $this->dispatchBrowserEvent('refreshEditor', $template->html);
        }
    }

    public function selectTemplate($id): void
    {
        $this->selectedTemplateId = $id;

        $template = EmailTemplate::find($id);
        if ($template) {
            if ($this->step == 1) {
                $this->bodyText = $template->html; // Move to step 2 if not already there
            } else {
                $this->bodyText = $template->html;
                $this->dispatchBrowserEvent('refreshEditor', $template->html);
            }
        }
    }

    // Select all users logic
    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedUsers = ['all'];
        } else {
            $this->selectedUsers = [];
        }
    }

    // Step navigation methods
    public function goToStep(int $step): void
    {
        $this->step = $step;
    }

    // Livewire computed property
    public function getFilteredUsersProperty()
    {
        return User::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"))
            ->get();
    }

    // Send email to selected users
    public function sendEmail(): void
    {
        $recipients = collect();

        if (in_array('all', $this->selectedUsers)) {
            $recipients = User::pluck('email');
        } else {
            $recipients = User::whereIn('id', $this->selectedUsers)->pluck('email');
        }
        $bodyText = html_entity_decode($this->bodyText, ENT_QUOTES, 'UTF-8');

        foreach ($recipients as $email) {
            Mail::to($email)->queue(new CustomTemplateMail(
                $this->subject,
                $bodyText,
                $this->buttonText,
                $this->buttonUrl,
                $this->bgColor,
                $this->textColor,
                $this->textAlign
            ));
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Emails Sent Successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.emailcompaign.create');
    }
}
