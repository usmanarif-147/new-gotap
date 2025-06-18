<div>

    <div class="container">
        <div>
            <div class="d-flex justify-content-between">
                <h2 class="card-header">
                    <span>
                        <h5 style="margin-top:10px"> Total: {{ $total }} </h4>
                    </span>
                </h2>
            </div>
        </div>
        <div class="row h-100">
            <!-- First Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <label> Select Status </label>
                                <select wire:model="filterByStatus" class="form-control form-select me-2">
                                    <option value="" selected> Select Status </option>
                                    @foreach ($statuses as $val => $status)
                                        <option value="{{ $val }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label> Sort by </label>
                                <select wire:model="sortBy" class="form-control form-select me-2">
                                    <option value="" selected> Select Sort </option>
                                    <option value="created_asc"> Created Date (Low to High)</option>
                                    <option value="created_desc"> Created Date (High to Low)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label> Search</label>
                                <input class="form-control me-2" type="search" wire:model="search"
                                    placeholder="Search">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive text-nowrap">
                                <table class="table admin-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th> Name </th>
                                            <th> Email </th>
                                            <th> Subscription </th>
                                            <th> Expiray Date </th>
                                            <th> Actions </th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($users as $user)
                                            <tr>
                                                <td> {{ $user->name ? $user->name : 'N/A' }}</td>
                                                <td>
                                                    {{ $user->email }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge 
                                                        {{ $user->userSubscription
                                                            ? (\Carbon\Carbon::parse($user->userSubscription->end_date)->isPast()
                                                                ? 'bg-label-danger'
                                                                : 'bg-label-success')
                                                            : 'bg-label-danger' }} 
                                                        me-1">
                                                        {{ $user->userSubscription
                                                            ? (\Carbon\Carbon::parse($user->userSubscription->end_date)->isPast()
                                                                ? 'Expired'
                                                                : 'Active')
                                                            : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $user->userSubscription
                                                        ? defaultDateFormat(\Carbon\Carbon::parse($user->userSubscription->end_date))
                                                        : 'No subscription' }}
                                                </td>


                                                <td>
                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="tooltip"
                                                        data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                                        title="change Subscription"
                                                        wire:click="editSubscription({{ $user->id }})">
                                                        <i class='bx bx-edit'></i>
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
                                    @if ($users->count() > 0)
                                        {{ $users->links() }}
                                    @else
                                        <p class="text-center"> No Record Found </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Create Enterprise</h2>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="registerEnterpriser">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Name
                                            <span class="text-danger">*</span>
                                            @error('name')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </label>
                                        <input type="text" class="form-control" wire:model="name">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Email
                                            <span class="text-danger">*</span>
                                            @error('email')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </label>
                                        <input type="email" class="form-control" wire:model="email">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Company Name
                                            <span class="text-danger">*</span>
                                            @error('companyName')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </label>
                                        <input type="text" class="form-control" wire:model="companyName">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Phone Number
                                            <span class="text-danger">*</span>
                                            @error('phone')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </label>
                                        <input type="tel" class="form-control" wire:model="phone">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Start Date
                                            <span class="text-danger">*</span>
                                            @error('startDate')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </label>
                                        <input type="date" class="form-control" wire:model="startDate">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            End Date
                                            <span class="text-danger">*</span>
                                            @error('endDate')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </label>
                                        <input type="date" class="form-control" wire:model="endDate">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Enterprise Type
                                            <span class="text-danger">*</span>
                                            @error('enterprise_type')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </label>
                                        <select class="form-control" wire:model="enterprise_type">
                                            <option value="">Select Enterprise Type</option>
                                            <option value="1">1-6 People</option>
                                            <option value="2">1-20 People</option>
                                            <option value="3">20+ People</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Upload Contract
                                            <span class="text-danger">*</span>
                                            @error('file')
                                                <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </label>
                                        <input type="file" class="form-control" wire:model="file">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4 mb-4">
                                <button type="submit" class="btn btn-primary w-50">Create Enterprise</button>
                            </div>
                        </form>
                    </div>
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
                    <h5 class="modal-title align-content-center" id="modalLabel">Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Form Fields  -->
                    <form wire:submit.prevent="updateSubscription">
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="nameInput" wire:model="subStartDate">
                            @error('subStartDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="nameInput" wire:model="subEndDate">
                            @error('subEndDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                Enterprise Type
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" wire:model="subType">
                                <option value="">Select Enterprise Type</option>
                                <option value="1">1-6 People</option>
                                <option value="2">1-20 People</option>
                                <option value="3">20+ People</option>
                            </select>
                            @error('subType')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                Description
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" wire:model="subDescription">
                                <option value="">Select Period</option>
                                <option value="Trail">Trail</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                            @error('subDescription')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                Upload Contract
                                <span class="text-danger">*</span>
                                @error('subFile')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                            </label>
                            <input type="file" class="form-control" wire:model="subFile">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" wire:click="updateSubscription">Add
                        Subscription</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('close-modal', event => {
            $('#editsubteamModal').modal('hide');
        });
        window.addEventListener('showEditModal', event => {
            $('#editsubteamModal').modal('show');
        });
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>
</div>
