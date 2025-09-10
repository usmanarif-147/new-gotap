<div class="container-fluid py-4">
    <style>
        .phone-frame {
            width: 220px;
            /* smaller width */
            border-radius: 1.5rem;
            /* rounded corners */
            overflow: hidden;
            /* clip inner content */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            /* optional soft shadow */
        }

        .notification-preview {
            top: 47%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 180px;
        }

        .phone-frame img {
            width: 100%;
            height: auto;
            border-radius: 1.5rem;
            display: block;
        }
    </style>
    <div class="row g-4">

        {{-- =======================================
             Left panel – composer form
        ======================================= --}}
        <div class="col-md-5">

            {{-- Push / Email toggle --}}
            <div class="btn-group w-100 mb-4" role="group">
                <button class="btn btn-dark" wire:click="send" {{ $this->canSend ? '' : 'disabled' }}>
                    <i class="bi bi-phone-vibrate me-1"></i> Send Push notification
                </button>
            </div>

            {{-- Recipients --}}
            <div class="mb-3 position-relative">
                <label class="form-label fw-semibold">
                    Recipients <span class="text-danger">*</span>
                </label>

                <input type="text" id="recipientInput" class="form-control" placeholder="Search users…"
                    wire:model.debounce.300ms="search"
                    wire:keydown.enter.prevent="addRecipient({{ $suggestions[0]['id'] ?? 0 }})">

                @if ($suggestions)
                    <ul class="list-group position-absolute start-0 top-100 w-100
                                   z-1055 bg-white shadow-sm autocomplete-list"
                        style="max-height:220px;overflow:auto;">
                        @foreach ($suggestions as $u)
                            <li class="list-group-item list-group-item-action"
                                wire:click="$emit('add-recipient', {{ $u['id'] }})">
                                <div class="fw-medium">{{ $u['name'] }}</div>
                                <small class="text-muted">{{ $u['email'] }}</small>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if ($recipients)
                    <div class="mt-2">
                        @if (count($recipients) > 100)
                            <span class="badge bg-success mb-1">
                                All users ({{ number_format(count($recipients)) }})
                                <i class="bi bi-x ms-1" style="cursor:pointer" wire:click="clearAll"></i>
                            </span>
                        @else
                            @foreach (\App\Models\User::findMany($recipients) as $u)
                                <span class="badge bg-primary me-1 mb-1">
                                    {{ $u->name }}
                                    <i class="bi bi-x ms-1" style="cursor:pointer"
                                        wire:click="removeRecipient({{ $u->id }})"></i>
                                </span>
                            @endforeach
                        @endif
                    </div>
                @endif

                {{-- Select All/ Clear All --}}
                <div class="dropdown position-absolute top-0 end-0">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle mt-1 me-1"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-people-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" wire:click.prevent="selectAll">
                                <i class="bi bi-check2-all me-1"></i> Select All
                            </a></li>
                        <li><a class="dropdown-item" href="#" wire:click.prevent="clearAll">
                                <i class="bi bi-trash me-1"></i> Clear All
                            </a></li>
                    </ul>
                </div>
            </div>

            {{-- Title --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Title</label>
                <input type="text" class="form-control" placeholder="Title" wire:model="title">
            </div>

            {{-- Message --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                <textarea rows="4" class="form-control" placeholder="Message..." wire:model="message"></textarea>
            </div>

            {{-- Schedule toggle --}}
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" id="scheduleToggle" wire:model="schedule">
                <label class="form-check-label" for="scheduleToggle">Schedule</label>
            </div>
        </div>

        {{-- =======================================
             Right panel – phone preview
        ======================================= --}}
        <div class="col-md-7 d-flex justify-content-center position-relative">
            {{-- phone mock‑up --}}
            <div class="phone-frame position-relative">
                {{-- Substitute with a better PNG if you wish --}}
                <img src="{{ asset('iphone14.png') }}" alt="phone-frame" class="img-fluid rounded-4">

                {{-- notification card --}}
                <div class="notification-preview position-absolute">
                    <div class="card bg-dark text-white border-0 rounded-3 shadow-sm">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex">
                                <i class="bi bi-bell-fill me-2"></i>
                                <div>
                                    <div class="fw-bold small">
                                        {{ $title ?: 'Title' }}
                                    </div>
                                    <div class="small">
                                        {{ $message ?: 'Message' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <script>
        document.addEventListener('livewire:load', function() {
            window.addEventListener('swal:modal', event => {
                swal({
                    title: event.detail.message,
                    icon: event.detail.type,
                });
            });
        });
    </script>
</div>
