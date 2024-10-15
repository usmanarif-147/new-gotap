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
            top: 80%;
            left: 13%;
            transform: translate(-50%, -50%);
            background-color: rgb(0, 0, 0);
            color: white;
            border-radius: 30%;
            padding: 2px;
            cursor: pointer;
            z-index: 2;
        }

        .camera-icon-profile i {
            font-size: 20px;
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
        <div class="col-xl">
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
                        <button type="submit" class="btn" style="background: #0EA7C1; color:white">Save</button>
                    </div>
                </form>
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

</div>
