<div>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="card-header">
                {{-- {{ $heading }} --}}
                <span>
                    <h5 style="margin-top:10px"> Total: {{ $total }} </h4>
                </span>
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3 offset-9">
                    <label for=""> Search </label>
                    <input class="form-control me-2" type="search" wire:model.debounce.500ms="search"
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
                                <th> Employee</th>
                                <th> Lead</th>
                                <th> Name </th>
                                <th> Email </th>
                                <th> Phone No </th>
                                <th> Created Date </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($leads as $ind => $lead)
                                <tr>
                                    <td>
                                        <div class="img-holder">
                                            <img src="{{ asset($lead->viewing_photo && Storage::disk('public')->exists($lead->viewing_photo) ? Storage::url($lead->viewing_photo) : 'user.png') }}"
                                                alt="Viewer Photo">
                                        </div>
                                        {{ $lead->viewing_username ? $lead->viewing_username : 'N/A' }}
                                    </td>
                                    <td>
                                        <div class="img-holder">
                                            <img src="{{ asset($lead->viewer_photo && Storage::disk('public')->exists($lead->viewer_photo) ? Storage::url($lead->viewer_photo) : 'user.png') }}"
                                                alt="Viewer Photo">
                                        </div>
                                        {{ $lead->viewer_username ? $lead->viewer_username : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $lead->name ? $lead->name : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $lead->email ? $lead->email : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $lead->phone ? $lead->phone : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $lead->created_at ? date('Y-m-d', strtotime($lead->created_at)) : 'N/A' }}
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
                        @if ($leads->count() > 0)
                            {{ $leads->links() }}
                        @else
                            <p class="text-center"> No Record Found </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
