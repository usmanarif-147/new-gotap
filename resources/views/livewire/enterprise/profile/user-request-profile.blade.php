<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Total: {{ $total }}</h5>
                </div>
                <div class="d-flex align-items-center">
                    <label for="search" class="me-2 mb-0">Search</label>
                    <input id="search" class="form-control" type="search" wire:model.debounce.500ms="search"
                        placeholder="Search" aria-label="Search">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive text-nowrap">
                    <table class="table admin-table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th> User </th>
                                <th> Status </th>
                                <th> Created Date </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($requestProfile as $ind => $reqPro)
                                <tr>
                                    <td style="width: 30%;">
                                        <div class="d-flex align-items-center">
                                            <!-- Profile Image -->
                                            <div
                                                style="width: 30px; height: 30px; border-radius: 50%; background-size: cover; background-position: center; overflow: hidden;">
                                                <img src="{{ asset($reqPro->photo && file_exists(public_path('storage/' . $reqPro->photo)) ? Storage::url($reqPro->photo) : 'user.png') }}"
                                                    alt="Viewer Photo" class="img-fluid"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                            <!-- Name and Email -->
                                            <div style="margin-left: 5%;">
                                                <span class="font-weight-bold text-dark"
                                                    style="font-size: 15px;">{{ $reqPro->username ? $reqPro->username : 'N/A' }}</span>
                                                <p class="mb-0" style="font-size: 12px;">
                                                    {{ $reqPro->email ? $reqPro->email : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="cell">
                                        @if ($reqPro->status == 0)
                                            <span class="badge bg-warning">
                                                Pending
                                            </span>
                                        @elseif($reqPro->status == 1)
                                            <span class="badge bg-success">
                                                Accept
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                d-Link
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="d-block">{{ $reqPro->created_at ? humanDateFormat($reqPro->created_at) : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if ($reqPro->status == 0)
                                            <button class="btn btn-dark btn-sm" data-bs-toggle="tooltip"
                                                data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                                title="Link Profile"
                                                wire:click="showProfileModal({{ $reqPro->UserId }} , {{ $reqPro->id }})">
                                                <i class='bx bxs-comment-add'></i>
                                            </button>
                                        @elseif ($reqPro->status == 1)
                                            <span class="badge bg-success">
                                                Attached With Profile
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                User D-Link From Profile
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="demo-inline-spacing">
                        @if ($requestProfile->count() > 0)
                            {{ $requestProfile->links() }}
                        @else
                            <p class="text-center"> No Record Found </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="manageProfilesModal" tabindex="-1"
        aria-labelledby="manageProfilesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attach Profiles To Users</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <input type="text" class="form-control" placeholder="Search profiles..."
                                    wire:model.live="searchTerm">
                            </div>
                            <hr>
                            <div class="profile-list" style="max-height: 300px; overflow-y: auto;">
                                <ul class="list-group">
                                    @forelse($profiles as $profile)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center" style="width: 75%;">

                                                <img src="{{ asset($profile->photo && Storage::disk('public')->exists($profile->photo) ? Storage::url($profile->photo) : 'user.png') }}"
                                                    alt="Profile Image" class="rounded-circle me-2" width="40"
                                                    height="40">

                                                <span class="profile-name text-truncate"
                                                    style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $profile->username ? $profile->username : $profile->name }}
                                                </span>
                                            </div>
                                            <button wire:click="attachToUser({{ $profile->id }})"
                                                class="btn btn-warning btn-sm" wire:loading.attr="disabled">
                                                Attach
                                            </button>
                                            <div wire:loading wire:target="attachToUser({{ $profile->id }})"
                                                class="spinner-border spinner-border-sm text-danger" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                            <a class="btn" style="background: #0EA7C1; color:white"
                                                href="{{ route('enterprise.profile.create') }}">
                                                Create Profile
                                            </a>
                                            <p class="mb-0">All Profiles Already Attached with Users create New.</p>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
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
        window.addEventListener('showProfilesModal', event => {
            $('#manageProfilesModal').modal('show');
        });
        window.addEventListener('close-profile-modal', event => {
            $('#manageProfilesModal').modal('hide');
        });
    </script>

</div>
