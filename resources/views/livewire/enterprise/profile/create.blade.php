<div>
    <style>
        /* Camera icon for cover photo */
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
            height: 300px;
            object-fit: cover;
        }


        /* Camera icon for profile photo */
        .camera-icon-profile {
            position: absolute;
            bottom: 0;
            /* Aligns to the bottom */
            left: 50px;
            /* Aligns to the right */
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
        }

        .profile-photo img {
            border: 5px solid #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 150px;
            height: 150px;
            object-fit: cover;
            position: relative;
            z-index: 1;
        }

        .scroll-container {
            height: 80vh;
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
                <form wire:submit.prevent="saveProfile">
                    <div class="card-body scroll-container">
                        <div class="row">
                            <!-- Cover Photo -->
                            <div class="cover-photo position-relative" style="height: 150px;">
                                @if ($cover_photo && !is_string($cover_photo))
                                    <img src="{{ $cover_photo->temporaryUrl() }}" alt="cover-photo"
                                        style="height: 150px; width: 100%; object-fit: cover;">
                                @else
                                    <div style="height: 150px; width: 100%; background-color: #f5f5f5;">
                                        <!-- Default Cover Photo -->
                                    </div>
                                @endif

                                <!-- Cover photo input and camera icon -->
                                <input type="file" id="cover_photo" wire:model="cover_photo"
                                    accept="image/png, image/jpeg, image/jpg, image/webp" class="d-none">
                                <label for="cover_photo" class="camera-icon-cover">
                                    <i class="bx bxs-camera"></i>
                                </label>
                            </div>


                            <div class="profile-photo position-relative"
                                style="position: relative; top: -75px; left:10px;">
                                @if ($photo && !is_string($photo))
                                    <img src="{{ $photo->temporaryUrl() }}" alt="user-avatar"
                                        class="rounded-circle border"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('frame_2.webp') }}" alt="user-avatar"
                                        class="rounded-circle border"
                                        style="width: 100px; height: 100px; object-fit: cover;">
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
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-dark" wire:loading.attr="disabled">Save</button>
                        <!-- Loader for save operation -->
                        <div wire:loading wire:target="saveProfile" class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Saving...</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Right Side: Mobile Preview create -->


        <div class="col-xl-3 align-content-center d-none d-xl-block ms-xl-3" style="position: sticky;">
            <!-- Mobile Frame Container -->
            <div class="mobile-frame"
                style="position: relative; max-width: 400px; border-radius: 40px; border: 2px solid #ccc; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); overflow: hidden; padding: 15px 10px;">

                <!-- Top Bar Emulation (Signal, Time, Battery) -->
                <div class="top-bar"
                    style="height: 20px; display: flex; justify-content: space-between; padding: 0 15px; font-size: 12px; color: #333;">
                    @php
                        use Carbon\Carbon;
                        $time = Carbon::now()->format('g : i a');
                    @endphp
                    <span>{{ $time }}</span>
                    <div style="display: flex; gap: 5px;">
                        <i class="bx bx-wifi" style="width: 15px; height: 10px;"></i>
                        <!-- Wi-Fi icon placeholder -->
                        <i class="bx bx-battery" style="width: 15px; height: 10px;"></i>
                        <!-- Battery icon placeholder -->
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="row d-flex justify-content-center"
                    style="background-color: white; border-radius: 30px; overflow: hidden;">
                    <!-- Cover and Profile Photo -->
                    <div class="col-12 p-0">
                        <div class="cover-photo-wrapper" style="position: relative;">
                            @if ($cover_photo && !is_string($cover_photo))
                                <img style="width: 100%; height: 100px; object-fit: cover; border-top-left-radius: 30px; border-top-right-radius: 30px;"
                                    src="{{ $cover_photo->temporaryUrl() }}" alt="Cover Photo">
                            @else
                                <div
                                    style="width: 100%; height: 100px; background-color: #eaeaea; border-top-left-radius: 30px; border-top-right-radius: 30px;">
                                </div>
                            @endif

                            <!-- Profile Photo -->
                            <div class="profile_img"
                                style="position: absolute; bottom: -40px; left: 50%; transform: translateX(-50%); border-radius: 50%; overflow: hidden; width: 90px; height: 90px; border: 5px solid white;">
                                @if ($photo && !is_string($photo))
                                    <img src="{{ $photo->temporaryUrl() }}" alt="Profile Photo"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="{{ asset('frame_2.webp') }}" alt="user-avatar"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="col-12 text-center" style="margin-top: 50px;">
                        <h3 class="user-name" style="font-size: 20px; font-weight: 600; color: #333;">
                            {{ $name ?: ($username ?: 'Your Name') }}
                        </h3>

                        <p style="font-size: 13px; color: #666; margin: 5px 0;">
                            @if ($job_title && $company)
                                {{ $job_title }} at {{ $company }}
                            @elseif ($job_title)
                                {{ $job_title }}
                            @elseif ($company)
                                {{ $company }}
                            @endif
                        </p>

                        <p style="font-size: 15px; color: #888; margin: 10px;">
                            {{ $bio ? $bio : 'Your bio will appear here.' }}
                        </p>
                    </div>

                    <!-- Save to Contact Button -->
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <button class="btn btn-block rounded-pill px-4 py-2"
                            style="background-color: #007aff; color: #fff; font-size: 14px; max-width: 220px;">
                            <b>Save to Contact</b>
                        </button>
                    </div>

                    <!-- Create Profile Button -->
                    <div class="col-12 d-flex justify-content-center mt-4 mb-3">
                        <button class="btn btn-block rounded-pill px-4 py-2"
                            style="background-color: white; color: black; font-size: 12px; border: 1px solid #000; max-width: 220px;">
                            <b>Create your own profile</b>
                        </button>
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
                style="position: relative; max-width: 400px; border-radius: 40px; background-color:#fff; border: 2px solid #ccc; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); overflow: hidden; padding: 15px 10px;">

                <!-- Top Bar Emulation (Signal, Time, Battery) -->
                <div class="top-bar"
                    style="height: 20px; display: flex; justify-content: space-between; padding: 0 15px; font-size: 12px; color: #333;">
                    <span>{{ $time }}</span>
                    <div style="display: flex; gap: 5px;">
                        <i class="bx bx-wifi" style="width: 15px; height: 10px;"></i>
                        <!-- Wi-Fi icon placeholder -->
                        <i class="bx bx-battery" style="width: 15px; height: 10px;"></i>
                        <!-- Battery icon placeholder -->
                    </div>
                </div>
                <div class="row d-flex justify-content-center"
                    style="background-color: white;border-radius: 30px; overflow: hidden;">
                    <div class="col-md-12 col-12 p-0">

                        <!-- Cover Photo -->
                        <div class="col-12 p-0">
                            <div class="cover-photo-wrapper" style="position: relative;">
                                @if ($cover_photo && !is_string($cover_photo))
                                    <img style="width: 100%; height: 100px; object-fit: cover;"
                                        src="{{ $cover_photo->temporaryUrl() }}" alt="Cover Photo">
                                @else
                                    <div style="width: 100%; height: 100px; background-color: #f5f5f5;"></div>
                                @endif
                                <!-- Profile Photo -->
                                <div class="profile_img"
                                    style="position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); border-radius: 50%; overflow: hidden; width: 90px; height: 90px; border: 4px solid white;">
                                    @if ($photo && !is_string($photo))
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Profile Photo"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('frame_2.webp') }}" alt="user-avatar"
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

    <!-- Modal for Subscription Expiry -->
    @if ($showSubscriptionModal)
        <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Limited Profiles</h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showSubscriptionModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>You have reached your profile creation limit. Please Upgrade your Plan to continue using this
                            feature.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showSubscriptionModal', false)">Close</button>
                        <a href="{{ route('enterprise.mysubscription') }}" class="btn btn-primary">Change
                            Subscription</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-backdrop fade show"></div>
    @endif

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
