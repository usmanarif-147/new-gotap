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
    @if ($user == null)
        <div class="card mx-auto">
            <div class="card-body text-center">
                No User Linked with this Profile
            </div>
        </div>
    @else
        <div>
            <div class="card">
                <div class="row p-2 align-items-center mb-3">
                    <div class="col-md-3 align-content-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="user-profile border p-1 shadow rounded-3 mt-5">
                                <img src="{{ asset($user->photo && file_exists(public_path('storage/' . $user->photo)) ? Storage::url($user->photo) : 'user.png') }}"
                                    class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="fw-bold text-center text-dark"> User Details </h4>
                                </div>
                                <div class="col-md-6">
                                    <x-custom.detail-section label="Name" :value="$user->name" />
                                    <x-custom.detail-section label="Email" :value="$user->email" />
                                    <x-custom.detail-section label="Phone" :value="$user->phone" />
                                    <x-custom.detail-section label="Registered" :value="$user->created_at->diffForHumans()" />
                                </div>
                                <div class="col-md-6">
                                    <x-custom.detail-section label="Username" :value="$user->username" />
                                    <x-custom.detail-section label="Role" :value="$user->role" />
                                    <x-custom.detail-section label="Views" :value="$user->tiks" />
                                    {{-- <x-custom.detail-section label="Platforms" :value="$total_platforms" /> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
