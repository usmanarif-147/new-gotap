<?php

namespace App\Http\Livewire\Enterprise\Profile;

use DB;
use JeroenDesloovere\VCard\VCard;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class LeadsMap extends Component
{
    public $leads, $leadId;
    public $downloadUrl;

    public $c_modal_heading = '', $c_modal_body = '', $c_modal_btn_text = '', $c_modal_btn_color = '', $c_modal_method = '';

    protected $listeners = [
        'downloadVCard',
        'confirmModal'
    ];

    public function confirmModal($id)
    {
        $this->leadId = $id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You want to delete this Lead!';
        $this->c_modal_btn_text = 'yes,remove';
        $this->c_modal_btn_color = 'btn-danger';
        $this->c_modal_method = 'delteContact';
        $this->dispatchBrowserEvent('confirm-modal');
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
    // public function downloadVCard($id)
    // {
    //     $lead = DB::table('leads')->find($id);
    //     // Create a new vCard
    //     $vcard = new VCard();
    //     $vcard->addName($lead->name);
    //     $vcard->addEmail($lead->email);
    //     $vcard->addPhoneNumber($lead->phone);

    //     // Generate filename
    //     $filename = $lead->name . '_contact.vcf';

    //     // Save the vCard to a temporary location
    //     $filePath = 'public/uploads/vcards/' . $filename;
    //     Storage::disk('local')->put($filePath, $vcard->getOutput());

    //     // Get the URL of the stored vCard
    //     $this->downloadUrl = Storage::url($filePath);

    //     // Emit event to trigger download in JavaScript
    //     $this->dispatchBrowserEvent('triggerVCardDownload');
    // }

    public function delteContact()
    {
        DB::table('leads')->where('id', $this->leadId)->delete();
        $this->emit('contactDeleted', $this->leadId);
        $this->closeModal();
    }

    public function getData()
    {
        $this->leads = DB::table('leads')->select(
            'leads.id',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.viewing_id',
            'leads.viewer_id',
            'leads.created_at',
            'leads.latitude',
            'leads.longitude',
            'leads.city',
            'leads.country',
            'leads.state',
            DB::raw('COALESCE(viewingProfile.username, "No Viewing") as viewing_username'),
            DB::raw('COALESCE(viewingProfile.photo, "null") as viewing_photo'),
            DB::raw('COALESCE(viewerProfile.username, "No Viewer") as viewer_username'),
            DB::raw('COALESCE(viewerProfile.photo, "null") as viewer_photo'),
        )
            // Join profiles on viewing_id
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            // Join profiles on viewer_id
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->where(function ($query) {
                // If viewer_id is null, just return the data; else, return profile data
                $query->whereNull('leads.viewer_id')
                    ->orWhere('viewingProfile.enterprise_id', auth()->id());
            })
            ->whereNotNull('leads.latitude')
            ->whereNotNull('leads.longitude')
            ->distinct('leads.latitude', 'leads.longitude')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->orderBy('leads.created_at', 'desc')->get();
    }

    public function render()
    {
        $this->getData();
        // dd($this->leads);
        return view('livewire.enterprise.profile.leads-map');
    }
}
