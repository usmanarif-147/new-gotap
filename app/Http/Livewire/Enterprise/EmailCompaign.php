<?php

namespace App\Http\Livewire\Enterprise;

use App\Mail\CompaignEmail;
use App\Mail\LeadEmail;
use App\Models\CompaignEmail as ModelsCompaignEmail;
use App\Models\Profile;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmailCompaign extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $showDropdown = false;
    public $recipients = [];
    public $selectedNames = [];
    public $leadNames = [];
    public $selectAll = false;
    public $selectAllLeads = false;
    public $profiles = [];
    public $leads = [];
    public $subject, $message;

    public function mount()
    {
        $this->profiles = Profile::select('id', 'name', 'email', 'username', 'photo')
            ->where('enterprise_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $this->leads = DB::table('leads')
            ->select('leads.id', 'leads.name', 'leads.email')
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->orderBy('leads.created_at', 'desc')
            ->get();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function removeRecipient($email)
    {
        $this->recipients = array_diff($this->recipients, [$email]);
        $this->updateInput();
    }

    // Update Selected Names
    public function updateInput()
    {
        $this->selectedNames = collect($this->profiles)
            ->whereIn('email', $this->recipients)
            ->pluck('name', 'email')
            ->toArray();

        $this->leadNames = collect($this->leads)
            ->whereIn('email', $this->recipients)
            ->pluck('name', 'email')
            ->toArray();

        $this->selectedNames = array_merge($this->selectedNames, $this->leadNames);
        // dd($this->selectedNames);

        // Uncheck 'Select All' if some profiles or leads are deselected
        $this->selectAll = count($this->recipients) === count($this->profiles);
        $this->selectAllLeads = count($this->recipients) === count($this->leads);
    }

    // Toggle 'Select All' Option for Profiles
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->recipients = collect($this->profiles)->pluck('email')->toArray();
        } else {
            $this->recipients = array_diff($this->recipients, collect($this->profiles)->pluck('email')->toArray());
        }

        $this->updateInput();
    }

    // Toggle 'Select All' Option for Leads
    public function toggleSelectAllLeads()
    {
        if ($this->selectAllLeads) {
            $this->recipients = array_merge(
                $this->recipients,
                collect($this->leads)->pluck('email')->toArray()
            );
        } else {
            $this->recipients = array_diff($this->recipients, collect($this->leads)->pluck('email')->toArray());
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
        $enterpriser = Auth::user();
        try {
            DB::beginTransaction();
            foreach ($data['recipients'] as $email) {
                Mail::to($email)->send(new CompaignEmail($this->subject, $this->message, $enterpriser));
            }
            ModelsCompaignEmail::create([
                'enterprise_id' => auth()->id(),
                'subject' => $this->subject,
                'message' => $this->message,
                'total' => count($data['recipients']),
            ]);
            DB::commit();

            $this->recipients = [];
            $this->selectedNames = [];
            $this->leadNames = [];
            $this->selectAll = false;
            $this->selectAllLeads = false;
            $this->subject = '';
            $this->message = '';
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'Emails sent successfully to Profiles and Leads.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteMessage($id)
    {
        $email = ModelsCompaignEmail::find($id);
        if ($email) {
            preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/', $email->message, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $imageUrl) {
                    if (Str::startsWith($imageUrl, 'https://enterprise.gocoompany.com/media/')) {
                        $imagePath = Str::after($imageUrl, 'https://enterprise.gocoompany.com/');
                        File::delete($imagePath);
                    }
                }
            }
        }
        $email->delete();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Email Delete successfully!.',
        ]);
    }

    public function getData()
    {
        $data = ModelsCompaignEmail::orderBy('created_at', 'desc');
        return $data;
    }
    public function render()
    {
        $data = $this->getData();
        $emails = $data->paginate(5);
        return view('livewire.enterprise.email-compaign', [
            'emails' => $emails,
        ]);
    }
}
