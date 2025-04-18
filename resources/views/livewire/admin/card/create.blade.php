<div>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <form wire:submit.prevent="store">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Description
                                    </label>
                                    <textarea id="basic-default-message" wire:model.debounce.500ms="description" class="form-control"
                                        placeholder="Enter description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Quantity
                                        <span class="text-danger"> * </span>
                                        @error('quantity')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <input type="number" wire:model="quantity" class="form-control"
                                        id="basic-default-company" placeholder="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Type
                                        <span class="text-danger"> * </span>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </label>
                                    <select wire:model="type" class="form-control form-select me-2">
                                        <option value="" selected> Select Type </option>
                                        @foreach ($types as $val => $type)
                                            <option value="{{ $val }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn" type="submit" style="background: #0EA7C1; color:white">Save</button>
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
