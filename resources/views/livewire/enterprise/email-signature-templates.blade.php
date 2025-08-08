<div class="bg-light min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow rounded-4 p-4 border-0">
                    <div class="row g-4 align-items-start">
                        <!-- Left: List or Form -->
                        <div class="col-md-6">
                            @if ($step === 'list')
                                <div class="fw-bold mb-3" style="font-size:1.1rem;">Email Signature Templates</div>
                                
                                <!-- Profile Selection Dropdown -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Select Profile</label>
                                    <select class="form-select" wire:model="selectedProfileId">
                                        @foreach ($profiles as $profile)
                                            <option value="{{ $profile->id }}">
                                                {{ $profile->name ?? $profile->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <button class="btn btn-dark rounded-pill px-4 mb-3" wire:click="showCreate">+ Create
                                    Email Signature</button>
                                
                                @if($selectedProfileId)
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($templates as $template)
                                                <tr>
                                                    <td>{{ $template->name }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            wire:click="showView({{ $template->id }})">View</button>
                                                        <button class="btn btn-sm btn-outline-secondary"
                                                            wire:click="showEdit({{ $template->id }})">Edit</button>
                                                        <button class="btn btn-sm btn-outline-danger"
                                                            wire:click="deleteTemplate({{ $template->id }})">Delete</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">
                                                        No templates found for this profile. Create your first template!
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bx bx-user bx-lg mb-2"></i>
                                        <p>Please select a profile to view templates</p>
                                    </div>
                                @endif
                            @else
                                <form wire:submit.prevent="saveTemplate">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Select Profile</label>
                                        <select class="form-select" wire:model="selectedProfileId">
                                            @foreach ($profiles as $profile)
                                                <option value="{{ $profile->id }}">
                                                    {{ $profile->name ?? $profile->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold" style="font-size:1.1rem;">Signature
                                            Name*</label>
                                        <input type="text" class="form-control rounded-pill"
                                            wire:model="templateName" placeholder="Enter signature name">
                                    </div>
                                    <div class="mb-4">
                                        <div class="fw-bold mb-2">Information</div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-6 d-flex align-items-center">
                                                <label class="me-2 mb-0">Name</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="fields.name">
                                                </div>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <label class="me-2 mb-0">Pronouns</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="fields.pronouns">
                                                </div>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <label class="me-2 mb-0">Job Title</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="fields.job_title">
                                                </div>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <label class="me-2 mb-0">Company Name</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="fields.company_name">
                                                </div>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <label class="me-2 mb-0">Location</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="fields.location">
                                                </div>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <label class="me-2 mb-0">Phone Number</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="fields.phone_number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="fw-bold mb-2">Images</div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-12 d-flex align-items-center">
                                                <label class="me-2 mb-0">Profile Pic</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="images.profile_pic">
                                                </div>
                                                <span class="text-muted ms-2" style="font-size:0.9em;">Member profile
                                                    photo will be displayed in their signature</span>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <label class="me-2 mb-0">Company Logo</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="images.company_logo">
                                                </div>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <label class="me-2 mb-0">GOCode</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="images.gocode">
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex align-items-center">
                                                <label class="me-2 mb-0">Banner</label>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="images.banner">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="border rounded-3 p-3 text-center" style="background:#fafafa;">
                                                <i class="bx bx-upload bx-lg text-muted mb-2"></i><br>
                                                <span class="fw-bold">Select</span> file or drag and drop one here
                                                <div class="row mt-2">
                                                    @if ($images['banner'] ?? false)
                                                        <div class="col-12 mb-2">
                                                            <input type="file" wire:model="imageFiles.banner"
                                                                class="form-control form-control-sm rounded-pill">
                                                            <span class="small text-muted">Banner</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                            wire:click="showList">Cancel</button>
                                        <button type="submit"
                                            class="btn btn-primary rounded-pill px-4">{{ $editMode ? 'Update' : 'Save' }}</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                        <!-- Right: Card Preview -->
                        <div class="col-md-6">
                            <div class="text-center mb-2 fw-bold" style="font-size:1.1rem;">Signature Preview</div>
                            @if($selectedProfileId && $this->selectedProfile)
                                <div class="d-flex justify-content-center">
                                    <div class="bg-white rounded-4 shadow-sm p-4 position-relative"
                                        style="min-width: 370px; min-height: 270px; display: flex; flex-direction: column; align-items: center; @if (($images['banner'] ?? false) && $imageFiles['banner']) background: url('{{ $imageFiles['banner']->temporaryUrl() }}') center/cover no-repeat;@elseif(($images['banner'] ?? false) && ($previewData['banner_path'] ?? null))background: url('{{ asset('storage/' . ($previewData['banner_path'] ?? '')) }}') center/cover no-repeat; @endif">
                                        <div
                                            style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.85);z-index:1;@if (($images['banner'] ?? false) && ($imageFiles['banner'] || $previewData['banner_path'] ?? null)) opacity:0.85;@else opacity:1; @endif">
                                        </div>
                                        <!-- Profile photo/logo in top right -->
                                        <div
                                            style="position:absolute;top:18px;right:18px;z-index:3;width:48px;height:48px;">
                                            @if (($images['profile_pic'] ?? false) && ($previewData['profile_pic'] ?? null))
                                                <img src="{{ asset('storage/' . ($previewData['profile_pic'] ?? '')) }}"
                                                    alt="Profile" class="rounded-circle"
                                                    style="width:48px;height:48px;object-fit:cover;">
                                            @else
                                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                                                    style="width:48px;height:48px;">
                                                    <svg width="28" height="28" fill="#bbb"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex w-100 align-items-start justify-content-between mb-2 position-relative"
                                            style="z-index:2;">
                                            <!-- Fields -->
                                            <div class="d-flex flex-column align-items-start" style="min-width: 80px;">
                                                @if (($images['company_logo'] ?? false) && auth()->user()->enterprise_logo)
                                                    <img class="rounded-circle"
                                                        src="{{ asset('storage/' . auth()->user()->enterprise_logo) }}"
                                                        alt="Enterprise Logo"
                                                        style="top:18px;left:18px;width:40px;height:40px;">
                                                @endif
                                                <div class="mt-1" style="font-size:1rem;">
                                                    @if (($fields['name'] ?? false) && ($previewData['name'] ?? null))
                                                        <div>{{ $previewData['name'] ?? '' }}</div>
                                                    @endif
                                                    @if (($fields['job_title'] ?? false) && ($previewData['job_title'] ?? null))
                                                        <div>{{ $previewData['job_title'] ?? '' }}</div>
                                                    @endif
                                                    @if (($fields['company_name'] ?? false) && ($previewData['company_name'] ?? null))
                                                        <div>{{ $previewData['company_name'] ?? '' }}</div>
                                                    @endif
                                                    @if (($fields['phone_number'] ?? false) && ($previewData['phone_number'] ?? null))
                                                        <div>{{ $previewData['phone_number'] ?? '' }}</div>
                                                    @endif
                                                    @if (($fields['location'] ?? false) && ($previewData['location'] ?? null))
                                                        <div>{{ $previewData['location'] ?? '' }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- QR/GOCode Area -->
                                            <div class="flex-grow-1 d-flex flex-column align-items-center"
                                                style="min-width: 150px;">
                                                <div class="fw-normal text-muted mb-1" style="font-size:0.95em;">Connect
                                                    with Me</div>
                                                <div class="d-flex align-items-center justify-content-center position-relative"
                                                    style="height:110px;width:110px;">
                                                    @php
                                                        $profileImagePath = null;
                                                        if (
                                                            ($images['profile_pic'] ?? false) &&
                                                            ($previewData['profile_pic'] ?? null)
                                                        ) {
                                                            $profileImagePath = public_path(
                                                                'storage/' . ($previewData['profile_pic'] ?? ''),
                                                            );
                                                        }
                                                    @endphp
                                                    @if ($images['gocode'] ?? false)
                                                        <div style="position:relative;width:100px;height:100px;">
                                                            {{-- @if ($profileImagePath)
                                                                {!! QrCode::format('png')->size(100)->margin(1)->merge($profileImagePath, 0.3, true)->generate(config('app.url') . '/profile/' . ($this->selectedProfile ? $this->selectedProfile->id : '')) !!}
                                                            @else --}}
                                                            {!! QrCode::size(100)->margin(1)->generate(config('app.url') . '/' . ($this->selectedProfile ? $this->selectedProfile->username : '')) !!}
                                                            {{-- @endif --}}
                                                        </div>
                                                    @else
                                                        <div class="bg-light border rounded-3"
                                                            style="width:100px;height:100px;"></div>
                                                    @endif
                                                </div>
                                                <div class="mt-2">
                                                    <a href="{{ config('app.url') . '/' . ($this->selectedProfile ? $this->selectedProfile->username : '') }}"
                                                        class="text-primary"
                                                        style="font-weight: 500; font-size:0.98em;">My Digital Business
                                                        card</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-2 text-muted" style="font-size:0.98em;">
                                    Preview based on {{ $previewData['email'] ?? '' }}
                                </div>
                                @if($step === 'list')
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <strong>Tip:</strong> Select a template and click "View" to see the full signature with copy functionality.
                                        </small>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-center mt-3">
                                        <button id="copyRichBtn" class="btn btn-outline-primary"
                                            onclick="copyRichSignature()">Copy Signature
                                            <span id="richTooltip" class="tooltip-text">Copied!</span>
                                        </button>
                                        <div id="signatureContainer" style="display:none; position:absolute; left:-9999px;">
                                            @include('livewire.enterprise.signature-html', [
                                                'preview' => $previewData,
                                                'profile' => $this->selectedProfile,
                                            ])
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <strong>How to use:</strong> Click "Copy Rich Text" to copy your email signature with QR code, then paste it directly into Gmail's signature settings.
                                        </small>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <i class="bx bx-user bx-lg text-muted mb-3"></i>
                                    <p class="text-muted">Please select a profile to see the signature preview</p>
                                </div>
                            @endif
                            <style>
                                .tooltip-text {
                                    visibility: hidden;
                                    background-color: #333;
                                    color: #fff;
                                    text-align: center;
                                    border-radius: 4px;
                                    padding: 4px 8px;
                                    position: absolute;
                                    top: -30px;
                                    left: 50%;
                                    transform: translateX(-50%);
                                    font-size: 0.75rem;
                                    z-index: 10;
                                    opacity: 0;
                                    transition: opacity 0.3s ease;
                                }

                                .btn.show-tooltip .tooltip-text {
                                    visibility: visible;
                                    opacity: 1;
                                }
                            </style>

                            <script>
                                function copyRichSignature() {
                                    const signatureContainer = document.getElementById('signatureContainer');
                                    const btn = document.getElementById('copyRichBtn');
                                    
                                    // Show the container temporarily
                                    signatureContainer.style.display = 'block';
                                    
                                    try {
                                        // Create a range and selection
                                        const range = document.createRange();
                                        range.selectNodeContents(signatureContainer);
                                        
                                        const selection = window.getSelection();
                                        selection.removeAllRanges();
                                        selection.addRange(range);
                                        
                                        // Copy the selection
                                        const successful = document.execCommand('copy');
                                        
                                        if (successful) {
                                            // Show success tooltip
                                            btn.classList.add('show-tooltip');
                                            
                                            // Hide tooltip after 2 seconds
                                            setTimeout(() => {
                                                btn.classList.remove('show-tooltip');
                                            }, 2000);
                                        } else {
                                            // Fallback to HTML copy
                                            const htmlContent = signatureContainer.innerHTML;
                                            const textarea = document.createElement('textarea');
                                            textarea.value = htmlContent;
                                            document.body.appendChild(textarea);
                                            textarea.select();
                                            document.execCommand('copy');
                                            document.body.removeChild(textarea);
                                            
                                            // Show success tooltip
                                            btn.classList.add('show-tooltip');
                                            
                                            // Hide tooltip after 2 seconds
                                            setTimeout(() => {
                                                btn.classList.remove('show-tooltip');
                                            }, 2000);
                                        }
                                    } catch (err) {
                                        console.error('Error copying rich text:', err);
                                        // Fallback to HTML copy
                                        const htmlContent = signatureContainer.innerHTML;
                                        const textarea = document.createElement('textarea');
                                        textarea.value = htmlContent;
                                        document.body.appendChild(textarea);
                                        textarea.select();
                                        document.execCommand('copy');
                                        document.body.removeChild(textarea);
                                        
                                        // Show success tooltip
                                        btn.classList.add('show-tooltip');
                                        
                                        // Hide tooltip after 2 seconds
                                        setTimeout(() => {
                                            btn.classList.remove('show-tooltip');
                                        }, 2000);
                                    } finally {
                                        // Clear selection
                                        window.getSelection().removeAllRanges();
                                        
                                        // Hide the container
                                        signatureContainer.style.display = 'none';
                                    }
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
