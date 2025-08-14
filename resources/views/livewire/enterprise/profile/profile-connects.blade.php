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
                                <th> Profile </th>
                                <th> Email </th>
                                <th> Phone No </th>
                                <th> Created Date </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($users as $ind => $user)
                                <tr>
                                    <td style="width: 30%;">
                                        <div class="d-flex align-items-center">
                                            <!-- Profile Image -->
                                            <div
                                                style="width: 30px; height: 30px; border-radius: 50%; background-size: cover; background-position: center; overflow: hidden;">
                                                <img src="{{ asset($user->connection_photo && file_exists(public_path('storage/' . $user->connection_photo)) ? Storage::url($user->connection_photo) : 'user.png') }}"
                                                    alt="Viewer Photo" class="img-fluid"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                            <!-- Name and Email -->
                                            <div style="margin-left: 5%;">
                                                <span class="font-weight-bold text-dark"
                                                    style="font-size: 15px;">{{ $user->connection_name ? $user->connection_name : $user->connection_user_name }}</span>
                                                <p class="mb-0" style="font-size: 12px;">
                                                    {{ $user->connection_job_title ? $user->connection_job_title : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="d-block">{{ $user->connection_email ? $user->connection_email : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="d-block">{{ $user->connection_phone ? $user->connection_phone : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="d-block">{{ $user->connection_created_at ? humanDateFormat($user->connection_created_at) : 'N/A' }}</span>
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
