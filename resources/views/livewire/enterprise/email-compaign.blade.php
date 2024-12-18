<div>
    <div class="container mt-4">
        <div class="row">
            <!-- Left Section: Form -->
            <div class="col-md-6">
                <div class="mb-3 position-relative">
                    <label class="form-label fw-bold">Profiles *</label>

                    <!-- Input Field Showing Selected Names -->
                    <div class="form-control d-flex align-items-center flex-wrap" style="cursor: pointer;"
                        wire:click="toggleDropdown">
                        @forelse ($selectedNames as $id => $name)
                            <span class="badge bg-dark text-white me-1 mb-1" style="cursor: pointer;"
                                wire:click.stop="removeRecipient({{ $id }})">
                                {{ $name }} &times;
                            </span>
                        @empty
                            <span class="text-muted">Select profiles...</span>
                        @endforelse
                    </div>

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

                            <!-- List of Profiles -->
                            @foreach ($profiles as $profile)
                                <div class="d-flex align-items-center mb-2">
                                    <input type="checkbox" wire:model="recipients" value="{{ $profile['id'] }}"
                                        class="form-check-input me-2" wire:change="updateInput">
                                    <img src="{{ asset($profile->photo && file_exists(public_path('storage/' . $profile->photo)) ? Storage::url($profile->photo) : 'user.png') }}"
                                        alt="{{ $profile['name'] }}" class="rounded-circle" width="30"
                                        height="30">
                                    <span class="ms-2">{{ $profile['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>


                <!-- Subject -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Subject *</label>
                    <input type="text" class="form-control" wire:model="subject" placeholder="Subject">
                </div>

                <!-- Message -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Message *</label>
                    <textarea class="form-control" wire:model="message" rows="5" placeholder="Message..."></textarea>
                </div>

                <button class="btn btn-dark" wire:click="sendEmail" @disabled(!$recipients)>Send email</button>
            </div>

            <!-- Right Section: Preview -->
            <div class="col-md-6 mt-3">
                <div class="card shadow-sm border-0">
                    <!-- Card Header -->
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Email Preview</h5>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body bg-light">
                        <!-- From -->
                        <div class="mb-3">
                            <h6 class="text-muted">From</h6>
                            <p class="fw-bold mb-0">You via Gotaps Teams</p>
                        </div>

                        <hr class="my-2">

                        <!-- Subject -->
                        <div class="mb-3">
                            <h6 class="text-muted">Subject</h6>
                            <p class="mb-0">{{ $subject ?? 'Subject' }}</p>
                        </div>

                        <hr class="my-2">

                        <!-- Message -->
                        <div>
                            <h6 class="text-muted">Message</h6>
                            <p class="mb-0">{{ $message ?? 'Message...' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
