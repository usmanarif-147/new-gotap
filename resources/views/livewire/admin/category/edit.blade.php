<div>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <form wire:submit.prevent="update">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Title (English) <span class="text-danger"> * </span>
                                        @error('name')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="name" class="form-control" placeholder="John doe">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Title (Swedish) <span class="text-danger"> * </span>
                                        @error('name_sv')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="text" wire:model="name_sv" class="form-control"
                                        placeholder="John doe">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Status <span class="text-danger"> * </span>
                                        @error('status')
                                            <span class="text-danger error-message">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <select class="form-select" wire:model="status">
                                        <option value="" selected="">Select</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn" style="background: #0EA7C1; color:white">Update</button>
                    </div>
                </form>
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
