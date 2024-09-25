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
                    <img src="{{ asset($profile->photo ? Storage::url($profile->photo) : 'user.png') }}"
                        class="img-fluid rounded" height="300" width="250">
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <h4 class="fw-bold text-center"> Profile Details </h4>
                            </div>
                            <div class="col-6">
                                <x-custom.detail-section label="Name" :value="$profile->name" />
                                <x-custom.detail-section label="Email" :value="$profile->email" />
                                <x-custom.detail-section label="Phone" :value="$profile->phone" />
                                <x-custom.detail-section label="Registered" :value="$profile->created_at->diffForHumans()" />
                            </div>
                            <div class="col-6">
                                {{-- <h4 class="fw-bold"> Analytics </h4> --}}
                                <x-custom.detail-section label="Profile" :value="$profile->username" />
                                <x-custom.detail-section label="Cards" :value="$profile->email" />
                                <x-custom.detail-section label="Views" :value="$profile->tiks" />
                                <x-custom.detail-section label="Platforms" :value="$total_platforms" />
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
                                <input class="form-check-input" wire:change="isDirect($event.target.checked)"
                                    type="checkbox" {{ $profile->user_direct == 1 ? 'checked' : '' }}>
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
                                    wire:change="isPrivate($event.target.checked)"
                                    {{ $profile->private == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 1 ? 'active' : '' }}" type="button"
                wire:click="editProfile()">Edit
                Profile</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 2 ? 'active' : '' }}" type="button"
                wire:click="platformsLinks()">Add
                Links</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 3 ? 'active' : '' }}" type="button"
                wire:click="platformsProfile()">Profile
                Links</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 4 ? 'active' : '' }}" type="button"
                wire:click="profileLinkedUser()">Linked User</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 5 ? 'active' : '' }}" type="button"
                wire:click="profileAnalytics()">Analytics</button>
        </li>
    </ul>
    <div>
        @if ($tab_change == 1)
            <livewire:enterprise.profile.edit :id="$profile_id" />
        @endif
    </div>
    <div>
        @if ($tab_change == 2)
            <livewire:enterprise.profile.platforms :id="$profile_id" :tab="$tab_change" />
        @endif
    </div>
    <div>
        @if ($tab_change == 3)
            <livewire:enterprise.profile.platforms :id="$profile_id" :tab="$tab_change" />
        @endif
    </div>
    <div>
        @if ($tab_change == 4)
            <livewire:enterprise.profile.profile-user :id="$profile_id" :tab="$tab_change" />
        @endif
    </div>
    <div>
        @if ($tab_change == 5)
            <livewire:enterprise.profile.profile-analytics-chart :id="$profile_id" :tab="$tab_change" />
        @endif
    </div>
    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>
    @section('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    @endsection
</div>
