<div>
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <form wire:submit.prevent="update" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        @if ($photo && !is_string($photo))
                                            <img src="{{ $photo->temporaryUrl() }}" alt="user-avatar"
                                                class="d-block rounded" height="200" width="170">
                                        @else
                                            <img src="{{ asset(isImageExist($preview_photo, 'profile')) }}"
                                                alt="user-avatar" class="d-block rounded" height="200" width="170">
                                        @endif

                                        <div wire:loading wire:target="photo" wire:key="photo">
                                            <i class="fa fa-spinner fa-spin mt-2 ml-2"></i>
                                        </div>

                                        <div class="photo-upload btn" style="background: #0EA7C1; color:white">
                                            <span>Upload Profile Photo</span>
                                            <input type="file" class="photo-input" wire:model="photo"
                                                accept="image/png, image/jpeg, image/jpg, image/webp">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                @if ($role == 'enterpriser')
                                    <div class="mb-3">
                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            @if ($enterprise_logo && !is_string($enterprise_logo))
                                                <img src="{{ $enterprise_logo->temporaryUrl() }}" alt="user-avatar"
                                                    class="d-block rounded" height="200" width="170">
                                            @else
                                                <img src="{{ asset(isImageExist($preview_enterprise_logo)) }}"
                                                    alt="user-avatar" class="d-block rounded" height="200"
                                                    width="170">
                                            @endif

                                            <div wire:loading wire:target="enterprise_logo" wire:key="enterprise_logo">
                                                <i class="fa fa-spinner fa-spin mt-2 ml-2"></i>
                                            </div>

                                            <div class="photo_cover-upload btn"
                                                style="background: #0EA7C1; color:white">
                                                <span>Upload Cover Photo</span>
                                                <input type="file" class="photo_cover-input"
                                                    wire:model="enterprise_logo"
                                                    accept="image/png, image/jpeg, image/jpg, image/webp">
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                                    <input type="text" wire:model="name" class="form-control" placeholder="John doe">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Email
                                    </label>
                                    <input type="email" wire:model="email" class="form-control"
                                        placeholder="Johndoe@gmail.com">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Username <span style="color:red"> * </span>
                                        @error('username')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="username" class="form-control"
                                        placeholder="john-doe">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Phone
                                    </label>
                                    <input type="text" wire:model="phone" class="form-control"
                                        placeholder="658 799 8941">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Company
                                    </label>
                                    <input type="text" wire:model="company_name" class="form-control"
                                        placeholder="Facebook">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Status <span class="text-danger"> * </span>
                                        @error('status')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <select class="form-select" wire:model="status">
                                        <option value="0">Deactive</option>
                                        <option value="1">Activate</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Address
                                    </label>
                                    <textarea class="form-control" wire:model="address" placeholder="bio here..."></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Verified <span class="text-danger"> * </span>
                                        @error('verified')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <select class="form-select" wire:model="verified">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Show In All Users <span class="text-danger"> * </span>
                                        @error('featured')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <select class="form-select" wire:model="featured">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Gender <span class="text-danger"> * </span>
                                        @error('gender')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <select class="form-select" wire:model="gender">
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card mt-4 text-center w-50 mx-auto">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="mb-0">User Cards Information</h5>
                        </div>
                        <div class="card-body mt-3">
                            @if ($user->profiles->isNotEmpty())
                                @foreach ($user->profiles as $profile)
                                    <div class="mb-4">
                                        @if ($profile->cards->isNotEmpty())
                                            <div class="list-group">
                                                @foreach ($profile->cards as $card)
                                                    <div class="list-group-item">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-4">
                                                                <strong>Profile UserName:</strong>
                                                                {{ $profile->username ?? 'Unnamed Profile' }}
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Card Type:</strong> {{ ucfirst($card->type) }}
                                                            </div>
                                                            <div class="col-md-4 text-end">
                                                                <strong>Status:</strong>
                                                                <span
                                                                    class="badge {{ $card->status ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ $card->status ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted fst-italic">No cards linked to this profile.</p>
                                        @endif
                                    </div>
                                    @if (!$loop->last)
                                        <hr>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-muted fst-italic">This user has no profiles.</p>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn" style="background: #0EA7C1; color:white"
                            wire:loading.attr="disabled" wire:target="update">
                            Update
                        </button>

                        <!-- Loading Indicator -->
                        <span wire:loading wire:target="update" style="margin-left: 10px; color: #0EA7C1;">
                            Processing...
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@section('script')
    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>
@endsection
