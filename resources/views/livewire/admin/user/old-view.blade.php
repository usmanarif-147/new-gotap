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
                                <x-custom.detail-section label="Role" :value="$account->role" />
                                <x-custom.detail-section label="Registered" :value="$account->created_at" />
                            </div>
                            <div class="col-5 offset-2">
                                <h4 class="fw-bold"> Analytics </h4>
                                <x-custom.detail-section label="Profiles" :value="$account->name" />
                                <x-custom.detail-section label="Cards" :value="$account->email" />
                                <x-custom.detail-section label="Views" :value="$account->phone" />
                                <x-custom.detail-section label="Platforms" :value="$account->role" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="card mb-3">
            <div class="row p-3">
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                            aria-selected="true">Home</button>
                        <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-profile" type="button" role="tab"
                            aria-controls="v-pills-profile" aria-selected="false">Profile</button>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                aria-labelledby="v-pills-home-tab" tabindex="0">
                                this is first profile
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                aria-labelledby="v-pills-profile-tab" tabindex="0">
                                this is second profile
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
