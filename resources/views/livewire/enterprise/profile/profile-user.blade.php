<div>
    @if ($user == null)
        <div class="card mx-auto">
            <div class="card-body text-center">
                No User Linked with this Profile
            </div>
        </div>
    @else
        <div>
            <div class="card mb-3">
                <div class="row p-2">
                    <div class="col-md-3 align-content-center">
                        <img src="{{ asset($user->photo ? Storage::url($user->photo) : 'user.png') }}"
                            class="img-fluid rounded" height="300" width="250">
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h4 class="fw-bold text-center"> user Details </h4>
                                </div>
                                <div class="col-6">
                                    <x-custom.detail-section label="Name" :value="$user->name" />
                                    <x-custom.detail-section label="Email" :value="$user->email" />
                                    <x-custom.detail-section label="Phone" :value="$user->phone" />
                                    <x-custom.detail-section label="Registered" :value="$user->created_at->diffForHumans()" />
                                </div>
                                <div class="col-6">
                                    {{-- <h4 class="fw-bold"> Analytics </h4> --}}
                                    <x-custom.detail-section label="UserName" :value="$user->username" />
                                    <x-custom.detail-section label="Cards" :value="$user->email" />
                                    <x-custom.detail-section label="Views" :value="$user->tiks" />
                                    <x-custom.detail-section label="Role" :value="$user->role" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                        </div>
                        <div class="col-4">
                            <div class="mb-1 row">
                                <div class="col-5">
                                    <label>D-link</label>
                                </div>
                                <div class="col-7 form-check form-switch">
                                    <input class="form-check-input" wire:change="dLinkUser({{ $user->id }})"
                                        type="checkbox" {{ $user ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
