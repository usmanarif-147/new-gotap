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
                                <th> Lead </th>
                                <th> Profile </th>
                                <th> Phone No </th>
                                <th> Created Date </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($leads as $ind => $lead)
                                <tr>
                                    <td style="width: 30%;">
                                        <div class="d-flex align-items-center">
                                            <!-- Profile Image -->
                                            <div
                                                style="width: 30px; height: 30px; border-radius: 50%; background-size: cover; background-position: center; overflow: hidden;">
                                                <img src="{{ asset($lead->viewer_photo && Storage::disk('public')->exists($lead->viewer_photo) ? Storage::url($lead->viewer_photo) : 'user.png') }}"
                                                    alt="Viewer Photo" class="img-fluid"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                            <!-- Name and Email -->
                                            <div style="margin-left: 5%;">
                                                <span class="font-weight-bold text-dark"
                                                    style="font-size: 15px;">{{ $lead->name ? $lead->name : 'N/A' }}</span>
                                                <p class="mb-0" style="font-size: 12px;">
                                                    {{ $lead->email ? $lead->email : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="img-holder"
                                            style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
                                            <img src="{{ asset($lead->viewing_photo && Storage::disk('public')->exists($lead->viewing_photo) ? Storage::url($lead->viewing_photo) : 'user.png') }}"
                                                alt="Viewing Photo" class="img-fluid"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">{{ $lead->phone ? $lead->phone : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="d-block">{{ $lead->created_at ? humanDateFormat($lead->created_at) : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('enterprise.leads.view', $lead->id) }}"
                                            class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                            data-bs-placement="top" data-bs-html="true" title="view Lead">
                                            {{-- Manage Profile --}}
                                            <i class='bx bx-show'></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                            title="delete" wire:click="confirmModal({{ $lead->id }})">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                        <a href="{{ route('lead.download', $lead->id) }}" class="btn btn-primary btn-sm"
                                            data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                            data-bs-html="true" title="download">
                                            <i class='bx bx-download'></i>
                                        </a>
                                        <button class="btn btn-dark btn-sm" data-bs-toggle="tooltip"
                                            data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                            title="{{ $lead->note ? 'Edit Note' : 'Add Note' }}"
                                            wire:click="showNoteModal({{ $lead->id }})">
                                            <i class='{{ $lead->note ? 'bx bxs-edit' : 'bx bxs-comment-add' }}'></i>
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

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="leadNoteModal" tabindex="-1" aria-labelledby="leadNoteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leadNoteModalLabel">Add / Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="noteSave({{ $leadId }})">
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="note" rows="3" wire:model="note"></textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Note</button>
                        </div>
                    </form>
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

        window.addEventListener('show-note-modal', event => {
            $('#leadNoteModal').modal('show')
        });

        window.addEventListener('close-modal', event => {
            $('#confirmModal').modal('hide')
        });

        window.addEventListener('noteSaved', event => {
            $('#leadNoteModal').modal('hide')
        });

        document.addEventListener('livewire:load', function() {
            // Initialize tooltips when Livewire loads for the first time
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Reinitialize tooltips after every Livewire update
            Livewire.hook('message.processed', (message, component) => {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl); // Reinitialize Bootstrap tooltips
                });
            });

            // Hide the tooltip when a button is clicked
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(element) {
                element.addEventListener('click', function() {
                    var tooltipInstance = bootstrap.Tooltip.getInstance(
                    element); // Get the tooltip instance
                    if (tooltipInstance) {
                        tooltipInstance.hide(); // Hide the tooltip when the button is clicked
                    }
                });
            });
        });
    </script>

</div>
