<div>
    <div>
        <div class="card mb-3">
            <div class="row p-2">
                <div class="col-md-3">
                    <img src="{{ asset($account->photo ? Storage::url($account->photo) : 'user.png') }}"
                        class="img-fluid rounded-start" height="300" width="250">
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <h4 class="fw-bold"> Details </h4>
                                <x-custom.detail-section label="Name" :value="$account->name" />
                                <x-custom.detail-section label="Email" :value="$account->email" />
                                <x-custom.detail-section label="Phone" :value="$account->phone" />
                                <x-custom.detail-section label="Role" :value="ucfirst($account->role)" />
                                <x-custom.detail-section label="Registered" :value="$account->created_at" />
                            </div>
                            <div class="col-5">
                                <h4 class="fw-bold"> Analytics </h4>
                                <x-custom.detail-section label="Profiles" :value="$account->name" />
                                <x-custom.detail-section label="Cards" :value="$account->email" />
                                <x-custom.detail-section label="Views" :value="$account->phone" />
                                <x-custom.detail-section label="Platforms" :value="$account->role" />
                            </div>
                            <div class="col-2">
                                <h4 class="fw-bold">
                                    <button class="btn btn-primary"> Accept </button>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab == 1 ? 'bg-primary text-white' : '' }}" type="button" role="tab"
                    aria-selected="{{ $tab == 1 ? 'true' : 'false' }}" wire:click="profilesTab">
                    Profiles
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab == 2 ? 'bg-primary text-white' : '' }}" type="button" role="tab"
                    aria-selected="{{ $tab == 2 ? 'true' : 'false' }}" wire:click="updateAccountTab">
                    Update Account
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $tab == 3 ? 'bg-primary text-white' : '' }}" type="button" role="tab"
                    aria-selected="{{ $tab == 3 ? 'true' : 'false' }}" wire:click="changePasswordTab">
                    Change Password
                </button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ $tab == 1 ? 'show active' : '' }}" role="tabpanel">
                <livewire:admin.user.tab.profiles />
            </div>
            <div class="tab-pane fade {{ $tab == 2 ? 'show active' : '' }}" role="tabpanel">
                <livewire:admin.user.tab.update-account />
            </div>
            <div class="tab-pane fade {{ $tab == 3 ? 'show active' : '' }}" role="tabpanel">
                <livewire:admin.user.tab.change-password />
            </div>
        </div>
    </div> --}}

</div>
