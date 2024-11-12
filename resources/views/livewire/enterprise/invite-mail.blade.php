<div class="container mt-4">
    <div class="row">
        <!-- Left Side: Form -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h5 class="card-title mb-0">Customize Invite Email</h5>
                </div>
                <div class="card-body">
                    {{-- <form wire:submit.prevent="mailInvitation"> --}}
                    <form>
                        <!-- To -->
                        <div class="mb-3">
                            <label for="to" class="form-label">To:</label>
                            <input type="email" id="to" class="form-control" placeholder="Recipient's email"
                                readonly>
                        </div>

                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject *:</label>
                            <input type="text" id="subject" wire:model="subject" class="form-control"
                                placeholder="Email subject">
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Message *:</label>
                            <textarea id="message" wire:model="message" class="form-control" rows="5"
                                placeholder="Type your message here..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="action" class="form-label">Call To Action *:</label>
                            <input type="text" id="action" wire:model="action" class="form-control">
                        </div>

                        <!-- Action Button -->
                        <button type="submit" class="btn btn-dark"
                            @if (!$hasChanges) disabled @endif>Update</button>
                    </form>

                    <!-- Success message -->
                    @if (session()->has('message'))
                        <div class="alert alert-success mt-3" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side: Live Preview -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h5 class="card-title mb-0">Preview</h5>
                </div>
                <div class="card-body">
                    <p><strong>Subject:</strong> {{ $subject ?: 'Email Subject' }}</p>

                    <div class="border-top pt-3">
                        <p>{{ $message ?: 'Your message preview will appear here...' }}</p>
                    </div>

                    <!-- Join Now Button -->
                    <div class="mt-3 text-center">
                        <div class="bg-primary text-white rounded-3 d-inline-block px-3"
                            style="width: auto; padding-left: 10%; padding-right: 10%;">
                            <span>{{ $action }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
