<div>
    @if ($tab_change == 2)
        <div class="row">
            @foreach ($platforms as $category)
                <h5 class="fs-4 fw-bolder">
                    {{ $category['name'] }}
                </h5>
                @foreach ($category['platforms'] as $platforms)
                    <div class="col-md-4 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2 align-content-center" style="height: 75px;width:75px">
                                        <img src="{{ asset($platforms['icon'] ? Storage::url($platforms['icon']) : 'pbg.png') }}"
                                            class="img-fluid rounded" height="100%" width="100%">
                                    </div>
                                    <div class="col-6 align-content-center">
                                        <h5 class="card-text">{{ $platforms['title'] }}</h5>
                                    </div>
                                    <div class="col-2 align-content-center">
                                        @if ($platforms['saved'] == 0)
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#platformModal"
                                                wire:click="addPlatform({{ $platforms['id'] }},'{{ $platforms['path'] }}','{{ $platforms['label'] }}','{{ $platforms['direct'] }}','{{ $platforms['title'] }}')">Add</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    @elseif ($tab_change == 3)
        <div wire:sortable="updateOrder" style="margin: 0 25%;">
            @forelse ($sort_platform as $platforms)
                <div wire:sortable.item="{{ $platforms['id'] }}" wire:key="platform-{{ $platforms['id'] }}">
                    <div class="mb-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div wire:sortable.handle class="col-md-2 py-3" style="height: 50px;width:50px">
                                        <img src="{{ asset('dots.png') }}" height="100%" width="100%"
                                            alt="">
                                    </div>
                                    <div class="col-md-2 py-2" style="height: 75px;width:75px">
                                        <img src="{{ asset($platforms['icon'] ? Storage::url($platforms['icon']) : 'pbg.png') }}"
                                            class="img-fluid rounded" height="100%" width="100%">
                                    </div>
                                    <div class="col-md-4 p-3">
                                        <h5 class="card-text">{{ $platforms['title'] }}
                                        </h5>
                                    </div>
                                    <div class="col-md-4 p-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-primary btn-sm mb-2"
                                                    data-bs-toggle="modal" data-bs-target="#platformModal"
                                                    wire:click="editPlatform({{ $platforms['id'] }},'{{ $platforms['path'] }}','{{ $platforms['label'] }}','{{ $platforms['direct'] }}','{{ $platforms['title'] }}')">Update</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-dark btn-sm"
                                                    wire:click="deletePlatform({{ $platforms['id'] }})">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>No selected platforms available.</p>
            @endforelse
        </div>
    @endif
    <!-- Bootstrap Modal -->
    <div wire:ignore.self class="modal fade" id="platformModal" tabindex="-1" aria-labelledby="platformModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="platformModalLabel">
                        {{ $isEditMode ? 'Edit Link' : 'Add Link' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="path" class="form-label">Url</label>
                            <input type="text" class="form-control" id="path" wire:model="path">
                            @error('path')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" wire:model="title" disabled>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="savePlatform">{{ $isEditMode ? 'Update' : 'Add' }}</button>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.admin.confirm-modal')
    <script>
        window.addEventListener('close-modal', event => {
            $('#platformModal').modal('hide');
            $('#confirmModal').modal('hide');
        });
        window.addEventListener('confirm-modal', event => {
            $('#confirmModal').modal('show')
        });
    </script>
    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>
</div>
