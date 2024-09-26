<div>
    {{-- <div>
        <div class="d-flex justify-content-between">
            <h2 class="card-header">
                <a href="{{ route('enterprise.profiles') }}"> Profiles </a> / {{ $heading }}
            </h2>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <form wire:submit.prevent="updateProfile">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        @if ($photo && !is_string($photo))
                                            <img src="{{ $photo->temporaryUrl() }}" alt="user-avatar"
                                                class="d-block rounded-circle" style="object-fit: cover" height="80"
                                                style="object-fit: cover" width="80">
                                        @else
                                            <img src="{{ asset($old_photo ? Storage::url($old_photo) : 'user.png') }}"
                                                alt="user-avatar" class="d-block rounded-circle shadow" height="80"
                                                style="object-fit: cover" width="80">
                                        @endif
                                        <div wire:loading wire:target="photo" wire:key="photo">
                                            <i class="fa fa-spinner fa-spin mt-2 ml-2"></i>
                                        </div>

                                        <div class="icon-upload btn-sm" style="background: #0EA7C1; color:white">
                                            <span>Upload Photo</span>
                                            <input type="file" class="icon-input" wire:model="photo"
                                                accept="image/png, image/jpeg, image/jpg, image/webp">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        @if ($cover_photo && !is_string($cover_photo))
                                            <img src="{{ $cover_photo->temporaryUrl() }}" alt="user-avatar"
                                                class="d-block rounded" style="object-fit: cover" height="70"
                                                width="170">
                                        @else
                                            <img src="{{ asset($old_cover_photo ? Storage::url($old_cover_photo) : 'user.png') }}"
                                                alt="user-avatar" class="d-block rounded" style="object-fit: cover"
                                                height="70" width="170">
                                        @endif
                                        <div wire:loading wire:target="cover_photo" wire:key="cover_photo">
                                            <i class="fa fa-spinner fa-spin mt-2 ml-2"></i>
                                        </div>

                                        <div class="icon-upload btn-sm" style="background: #0EA7C1; color:white">
                                            <span>Upload Cover Photo</span>
                                            <input type="file" class="icon-input" wire:model="cover_photo"
                                                accept="image/png, image/jpeg, image/jpg, image/webp">
                                        </div>
                                    </div>
                                </div>
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
                        <button type="submit" class="btn" style="background: #0EA7C1; color:white">
                            Update
                        </button>
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
