<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Total: {{ $total }}</h5>
                </div>
                <div class="flex-grow-1 d-flex justify-content-center">
                    <button wire:click="showBulkEmailModal" class="btn btn-dark"
                        @if (count($selectedLeads) === 0) disabled @endif>
                        Send Mails
                    </button>
                </div>
                <div class="flex-grow-1 d-flex justify-content-center">
                    <button wire:click="showManualModel" class="btn btn-dark">
                        Add Lead
                    </button>
                </div>
                <div class="flex-grow-1 d-flex justify-content-center">
                    <button wire:click="exportLeads" class="btn btn-dark">
                        Export
                        <span wire:loading wire:target="exportLeads" class="spinner-border spinner-border-sm"></span>
                    </button>
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
                                <th>
                                    <input type="checkbox" wire:model="selectAll">
                                </th>
                                <th> Lead </th>
                                <th> Profile </th>
                                <th> Type </th>
                                <th> Phone No </th>
                                <th> Created Date </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($leads as $ind => $lead)
                                <tr>
                                    <td><input type="checkbox" wire:model="selectedLeads" value="{{ $lead->id }}">
                                    </td>
                                    <td style="width: 30%;">
                                        <div class="d-flex align-items-center">
                                            <!-- Profile Image -->
                                            {{-- <div
                                                style="width: 30px; height: 30px; border-radius: 50%; background-size: cover; background-position: center; overflow: hidden;">
                                                <img src="{{ asset($lead->viewer_photo && file_exists(public_path('storage/' . $lead->viewer_photo)) ? Storage::url($lead->viewer_photo) : 'user.png') }}"
                                                    alt="Viewer Photo" class="img-fluid"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div> --}}
                                            <div
                                                style="width: 30px; height: 30px; border-radius: 50%; overflow: hidden; display: flex; justify-content: center; align-items: center; background: {{ $lead->viewer_photo && file_exists(public_path('storage/' . $lead->viewer_photo)) ? 'none' : '#000' }};">
                                                @if ($lead->viewer_photo && file_exists(public_path('storage/' . $lead->viewer_photo)))
                                                    <img src="{{ Storage::url($lead->viewer_photo) }}"
                                                        alt="Viewer Photo" class="img-fluid"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <span style="color: #fff; font-weight: bold; font-size: 16px;">
                                                        {{ $lead->name ? strtoupper(substr($lead->name, 0, 1)) : 'No' }}
                                                    </span>
                                                @endif
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
                                            <img src="{{ asset($lead->viewing_photo && file_exists(public_path('storage/' . $lead->viewing_photo)) ? Storage::url($lead->viewing_photo) : 'user.png') }}"
                                                alt="Viewing Photo" class="img-fluid"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('enterprise.leads.view', $lead->id) }}" style="color:black"
                                            data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                            data-bs-html="true"
                                            title="{{ $lead->type == 1 ? 'Scanned' : ($lead->type == 2 ? 'Lead form' : ($lead->type == 3 ? 'Manually Add' : 'User To User')) }}">
                                            <i
                                                class="{{ $lead->type == 1 ? 'bx bxs-devices' : ($lead->type == 2 ? 'bx bxs-receipt' : ($lead->type == 3 ? 'bx bxs-detail' : 'bx bxs-group')) }}"></i>
                                        </a>
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
                                        <a href="{{ route('lead.download', $lead->id) }}"
                                            class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                            data-bs-placement="top" data-bs-html="true" title="download">
                                            <i class='bx bx-download'></i>
                                        </a>
                                        <button class="btn btn-dark btn-sm" data-bs-toggle="tooltip"
                                            data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                            title="{{ $lead->note ? 'Edit Note' : 'Add Note' }}"
                                            wire:click="showNoteModal({{ $lead->id }})">
                                            <i class='{{ $lead->note ? 'bx bxs-edit' : 'bx bxs-comment-add' }}'></i>
                                        </button>
                                        <button class="btn btn-dark btn-sm" data-bs-toggle="tooltip"
                                            data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                            title="send Mail" wire:click="showEmailModal({{ $lead->id }})">
                                            <i class="bx bx-envelope"></i>
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

    <!--Note Modal -->
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

    <!-- EmailModal -->
    <div wire:ignore.self class="modal fade" id="leadEmailModal" tabindex="-1"
        aria-labelledby="leadEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leadEmailModalLabel">Send Mail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="sendEmailToLead({{ $leadId }})">
                        <div class="mb-3">
                            <label class="form-label" for="leadEmail">Lead Email:</label>
                            <input type="email" class="form-control" id="leadEmail" wire:model="leadEmail"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="subject">Subject *:</label>
                            <input type="text" class="form-control" id="subject" wire:model="subject">
                        </div>
                        <div wire:ignore class="mb-3">
                            <label for="customMessage" class="form-label">Custom Message *:</label>
                            <textarea class="form-control" id="customMessage" rows="3" wire:model.defer="customMessage"></textarea>
                            @error('customMessage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Send Email</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- BulkEmailModal -->
    <div wire:ignore.self class="modal fade" id="leadBulkEmailModal" tabindex="-1"
        aria-labelledby="leadBulkEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leadBulkEmailModalLabel">Send Bulk Mails</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="sendBulkEmail">
                        <div class="mb-3">
                            <label class="form-label" for="subject1">Subject:</label>
                            <input type="text" class="form-control" id="subject1" wire:model="subject">
                        </div>
                        <div wire:ignore class="mb-3">
                            <label for="customMessage" class="form-label">Custom Message:</label>
                            <textarea class="form-control" id="customMessage1" rows="3" wire:model.defer="customMessage"></textarea>
                            @error('customMessage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Send Bulk Emails</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Manuall add Modal -->
    <div wire:ignore.self class="modal fade" id="leadManualModal" tabindex="-1"
        aria-labelledby="leadManualModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leadManualModalLabel">Add Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="addManualLead">
                        <div class="mb-3">
                            <label for="profileId" class="form-label">Name</label>
                            <select wire:model="profileId" id="profileId" class="form-control">
                                <option value="">Select Profile</option>
                                @foreach ($profiles as $profile)
                                    <option value="{{ $profile->id }}">{{ $profile->username }}</option>
                                @endforeach
                            </select>
                            @error('profileId')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" wire:model="name" class="form-control">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" id="email" wire:model="email" class="form-control">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" wire:model="phone" class="form-control">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="noteLead" class="form-label">Note</label>
                            <textarea class="form-control" id="noteLead" rows="3" wire:model="noteLead"></textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading wire:target="addManualLead"
                                    class="spinner-border spinner-border-sm"></span>
                                Save Lead</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @include('livewire.admin.confirm-modal')
    <!-- CKEditor Script -->
    <script src="{{ asset('assets/js/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('livewire:load', function() {
            if (document.querySelector('#customMessage')) {
                ClassicEditor
                    .create(document.querySelector('#customMessage'))
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                            @this.set('customMessage', editor.getData());
                        });
                        Livewire.on('refreshEditor', content => {
                            editor.setData(content);
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            if (document.querySelector('#customMessage1')) {
                ClassicEditor
                    .create(document.querySelector('#customMessage1'))
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                            @this.set('customMessage', editor.getData());
                        });

                        Livewire.on('refreshEditor', content => {
                            editor.setData(content);
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    </script>


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

        window.addEventListener('show-manual-add-modal', event => {
            $('#leadManualModal').modal('show')
        });

        window.addEventListener('show-email-modal', event => {
            $('#leadEmailModal').modal('show')
        });

        window.addEventListener('show-bulk-email-modal', event => {
            $('#leadBulkEmailModal').modal('show')
        });

        window.addEventListener('close-modal', event => {
            $('#confirmModal').modal('hide')
        });

        window.addEventListener('noteSaved', event => {
            $('#leadNoteModal').modal('hide')
        });

        window.addEventListener('leadSaved', event => {
            $('#leadManualModal').modal('hide')
        });

        window.addEventListener('emailSend', event => {
            $('#leadEmailModal').modal('hide')
        });

        window.addEventListener('emailBulkSend', event => {
            $('#leadBulkEmailModal').modal('hide')
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
