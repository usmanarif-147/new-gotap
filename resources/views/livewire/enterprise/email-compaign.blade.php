<div>
    <style>
        .position-relative:hover .nested-dropdown {
            display: block;
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
                <div class="mb-3">
                    <label class="form-label fw-bold">Message *</label>
                    @error('message')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <textarea class="form-control" wire:model.live="message" rows="5" placeholder="Message..."></textarea>
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
                        <div>
                            <h6 class="text-muted mb-1">Message:</h6>
                            <p class="text-dark">{{ $message ?? 'Message...' }}</p>
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
                                    <th> Message </th>
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
                                            {{ $email->message }}
                                        </td>
                                        <td>
                                            <button class="btn btn-dark btn-sm" data-bs-toggle="tooltip"
                                                data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                                title="View">
                                                view
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
    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>

</div>
