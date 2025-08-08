<?php

namespace App\Http\Livewire\Enterprise;

use App\Models\Profile;
use App\Models\EmailSignatureTemplate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EmailSignatureTemplates extends Component
{
    use WithFileUploads;

    public $profiles = [];
    public $selectedProfileId = null;
    public $templates = [];
    public $selectedTemplate = null;
    public $templateName = '';
    public $fields = [
        'name' => true,
        'pronouns' => false,
        'job_title' => true,
        'company_name' => true,
        'location' => true,
        'phone_number' => true,
    ];
    public $images = [
        'profile_pic' => true,
        'company_logo' => false,
        'gocode' => false,
        'banner' => false,
    ];
    public $imageFiles = [
        'banner' => null,
    ];
    public $previewData = [];
    public $editMode = false;
    public $templateCount = 0;
    public $step = 'list'; // 'list', 'create', 'edit', 'view'
    public $selectedTemplateId = null;
    public $signatureHtml = '';
    protected $defaultFields = [
        'name' => true,
        'pronouns' => false,
        'job_title' => true,
        'company_name' => true,
        'location' => true,
        'phone_number' => true,
    ];
    protected $defaultImages = [
        'profile_pic' => true,
        'company_logo' => false,
        'gocode' => false,
        'banner' => false,
    ];

    public function mount()
    {
        $this->profiles = Profile::where('enterprise_id', auth()->id())->get();
        if ($this->profiles->count() > 0) {
            $this->selectedProfileId = $this->profiles->first()->id;
        }
        $this->showList();
    }

    public function getSelectedProfileProperty()
    {
        return $this->profiles->where('id', $this->selectedProfileId)->first();
    }

    public function updatedSelectedProfileId()
    {
        $this->loadTemplates();
        $this->resetTemplateForm();
        $this->previewData = $this->generatePreviewData();
    }

    public function showList()
    {
        $this->step = 'list';
        $this->loadTemplates();
        $this->resetTemplateForm();
        $this->previewData = $this->generatePreviewData();
    }

    public function showCreate()
    {
        $this->step = 'create';
        $this->resetTemplateForm();
        $this->previewData = $this->generatePreviewData();
    }

    public function showEdit($id)
    {
        $this->step = 'edit';
        $this->selectTemplate($id);
        $this->previewData = $this->generatePreviewData();
    }

    public function showView($id)
    {
        $this->step = 'view';
        $this->selectTemplate($id);
        $this->signatureHtml = $this->generateSignatureHtml();
        $this->previewData = $this->generatePreviewData();
    }

    public function loadTemplates()
    {
        if ($this->selectedProfileId) {
            $this->templates = EmailSignatureTemplate::where('profile_id', $this->selectedProfileId)->get();
            $this->templateCount = $this->templates->count();
        } else {
            $this->templates = collect();
            $this->templateCount = 0;
        }
    }

    public function selectTemplate($templateId)
    {
        $template = EmailSignatureTemplate::find($templateId);
        if ($template) {
            $this->selectedTemplate = $template;
            $this->selectedTemplateId = $template->id;
            $this->templateName = $template->name;
            $this->fields = array_merge($this->defaultFields, $template->fields ?? []);
            $this->images = array_merge($this->defaultImages, $template->images ?? []);
            
            // Initialize previewData with default values and merge with saved data
            $defaultPreviewData = [
                'name' => null,
                'pronouns' => null,
                'job_title' => null,
                'company_name' => null,
                'location' => null,
                'phone_number' => null,
                'profile_pic' => null,
                'email' => null,
                'banner' => null,
                'banner_path' => null,
                'banner_temp' => null,
            ];
            $this->previewData = array_merge($defaultPreviewData, $template->preview_data ?? []);
            $this->editMode = true;
        }
    }

    public function createTemplate()
    {
        $this->resetTemplateForm();
        $this->editMode = false;
        $this->step = 'create';
        $this->previewData = $this->generatePreviewData();
    }

    public function saveTemplate()
    {
        $data = [
            'profile_id' => $this->selectedProfileId,
            'name' => $this->templateName,
            'fields' => $this->fields,
            'images' => $this->images,
            'preview_data' => $this->generatePreviewData(),
        ];
        // Handle banner upload
        if ($this->imageFiles['banner']) {
            $data['images']['banner_path'] = Storage::disk('public')->put('uploads/signature-templates', $this->imageFiles['banner']);
        }
        if ($this->editMode && $this->selectedTemplate) {
            $this->selectedTemplate->update($data);
        } else {
            EmailSignatureTemplate::create($data);
        }
        $this->showList();
    }

    public function deleteTemplate($templateId)
    {
        $template = EmailSignatureTemplate::find($templateId);
        if ($template) {
            $template->delete();
            $this->showList();
        }
    }

    public function resetTemplateForm()
    {
        $this->selectedTemplate = null;
        $this->selectedTemplateId = null;
        $this->templateName = '';
        $this->fields = $this->defaultFields;
        $this->images = $this->defaultImages;
        $this->imageFiles = [
            'banner' => null,
        ];
        $this->previewData = [];
        $this->editMode = false;
        $this->signatureHtml = '';
    }

    public function updated($propertyName)
    {
        $this->previewData = $this->generatePreviewData();
    }

    public function updatedImageFiles($propertyName)
    {
        if ($propertyName === 'imageFiles.banner' && $this->imageFiles['banner']) {
            // Store the banner temporarily for preview
            $this->previewData['banner_temp'] = $this->imageFiles['banner']->getRealPath();
        }
    }

    public function generatePreviewData()
    {
        $profile = $this->selectedProfile;
        if (!$profile) {
            return [];
        }
        
        $previewData = [
            'name' => $this->fields['name'] ? $profile->name : null,
            'pronouns' => $this->fields['pronouns'] ? ($profile->pronouns ?? null) : null,
            'job_title' => $this->fields['job_title'] ? $profile->job_title : null,
            'company_name' => $this->fields['company_name'] ? $profile->company : null,
            'location' => $this->fields['location'] ? $profile->address : null,
            'phone_number' => $this->fields['phone_number'] ? $profile->phone : null,
            'profile_pic' => ($this->images['profile_pic'] ?? false) ? ($profile->photo ?? null) : null,
            'email' => $profile->email ?? null,
            'banner' => null,
            'banner_path' => null,
            'banner_temp' => null,
        ];

        // Handle banner data
        if ($this->images['banner'] ?? false) {
            if ($this->selectedTemplate && isset($this->selectedTemplate->images['banner_path'])) {
                $previewData['banner_path'] = $this->selectedTemplate->images['banner_path'];
            } elseif (isset($this->previewData['banner_temp'])) {
                $previewData['banner_temp'] = $this->previewData['banner_temp'];
            }
        }

        return $previewData;
    }

    public function generateSignatureHtml()
    {
        $profile = $this->selectedProfile;
        $preview = $this->generatePreviewData();
        $qr = \QrCode::size(90)->margin(1)->generate(config('app.url').'/profile/'.($profile ? $profile->id : ''));
        return view('livewire.enterprise.signature-html', compact('preview', 'profile', 'qr'))->render();
    }

    public function render()
    {
        return view('livewire.enterprise.email-signature-templates');
    }
} 