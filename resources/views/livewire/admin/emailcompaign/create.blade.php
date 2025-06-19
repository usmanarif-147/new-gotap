<div>
    <div class="row">
        <div class="col-md-6">
            <div class="col-10 mb-3 d-flex justify-content-between">
                <button class="btn btn-outline-dark" wire:click="goToStep(1)" @disabled($step === 1)>
                    Step 1: Select Template
                </button>
                <button class="btn btn-outline-dark" wire:click="goToStep(2)" @disabled($step === 2)>
                    Step 2: Compose Email
                </button>
            </div>

            @if ($step === 1)
                <div class="col-12">
                    <label class="mb-2">Choose a Template</label>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach ($templates as $template)
                            <div class="p-2 border rounded template-card"
                                style="
                                    border: 2px solid {{ $selectedTemplateId === $template->id ? '#007bff' : '#ccc' }};
                                    background-color: {{ $selectedTemplateId === $template->id ? '#e9f5ff' : '#fff' }};
                                    width: 45%; cursor: pointer;"
                                wire:click="selectTemplate({{ $template->id }})">
                                <strong>{{ $template->name }}</strong>
                                <div class="small text-muted">Preview:</div>
                                <div style="max-height: 80px; overflow: hidden; font-size: 12px;">
                                    {!! $template->html !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($step === 2)
                <div class="col-12">
                    @if ($errors->any())
                        <div class="alert alert-danger mt-2">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3" wire:ignore>
                        <label for="summernote-editor">Email Body</label>
                        <textarea id="summernote-editor" class="form-control">{{ $bodyText }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3 mt-2">
                        <label>Subject<span class="text-danger"> * </span> </label>
                        <input type="text" wire:model.live="subject" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 d-flex align-items-center">
                            <label class="w-100">
                                <input type="checkbox" wire:model.live="selectAll">
                                Send to All Users
                            </label>
                        </div>

                        @unless ($selectAll)
                            <div class="col-md-6 mb-3">
                                <input type="text" wire:model.live="search" class="form-control"
                                    placeholder="Search by name or email...">
                            </div>

                            <div class="col-12 mb-3">
                                <div style="max-height: 200px; overflow-y: auto;" class="border p-2 rounded">
                                    @forelse ($this->filteredUsers as $user)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" wire:model.live="selectedUsers"
                                                value="{{ $user->id }}" id="user-{{ $user->id }}">
                                            <label class="form-check-label" for="user-{{ $user->id }}">
                                                {{ $user->name }} ({{ $user->email }})
                                            </label>
                                        </div>
                                    @empty
                                        <div>No users found.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endunless
                    </div>

                    <div class="col-12">
                        <button wire:click="sendEmail" class="btn btn-primary mt-3">Send Email</button>
                    </div>

                    @if (session()->has('message'))
                        <div class="col-12">
                            <div class="alert alert-success mt-2">{{ session('message') }}</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-md-6" style="margin-top:40px;">
            <h3 class="mb-3">Email Preview</h3>
            <div class="p-4 border rounded editor-content" style="max-height: 500px; overflow-y: auto;">
                <div style="white-space: pre-line;">{!! $bodyText !!}</div>
                @if ($buttonText && $buttonUrl)
                    <div style="text-align: {{ $textAlign }};">
                        <a href="{{ $buttonUrl }}" class="btn btn-primary mt-2">{{ $buttonText }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3>Email Campaign Send</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive text-nowrap">
                    <table class="table admin-table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th> Nr </th>
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
                                        {{ $email->subject ?? 'No Subject' }}
                                    </td>
                                    <td>
                                        {{-- {!! preg_replace('/<img[^>]+\>/i', '', $email->message) ?? 'Message...' !!} --}}
                                        {{ $email->total }}
                                    </td>
                                    <td>
                                        <button wire:click="showModel({{ $email->id }})" class="btn btn-dark btn-sm"
                                            data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                            data-bs-html="true" title="view">
                                            view
                                        </button>
                                        <button wire:click="deleteMessage({{ $email->id }})"
                                            class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                            data-bs-placement="top" data-bs-html="true" title="delete">
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
    <div wire:ignore.self class="modal fade" id="emailViewModal" tabindex="-1" aria-labelledby="emailViewModalLabel"
        aria-hidden="true">
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
    <script>
        function initSummernote(content) {
            const editor = $('#summernote-editor');
            if (editor.length) {
                editor.summernote('destroy');
                editor.summernote({
                    height: 450,
                    callbacks: {
                        onChange: function(contents) {
                            @this.set('bodyText', contents);
                        }
                    }
                });
                editor.summernote('code', content);
            }
        }

        document.addEventListener('livewire:load', function() {
            window.addEventListener('init-summernote', event => {
                initSummernote(event.detail.content);
            });

            if (document.querySelector('#summernote-editor')) {
                initSummernote(@this.bodyText);
            }

            window.addEventListener('swal:modal', event => {
                swal({
                    title: event.detail.message,
                    icon: event.detail.type,
                });
            });
            window.addEventListener('show-view-modal', event => {
                $('#emailViewModal').modal('show')
            });
        });
    </script>
</div>
