<div>
    <style>
        .camera-icon-cover {
            position: absolute;
            bottom: 5%;
            right: 5%;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            border-radius: 30%;
            padding: 10px;
            cursor: pointer;
            z-index: 10;
        }

        .camera-icon-cover i {
            font-size: 20px;
            pointer-events: none;
        }

        .cover-photo img {
            width: 100%;
            height: 150px;
            width: 100%;
            object-fit: cover;
        }

        .camera-icon-profile {
            position: absolute;
            bottom: 0;
            left: 50px;
            background-color: #ffffff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #ccc;
            cursor: pointer;
            z-index: 999;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .camera-icon-profile i {
            font-size: 20px;
            color: #000;
        }

        .profile-photo {
            position: relative;
            top: -75px;
            left: 10px;
        }

        .profile-photo img {
            border: 5px solid #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 100px;
            height: 100px;
            object-fit: cover;
            position: relative;
            z-index: 1;
        }

        .scroll-container {
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-right: 15px;
        }

        .scroll-container::-webkit-scrollbar {
            display: none;
        }
    </style>
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <form wire:submit.prevent="updateProfile">
                    <div class="card-body scroll-container" style="height: 77vh;">
                        <div class="row">
                            <!-- Cover Photo -->
                            <div class="cover-photo position-relative">
                                @if ($cover_photo && !is_string($cover_photo))
                                    <img src="{{ $cover_photo->temporaryUrl() }}" alt="cover-photo">
                                @else
                                    <img src="{{ asset($old_cover_photo && file_exists(public_path('storage/' . $old_cover_photo)) ? Storage::url($old_cover_photo) : 'user.png') }}"
                                        alt="user-avatar">
                                @endif

                                <!-- Cover photo input and camera icon -->
                                <input type="file" id="cover_photo" wire:model="cover_photo"
                                    accept="image/png, image/jpeg, image/jpg, image/webp" class="d-none">
                                <label for="cover_photo" class="camera-icon-cover" style="">
                                    <i class="bx bxs-camera" style="font-size: 20px;pointer-events: none;"></i>
                                </label>
                            </div>


                            <div class="profile-photo position-relative">
                                @if ($photo && !is_string($photo))
                                    <img src="{{ $photo->temporaryUrl() }}" alt="user-avatar"
                                        class="rounded-circle border">
                                @else
                                    <img src="{{ asset($old_photo && file_exists(public_path('storage/' . $old_photo)) ? Storage::url($old_photo) : 'user.png') }}"
                                        alt="user-avatar" class="rounded-circle border">
                                @endif
                                <div wire:loading wire:target="photo" wire:key="photo">
                                    <i class="fa fa-spinner fa-spin mt-2 ml-2"></i>
                                </div>
                                <input type="file" id="profile_photo" wire:model="photo"
                                    accept="image/png, image/jpeg, image/jpg, image/webp" class="d-none">
                                <label for="profile_photo" class="camera-icon-profile">
                                    <i class="bx bxs-camera"></i>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Name
                                        @error('name')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="name" class="form-control"
                                        placeholder="Enter name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Email
                                        @error('email')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="email" wire:model="email" class="form-control"
                                        placeholder="Enter email">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Phone
                                        @error('phone')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="tel" wire:model="phone" class="form-control"
                                        placeholder="Enter phone">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Username <span class="text-danger"> * </span>
                                        @error('username')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="username" class="form-control"
                                        placeholder="Enter username">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Work Position
                                        @error('work_position')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="work_position" class="form-control"
                                        placeholder="Enter work position">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Job Title
                                        @error('job_title')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="job_title" class="form-control"
                                        placeholder="Enter job title">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Company
                                        @error('company')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="company" class="form-control"
                                        placeholder="Enter company">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Address
                                        @error('address')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="address" class="form-control"
                                        placeholder="Enter address">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Bio
                                        @error('bio')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="bio" class="form-control"
                                        placeholder="Enter bio">
                                </div>
                            </div>
                            <div class="col-6 mt-2">
                                <div class="mb-3 row">
                                    <div class="col-8">
                                        <label>Lead Capture Mode</label>
                                        <p style="color: lightgray">Turn this on the collect the other person's info
                                            before they access yours</p>
                                    </div>
                                    <div class="col-4 form-check form-switch align-content-center">
                                        <input class="form-check-input" type="checkbox" wire:model="is_leads_enabled"
                                            {{ $is_leads_enabled == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-dark">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-xl-3 d-none d-xl-block ms-xl-3" style="position: sticky;">
            <div class="mobile-frame"
                style="position: relative; max-width: 500px; border-radius: 40px; border: 2px solid #ccc; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); overflow: hidden; padding: 15px 10px;">

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
                <div class="row d-flex justify-content-center" style="background-color: white;">
                    <div class="col-md-12 col-12 p-0" style="overflow: hidden; max-width: 400px;">
                        <div class="row d-flex justify-content-center">

                            <!-- Cover Photo -->
                            <div class="col-12 p-0">
                                <div class="cover-photo-wrapper" style="position: relative;">
                                    @if ($cover_photo && !is_string($cover_photo))
                                        <img style="width: 100%; height: 100px; object-fit: cover;"
                                            src="{{ $cover_photo->temporaryUrl() }}" alt="Cover Photo">
                                    @else
                                        <img src="{{ asset($old_cover_photo && file_exists(public_path('storage/' . $old_cover_photo)) ? Storage::url($old_cover_photo) : 'user.png') }}"
                                            alt="user-avatar" style="object-fit: cover;height: 100px; width: 100%;">
                                    @endif
                                    <!-- Profile Photo -->
                                    <div class="profile_img"
                                        style="position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); border-radius: 50%; overflow: hidden; width: 90px; height: 90px; border: 4px solid white;">
                                        @if ($photo && !is_string($photo))
                                            <img src="{{ $photo->temporaryUrl() }}" alt="Profile Photo"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <img src="{{ asset($old_photo && file_exists(public_path('storage/' . $old_photo)) ? Storage::url($old_photo) : 'user.png') }}"
                                                alt="user-avatar"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Info -->
                            <div class="col-12 text-center" style="margin-top: 60px;">
                                <!-- Name or Username -->
                                <h3 class="user-name" style="font-size: 22px;">
                                    {{ $name ?: ($username ?: 'Your Name') }}
                                </h3>

                                <!-- Job Title and Company -->
                                <p style="font-size: 14px; color:#24171E; margin-bottom: 10px;">
                                    @if ($job_title && $company)
                                        {{ $job_title }} at {{ $company }}
                                    @elseif ($job_title)
                                        {{ $job_title }}
                                    @elseif ($company)
                                        {{ $company }}
                                    @endif
                                </p>

                                <!-- Bio -->
                                <p style="font-size: 16px; color:#555;">
                                    {{ $bio ? $bio : 'Your bio will appear here.' }}
                                </p>
                            </div>

                            <!-- Save to Contact Button -->
                            <div class="col-12 d-flex justify-content-center mt-3">
                                <button class="btn btn-block rounded-pill px-4 py-2"
                                    style="background-color: #000; color: #fff; font-size: 14px; max-width: 220px;">
                                    <b>Save to Contact</b>
                                </button>
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
            <button id="toggleMobilePreviewBtn" class="btn btn-primary fixed-bottom mb-3 mx-auto d-block"
                style="max-width: 200px; z-index: 1000;">
                Show Mobile Preview
            </button>
        </div>

        <!-- Mobile Preview for Small Screens (Initially Hidden) -->
        <div id="mobilePreview" class="col-12 align-content-center d-none"
            style="position: fixed; bottom: 60px; left: 0; right: 0;z-index:9999; max-width: 300px; margin: 0 auto;">
            <div class="mobile-frame"
                style="position: relative; background-color: white; max-width: 500px; border-radius: 40px; border: 2px solid #ccc; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); overflow: hidden; padding: 15px 10px;">

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
                <div class="row d-flex justify-content-center" style="background-color: white;">
                    <div class="col-md-12 col-12 p-0" style="overflow: hidden;">

                        <!-- Cover Photo -->
                        <div class="col-12 p-0">
                            <div class="cover-photo-wrapper" style="position: relative;">
                                @if ($cover_photo && !is_string($cover_photo))
                                    <img style="width: 100%; height: 100px; object-fit: cover;"
                                        src="{{ $cover_photo->temporaryUrl() }}" alt="Cover Photo">
                                @else
                                    <img src="{{ asset($old_cover_photo && file_exists(public_path('storage/' . $old_cover_photo)) ? Storage::url($old_cover_photo) : 'user.png') }}"
                                        alt="user-avatar" style="object-fit: cover;height: 100px; width: 100%;">
                                @endif
                                <!-- Profile Photo -->
                                <div class="profile_img"
                                    style="position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); border-radius: 50%; overflow: hidden; width: 90px; height: 90px; border: 4px solid white;">
                                    @if ($photo && !is_string($photo))
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Profile Photo"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="{{ asset($old_photo && file_exists(public_path('storage/' . $old_photo)) ? Storage::url($old_photo) : 'user.png') }}"
                                            alt="user-avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Profile Info -->
                        <div class="col-12 text-center" style="margin-top: 60px;">
                            <!-- Name or Username -->
                            <h3 class="user-name" style="font-size: 22px;">
                                {{ $name ?: ($username ?: 'Your Name') }}
                            </h3>

                            <!-- Job Title and Company -->
                            <p style="font-size: 14px; color:#24171E; margin-bottom: 10px;">
                                @if ($job_title && $company)
                                    {{ $job_title }} at {{ $company }}
                                @elseif ($job_title)
                                    {{ $job_title }}
                                @elseif ($company)
                                    {{ $company }}
                                @endif
                            </p>

                            <!-- Bio -->
                            <p style="font-size: 16px; color:#555;">
                                {{ $bio ? $bio : 'Your bio will appear here.' }}
                            </p>
                        </div>

                        <!-- Save to Contact Button -->
                        <div class="col-12 d-flex justify-content-center mt-3">
                            <button class="btn btn-block rounded-pill px-4 py-2"
                                style="background-color: #000; color: #fff; font-size: 14px; max-width: 220px;">
                                <b>Save to Contact</b>
                            </button>
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

    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>

    <script>
        // Toggle functionality for mobile preview
        document.getElementById('toggleMobilePreviewBtn').addEventListener('click', function() {
            const mobilePreview = document.getElementById('mobilePreview');
            const toggleBtn = document.getElementById('toggleMobilePreviewBtn');

            if (mobilePreview.classList.contains('d-none')) {
                mobilePreview.classList.remove('d-none');
                toggleBtn.textContent = 'Hide Mobile Preview';
            } else {
                mobilePreview.classList.add('d-none');
                toggleBtn.textContent = 'Show Mobile Preview';
            }
        });
    </script>

</div>
