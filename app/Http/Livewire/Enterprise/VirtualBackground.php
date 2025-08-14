<?php

namespace App\Http\Livewire\Enterprise;

use App\Models\Profile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class VirtualBackground extends Component
{
    use WithFileUploads;

    public $profiles = [];
    public $selectedProfile = null;
    public $virtualBackground = null;
    public $virtualBackgroundEnabled = false;
    public $oldVirtualBackground = null;
    public $showPreview = false;
    public $generatedBackgroundUrl = null;
    public $isGenerating = false;
    public $showBulkModal = false;

    // Element visibility properties
    public $showUsername = true;
    public $showEmail = true;
    public $showAddress = true;
    public $showJobTitle = true;
    public $selectedBackgroundTemplate = 'professional';
    public $previewUrl = null;
    public $customBackgroundUrl = null;
    public $hasCustomBackground = false;
    public $mirrorForVideoCall = false; // New property for mirroring
    public $elementPositions = [
        'username' => ['x' => 50, 'y' => 50],
        'email' => ['x' => 50, 'y' => 100],
        'address' => ['x' => 50, 'y' => 150],
        'job_title' => ['x' => 50, 'y' => 200],
        'qr_code' => ['x' => 1700, 'y' => 50],
        'profile_photo' => ['x' => 50, 'y' => 250]
    ];

    // Background template options
    public $backgroundTemplates = [
        'professional' => [
            'name' => 'Professional',
            'image' => '/assets/img/backgrounds/professional-bg.jpg',
            'description' => 'Clean and corporate style'
        ],
        'creative' => [
            'name' => 'Creative',
            'image' => '/assets/img/backgrounds/creative-bg.jpg',
            'description' => 'Modern and artistic'
        ],
        'minimal' => [
            'name' => 'Minimal',
            'image' => '/assets/img/backgrounds/minimal-bg.jpg',
            'description' => 'Simple and clean'
        ],
        'gradient' => [
            'name' => 'Gradient',
            'image' => '/assets/img/backgrounds/gradient-bg.jpg',
            'description' => 'Colorful gradients'
        ],
        'abstract' => [
            'name' => 'Abstract',
            'image' => '/assets/img/backgrounds/abstract-bg.jpg',
            'description' => 'Abstract patterns'
        ]
    ];

    protected $listeners = [
        'updateElementPosition' => 'updateElementPosition',
        'downloadPreview' => 'downloadPreview'
    ];

    public function mount()
    {
        $this->loadProfiles();
    }

    public function loadProfiles()
    {
        $this->profiles = Profile::where('enterprise_id', auth()->id())
            ->select('id', 'name', 'username', 'email', 'address', 'job_title', 'company', 'photo', 'virtual_background', 'virtual_background_enabled')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function selectProfile($profileId)
    {
        $this->selectedProfile = Profile::find($profileId);
        $this->virtualBackgroundEnabled = $this->selectedProfile->virtual_background_enabled ?? false;
        $this->oldVirtualBackground = $this->selectedProfile->virtual_background;
        $this->virtualBackground = null;
        $this->showPreview = false;
        $this->previewUrl = null;
        $this->customBackgroundUrl = null;
        $this->hasCustomBackground = false;

        // Set all toggles to true by default when profile is selected
        $this->showUsername = true;
        $this->showEmail = true;
        $this->showAddress = true;
        $this->showJobTitle = true;

        // Automatically enable mirroring for video calls when profile is selected
        $this->mirrorForVideoCall = false;

        // Check if profile has custom background
        if ($this->selectedProfile->virtual_background) {
            $this->customBackgroundUrl = Storage::url($this->selectedProfile->virtual_background);
            $this->hasCustomBackground = true;
        }

        // Auto-generate preview when profile is selected
        $this->generatePreview();
    }

    public function generatePreview()
    {
        if (!$this->selectedProfile) {
            return;
        }

        // Trigger client-side preview generation
        $this->dispatchBrowserEvent('generate-preview', [
            'profile' => [
                'name' => $this->selectedProfile->name,
                'username' => $this->selectedProfile->username,
                'email' => $this->selectedProfile->email,
                'address' => $this->selectedProfile->address,
                'job_title' => $this->selectedProfile->job_title,
                'company' => $this->selectedProfile->company,
                'photo' => $this->selectedProfile->photo ? Storage::url($this->selectedProfile->photo) : null,
                'profile_url' => config('app.profile_url') . '/' . $this->selectedProfile->username
            ],
            'settings' => [
                'showUsername' => $this->showUsername,
                'showEmail' => $this->showEmail,
                'showAddress' => $this->showAddress,
                'showJobTitle' => $this->showJobTitle,
                'backgroundTemplate' => $this->selectedBackgroundTemplate,
                'customBackgroundUrl' => $this->customBackgroundUrl,
                'hasCustomBackground' => $this->hasCustomBackground,
                'mirrorForVideoCall' => $this->mirrorForVideoCall,
                'elementPositions' => $this->elementPositions
            ]
        ]);
    }

    public function updateElementPosition($element, $x, $y)
    {
        if (isset($this->elementPositions[$element])) {
            $this->elementPositions[$element] = ['x' => $x, 'y' => $y];
            $this->generatePreview();
        }
    }

    public function toggleElement($element)
    {
        switch ($element) {
            case 'username':
                $this->showUsername = !$this->showUsername;
                break;
            case 'email':
                $this->showEmail = !$this->showEmail;
                break;
            case 'address':
                $this->showAddress = !$this->showAddress;
                break;
            case 'job_title':
                $this->showJobTitle = !$this->showJobTitle;
                break;
        }

        // Regenerate preview immediately after toggle
        $this->generatePreview();
    }

    public function toggleMirrorForVideoCall()
    {
        $this->mirrorForVideoCall = !$this->mirrorForVideoCall;
        $this->generatePreview();
    }

    public function selectBackgroundTemplate($template)
    {
        $this->selectedBackgroundTemplate = $template;
        $this->generatePreview();
    }

    public function saveVirtualBackground()
    {
        if (!$this->selectedProfile) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'Please select a profile first.',
            ]);
            return;
        }

        $data = [
            'virtual_background_enabled' => $this->virtualBackgroundEnabled,
        ];

        if ($this->virtualBackground) {
            // Delete old background if exists
            if ($this->oldVirtualBackground) {
                Storage::disk('public')->delete($this->oldVirtualBackground);
            }

            // Store new background
            $data['virtual_background'] = Storage::disk('public')->put('uploads/virtual-backgrounds', $this->virtualBackground);
        }

        $this->selectedProfile->update($data);
        $this->loadProfiles();

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Virtual background saved successfully!',
        ]);
    }

    public function toggleBackgroundEnabled($profileId = null)
    {
        if ($profileId) {
            // For bulk operations
            $profile = Profile::find($profileId);
            if ($profile && $profile->enterprise_id == auth()->id()) {
                $profile->update([
                    'virtual_background_enabled' => !$profile->virtual_background_enabled
                ]);
                $this->loadProfiles();
            }
        } else {
            // For single profile toggle
            if ($this->selectedProfile) {
                $this->selectedProfile->update([
                    'virtual_background_enabled' => !$this->selectedProfile->virtual_background_enabled
                ]);
                $this->loadProfiles();

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'message' => 'Virtual background ' . ($this->selectedProfile->virtual_background_enabled ? 'enabled' : 'disabled') . ' successfully!',
                ]);
            }
        }
    }

    public function deleteBackground($profileId)
    {
        $profile = Profile::find($profileId);
        if ($profile && $profile->enterprise_id == auth()->id()) {
            // Delete file from storage
            if ($profile->virtual_background) {
                Storage::disk('public')->delete($profile->virtual_background);
            }

            $profile->update([
                'virtual_background' => null,
                'virtual_background_enabled' => false
            ]);
            $this->loadProfiles();

            if ($this->selectedProfile && $this->selectedProfile->id == $profileId) {
                $this->virtualBackground = null;
                $this->virtualBackgroundEnabled = false;
                $this->oldVirtualBackground = null;
                $this->previewUrl = null;
                $this->customBackgroundUrl = null;
                $this->hasCustomBackground = false;
                $this->showUsername = true;
                $this->showEmail = true;
                $this->showAddress = true;
                $this->showJobTitle = true;
                $this->mirrorForVideoCall = false;
            }

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'Virtual background deleted successfully!',
            ]);
        }
    }

    public function downloadPreview()
    {
        if (!$this->selectedProfile) {
            return;
        }

        $this->dispatchBrowserEvent('download-preview', [
            'profile' => [
                'name' => $this->selectedProfile->name,
                'username' => $this->selectedProfile->username,
                'email' => $this->selectedProfile->email,
                'address' => $this->selectedProfile->address,
                'job_title' => $this->selectedProfile->job_title,
                'company' => $this->selectedProfile->company,
                'photo' => $this->selectedProfile->photo ? Storage::url($this->selectedProfile->photo) : null,
                'profile_url' => config('app.profile_url') . '/' . $this->selectedProfile->username
            ],
            'settings' => [
                'showUsername' => $this->showUsername,
                'showEmail' => $this->showEmail,
                'showAddress' => $this->showAddress,
                'showJobTitle' => $this->showJobTitle,
                'backgroundTemplate' => $this->selectedBackgroundTemplate,
                'customBackgroundUrl' => $this->customBackgroundUrl,
                'hasCustomBackground' => $this->hasCustomBackground,
                'mirrorForVideoCall' => true,
                'elementPositions' => $this->elementPositions
            ],
            'filename' => 'virtual_background_' . ($this->selectedProfile->username ?: 'profile') . '.png'
        ]);
    }

    public function render()
    {
        return view('livewire.enterprise.virtual-background');
    }

    // File upload handling
    public function updatedVirtualBackground()
    {
        $this->validate([
            'virtualBackground' => 'image|max:5120', // 5MB max
        ]);

        if ($this->virtualBackground) {
            // Delete old background if exists
            if ($this->oldVirtualBackground) {
                Storage::disk('public')->delete($this->oldVirtualBackground);
            }

            // Store new background
            $path = Storage::disk('public')->put('uploads/virtual-backgrounds', $this->virtualBackground);

            // Update the selected profile
            if ($this->selectedProfile) {
                $this->selectedProfile->update([
                    'virtual_background' => $path,
                    'virtual_background_enabled' => $this->virtualBackgroundEnabled
                ]);

                $this->oldVirtualBackground = $path;
                $this->customBackgroundUrl = Storage::url($path);
                $this->hasCustomBackground = true;
                $this->loadProfiles();

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'message' => 'Virtual background uploaded successfully!',
                ]);

                // Regenerate preview with new custom background
                $this->generatePreview();
            }
        }
    }

    public function triggerFileUpload()
    {
        $this->dispatchBrowserEvent('trigger-file-upload');
    }
}
