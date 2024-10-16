<div>
    <style>
        /* Background blur when modal is active */
        .modal-backdrop {
            backdrop-filter: blur(8px);
            /* Adjust the intensity of the blur */
        }

        /* Center modal content */
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Styling for logo preview */
        #logoPreview {
            border: 2px solid #007bff;
            padding: 5px;
            background-color: #fff;
            object-fit: cover;
        }

        /* Success message styling */
        .alert-success {
            margin-top: 10px;
        }

        .camera-icon-profile {
            position: absolute;
            top: 15%;
            /* left: 13%; */
            transform: translate(-50%, -50%);
            background-color: rgb(0, 0, 0);
            color: white;
            border-radius: 30%;
            padding: 2px;
            cursor: pointer;
            z-index: 2;
        }

        .camera-icon-profile i {
            font-size: 20px;
        }

        .toast-container {
            z-index: 1055;
            /* Make sure it's above other elements */
        }
    </style>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="card-header">
                <span>
                    <h5 style="margin-top:10px"> Total: {{ $total }} </h4>
                </span>
            </h2>
            <h5 class="card-header">
                <button type="button" class="btn" style="background: #0EA7C1; color:white" data-bs-toggle="modal"
                    data-bs-target="#subteamModal">
                    Create Sub Team
                </button>
            </h5>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3 offset-9">
                    <label for=""> Search </label>
                    <input class="form-control me-2" type="search" wire:model.debounce.500ms="search"
                        placeholder="Search By Name" aria-label="Search">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive text-nowrap">
                    <table class="table admin-table table-sm">
                        <thead class="table-light">
                            <tr style="text-align: center">
                                <th> Detail </th>
                                <th> Profiles </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($subteams as $ind => $subteam)
                                <tr>
                                    <td style="table-layout: auto; width:90%;">
                                        <div class="img-holder d-flex">
                                            <img src="{{ asset($subteam->logo && Storage::disk('public')->exists($subteam->logo) ? Storage::url($subteam->logo) : 'user.png') }}"
                                                alt="Viewer Photo">
                                            <div style="margin-left:20px;">
                                                <span
                                                    style="font-weight: bold; color:black; font-size:15px;">{{ $subteam->name ? $subteam->name : 'N/A' }}</span>
                                                <p style="padding-top: 5px;">
                                                    {{ $subteam->description ? $subteam->description : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width:30%;">
                                        <div class="">
                                            <i class="bx bx-user"></i>
                                            2
                                        </div>
                                    </td>
                                    <td style="width: 10%;">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="tooltip"
                                            data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                            title="edit" wire:click="editModal({{ $subteam->id }})">
                                            <i class='bx bx-edit'></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                            title="delete" wire:click="confirmModal({{ $subteam->id }})">
                                            <i class='bx bx-trash'></i>
                                        </button>
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
                        @if ($subteams->count() > 0)
                            {{ $subteams->links() }}
                        @else
                            <p class="text-center"> No Record Found </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Livewire Modal Add -->
    <div wire:ignore.self class="modal fade" id="subteamModal" tabindex="-1" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="width: 30%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title align-content-center" id="modalLabel">Add Sub Team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Form Fields -->
                    <form wire:submit.prevent="save">
                        <div class="text-center mb-3">
                            @if ($logo && !is_string($logo))
                                <img src="{{ $logo->temporaryUrl() }}" alt="user-avatar" class="rounded-circle border"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <img src="{{ asset('frame_2.webp') }}" alt="user-avatar" class="rounded-circle border"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            @endif
                            <div wire:loading wire:target="photo" wire:key="photo">
                                <i class="fa fa-spinner fa-spin mt-2 ml-2"></i>
                            </div>
                            <input type="file" id="profile_photo" wire:model="logo"
                                accept="image/png, image/jpeg, image/jpg, image/webp" class="d-none">
                            <label for="profile_photo" class="camera-icon-profile">
                                <i class="bx bxs-camera"></i>
                            </label>
                            @error('logo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="nameInput" wire:model="name"
                                placeholder="Enter name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="descriptionInput" class="form-label">Description</label>
                            <textarea class="form-control" id="descriptionInput" wire:model="description" rows="3"
                                placeholder="Enter description"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- <!-- Success message -->
                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" wire:click="save">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Livewire Modal Edit -->
    <div wire:ignore.self class="modal fade" id="editsubteamModal" tabindex="-1" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="width: 30%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title align-content-center" id="modalLabel">Edit Sub Team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Form Fields -->
                    <form wire:submit.prevent="updateSubteam">
                        <div class="text-center mb-3">
                            @if ($logo && !is_string($logo))
                                <img src="{{ $logo->temporaryUrl() }}" alt="user-avatar"
                                    class="rounded-circle border"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            @elseif ($subteamData)
                                <img src="{{ asset($subteamData->logo && Storage::disk('public')->exists($subteamData->logo) ? Storage::url($subteamData->logo) : 'user.png') }}"
                                    alt="user-avatar" class="rounded-circle border"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <img src="{{ asset('frame_2.webp') }}" alt="user-avatar"
                                    class="rounded-circle border"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            @endif
                            <div wire:loading wire:target="photo" wire:key="photo">
                                <i class="fa fa-spinner fa-spin mt-2 ml-2"></i>
                            </div>
                            <input type="file" id="profile_photo" wire:model="logo"
                                accept="image/png, image/jpeg, image/jpg, image/webp" class="d-none">
                            <label for="profile_photo" class="camera-icon-profile">
                                <i class="bx bxs-camera"></i>
                            </label>
                            @error('logo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="nameInput" wire:model="name"
                                placeholder="Enter name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="descriptionInput" class="form-label">Description</label>
                            <textarea class="form-control" id="descriptionInput" wire:model="description" rows="3"
                                placeholder="Enter description"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" wire:click="save">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.admin.confirm-modal')

    <script>
        window.addEventListener('close-modal', event => {
            $('#subteamModal').modal('hide');
            $('#confirmModal').modal('hide');
            $('#editsubteamModal').modal('hide');
        });
        window.addEventListener('confirm-modal', event => {
            $('#confirmModal').modal('show');
        });
        window.addEventListener('showEditModal', event => {
            $('#editsubteamModal').modal('show');
        });
    </script>
    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>


</div>
