<div>
    <style>
        .background-preview {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 15px;
        }

        .profile-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-card.selected {
            border-color: #007bff;
            background-color: #f8f9ff;
        }

        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .upload-area:hover {
            border-color: #007bff;
            background-color: #f8f9ff;
        }

        .upload-area.dragover {
            border-color: #007bff;
            background-color: #e3f2fd;
        }

        .background-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .element-toggle {
            transition: all 0.3s ease;
        }

        .element-toggle:hover {
            transform: scale(1.05);
        }

        .background-template {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .background-template:hover {
            transform: scale(1.02);
            border-color: #007bff;
        }

        .background-template.selected {
            border-color: #007bff;
            background-color: #f8f9ff;
        }

        .preview-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .preview-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .element-controls {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1rem;
        }

        .template-item {
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .template-item:hover {
            border-color: #0d6efd;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transform: translateY(-2px);
        }

        .template-item.selected {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .template-item.loading .loading-overlay {
            display: flex;
        }

        #preview-canvas {
            width: 100%;
            max-width: 800px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Team Profiles</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach ($profiles as $profile)
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center {{ $selectedProfile && $selectedProfile->id == $profile->id ? 'active' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $profile->name ?: $profile->username }}</h6>
                                            <small
                                                class="text-muted">{{ $profile->job_title ?: 'No job title' }}</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-dark"
                                        wire:click="selectProfile({{ $profile->id }})">
                                        Select
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                @if ($selectedProfile)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Virtual Background Generator -
                                {{ $selectedProfile->name ?: $selectedProfile->username }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Element Controls -->
                            <div class="element-controls">
                                <h6 class="mb-3"><i class="bx bx-cog"></i> Element Controls</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="toggleElement('username')"
                                                {{ $showUsername ? 'checked' : '' }}
                                                {{ $selectedProfile->name || $selectedProfile->username ? '' : 'disabled' }}>
                                            <label class="form-check-label">
                                                <i class="bx bx-user"></i> Username
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="toggleElement('email')" {{ $showEmail ? 'checked' : '' }}
                                                {{ $selectedProfile->email ? '' : 'disabled' }}>
                                            <label class="form-check-label">
                                                <i class="bx bx-envelope"></i> Email
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="toggleElement('address')"
                                                {{ $showAddress ? 'checked' : '' }}
                                                {{ $selectedProfile->address ? '' : 'disabled' }}>
                                            <label class="form-check-label">
                                                <i class="bx bx-map"></i> Address
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="toggleElement('job_title')"
                                                {{ $showJobTitle ? 'checked' : '' }}
                                                {{ $selectedProfile->job_title ? '' : 'disabled' }}>
                                            <label class="form-check-label">
                                                <i class="bx bx-briefcase"></i> Job Title
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Background Template Selection (Only show if no custom background) -->
                            <div class="mb-4">
                                <h6 class="mb-3"><i class="bx bx-palette"></i> Background Template</h6>
                                <div class="template-grid">
                                    @foreach ($backgroundTemplates as $key => $template)
                                        <div class="template-item position-relative {{ !$hasCustomBackground && $selectedBackgroundTemplate === $key ? 'selected' : '' }}"
                                            wire:click="selectBackgroundTemplate('{{ $key }}')"
                                            wire:key="template-{{ $key }}" wire:loading.class="loading"
                                            wire:target="selectBackgroundTemplate">

                                            <!-- Loading indicator -->
                                            <div class="loading-overlay">
                                                <div class="spinner-border spinner-border-sm text-primary"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>

                                            <!-- Template image -->
                                            <div class="mb-2 ratio ratio-16x9">
                                                <img src="{{ $template['image'] }}" alt="{{ $template['name'] }}"
                                                    class="img-fluid rounded" loading="lazy"
                                                    style="object-fit: cover; height: 100%; width: 100%;">
                                            </div>

                                            <h6 class="mb-1">{{ $template['name'] }}</h6>
                                            <small class="text-muted">{{ $template['description'] }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Live Preview -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0"><i class="bx bx-show"></i> Live Preview</h6>
                                    <button type="button" class="btn btn-success"
                                        onclick="downloadVirtualBackground()">
                                        <i class="bx bx-download"></i> Download
                                    </button>
                                </div>
                                <div class="alert alert-info mb-3">
                                    <i class="bx bx-info-circle"></i>
                                    <strong>On Download Mirror Mode Active:</strong> This image is mirrored for video
                                    calls.
                                    Text will appear correctly in Google Meet, Zoom, and other video platforms.
                                </div>

                                <div class="preview-container">
                                    <canvas id="preview-canvas" width="1920" height="1080"></canvas>
                                </div>

                                <!-- Hidden QR Code for canvas use -->
                                <div id="qr-code-container" style="display: none;">
                                    {!! QrCode::size(200)->margin(1)->generate(config('app.profile_url') . '/' . ($this->selectedProfile ? $this->selectedProfile->username : '')) !!}
                                </div>
                            </div>

                            <!-- Upload Section (Optional) -->
                            {{-- <div class="mb-4">
                                <h6 class="mb-3"><i class="bx bx-upload"></i> Custom Background (Optional)</h6>

                                <div class="upload-area border rounded p-4 text-center" wire:click="triggerFileUpload()"
                                    style="cursor: pointer; border-style: dashed !important;">

                                    @if ($selectedProfile->virtual_background)
                                        <img src="{{ Storage::url($selectedProfile->virtual_background) }}"
                                            alt="Current Background" class="img-fluid mb-2" style="max-height: 200px;">
                                        <p class="text-muted mb-0">Click to change custom background</p>
                                    @else
                                        <i class="bx bx-upload fs-1 text-muted"></i>
                                        <p class="text-muted mb-0">Click to upload custom background (optional)</p>
                                        <small class="text-muted">If no custom background is uploaded, template
                                            backgrounds will be used</small>
                                    @endif
                                </div>

                                <input type="file" wire:model="virtualBackground" id="backgroundUpload"
                                    accept="image/*" class="d-none">
                                @if ($selectedProfile->virtual_background)
                                    <div class="mt-3 text-end">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            wire:click="deleteBackground({{ $selectedProfile->id }})">
                                            <i class="bx bx-trash"></i> Remove Custom Background
                                        </button>
                                    </div>
                                @endif
                            </div> --}}
                            <div class="mb-4">
                                <h6 class="mb-3"><i class="bx bx-upload"></i> Custom Background (Optional)</h6>

                                @if ($selectedProfile->virtual_background)
                                    <!-- Display only (non-clickable) when background exists -->
                                    <div class="border rounded p-4 text-center"
                                        style="border-style: dashed !important;">
                                        <img src="{{ Storage::url($selectedProfile->virtual_background) }}"
                                            alt="Current Background" class="img-fluid mb-2" style="max-height: 200px;">
                                        <p class="text-muted mb-0">Custom background is set</p>
                                    </div>
                                @else
                                    <!-- Clickable upload area when no background exists -->
                                    <div class="upload-area border rounded p-4 text-center"
                                        wire:click="triggerFileUpload()"
                                        style="cursor: pointer; border-style: dashed !important;">
                                        <i class="bx bx-upload fs-1 text-muted"></i>
                                        <p class="text-muted mb-0">Click to upload custom background (optional)</p>
                                        <small class="text-muted">If no custom background is uploaded, template
                                            backgrounds will be used</small>
                                    </div>
                                @endif

                                <input type="file" wire:model="virtualBackground" id="backgroundUpload"
                                    accept="image/*" class="d-none">

                                @if ($selectedProfile->virtual_background)
                                    <div class="mt-3 text-end">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            wire:click="deleteBackground({{ $selectedProfile->id }})">
                                            <i class="bx bx-trash"></i> Remove Custom Background
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bx bx-user fs-1 text-muted mb-3"></i>
                            <h5>Select a Profile</h5>
                            <p class="text-muted">Choose a profile from the left panel to generate their virtual
                                background.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script>
            // Debounce function to improve performance
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // QR Code generation function using Laravel QR Code
            function generateQRCode() {
                return new Promise((resolve) => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = 200;
                    canvas.height = 200;

                    // Get the QR code from the hidden div
                    const qrContainer = document.getElementById('qr-code-container');
                    if (qrContainer && qrContainer.innerHTML) {
                        // Convert SVG to canvas
                        const img = new Image();
                        img.onload = function() {
                            ctx.drawImage(img, 0, 0, 200, 200);
                            resolve(canvas);
                        };

                        // Convert SVG to data URL
                        const svgContent = qrContainer.innerHTML;
                        const svgBlob = new Blob([svgContent], {
                            type: 'image/svg+xml'
                        });
                        const url = URL.createObjectURL(svgBlob);
                        img.src = url;
                    } else {
                        // Fallback to simple QR code
                        ctx.fillStyle = '#000';
                        ctx.fillRect(0, 0, 200, 200);
                        ctx.fillStyle = '#fff';
                        ctx.fillRect(10, 10, 180, 180);
                        ctx.fillStyle = '#000';
                        ctx.font = '12px Arial';
                        ctx.textAlign = 'center';
                        ctx.fillText('QR Code', 100, 100);
                        resolve(canvas);
                    }
                });
            }

            // Debounced version of generateVirtualBackground
            const debouncedGenerateVirtualBackground = debounce(generateVirtualBackground, 50);

            function generateVirtualBackground(data) {
                console.log('Generate preview with data:', data);

                const canvas = document.getElementById('preview-canvas');
                const ctx = canvas.getContext('2d');

                // Clear canvas and reset transformations (always show normal preview)
                ctx.clearRect(0, 0, 1920, 1080);
                ctx.setTransform(1, 0, 0, 1, 0, 0);

                // Load background image
                const backgroundImg = new Image();
                backgroundImg.crossOrigin = 'anonymous';

                backgroundImg.onload = function() {
                    // Draw background
                    ctx.drawImage(backgroundImg, 0, 0, 1920, 1080);

                    // Add overlay for better text readability
                    ctx.fillStyle = 'rgba(0, 0, 0, 0.4)';
                    ctx.fillRect(0, 0, 1920, 1080);

                    // Add profile information based on visibility settings
                    ctx.fillStyle = '#ffffff';
                    ctx.font = 'bold 48px Arial';
                    ctx.textAlign = 'left';

                    let yPosition = 100;

                    if (data.settings.showUsername && (data.profile.name || data.profile.username)) {
                        ctx.fillText(data.profile.name || data.profile.username, 100, yPosition);
                        yPosition += 80;
                    }

                    if (data.settings.showEmail && data.profile.email) {
                        ctx.font = '32px Arial';
                        ctx.fillText(data.profile.email, 100, yPosition);
                        yPosition += 60;
                    }

                    if (data.settings.showAddress && data.profile.address) {
                        ctx.font = '28px Arial';
                        ctx.fillText(data.profile.address, 100, yPosition);
                        yPosition += 50;
                    }

                    if (data.settings.showJobTitle && data.profile.job_title) {
                        ctx.font = '24px Arial';
                        ctx.fillText(data.profile.job_title, 100, yPosition);
                        yPosition += 40;
                    }

                    if (data.profile.company) {
                        ctx.font = '20px Arial';
                        ctx.fillText(data.profile.company, 100, yPosition);
                    }

                    // Always add QR code with profile URL
                    generateQRCode().then(qrCanvas => {
                        ctx.drawImage(qrCanvas, 1700, 100, 150, 150);
                    });
                };

                // Set background source
                if (data.settings.hasCustomBackground && data.settings.customBackgroundUrl) {
                    backgroundImg.src = data.settings.customBackgroundUrl;
                } else {
                    const template = data.settings.backgroundTemplate;
                    const templateUrl = `/assets/img/backgrounds/${template}-bg.jpg`;
                    backgroundImg.src = templateUrl;
                }
            }

            function downloadVirtualBackground() {
                const canvas = document.getElementById('preview-canvas');
                if (!canvas) {
                    alert('No preview available. Please select a profile first.');
                    return;
                }

                // Get profile name for filename
                const profileName = document.querySelector('.card-title')?.textContent?.split(' - ')[1] || 'profile';
                const filename = `virtual_background_${profileName.replace(/[^a-zA-Z0-9]/g, '_')}.png`;

                // Create a temporary canvas to apply mirroring
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = canvas.width;
                tempCanvas.height = canvas.height;
                const ctx = tempCanvas.getContext('2d');

                // Apply mirror transformation for download
                ctx.scale(-1, 1);
                ctx.translate(-canvas.width, 0);
                ctx.drawImage(canvas, 0, 0);

                // Convert canvas to blob and download
                tempCanvas.toBlob(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                }, 'image/png');
            }

            // Listen for preview generation event
            window.addEventListener('generate-preview', event => {
                debouncedGenerateVirtualBackground(event.detail);
            });

            // Listen for file upload trigger
            window.addEventListener('trigger-file-upload', event => {
                document.getElementById('backgroundUpload').click();
            });
            window.addEventListener('swal:modal', event => {
                swal({
                    title: event.detail.message,
                    icon: event.detail.type,
                });
            });
        </script>
    </div>
</div>
