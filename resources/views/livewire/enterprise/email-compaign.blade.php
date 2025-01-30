<div>
    <style>
        .position-relative:hover .nested-dropdown {
            display: block;
        }

        .modal-body {
            background-color: #f8f9fa;
            /* Light background for the modal content */
            padding: 2rem;
            /* Additional padding inside the modal */
            color: #495057;
            /* Default text color */
        }

        .email-content img {
            max-width: 100%;
            /* Ensure images are responsive */
            border-radius: 5px;
            /* Optional: Round image corners */
        }

        .email-content a {
            color: #007bff;
            /* Color for links */
            text-decoration: none;
            /* Remove underline */
        }

        .email-content a:hover {
            text-decoration: underline;
            /* Underline on hover */
        }

        .ck-editor__editable {
            max-height: 400px !important;
            overflow-y: auto !important;
        }
    </style>
    <div class="container mt-4">
        <div class="row">
            <!-- Left Section: Form -->
            <div class="col-md-6">
                <div class="mb-3 position-relative">
                    <label class="form-label fw-bold">Recipients *</label>

                    <!-- Input Field Showing Selected Names -->
                    <div class="form-control d-flex align-items-center flex-wrap" style="cursor: pointer;"
                        wire:click="toggleDropdown">
                        @forelse ($selectedNames as $email => $name)
                            <span class="badge bg-dark text-white me-1 mb-1" style="cursor: pointer;"
                                wire:click.stop="removeRecipient('{{ $email }}')">
                                {{ $name }} &times;
                            </span>
                        @empty
                            <span class="text-muted">Select profiles and leads...</span>
                        @endforelse
                    </div>

                    <!-- Dropdown List -->
                    <!-- Dropdown List -->
                    @if ($showDropdown)
                        <div class="border position-absolute bg-white w-100 p-2 rounded shadow"
                            style="max-height: 200px; overflow-y: auto; z-index: 10;">

                            <!-- 'All Profiles' Option -->
                            <div class="d-flex align-items-center mb-2">
                                <input type="checkbox" wire:model="selectAll" wire:change="toggleSelectAll"
                                    class="form-check-input me-2">
                                <span class="fw-bold">All Profiles</span>
                            </div>

                            @foreach ($profiles as $profile)
                                <div class="d-flex align-items-center mb-2">
                                    <input type="checkbox" wire:model="recipients" value="{{ $profile['email'] }}"
                                        class="form-check-input me-2" wire:change="updateInput">
                                    <img src="{{ asset($profile->photo && file_exists(public_path('storage/' . $profile->photo)) ? Storage::url($profile->photo) : 'user.png') }}"
                                        alt="{{ $profile['name'] }}" class="rounded-circle" width="30"
                                        height="30">
                                    <span class="ms-2">{{ $profile['name'] }}</span>
                                </div>
                            @endforeach

                            <!-- 'All Leads' Option -->
                            <div class="d-flex align-items-center mb-2">
                                <input type="checkbox" wire:model="selectAllLeads" wire:change="toggleSelectAllLeads"
                                    class="form-check-input me-2">
                                <span class="fw-bold">All Leads</span>
                            </div>

                            @foreach ($leads as $lead)
                                <div class="d-flex align-items-center mb-2">
                                    <input type="checkbox" wire:model="recipients" value="{{ $lead['email'] }}"
                                        class="form-check-input me-2" wire:change="updateInput">
                                    <span
                                        style="color: #fff; font-weight: bold; font-size: 16px;background:#000;width: 30px; height: 30px; border-radius: 50%;justify-content: center; align-items: center;overflow: hidden; display: flex;">
                                        {{ $lead['name'] ? strtoupper(substr($lead['name'], 0, 1)) : 'No' }}
                                    </span>
                                    <span class="ms-2">{{ $lead['name'] ? $lead['name'] : 'n/a' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>

                <!-- Subject -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Subject *</label>
                    @error('subject')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <input type="text" class="form-control" wire:model.live="subject" placeholder="Subject">
                </div>

                <!-- Message -->
                <div class="mb-3" wire:ignore>
                    <label class="form-label fw-bold">Message *</label>
                    @error('message')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <textarea class="form-control overflow-auto" id="customMessage" wire:model.defer="message" placeholder="Message..."></textarea>
                </div>

                <button class="btn btn-dark" wire:click="sendEmail" @disabled(!$recipients)>
                    <span wire:loading wire:target="sendEmail">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                    <span wire:loading.remove wire:target="sendEmail">
                        Send Email
                    </span>
                </button>

            </div>


            <!-- Right Section: Preview -->
            <div class="col-md-6 mt-3">
                <div class="card shadow border-0">
                    <!-- Card Header -->
                    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 text-white">Email Preview</h5>
                        <span class="badge bg-secondary">Preview Mode</span>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body bg-light">
                        <!-- From Section -->
                        <div class="mb-4 mt-4">
                            <h6 class="text-muted mb-1">From:</h6>
                            <p class="fw-bold text-dark mb-0">You via Gotaps Teams</p>
                        </div>

                        <hr class="my-3">

                        <!-- Subject Section -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-1">Subject:</h6>
                            <p class="fw-semibold text-dark mb-0">{{ $subject ?? 'Subject' }}</p>
                        </div>

                        <hr class="my-3">

                        <!-- Message Section -->
                        <h6 class="text-muted mb-1">Message:</h6>
                        <div class="email-content">
                            <div class="border p-3 rounded-3 shadow-sm bg-light"
                                style="max-height: 400px; overflow-y: auto;">
                                {!! $message !!}
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-white text-center border-top">
                        <small class="text-muted">This is a preview of the email. Actual formatting may vary.</small>
                    </div>
                </div>
            </div>


        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3>Email Compaign Send</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        <table class="table admin-table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th> Sr </th>
                                    <th> Date </th>
                                    <th> Subject </th>
                                    <th> Total </th>
                                    <th> Actions </th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($emails as $ind => $email)
                                    <tr>
                                        <td>
                                            {{ $ind + 1 }}
                                        </td>
                                        <td>
                                            {{ humanDateFormat($email->created_at) }}
                                        </td>
                                        <td>
                                            {{ $email->subject }}
                                        </td>
                                        <td>
                                            {{-- {!! preg_replace('/<img[^>]+\>/i', '', $email->message) ?? 'Message...' !!} --}}
                                            {{ $email->total }}
                                        </td>
                                        <td>
                                            <button wire:click="showModel({{ $email->id }})"
                                                class="btn btn-dark btn-sm" data-bs-toggle="tooltip"
                                                data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                                title="view">
                                                view
                                            </button>
                                            <button wire:click="deleteMessage({{ $email->id }})"
                                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                                title="delete">
                                                Delete
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
                            @if ($emails->count() > 0)
                                {{ $emails->links() }}
                            @else
                                <p class="text-center"> No Record Found </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Note Modal -->
    <div wire:ignore.self class="modal fade" id="emailViewModal" tabindex="-1"
        aria-labelledby="emailViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailViewModalLabel">View Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($emailDetail)
                        <!-- Email Subject Section -->
                        <div class="mb-3">
                            <h5 class="fw-bold text-dark">{{ $emailDetail->subject }}</h5>
                        </div>

                        <!-- Total Sent Section -->
                        <div class="mb-4">
                            <p class="text-muted"><strong>Total Sent:</strong> {{ $emailDetail->total }}</p>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Email Message Section -->
                        <div class="email-content">
                            <div class="border p-3 rounded-3 shadow-sm bg-light"
                                style="max-height: 400px; overflow-y: auto;">
                                {!! $emailDetail->message !!}
                            </div>
                        </div>
                    @else
                        <!-- If no email selected -->
                        <div class="text-center">
                            <p class="mb-0 text-muted">No email selected.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>


    <script src="{{ asset('assets/js/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function initializeEditor() {
                const editorElement = document.querySelector('#customMessage');
                if (editorElement) {
                    ClassicEditor
                        .create(editorElement, {
                            height: 50,
                            ckfinder: {
                                uploadUrl: '{{ route('ckeditor.upload') . '?_token=' . csrf_token() }}'
                            }
                        })
                        .then(editor => {
                            editor.ui.view.editable.element.style.overflowY = 'auto';
                            // Bind CKEditor changes to Livewire property
                            editor.model.document.on('change:data', () => {
                                @this.set('message', editor.getData());
                            });

                            // Refresh editor content when Livewire emits an event
                            Livewire.on('refreshEditor', content => {
                                editor.setData(content);
                            });

                            // Store the editor instance globally if needed
                            window.livewireEditorInstance = editor;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            }

            // Initialize editor on page load
            initializeEditor();

            // Re-initialize CKEditor after Livewire updates
            Livewire.hook('message.processed', (message, component) => {
                if (!document.querySelector('#customMessage')) {
                    return; // Do nothing if the editor element is removed
                }

                if (!window.livewireEditorInstance || !window.livewireEditorInstance.ui.view.editable
                    .element.isConnected) {
                    initializeEditor(); // Reinitialize if the instance is destroyed
                }
            });
        });

        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
        window.addEventListener('show-view-modal', event => {
            $('#emailViewModal').modal('show')
        });
    </script>

</div>
