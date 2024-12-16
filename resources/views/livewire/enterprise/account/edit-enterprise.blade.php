<div>
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <form wire:submit.prevent="updateEnterpriser">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        @if ($enterprise_logo && !is_string($enterprise_logo))
                                            <img src="{{ $enterprise_logo->temporaryUrl() }}" alt="user-avatar"
                                                class="d-block rounded-circle" style="object-fit: cover" height="80"
                                                style="object-fit: cover" width="80">
                                        @else
                                            <img src="{{ asset($old_enterprise_logo && file_exists(public_path('storage/' . $old_enterprise_logo)) ? Storage::url($old_enterprise_logo) : 'user.png') }}"
                                                alt="user-avatar" class="d-block rounded-circle shadow" height="80"
                                                style="object-fit: cover" width="80">
                                        @endif
                                        <div wire:loading wire:target="enterprise_logo" wire:key="enterprise_logo">
                                            <i class="fa fa-spinner fa-spin mt-2 ml-2"></i>
                                        </div>

                                        <div class="icon-upload btn-sm btn-dark">
                                            <span>Upload logo</span>
                                            <input type="file" class="icon-input" wire:model="enterprise_logo"
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
                                        placeholder="Enter email" disabled>
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
                                        Company Name
                                        @error('company_name')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="company_name" class="form-control"
                                        placeholder="Enter Enterprise Type">
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
