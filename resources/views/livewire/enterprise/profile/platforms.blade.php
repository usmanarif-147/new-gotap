<div>
    @if ($tab_change == 2)
        <style>
            .scroll-container {
                overflow-y: auto;
                scrollbar-width: none;
                -ms-overflow-style: none;
                /* padding-right: 15px; */
            }

            .scroll-container::-webkit-scrollbar {
                display: none;
            }
        </style>
        <div class="row">
            <div class="col-md-3 ms-auto">
                <label for=""> Search </label>
                <input class="form-control me-2" type="text" wire:model.debounce.500ms="searchTerm" placeholder="Search"
                    aria-label="Search">
            </div>
            <div class="row">
                <div class="col-xl-8" style="background-color: white;border-radius:10px;">
                    <div style="background-color: white;border-radius:10px;">
                        <h5 class="py-3 px-3">
                            Platforms
                        </h5>
                    </div>
                    <div class="row scroll-container" style="height: 80vh;">
                        @foreach ($platforms as $category)
                            <h5 class="fs-4 fw-bolder my-4 text-dark">
                                {{ $category['name'] }}
                            </h5>
                            @foreach ($category['platforms'] as $platforms)
                                <div class="col-md-6 mb-2">
                                    <div class="card">
                                        <div class="card-body px-4 py-3 shadow">
                                            <div class="row">
                                                <div class="col-2 align-content-center" style="height: 75px;width:75px">
                                                    <img src="{{ asset($platforms['icon'] && Storage::exists($platforms['icon']) ? Storage::url($platforms['icon']) : 'pbg.png') }}"
                                                        class="img-fluid rounded" height="100%" width="100%">
                                                </div>
                                                <div class="col-6 align-content-center">
                                                    <h5 class="card-text">{{ $platforms['title'] }}</h5>
                                                </div>
                                                <div class="col-2 align-content-center">
                                                    @if ($platforms['saved'] == 0)
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#platformModal"
                                                            wire:click="addPlatform({{ $platforms['id'] }},'{{ $platforms['path'] }}','{{ $platforms['label'] }}','{{ $platforms['direct'] }}','{{ $platforms['title'] }}','{{ $platforms['input'] }}')">Add</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                <div class="col-xl-3 mt-3 d-none d-xl-block ms-xl-5" style="position: sticky;">
                    <div class="mobile-frame"
                        style="position: relative; max-width: 400px; border-radius: 40px; border: 2px solid #ccc; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); overflow: hidden; padding: 15px 10px;">

                        <!-- Top Bar Emulation (Signal, Time, Battery) -->
                        <div class="top-bar"
                            style="height: 20px; display: flex; justify-content: space-between; padding: 0 15px; font-size: 12px; color: #333;">
                            <span>{{ now()->format('g : i a') }}</span>
                            <div style="display: flex; gap: 5px;">
                                <i class="bx bx-wifi" style="width: 15px; height: 10px;"></i>
                                <!-- Wi-Fi icon placeholder -->
                                <i class="bx bx-battery" style="width: 15px; height: 10px;"></i>
                                <!-- Battery icon placeholder -->
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center scroll-container"
                            style="background-color: white;height: 80vh;">
                            <div class="col-md-12 col-12 p-0" style="overflow: hidden; max-width: 400px;">
                                <div class="row d-flex justify-content-center">
                                    <!-- Cover Photo -->
                                    <div class="col-12 p-0">
                                        <div class="cover-photo-wrapper" style="position: relative;">
                                            <img src="{{ asset($profile->cover_photo && file_exists(public_path('storage/' . $profile->cover_photo)) ? Storage::url($profile->cover_photo) : 'user.png') }}"
                                                alt="user-avatar" style="object-fit: cover;height: 100px; width: 100%;">
                                            <!-- Profile Photo -->
                                            <div class="profile_img"
                                                style="position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); border-radius: 50%; overflow: hidden; width: 90px; height: 90px; border: 4px solid white;">
                                                <img src="{{ asset($profile->photo && file_exists(public_path('storage/' . $profile->photo)) ? Storage::url($profile->photo) : 'user.png') }}"
                                                    alt="user-avatar"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Profile Info -->
                                    <div class="col-12 text-center" style="margin-top: 60px;">
                                        <!-- Name or Username -->
                                        <h3 class="user-name" style="font-size: 22px;">
                                            {{ $profile->name ?: ($profile->username ?: 'Your Name') }}
                                        </h3>

                                        <!-- Job Title and Company -->
                                        <p style="font-size: 14px; color:#24171E; margin-bottom: 10px;">
                                            @if ($profile->job_title && $profile->company)
                                                {{ $profile->job_title }} at {{ $profile->company }}
                                            @elseif ($profile->job_title)
                                                {{ $profile->job_title }}
                                            @elseif ($profile->company)
                                                {{ $profile->company }}
                                            @endif
                                        </p>

                                        <!-- Bio -->
                                        <p style="font-size: 16px; color:#555;">
                                            {{ $profile->bio ? $profile->bio : 'Your bio will appear here.' }}
                                        </p>
                                    </div>

                                    <!-- Save to Contact Button -->
                                    <div class="col-12 d-flex justify-content-center mt-3 mb-2">
                                        <button class="btn btn-block rounded-pill px-4 py-2"
                                            style="background-color: #000; color: #fff; font-size: 14px; max-width: 220px;">
                                            <b>Save to Contact</b>
                                        </button>
                                    </div>

                                    <div class="container">
                                        <div class="row">
                                            @foreach ($sort_platform as $platform)
                                                <div class="col-4 d-flex justify-content-center"
                                                    style="margin-bottom: 20px">
                                                    <a class="social text-center" style="text-decoration:none;">
                                                        <img src="{{ asset(isImageExist($platform['icon'], 'platform')) }}"
                                                            class="gallery-image img-fluid"
                                                            style="max-width: 40px; max-height: 40px; object-fit: cover; display: block; margin: 0 auto;">
                                                        <label
                                                            style="display: block; font-size:10px; color:black; font-weight:bold">
                                                            {{ $platform['title'] }}
                                                        </label>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Create Profile Button -->
                                    <div class="col-12 d-flex justify-content-center mt-4 mb-3">
                                        <button class="btn btn-block rounded-pill px-4 py-2"
                                            style="background-color: white; color: black; font-size: 14px; border: 1px solid #000; max-width: 220px;">
                                            <b>Create your own profile</b>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Toggle Button for Mobile Screens (Only Visible on Small Screens) -->
                <div class="d-xl-none">
                    <button id="PreviewBtn" class="btn btn-primary fixed-bottom mb-3 mx-auto d-block"
                        style="max-width: 200px; z-index: 1000;">
                        Show Mobile Preview
                    </button>
                </div>

                <!-- Mobile Preview for Small Screens (Initially Hidden) -->
                <div id="Preview" class="col-12 align-content-center d-none"
                    style="position: fixed; bottom: 60px; left: 0; right: 0;z-index:9999; max-width: 300px; margin: 0 auto;">
                    <div class="mobile-frame"
                        style="position: relative; background-color: white; max-width: 500px; border-radius: 40px; border: 2px solid #ccc; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); overflow: hidden; padding: 15px 10px;">

                        <!-- Top Bar Emulation (Signal, Time, Battery) -->
                        <div class="top-bar"
                            style="height: 20px;display: flex; justify-content: space-between; padding: 0 15px; font-size: 12px; color: #333;">
                            <span>{{ now()->format('g : i a') }}</span>
                            <div style="display: flex; gap: 5px;">
                                <i class="bx bx-wifi" style="width: 15px; height: 10px;"></i>
                                <!-- Wi-Fi icon placeholder -->
                                <i class="bx bx-battery" style="width: 15px; height: 10px;"></i>
                                <!-- Battery icon placeholder -->
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center scroll-container"
                            style="background-color: white;height: 50vh;">
                            <div class="col-md-12 col-12 p-0" style="overflow: hidden;">

                                <!-- Cover Photo -->
                                <div class="col-12 p-0">
                                    <div class="cover-photo-wrapper" style="position: relative;">
                                        <img src="{{ asset($profile->cover_photo && file_exists(public_path('storage/' . $profile->cover_photo)) ? Storage::url($profile->cover_photo) : 'user.png') }}"
                                            alt="user-avatar" style="object-fit: cover;height: 100px; width: 100%;">
                                        <!-- Profile Photo -->
                                        <div class="profile_img"
                                            style="position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); border-radius: 50%; overflow: hidden; width: 90px; height: 90px; border: 4px solid white;">
                                            <img src="{{ asset($profile->photo && file_exists(public_path('storage/' . $profile->photo)) ? Storage::url($profile->photo) : 'user.png') }}"
                                                alt="user-avatar"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Profile Info -->
                                <div class="col-12 text-center" style="margin-top: 60px;">
                                    <!-- Name or Username -->
                                    <h3 class="user-name" style="font-size: 22px;">
                                        {{ $profile->name ?: ($profile->username ?: 'Your Name') }}
                                    </h3>

                                    <!-- Job Title and Company -->
                                    <p style="font-size: 14px; color:#24171E; margin-bottom: 10px;">
                                        @if ($profile->job_title && $profile->company)
                                            {{ $profile->job_title }} at {{ $profile->company }}
                                        @elseif ($profile->job_title)
                                            {{ $profile->job_title }}
                                        @elseif ($profile->company)
                                            {{ $profile->company }}
                                        @endif
                                    </p>

                                    <!-- Bio -->
                                    <p style="font-size: 16px; color:#555;">
                                        {{ $profile->bio ? $profile->bio : 'Your bio will appear here.' }}
                                    </p>
                                </div>

                                <!-- Save to Contact Button -->
                                <div class="col-12 d-flex justify-content-center mt-3 mb-3">
                                    <button class="btn btn-block rounded-pill px-4 py-2"
                                        style="background-color: #000; color: #fff; font-size: 14px; max-width: 220px;">
                                        <b>Save to Contact</b>
                                    </button>
                                </div>

                                <div class="container">
                                    <div class="row">
                                        @foreach ($sort_platform as $platform)
                                            <div class="col-4 d-flex justify-content-center"
                                                style="margin-bottom: 20px">
                                                <a class="social text-center" style="text-decoration:none;">
                                                    <img src="{{ asset(isImageExist($platform['icon'], 'platform')) }}"
                                                        class="gallery-image img-fluid"
                                                        style="max-width: 40px; max-height: 40px; object-fit: cover; display: block; margin: 0 auto;">
                                                    <label
                                                        style="display: block; font-size:8px; color:black; font-weight:bold">
                                                        {{ $platform['title'] }}
                                                    </label>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Create Profile Button -->
                                <div class="col-12 d-flex justify-content-center mt-4 mb-3">
                                    <button class="btn btn-block rounded-pill px-4 py-2"
                                        style="background-color: white; color: black; font-size: 14px; border: 1px solid #000; max-width: 220px;">
                                        <b>Create your own profile</b>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Toggle functionality for mobile preview
            document.getElementById('PreviewBtn').addEventListener('click', function() {
                const mobilePreviewPlatform = document.getElementById('Preview');
                const toggleBtnPlatform = document.getElementById('PreviewBtn');

                if (mobilePreviewPlatform.classList.contains('d-none')) {
                    mobilePreviewPlatform.classList.remove('d-none');
                    toggleBtnPlatform.textContent = 'Hide Mobile Preview';
                } else {
                    mobilePreviewPlatform.classList.add('d-none');
                    toggleBtnPlatform.textContent = 'Show Mobile Preview';
                }
            });
        </script>
    @elseif ($tab_change == 3)
        <div wire:sortable="updateOrder" class="row">
            @forelse ($sort_platform as $platforms)
                <div wire:sortable.item="{{ $platforms['id'] }}" wire:key="platform-{{ $platforms['id'] }}"
                    class="col-md-4 mb-2">
                    <div class="card my-4">
                        <div class="card-body">
                            <div class="row">
                                <div wire:sortable.handle class="col-md-2 py-3" style="height: 50px;width:50px">
                                    <img src="{{ asset('dots.png') }}" height="100%" width="100%" alt=""
                                        class="cursor-pointer">
                                </div>
                                <div class="col-md-2 py-2" style="height: 75px;width:75px">
                                    <img src="{{ asset($platforms['icon'] && Storage::exists($platforms['icon']) ? Storage::url($platforms['icon']) : 'pbg.png') }}"
                                        class="img-fluid rounded" height="100%" width="100%">
                                </div>
                                <div class="col-md-6 p-3">
                                    <h5 class="card-text pt-1">{{ $platforms['title'] }}
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-primary btn-sm mb-2"
                                            data-bs-toggle="modal" data-bs-target="#platformModal"
                                            wire:click="editPlatform({{ $platforms['id'] }},'{{ $platforms['path'] }}','{{ $platforms['label'] }}','{{ $platforms['direct'] }}','{{ $platforms['title'] }}','{{ $platforms['input'] }}')">Update</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            wire:click="deletePlatform({{ $platforms['id'] }})">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>No selected platforms available.</p>
            @endforelse
        </div>
    @endif
    <!-- Bootstrap Modal -->
    <div wire:ignore.self class="modal fade" id="platformModal" tabindex="-1" aria-labelledby="platformModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="platformModalLabel">
                        {{ $isEditMode ? 'Edit Link' : 'Add Link' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="path" class="form-label">
                                @if ($input === 'url')
                                    URL
                                @elseif($input === 'email')
                                    Email
                                @elseif($input === 'phone')
                                    Phone
                                @elseif($input === 'username')
                                    Username
                                @else
                                    Other
                                @endif
                            </label>
                            @if ($input === 'url')
                                <input type="url" class="form-control" id="path" wire:model="path">
                            @elseif($input === 'email')
                                <input type="email" class="form-control" id="path" wire:model="path">
                            @elseif($input === 'phone')
                                <input type="tel" class="form-control" id="path" wire:model="path">
                            @elseif($input === 'username')
                                <input type="text" class="form-control" id="path" wire:model="path">
                            @else
                                <input type="text" class="form-control" id="path" wire:model="path"
                                    placeholder="Enter details">
                            @endif
                            @error('path')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" wire:model="title" disabled>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="savePlatform">{{ $isEditMode ? 'Update' : 'Add' }}</button>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.admin.confirm-modal')
    <script>
        window.addEventListener('close-modal', event => {
            $('#platformModal').modal('hide');
            $('#confirmModal').modal('hide');
        });
        window.addEventListener('confirm-modal', event => {
            $('#confirmModal').modal('show')
        });
    </script>
    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>
</div>
