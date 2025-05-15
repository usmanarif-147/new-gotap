<div>
    <div class="row">
        <div class="col-md-5">
            @if ($errors->any())
                <div style="color: red; margin-bottom: 1rem;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label>Subject</label>
                <input type="text" wire:model="subject" class="form-control" />
            </div>

            <div class="mb-3">
                <label>Body Text</label>
                <textarea wire:model="bodyText" class="form-control" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label>Optional Button Text</label>
                <input type="text" wire:model="buttonText" class="form-control"
                    placeholder="Leave blank to hide button" />
            </div>

            <div class="mb-3">
                <label>Optional Button URL</label>
                <input type="text" wire:model="buttonUrl" class="form-control" placeholder="https://example.com" />
            </div>

            <div class="d-flex mb-3 gap-2">
                <div>
                    <label>Background Color</label>
                    <input type="color" wire:model="bgColor" class="form-control form-control-color" />
                </div>
                <div>
                    <label>Text Color</label>
                    <input type="color" wire:model="textColor" class="form-control form-control-color" />
                </div>
            </div>

            <div class="mb-3">
                <label>Text Alignment</label>
                <select wire:model="textAlign" class="form-control">
                    <option value="left">Left</option>
                    <option value="center">Center</option>
                    <option value="right">Right</option>
                </select>
            </div>

            <div class="mb-3">
                <label><input type="checkbox" wire:model="selectAll"> Send to All Users</label>
            </div>

            @if (!$selectAll)
                <div class="mb-2">
                    <input type="text" wire:model="search" class="form-control"
                        placeholder="Search by name or email..." />
                </div>

                <div style="max-height: 200px; overflow-y: auto;" class="border p-2 rounded">
                    @foreach ($this->filteredUsers as $user)
                        <div>
                            <label>
                                <input type="checkbox" wire:model="selectedUsers" value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->email }})
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif

            <button wire:click="sendEmail" class="btn btn-primary mt-3">Send Email</button>

            @if (session()->has('message'))
                <div class="alert alert-success mt-2">{{ session('message') }}</div>
            @endif
        </div>

        <div class="col-md-7 mt-3">
            <div class="p-4 border rounded"
                style="background: {{ $bgColor }}; color: {{ $textColor }}; text-align: {{ $textAlign }};">
                <h4>{{ $subject }}</h4>
                <p style="white-space: pre-line;">{{ $bodyText }}</p>
                @if ($buttonText && $buttonUrl)
                    <div style="text-align: {{ $textAlign }};">
                        <a href="{{ $buttonUrl }}" class="btn btn-primary mt-2">{{ $buttonText }}</a>
                    </div>
                @endif
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
