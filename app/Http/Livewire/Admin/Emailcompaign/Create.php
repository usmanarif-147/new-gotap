<?php

namespace App\Http\Livewire\Admin\Emailcompaign;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomTemplateMail;
use App\Models\AdminEmailCompaign;
use App\Models\EmailTemplate;
use Livewire\WithPagination;

class Create extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
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
    public $selectedTemplateId, $emailDetail;

    public function mount(): void
    {
        $this->templates = EmailTemplate::all();
        $this->selectedTemplateId = $this->templates->first()?->id;

        if ($this->selectedTemplateId) {
            $this->bodyText = $this->templates->first()->html ?? '';
        }
    }

    public function goToStep(int $step): void
    {
        $this->step = $step;

        if ($step === 2) {
            $this->dispatchBrowserEvent('init-summernote', ['content' => $this->bodyText]);
        }
    }

    public function selectTemplate($id): void
    {
        $this->selectedTemplateId = $id;

        $template = EmailTemplate::find($id);
        if ($template) {
            $this->bodyText = $template->html;
            if ($this->step === 2) {
                $this->dispatchBrowserEvent('init-summernote', ['content' => $this->bodyText]);
            }
        }
    }

    public function updatedSelectAll($value): void
    {
        $this->selectedUsers = $value ? ['all'] : [];
    }

    public function updatedSelectedTemplateId($id): void
    {
        $template = EmailTemplate::find($id);
        if ($template) {
            $this->bodyText = $template->html;
            $this->dispatchBrowserEvent('init-summernote', ['content' => $this->bodyText]);
        }
    }

    public function getFilteredUsersProperty()
    {
        return User::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%"))
            ->get();
    }

    public function sendEmail(): void
    {
        if (empty($this->selectedUsers)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'Please select at least one user or all users.',
            ]);
            return;
        }
        if (empty($this->subject) || empty($this->bodyText)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'Subject and Body Text cannot be empty.',
            ]);
            return;
        }
        $recipients = in_array('all', $this->selectedUsers)
            ? User::pluck('email')
            : User::whereIn('id', $this->selectedUsers)->pluck('email');
        $bodyText = html_entity_decode($this->bodyText, ENT_QUOTES, 'UTF-8');

        try {
            AdminEmailCompaign::create([
                'subject' => $this->subject,
                'message' => $bodyText,
                'total' => count($recipients),
            ]);
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
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'Failed to save email campaign: ' . $e->getMessage(),
            ]);
            return;
        }
    }

    public function showModel($id)
    {
        $this->emailDetail = AdminEmailCompaign::find($id);
        $this->dispatchBrowserEvent('show-view-modal');
    }

    public function deleteMessage($id)
    {
        $email = AdminEmailCompaign::find($id);
        $email->delete();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Email Delete successfully!.',
        ]);
    }

    public function getData()
    {
        $data = AdminEmailCompaign::orderBy('created_at', 'desc');
        return $data;
    }

    public function render()
    {
        $data = $this->getData();
        $emails = $data->paginate(5);
        return view('livewire.admin.emailcompaign.create', [
            'emails' => $emails,
        ]);
    }
}
