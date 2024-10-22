<?php

namespace App\Http\Livewire\Enterprise\Profile;

use DB;
use JeroenDesloovere\VCard\VCard;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Leads extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $downloadUrl;

    public $total, $leadId;

    public $c_modal_heading = '', $c_modal_body = '', $c_modal_btn_text = '', $c_modal_btn_color = '', $c_modal_method = '';

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

    public function downloadVCard($id)
    {
        $lead = DB::table('leads')->find($id);
        // Create a new vCard
        $vcard = new VCard();
        $vcard->addName($lead->name);
        $vcard->addEmail($lead->email);
        $vcard->addPhoneNumber($lead->phone);

        // Generate filename
        $filename = $lead->name . '_contact.vcf';

        // Save the vCard to a temporary location
        $filePath = 'public/uploads/vcards/' . $filename;
        Storage::disk('local')->put($filePath, $vcard->getOutput());

        // Get the URL of the stored vCard
        $this->downloadUrl = Storage::url($filePath);

        // Emit event to trigger download in JavaScript
        $this->dispatchBrowserEvent('triggerVCardDownload');
    }

    public function getData()
    {
        $filteredData = DB::table('leads')->select(
            'leads.id',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.viewing_id',
            'leads.viewer_id',
            'leads.created_at',
            DB::raw('COALESCE(viewingProfile.username, "No Viewing") as viewing_username'),
            DB::raw('COALESCE(viewingProfile.photo, "photo") as viewing_photo'),
            DB::raw('COALESCE(viewerProfile.username, "No Viewer") as viewer_username'),
            DB::raw('COALESCE(viewerProfile.photo, "photo") as viewer_photo'),
        )
            // Join profiles on viewing_id
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            // Join profiles on viewer_id
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
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
