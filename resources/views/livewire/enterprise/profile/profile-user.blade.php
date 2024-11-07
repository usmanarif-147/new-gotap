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
        {{-- <div>
            <div class="card my-5">
                <div class="row p-2 align-items-center">
                    <div class="col-3 col-md-3 col-sm-3 col-xl-3 align-content-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="user-profile-picture">
                                <img src="{{ asset($user->photo && file_exists(public_path('storage/' . $user->photo)) ? Storage::url($user->photo) : 'user.png') }}"
                                    class="img-fluid rounded-circle border shadow mt-3" style="object-fit: cover">
                            </div>
                        </div>

                    </div>
                    <div class="col-9 col-md-9 col-sm-9 col-xl-9">
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-8 col-sm-8 col-md-8 col-xl-8">
                                    <h4 class="fw-bold text-center text-dark"> User Details </h4>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-xl-6">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-sm-4 col-md-4 col-xl-4">
                                            <h6 class="text-dark fw-bold m-0">Name</h6>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-8 col-xl-8">
                                            <p class="text-truncate m-0">{{ $user->name ? $user->name : '--' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-xl-6">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-sm-4 col-md-4 col-xl-4">
                                            <h6 class="text-dark fw-bold m-0">Username</h6>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-8 col-xl-8">
                                            <p class="text-truncate m-0">{{ $user->username ? $user->username : '--' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-xl-6">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-sm-4 col-md-4 col-xl-4">
                                            <h6 class="text-dark fw-bold m-0">Email</h6>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-8 col-xl-8">
                                            <p class="text-truncate m-0">{{ $user->email ? $user->email : '--' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-xl-6">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-sm-4 col-md-4 col-xl-4">
                                            <h6 class="text-dark fw-bold m-0">Cards</h6>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-8 col-xl-8">
                                            <p class="text-truncate m-0">{{ $user->name ? $user->name : '--' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-xl-6">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-sm-4 col-md-4 col-xl-4">
                                            <h6 class="text-dark fw-bold m-0">Phone</h6>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-8 col-xl-8">
                                            <p class="text-truncate m-0">{{ $user->phone ? $user->phone : '--' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-xl-6">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-sm-4 col-md-4 col-xl-4">
                                            <h6 class="text-dark fw-bold m-0">Views</h6>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-8 col-xl-8">
                                            <p class="text-truncate m-0">{{ $user->tiks ? $user->tiks : '--' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-xl-6">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-sm-4 col-md-4 col-xl-4">
                                            <h6 class="text-dark fw-bold m-0">Registered</h6>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-8 col-xl-8">
                                            <p class="text-truncate m-0">
                                                {{ $user->created_at ? $user->created_at->diffForHumans() : '--' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-xl-6">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-sm-4 col-md-4 col-xl-4">
                                            <h6 class="text-dark fw-bold m-0">Role</h6>
                                        </div>
                                        <div class="col-8 col-sm-8 col-md-8 col-xl-8">
                                            <p class="text-truncate m-0">{{ $user->role ? $user->role : '--' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    @endif

</div>
