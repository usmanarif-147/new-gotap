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
                <div class="col-md-3 offset-3">
                    <label> Select Status </label>
                    <select wire:model="filterByStatus" class="form-control form-select me-2">
                        <option value="" selected> Select Status </option>
                        <option value="1" selected> Pending </option>
                        <option value="2" selected> Approved </option>
                        <option value="3" selected> Rejected </option>
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
                    <label> Search </label>
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
                                <th> Name </th>
                                <th> Email </th>
                                <th> Phone </th>
                                <th> Type </th>
                                <th> Status </th>
                                <th> Apply Date </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($applications as $applicant)
                                <tr>
                                    <td> {{ $applicant->name }}</td>
                                    <td>
                                        {{ $applicant->email }}
                                    </td>
                                    <td>
                                        {{ $applicant->phone }}
                                    </td>
                                    <td>
                                        @if ($applicant->enterprise_type == '1')
                                            1-6 People
                                        @elseif ($applicant->enterprise_type == '2')
                                            1-20 People
                                        @elseif ($applicant->enterprise_type == '3')
                                            20+ People
                                        @else
                                            {{ $applicant->enterprise_type }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($applicant->status == 0)
                                            <span class="badge bg-label-warning me-1">
                                                Pending
                                            </span>
                                        @elseif($applicant->status == 1)
                                            <span class="badge bg-label-success me-1">
                                                Approved
                                            </span>
                                        @elseif($applicant->status == 2)
                                            <span class="badge bg-label-danger me-1">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <th>
                                        {{ defaultDateFormat($applicant->created_at) }}
                                    </th>
                                    <td>
                                        @if ($applicant->status == 0)
                                            <button wire:click="confirmAcceptApplication({{ $applicant->id }})"
                                                class="btn btn-success" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                                data-bs-placement="top" data-bs-html="true" title="Accept Request">
                                                <i class='bx bxs-user-check'></i>
                                            </button>
                                            <button wire:click="confirmRejectApplication({{ $applicant->id }})"
                                                class="btn btn-danger" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                                data-bs-placement="top" data-bs-html="true" title="Reject Request">
                                                <i class='bx bxs-user-x'></i>
                                            </button>
                                            @if ($applicant->file)
                                                <button wire:click="viewContract({{ $applicant->id }})"
                                                    class="btn btn-info" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                                    data-bs-placement="top" data-bs-html="true" title="View Contract">
                                                    <i class='bx bx-street-view'></i>
                                                </button>
                                            @endif
                                        @else
                                            --
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
                        @if ($applications->count() > 0)
                            {{ $applications->links() }}
                        @else
                            <p class="text-center"> No Record Found </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div>
        <div wire:ignore.self class="modal fade" id="reasonModal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reasonModalLabel">Rejection Reason</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="reject">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    Reason
                                    <span class="text-danger"> * </span>
                                    @error('reason')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <input type="text" wire:model="reason" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning">
                                Reject
                            </button>
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div wire:ignore.self class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Contract View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($pdfFile)
                        <iframe src="{{ Storage::url($pdfFile) }}" frameborder="0" width="100%"
                            height="500px"></iframe>
                    @else
                        <p>No PDF file selected.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>



    @include('livewire.admin.confirm-modal')



    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
        window.addEventListener('confirm-modal', event => {
            $('#confirmModal').modal('show')
        });
        window.addEventListener('reason-modal', event => {
            $('#reasonModal').modal('show')
        });

        window.addEventListener('show-pdf-modal', event => {
            $('#pdfModal').modal('show')
        });
        window.addEventListener('close-modal', event => {
            $('#confirmModal').modal('hide')
            $('#reasonModal').modal('hide')
        });
    </script>
</div>
