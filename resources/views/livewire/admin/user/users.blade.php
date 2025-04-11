<div>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="card-header">
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
                    <input class="form-control me-2" type="search" wire:model="search" placeholder="Search">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive text-nowrap">
                    <table class="table admin-table">
                        <thead class="table-light">
                            <tr>
                                <th> Sr </th>
                                <th> Name </th>
                                <th> Email </th>
                                <th> Role </th>
                                <th> Status </th>
                                <th> Registration Date </th>
                                <th>Cards Types</th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($users as $ind => $user)
                                <tr>
                                    <td>{{ $ind + 1 }}</td>
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
                                        @php
                                            $hasCards = $user->profiles->flatMap(fn($p) => $p->cards)->count();
                                        @endphp

                                        @if ($hasCards)
                                            <button class="btn btn-info btn-sm"
                                                wire:click="showCards({{ $user->id }})">
                                                View Cards
                                            </button>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.user.edit', [$user->id]) }}" class="btn btn-warning"
                                            data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                            data-bs-html="true" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <a class="btn btn-primary" href="{{ route('admin.user.view', [$user->id]) }}">
                                            <i class='bx bx-street-view'></i>
                                        </a>
                                        <button class="btn btn-danger" wire:click ="confirmModal({{ $user->id }})">
                                            <i class="bx bx-trash"></i>
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

    @if ($showCardModal && $selectedUser)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Card Details for {{ $selectedUser->name }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('showCardModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        @foreach ($selectedUser->profiles as $profile)
                            @if ($profile->cards->count())
                                <h6>Profile: {{ $profile->name ?? 'N/A' }}</h6>
                                <ul class="list-group mb-3">
                                    @foreach ($profile->cards as $card)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ ucfirst($card->type) }}</span>
                                            <span class="badge {{ $card->status ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $card->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('showCardModal', false)">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @include('layouts.admin.partials.confirm_modal')

    <script>
        window.addEventListener('swal:modal', event => {
            $('#confirmModal').modal('hide');
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });

        window.addEventListener('confirmModal', event => {
            $('#confirmModal').modal('show');
        });

        window.addEventListener('close-modal', event => {
            $('#confirmModal').modal('hide');
        });

        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>

</div>
