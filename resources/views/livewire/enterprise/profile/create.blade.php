@section('style')
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
            left: 70px;
            /* Aligns to the right */
            background-color: #ffffff;
            border-radius: 50%;
            width: 30px;
            height: 30px;
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
    </style>
@endsection
<div>
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <form wire:submit.prevent="saveProfile">
                    <div class="card-body">
                        <div class="row">
                            <!-- Cover Photo -->
                            <div class="cover-photo position-relative" style="height: 300px;">
                                @if ($cover_photo && !is_string($cover_photo))
                                    <img src="{{ $cover_photo->temporaryUrl() }}" alt="cover-photo"
                                        style="height: 300px; width: 100%; object-fit: cover;">
                                @else
                                    <div style="height: 300px; width: 100%; background-color: #f5f5f5;">
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
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('frame_2.webp') }}" alt="user-avatar"
                                        class="rounded-circle border"
                                        style="width: 150px; height: 150px; object-fit: cover;">
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
                        <button type="submit" class="btn" style="background: #0EA7C1; color:white"
                            wire:loading.attr="disabled">Save</button>
                        <!-- Loader for save operation -->
                        <div wire:loading wire:target="saveProfile" class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Saving...</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Right Side: Mobile Preview create -->
        <div class="col-xl-3 align-content-center d-none d-md-block"
            style="position: fixed;background-color: white; top: 40px; right: 20px;">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 col-12 p-0"
                    style="box-shadow: 0 0 15px 5px #ccc; border-radius: 20px; overflow: hidden; max-width: 400px;">
                    <div class="row d-flex justify-content-center">

                        <!-- Top Banner -->
                        <a target="_blank" {{-- href="{{ route('view.profile.username', $username ? $username : 'username') }}" --}}
                            class="col-12 header-navbar TopBanner text-center p-2"
                            style="background-color: #f1f1f1; font-weight: bold; font-size: 14px;">
                            Tap here to view your Gotap profile
                        </a>

                        <!-- Cover Photo -->
                        <div class="col-12 p-0">
                            <div class="cover-photo-wrapper" style="position: relative;">
                                @if ($cover_photo && !is_string($cover_photo))
                                    <img style="width: 100%; height: 200px; object-fit: cover;"
                                        src="{{ $cover_photo->temporaryUrl() }}" alt="Cover Photo">
                                @else
                                    <div style="width: 100%; height: 200px; background-color: #f5f5f5;"></div>
                                @endif
                                <!-- Profile Photo -->
                                <div class="profile_img"
                                    style="position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); border-radius: 50%; overflow: hidden; width: 110px; height: 110px; border: 4px solid white;">
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

        <!-- Toggle Button for Mobile Screens (Only Visible on Small Screens) -->
        <div class="d-md-none">
            <button id="toggleMobilePreviewBtn" class="btn btn-primary fixed-bottom mb-3 mx-auto d-block"
                style="max-width: 200px; z-index: 1000;">
                Show Mobile Preview
            </button>
        </div>

        <!-- Mobile Preview for Small Screens (Initially Hidden) -->
        <div id="mobilePreview" class="col-12 align-content-center d-none"
            style="position: fixed; bottom: 50px; left: 0; right: 0;background-color: white;z-index:9999; max-width: 400px; margin: 0 auto;">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 col-12 p-0"
                    style="box-shadow: 0 0 15px 5px #ccc; border-radius: 20px; overflow: hidden;">
                    <!-- Your existing mobile preview content goes here -->
                    <!-- Top Banner -->
                    <a target="_blank" {{-- href="{{ route('view.profile.username', $username ? $username : 'username') }}" --}} class="col-12 header-navbar TopBanner text-center p-2"
                        style="background-color: #f1f1f1; font-weight: bold; font-size: 14px; width:100%;">
                        Tap here to view your Gotap profile
                    </a>

                    <!-- Cover Photo -->
                    <div class="col-12 p-0">
                        <div class="cover-photo-wrapper" style="position: relative;">
                            @if ($cover_photo && !is_string($cover_photo))
                                <img style="width: 100%; height: 200px; object-fit: cover;"
                                    src="{{ $cover_photo->temporaryUrl() }}" alt="Cover Photo">
                            @else
                                <div style="width: 100%; height: 200px; background-color: #f5f5f5;"></div>
                            @endif
                            <!-- Profile Photo -->
                            <div class="profile_img"
                                style="position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); border-radius: 50%; overflow: hidden; width: 110px; height: 110px; border: 4px solid white;">
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
