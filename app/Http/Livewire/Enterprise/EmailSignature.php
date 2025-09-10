<?php

namespace App\Http\Livewire\Enterprise;

use App\Models\Profile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EmailSignature extends Component
{
    use WithFileUploads;

    public $profiles = [];
    public $selectedProfile = null;
    public $emailSignature = '';
    public $emailSignatureEnabled = false;
    public $showPreview = false;
    public $previewSignature = '';

    public function mount()
    {
        $this->loadProfiles();
    }

    public function loadProfiles()
    {
        $this->profiles = Profile::where('enterprise_id', auth()->id())
            ->select('id', 'name', 'username', 'email', 'email_signature', 'email_signature_enabled')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function selectProfile($profileId)
    {
        $this->selectedProfile = Profile::find($profileId);
        $this->emailSignature = $this->selectedProfile->email_signature ?? '';
        $this->emailSignatureEnabled = $this->selectedProfile->email_signature_enabled ?? false;
        $this->showPreview = false;
    }

    public function togglePreview()
    {
        $this->showPreview = !$this->showPreview;
        if ($this->showPreview) {
            $this->previewSignature = $this->emailSignature;
        }
    }

    public function saveEmailSignature()
    {
        if (!$this->selectedProfile) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'Please select a profile first.',
            ]);
            return;
        }

        $this->selectedProfile->update([
            'email_signature' => $this->emailSignature,
            'email_signature_enabled' => $this->emailSignatureEnabled,
        ]);

        $this->loadProfiles();

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Email signature saved successfully!',
        ]);
    }

    public function toggleSignatureEnabled($profileId)
    {
        $profile = Profile::find($profileId);
        if ($profile && $profile->enterprise_id == auth()->id()) {
            $profile->update([
                'email_signature_enabled' => !$profile->email_signature_enabled
            ]);
            $this->loadProfiles();
        }
    }

    public function deleteSignature($profileId)
    {
        $profile = Profile::find($profileId);
        if ($profile && $profile->enterprise_id == auth()->id()) {
            $profile->update([
                'email_signature' => null,
                'email_signature_enabled' => false
            ]);
            $this->loadProfiles();
            
            if ($this->selectedProfile && $this->selectedProfile->id == $profileId) {
                $this->emailSignature = '';
                $this->emailSignatureEnabled = false;
            }

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'Email signature deleted successfully!',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.enterprise.email-signature');
    }
} 