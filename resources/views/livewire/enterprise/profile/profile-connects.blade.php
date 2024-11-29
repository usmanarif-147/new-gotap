<div>
    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Total: {{ $total }}</h5>
                </div>
                <div class="d-flex align-items-center">
                    <label for="search" class="me-2 mb-0">Search</label>
                    <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search"
                        aria-label="Search">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive text-nowrap">
                    <table class="table admin-table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th> Name </th>
                                <th> Photo </th>
                                <th> Email </th>
                                <th> Phone No </th>
                                <th> Created Date </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($users as $ind => $user)
                                <tr>
                                    <td>
                                        <span class="d-block">{{ $user->name ? $user->name : $user->username }}</span>
                                    </td>
                                    <td>
                                        <div class="img-holder"
                                            style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
                                            <img src="{{ asset($user->photo && file_exists(public_path('storage/' . $user->photo)) ? Storage::url($user->photo) : 'user.png') }}"
                                                alt="Viewing Photo" class="img-fluid"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">{{ $user->email ? $user->email : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="d-block">{{ $user->phone ? $user->phone : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="d-block">{{ $user->created_at ? humanDateFormat($user->created_at) : 'N/A' }}</span>
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
