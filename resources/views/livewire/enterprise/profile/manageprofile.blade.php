<div>
    <style>
        .user-profile {
            height: 150px;
            width: 150px;
        }

        .user-profile img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }

        .col-5 label {
            font-weight: bold;
            color: black;
        }

        .col-4 label {
            font-weight: bold;
            color: black;
        }

        .col-8 label {
            font-weight: bold;
            color: black;
        }
    </style>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 0 ? 'active' : '' }}"
                style=" {{ $tab_change == 0 ? 'background-color:black;box-shadow:0 2px 4px 0 rgb(0,0,0)' : '' }}"
                type="button" wire:click="viewProfile()">Profile</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 1 ? 'active' : '' }}"
                style="{{ $tab_change == 1 ? 'background-color:black;box-shadow:0 2px 4px 0 rgb(0,0,0)' : '' }}"
                type="button" wire:click="editProfile()">Edit
                Profile</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 2 ? 'active' : '' }}" type="button"
                style="{{ $tab_change == 2 ? 'background-color:black;box-shadow:0 2px 4px 0 rgb(0,0,0)' : '' }}"
                wire:click="platformsLinks()">Add
                Links</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 3 ? 'active' : '' }}" type="button"
                style="{{ $tab_change == 3 ? 'background-color:black;box-shadow:0 2px 4px 0 rgb(0,0,0)' : '' }}"
                wire:click="platformsProfile()">Profile
                Links</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 4 ? 'active' : '' }}" type="button"
                style="{{ $tab_change == 4 ? 'background-color:black;box-shadow:0 2px 4px 0 rgb(0,0,0)' : '' }}"
                wire:click="profileLinkedUser()">Linked User</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 5 ? 'active' : '' }}" type="button"
                style="{{ $tab_change == 5 ? 'background-color:black;box-shadow:0 2px 4px 0 rgb(0,0,0)' : '' }}"
                wire:click="profileAnalytics()">Analytics</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 6 ? 'active' : '' }}" type="button"
                style="{{ $tab_change == 6 ? 'background-color:black;box-shadow:0 2px 4px 0 rgb(0,0,0)' : '' }}"
                wire:click="profileLeads()">Leads</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_change == 7 ? 'active' : '' }}" type="button"
                style="{{ $tab_change == 7 ? 'background-color:black;box-shadow:0 2px 4px 0 rgb(0,0,0)' : '' }}"
                wire:click="profileConnects()">People</button>
        </li>
    </ul>
    <div>
        @if ($tab_change == 0)
            <div>
                <div class="card mb-3">
                    <div class="row p-2 align-items-center">
                        <div class="col-md-3 align-content-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="user-profile border p-1 shadow rounded-3 mt-5">
                                    <img src="{{ asset($profile->photo && file_exists(public_path('storage/' . $profile->photo)) ? Storage::url($profile->photo) : 'user.png') }}"
                                        class="img-fluid rounded">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="fw-bold text-center text-dark"> Profile Details </h4>
                                    </div>
                                    <div class="col-md-6">
                                        <x-custom.detail-section label="Name" :value="$profile->name" />
                                        <x-custom.detail-section label="Email" :value="$profile->email" />
                                        <x-custom.detail-section label="Phone" :value="$profile->phone" />
                                        <x-custom.detail-section label="Registered" :value="$profile->created_at->diffForHumans()" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-custom.detail-section label="Username" :value="$profile->username" />
                                        <x-custom.detail-section label="Card" :value="$card_status" />
                                        <x-custom.detail-section label="Views" :value="$profile->tiks" />
                                        <x-custom.detail-section label="Platforms" :value="$total_platforms" />
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="mb-1 row">
                                            <div class="col-5">
                                                <label>Direct</label>
                                            </div>
                                            <div class="col-7 form-check form-switch">
                                                <input class="form-check-input"
                                                    wire:change="isDirect($event.target.checked)" type="checkbox"
                                                    {{ $profile->user_direct == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
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
                </div>
            </div>
        @endif
    </div>
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
    <div>
        @if ($tab_change == 6)
            <livewire:enterprise.profile.profile-leads :id="$profile_id" :tab="$tab_change" />
        @endif
    </div>
    <div>
        @if ($tab_change == 7)
            <livewire:enterprise.profile.profile-connects :id="$profile_id" :tab="$tab_change" />
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
        <script src="{{ asset('assets/js/chart.js') }}"></script>
    @endsection
</div>
