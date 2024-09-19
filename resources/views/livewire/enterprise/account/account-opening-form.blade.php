<div class="container">
    @if ($message)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card" style="margin:0 15%">
        <div class="card-header">
            <h2 class="text-center">Register</h2>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveApplication">
                <div class="mb-3">
                    <label class="form-label">
                        Name
                        <span class="text-danger">*</span>
                        @error('name')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </label>
                    <input type="text" class="form-control" wire:model="name">
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        Email
                        <span class="text-danger">*</span>
                        @error('email')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </label>
                    <input type="email" class="form-control" wire:model="email">
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        Phone Number
                        <span class="text-danger">*</span>
                        @error('phone')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </label>
                    <input type="tel" class="form-control" wire:model="phone">
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        Enterprise Type
                        <span class="text-danger">*</span>
                        @error('enterprise_type')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </label>
                    <input type="text" class="form-control" wire:model="enterprise_type">
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>

</div>
