<div>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="card-header">
                {{ $heading }}
                <span>
                    <h5 style="margin-top:10px"> Total: {{ $total }} </h4>
                </span>
            </h2>
            <h5 class="card-header">
                <a class="btn" style="background: #0EA7C1; color:white" href="{{ route('admin.platform.create') }}">
                    Create
                </a>
            </h5>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <label for=""> Select status </label>
                    <select wire:model="filterByStatus" class="form-control form-select me-2">
                        <option value="" selected> Select Status </option>
                        @foreach ($statuses as $val => $status)
                            <option value="{{ $val }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for=""> Sort by </label>
                    <select wire:model="sortBy" class="form-control form-select me-2">
                        <option value="" selected> Select Sort </option>
                        <option value="created_asc"> Created Date (Low to High)</option>
                        <option value="created_desc"> Created Date (High to Low)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for=""> Select Category </label>
                    <select wire:model="filterByCategory" class="form-control form-select me-2">
                        <option value="" selected> Select Category </option>
                        @foreach ($categories as $val => $category)
                            <option value="{{ $val }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for=""> Search by Title </label>
                    <input class="form-control me-2" type="search" wire:model.debounce.500ms="searchQuery"
                        placeholder="Search" aria-label="Search">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive text-nowrap">
                    <table class="table admin-table">
                        <thead class="table-light">
                            <tr>
                                <th> Icon </th>
                                <th> Title </th>
                                <th> Category </th>
                                <th> Type </th>
                                <th> Base Url </th>
                                <th> Status </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($platforms as $platform)
                                <tr>
                                    <td>
                                        <div class="img-holder">
                                            <img
                                                src="{{ asset($platform->icon ? Storage::url($platform->icon) : 'pbg.png') }}">
                                        </div>
                                    </td>
                                    <td> {{ $platform->title }}</td>
                                    <td>
                                        {{ $platform->category }}
                                    </td>
                                    <th>
                                        @if ($platform->pro == 1)
                                            Pro
                                        @else
                                            Free
                                        @endif
                                    </th>
                                    <td>
                                        {{ $platform->baseURL ? $platform->baseURL : '--' }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $platform->status ? 'bg-label-success' : 'bg-label-danger' }} me-1">
                                            {{ $platform->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.platform.edit', [$platform->id]) }}"
                                            class="btn btn-warning" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                            data-bs-placement="top" data-bs-html="true" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="demo-inline-spacing">
                        @if ($platforms->count() > 0)
                            {{ $platforms->links() }}
                        @else
                            <p class="text-center"> No Record Found </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Modal -->
    @include('layouts.admin.partials.confirm_modal')

    <script>
        window.addEventListener('swal:modal', event => {
            $('#confirmModal').modal('hide');
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });

        window.addEventListener('confirmModal', event => {
            $('#confirmModal').modal('show');
        });
    </script>

</div>
