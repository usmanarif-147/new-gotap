<div>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="card-header">
                {{ $heading }}
                <span>
                    <h5 style="margin-top:10px"> Total: {{ $total }} </h4>
                </span>
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <label> Select Role </label>
                    <select wire:model="filterByRole" class="form-control form-select me-2">
                        <option value="" selected> Select Role </option>
                        <option value="user">User</option>
                        <option value="enterpriser">Enterpiser</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label> Select Status </label>
                    <select wire:model="filterByStatus" class="form-control form-select me-2">
                        <option value="" selected> Select Status </option>
                        @foreach ($statuses as $val => $status)
                            <option value="{{ $val }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label> Sort by </label>
                    <select wire:model="sortBy" class="form-control form-select me-2">
                        <option value="" selected> Select Sort </option>
                        <option value="created_asc"> Created Date (Low to High)</option>
                        <option value="created_desc"> Created Date (High to Low)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label> Search by Name or Email </label>
                    <input class="form-control me-2" type="search" wire:model="search" placeholder="Search" disabled>
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
                                <th> Role </th>
                                <th> Status </th>
                                <th> Registration Date </th>
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
                                        {{ ucfirst($user->role) }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $user->status ? 'bg-label-success' : 'bg-label-danger' }} me-1">
                                            {{ $user->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <th>
                                        {{ defaultDateFormat($user->created_at) }}
                                    </th>
                                    <td>

                                        <a class="btn btn-primary" href="{{ route('admin.user.view', [$user->id]) }}">
                                            <i class='bx bx-street-view'></i>
                                        </a>
                                        {{-- <div class="dropdown">
                                            <button class="btn p-0" type="button" id="cardOpt3"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                                <a class="btn btn-icon btn-outline-secondary" data-bs-toggle="tooltip"
                                                    data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                                    title=""
                                                    data-bs-original-title="<i class='bx bx-edit-alt bx-xs' ></i> <span>View</span>"
                                                    href="{{ url('admin/user/' . $user->id . '/view') }}">
                                                    <i class='bx bx-street-view'></i>
                                                </a>
                                            </div>
                                        </div> --}}
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
    <!-- Modal -->
    {{-- @include('livewire.admin.merchant.create_modal')
    @include('livewire.admin.merchant.edit_modal')
    @include('livewire.admin.merchant.edit_password')
    @include('livewire.admin.merchant.edit_balance')
    @include('admin.partials.confirm_modal') --}}

</div>

@section('script')
    {{-- <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });

        window.addEventListener('show-create-modal', event => {
            $('#createMerchantModal').modal('show')
        });

        window.addEventListener('show-edit-modal', event => {
            $('#editMerchantModal').modal('show')
        });

        window.addEventListener('edit-password-modal', event => {
            $('#editPasswordModal').modal('show')
        });

        window.addEventListener('edit-balance-modal', event => {
            $('#editBalanceModal').modal('show')
        });

        window.addEventListener('close-modal', event => {
            $('#createMerchantModal').modal('hide');
            $('#editMerchantModal').modal('hide')
            $('#confirmModal').modal('hide');
            $('#editPasswordModal').modal('hide')
            $('#editBalanceModal').modal('hide')
        });

        window.addEventListener('open-confirm-modal', event => {
            $('#confirmModal').modal('show');
        });
    </script> --}}
@endsection
