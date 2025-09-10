<div>
    <style>
        .signature-preview {
            border: 1px solid #ddd;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .profile-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .profile-card.selected {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
        
        .signature-editor {
            min-height: 200px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Email Signature Management</h4>
                        <p class="card-text">Create and manage email signatures for your team profiles</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Profile Selection -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Select Profile</h5>
                                    </div>
                                    <div class="card-body">
                                        @if(count($profiles) > 0)
                                            @foreach($profiles as $profile)
                                                <div class="card profile-card mb-3 {{ $selectedProfile && $selectedProfile->id == $profile->id ? 'selected' : '' }}"
                                                     wire:click="selectProfile({{ $profile->id }})">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar avatar-sm">
                                                                    <span class="avatar-initial rounded-circle bg-primary">
                                                                        {{ strtoupper(substr($profile->name ?? $profile->username, 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6 class="mb-1">{{ $profile->name ?? $profile->username }}</h6>
                                                                <small class="text-muted">{{ $profile->email }}</small>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" 
                                                                           wire:click.stop="toggleSignatureEnabled({{ $profile->id }})"
                                                                           {{ $profile->email_signature_enabled ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($profile->email_signature)
                                                            <div class="mt-2">
                                                                <small class="text-success">
                                                                    <i class="bx bx-check-circle"></i> Signature configured
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-4">
                                                <i class="bx bx-user-plus bx-lg text-muted"></i>
                                                <p class="text-muted mt-2">No profiles found. Create profiles first.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Signature Editor -->
                            <div class="col-md-8">
                                @if($selectedProfile)
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                Email Signature for {{ $selectedProfile->name ?? $selectedProfile->username }}
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Email Signature Content</label>
                                                <div class="signature-editor">
                                                    <textarea wire:model="emailSignature" 
                                                              class="form-control border-0" 
                                                              rows="8" 
                                                              placeholder="Enter your email signature here...&#10;&#10;You can use HTML tags for formatting.&#10;Example:&#10;&lt;strong&gt;John Doe&lt;/strong&gt;&#10;&lt;em&gt;Marketing Manager&lt;/em&gt;&#10;&lt;br&gt;&#10;Company Name&#10;&lt;br&gt;&#10;Phone: +1 234 567 8900"></textarea>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           wire:model="emailSignatureEnabled" 
                                                           id="enableSignature">
                                                    <label class="form-check-label" for="enableSignature">
                                                        Enable email signature for this profile
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-primary" wire:click="saveEmailSignature">
                                                    <i class="bx bx-save"></i> Save Signature
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary" wire:click="togglePreview">
                                                    <i class="bx bx-show"></i> {{ $showPreview ? 'Hide' : 'Show' }} Preview
                                                </button>
                                                @if($selectedProfile->email_signature)
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            wire:click="deleteSignature({{ $selectedProfile->id }})">
                                                        <i class="bx bx-trash"></i> Delete
                                                    </button>
                                                @endif
                                            </div>

                                            @if($showPreview)
                                                <div class="mt-4">
                                                    <h6>Preview:</h6>
                                                    <div class="signature-preview">
                                                        {!! $previewSignature !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="card">
                                        <div class="card-body text-center py-5">
                                            <i class="bx bx-edit bx-lg text-muted"></i>
                                            <h5 class="mt-3">Select a Profile</h5>
                                            <p class="text-muted">Choose a profile from the left panel to manage its email signature.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 