<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeadEmail;
use Illuminate\Support\Facades\DB;

class Leads extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $downloadUrl;

    public $total, $leadId;

    public $note;

    public $selectedLeads = [];

    public $selectAll = false;

    public $leadEmail;
    public $customMessage, $subject;

    public $c_modal_heading = '', $c_modal_body = '', $c_modal_btn_text = '', $c_modal_btn_color = '', $c_modal_method = '';

    public function mount()
    {
        $this->emit('refreshEditor', $this->customMessage);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedLeads = DB::table('leads')->where('enterprise_id', auth()->id())->pluck('id')->toArray();
        } else {
            $this->selectedLeads = [];
        }
    }

    public function updatedSelectedLeads()
    {
        $this->selectAll = DB::table('leads')->where('enterprise_id', auth()->id())->count() === count($this->selectedLeads);
    }

    public function showBulkEmailModal()
    {
        $this->dispatchBrowserEvent('show-bulk-email-modal');
    }

    public function sendBulkEmail()
    {
        $this->validate([
            'subject' => 'required|string|max:255',
            'customMessage' => 'required|string|max:5000',
        ]);
        $leads = DB::table('leads')->whereIn('id', $this->selectedLeads)->get();
        foreach ($leads as $lead) {
            $enterpriser = User::find($lead->enterprise_id);
            Mail::to($lead->email)->send(new LeadEmail($lead, $this->subject, $this->customMessage, $enterpriser));
        }
        $this->dispatchBrowserEvent('emailBulkSend');
        $this->selectedLeads = [];
        $this->selectAll = false;
        $this->subject = '';
        $this->customMessage = '';
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Emails sent successfully to all leads.',
        ]);
    }
    public function showEmailModal($id)
    {
        $this->leadId = $id;
        $lead = DB::table('leads')->where('id', $id)->first();
        $this->leadEmail = $lead->email;
        $this->dispatchBrowserEvent('show-email-modal');
    }

    public function sendEmailToLead($id)
    {
        $this->validate([
            'subject' => 'required|string|max:255',
            'customMessage' => 'required|string|max:5000',
        ]);
        $lead = DB::table('leads')->where('id', $id)->first();
        $enterpriser = User::find($lead->enterprise_id);
        Mail::to($this->leadEmail)->send(new LeadEmail($lead, $this->subject, $this->customMessage, $enterpriser));

        $this->dispatchBrowserEvent('emailSend');
        $this->subject = '';
        $this->customMessage = '';
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Email sent successfully to the lead.',
        ]);
    }

    public function showNoteModal($id)
    {
        $this->leadId = $id;
        $lead = DB::table('leads')->where('id', $id)->first();
        $this->note = $lead->note;
        $this->dispatchBrowserEvent('show-note-modal');
    }
    public function noteSave($id)
    {
        $lead = DB::table('leads')->where('id', $id)->first();
        if ($lead) {
            if ($lead->note === null) {
                // If no note exists, insert the new note
                DB::table('leads')
                    ->where('id', $id)
                    ->update(['note' => $this->note]);
            } else {
                // Update the existing note
                DB::table('leads')
                    ->where('id', $id)
                    ->update(['note' => $this->note]);
            }
        }
        $this->dispatchBrowserEvent('noteSaved');
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Lead Note Updated successfully!',
        ]);
    }

    public function confirmModal($id)
    {
        $this->leadId = $id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You want to delete this Lead!';
        $this->c_modal_btn_text = 'Delete';
        $this->c_modal_btn_color = 'btn-danger';
        $this->c_modal_method = 'delete';
        $this->dispatchBrowserEvent('confirm-modal');
    }

    public function delete()
    {
        DB::table('leads')->where('id', $this->leadId)->delete();
        $this->closeModal();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Lead deleted successfully!',
        ]);
    }

    public function closeModal()
    {
        $this->c_modal_heading = '';
        $this->c_modal_body = '';
        $this->c_modal_btn_text = '';
        $this->c_modal_btn_color = '';
        $this->c_modal_method = '';
        $this->dispatchBrowserEvent('close-modal');
    }

    public function getData()
    {
        $filteredData = DB::table('leads')->select(
            'leads.id',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.note',
            'leads.type',
            'leads.viewing_id',
            'leads.viewer_id',
            'leads.created_at',
            DB::raw('COALESCE(viewingProfile.username, "No Viewing") as viewing_username'),
            DB::raw('COALESCE(viewingProfile.photo, "photo") as viewing_photo'),
            DB::raw('COALESCE(viewerProfile.username, "No Viewer") as viewer_username'),
            DB::raw('COALESCE(viewerProfile.photo, "photo") as viewer_photo'),
            DB::raw('COALESCE(viewingUser.photo, "photo") as viewing_user_photo'),
        )
            // Join profiles on viewing_id
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            // Join profiles on viewer_id
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->leftJoin('users as viewingUser', 'viewingProfile.user_id', '=', 'viewingUser.id')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('leads.name', 'like', "%$this->search%")
                        ->orWhere('leads.email', 'like', "%$this->search%")
                        ->orWhere('leads.phone', 'like', "%$this->search%")
                        ->orWhere('viewingProfile.username', 'like', "%$this->search%");
                });
            })
            ->where(function ($query) {
                // If viewer_id is null, just return the data; else, return profile data
                $query->whereNull('leads.viewer_id')
                    ->orWhere('viewingProfile.enterprise_id', auth()->id());
            })
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->orderBy('leads.created_at', 'desc');


        return $filteredData;
    }

    public function render()
    {
        $data = $this->getData();
        $leads = $data->paginate(10);

        $this->total = $leads->total();
        return view('livewire.enterprise.profile.leads', [
            'leads' => $leads,
        ]);
    }
}
