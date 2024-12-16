<div class="container mt-5">
    <form wire:submit.prevent="registerEnterpriser">
        <div class="row">
            <!-- Name -->
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">{{ __('Name') }}
                    @error('name')
                        <div class="text-danger error-message">{{ $message }}</div>
                    @enderror
                </label>
                <input type="text" class="form-control" id="name" wire:model="name" required autofocus>
            </div>

            <!-- Email Address -->
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">{{ __('Email') }}
                    @error('email')
                        <div class="text-danger error-message">{{ $message }}</div>
                    @enderror
                </label>
                <input type="email" class="form-control" id="email" wire:model="email" autocomplete="off"
                    required>
            </div>
        </div>

        <div class="row">
            <!-- Phone Number -->
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">{{ __('Phone Number') }}
                    @error('phone')
                        <div class="text-danger error-message">{{ $message }}</div>
                    @enderror
                </label>
                <input type="text" class="form-control" id="phone" wire:model="phone" autocomplete="off"
                    required>
            </div>

            <!-- Enterprise Type -->
            <div class="col-md-6 mb-3">
                <label for="enterprise_type" class="form-label">{{ __('Enterprise Type') }}
                    @error('enterprise_type')
                        <div class="text-danger error-message">{{ $message }}</div>
                    @enderror
                </label>
                <input type="text" class="form-control" id="enterprise_type" wire:model="enterprise_type" required>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </div>
    </form>
    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>
</div>
