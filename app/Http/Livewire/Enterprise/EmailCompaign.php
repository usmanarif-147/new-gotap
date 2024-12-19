<?php

namespace App\Http\Livewire\Enterprise;

use App\Mail\LeadEmail;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class EmailCompaign extends Component
{


    public $showDropdown = false;
    public $recipients = [];
    public $selectedNames = [];
    public $selectAll = false;
    public $profiles = [];
    public $subject, $message;

    // Mount Function to Load Profiles
    public function mount()
    {
        $this->profiles = Profile::select(
            'profiles.id',
            'profiles.name',
            'profiles.email',
            'profiles.username',
            'profiles.photo',
        )
            ->where('profiles.enterprise_id', auth()->id())
            ->orderBy('profiles.created_at', 'desc')->get();
    }

    // Toggle Dropdown Visibility
    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    // Update Selected Names
    public function updateInput()
    {
        $this->selectedNames = collect($this->profiles)
            ->whereIn('id', $this->recipients)
            ->pluck('name', 'id')
            ->toArray();

        // Uncheck 'Select All' if some profiles are deselected
        $this->selectAll = count($this->recipients) === count($this->profiles);
    }

    // Remove Recipient from Selected
    public function removeRecipient($id)
    {
        $this->recipients = array_diff($this->recipients, [$id]);
        $this->updateInput();
    }

    // Toggle 'Select All' Option
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->recipients = collect($this->profiles)->pluck('id')->toArray();
        } else {
            $this->recipients = [];
        }

        $this->updateInput();
    }

    protected function rules()
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'recipients' => ['required'],
        ];
    }

    protected function messages()
    {
        return [
            'subject.required' => 'Subject is required',
            'subject.max' => 'Maximum 255 characters',
            'message.required' => 'Message is required',
            'message.min' => 'Minimum 20 characters',
            'message.max' => 'Maximum 5000 characters',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function sendEmail()
    {
        $data = $this->validate();
        $profiles = Profile::whereIn('id', $data['recipients'])->get();
        foreach ($profiles as $profile) {
            $enterpriser = User::find($profile->enterprise_id);
            Mail::to($profile->email)->send(new LeadEmail($profile, $this->subject, $this->message, $enterpriser));
        }
        $this->recipients = [];
        $this->selectedNames = [];
        $this->selectAll = false;
        $this->subject = '';
        $this->message = '';
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Emails sent successfully to Profiles.',
        ]);
    }
    public function render()
    {
        return view('livewire.enterprise.email-compaign');
    }
}
