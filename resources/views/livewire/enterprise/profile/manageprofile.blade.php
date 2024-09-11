<div>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="card-header">
                <a href="{{ route('enterprise.profiles') }}"> Profiles </a> / {{ $heading }}
            </h2>
        </div>
    </div>
    <div>
        <div class="card mb-3">
            <div class="row p-2">
                <div class="col-md-3 align-content-center">
                    <img src="{{ !is_null($photo) || Storage::exists($photo) ? Storage::url($photo) : asset('user.png') }}"
                        class="img-fluid rounded" height="300" width="250">
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <h4 class="fw-bold text-center"> Profile Details </h4>
                            </div>
                            <div class="col-6">
                                <x-custom.detail-section label="Name" :value="$name" />
                                <x-custom.detail-section label="Email" :value="$email" />
                                <x-custom.detail-section label="Phone" :value="$phone" />
                                <x-custom.detail-section label="Registered" :value="$created_at" />
                            </div>
                            <div class="col-6">
                                {{-- <h4 class="fw-bold"> Analytics </h4> --}}
                                <x-custom.detail-section label="Profile" :value="$username" />
                                <x-custom.detail-section label="Cards" :value="$email" />
                                <x-custom.detail-section label="Views" :value="$phone" />
                                <x-custom.detail-section label="Platforms" :value="null" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        {{-- <h6>Active profile</h6> --}}
                    </div>
                    <div class="col-4">
                        <div class="mb-1 row">
                            <div class="col-5">
                                <label>Direct</label>
                            </div>
                            <div class="col-7 form-check form-switch">
                                <input class="form-check-input" wire:change="Isdirect($event.target.checked)"
                                    type="checkbox" {{ $user_direct == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-1 row">
                            <div class="col-5">
                                <label>Private Profile</label>
                            </div>
                            <div class="col-7 form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    wire:change="Isprivate($event.target.checked)" {{ $private == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                type="button" role="tab" aria-controls="pills-home" aria-selected="true"
                wire:click="edit_profile({{ $profile_id }})">Edit</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Manage
                Plateforms</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            @if ($edit_profile)
                <livewire:enterprise.profile.edit :id="$profile_id" />
            @endif
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            @if ($edit_profile)
                <livewire:enterprise.profile.platforms :id="$profile_id" />
            @endif
        </div>
    </div>
    {{-- <div>
        <!-- Toggle Buttons -->
        <div class="btn-group mb-3" role="group">
            <button class="btn btn-primary {{ $edit_profile ? 'active' : '' }}" data-bs-toggle="tooltip"
                data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="Edit"
                wire:click="edit_profile({{ $profile_id }})"><i class="bx bx-edit-alt"></i></button>
            <button class="btn btn-secondary {{ !$edit_profile ? 'active' : '' }}" data-bs-toggle="tooltip"
                data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="plateform"
                wire:click="plateforms"><i class='bx bxs-box'></i></button>
        </div>

        <!-- Component 1 -->
        @if ($edit_profile)
            <livewire:enterprise.profile.edit :id="$profile_id" />
        @endif

        <!-- Component 2 -->
        @if (!$edit_profile)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Component 2</h5>
                    <p class="card-text">This is the content of the second component.</p>
                </div>
            </div>
        @endif
    </div> --}}

</div>
