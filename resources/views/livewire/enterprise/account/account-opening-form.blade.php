<div class="container">
    @if ($message)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card" style="margin:0 15%">
        <div class="card-header">
            <h2 class="text-center">Register Enterprise</h2>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveApplication">
                <div class="row">
                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
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
                    </div>
                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
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
                    </div>
                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">
                                Company Name
                                <span class="text-danger">*</span>
                                @error('companyName')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                            </label>
                            <input type="text" class="form-control" wire:model="companyName">
                        </div>
                    </div>
                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
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
                    </div>
                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">
                                Start Date
                                <span class="text-danger">*</span>
                                @error('startDate')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                            </label>
                            <input type="date" class="form-control" wire:model="startDate">
                        </div>
                    </div>
                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">
                                End Date
                                <span class="text-danger">*</span>
                                @error('endDate')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                            </label>
                            <input type="date" class="form-control" wire:model="endDate">
                        </div>
                    </div>
                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">
                                Enterprise Type
                                <span class="text-danger">*</span>
                                @error('enterprise_type')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                            </label>
                            <select class="form-control" wire:model="enterprise_type">
                                <option value="">Select Enterprise Type</option>
                                <option value="10">1-10 People</option>
                                <option value="30">1-30 People</option>
                                <option value="31">30+ People</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">
                                Upload Contract
                                <span class="text-danger">*</span>
                                @error('file')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                            </label>
                            <input type="file" class="form-control" wire:model="file">
                        </div>
                    </div>
                </div>
                <div class=" text-center">
                    <button type="submit" class="btn btn-primary w-50">Register Enterprise</button>
                </div>
            </form>
        </div>
    </div>

</div>
