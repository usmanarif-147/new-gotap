<div>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="card-header">
                <span>
                    <h5 style="margin-top:10px"> Total: {{ $total }} </h4>
                </span>
            </h2>
            <h5 class="card-header">
                <a class="btn" style="background: #0EA7C1; color:white" href="{{ route('admin.card.create') }}"> Create
                </a>
                <button class="btn btn-info text-center" {{ $total == 0 ? 'disabled' : '' }} wire:click="exportCsv">
                    <i class="mdi mdi-download me-2 "></i>
                    Download CSV
                </button>
            </h5>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3 offset-6">
                    <label for=""> Select status </label>
                    <select wire:model="filterByStatus" class="form-control form-select me-2">
                        <option value="" selected> Select Status </option>
                        @foreach ($statuses as $val => $status)
                            <option value="{{ $val }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for=""> Search by Uuid or Description </label>
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
                                <th> Sr </th>
                                <th>Uuid</th>
                                <th>Assigned To</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($cards as $index => $card)
                                <tr>
                                    <td>
                                        {{ $index + 1 + ($cards->currentPage() - 1) * $cards->perPage() }}
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <input id="card-{{ $card->id }}" type="hidden"
                                                    value="{{ $card->uuid }}">
                                                {{ $card->uuid }}
                                            </div>
                                            <div class="col-md-2">
                                                <a href="javascript:void(0)" onclick="copy('{{ $card->id }}')">
                                                    <i class="bx bx-clipboard" data-toggle="tooltip"
                                                        data-placement="top" title="Copy Link" aria-hidden="true"></i>
                                                </a>

                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $card->username ? $card->username : '--' }}
                                    </td>
                                    <td>
                                        {{ $card->description ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $card->type ? Str::ucfirst($card->type) : 'N/A' }}
                                    <td>
                                        <span
                                            class="badge {{ $card->status ? 'bg-label-success' : 'bg-label-danger' }} me-1">
                                            {{ $card->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" wire:click ="confirmModal({{ $card->id }})">
                                            <i class="bx bx-trash"></i>
                                        </button>
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
                        @if ($cards->count() > 0)
                            {{ $cards->links() }}
                        @else
                            <p class="text-center"> No Record Found </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
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

        window.addEventListener('close-modal', event => {
            $('#confirmModal').modal('hide');
        });

        window.addEventListener('download-csv', event => {
            window.location.href = event.detail.url;
        });

        function copy(id) {
            let url = window.location.origin + '/card_id' + '/' + $('#card-' + id).val();

            const textArea = document.createElement("textarea");
            textArea.value = url;
            document.body.appendChild(textArea);

            // Select and copy the text
            textArea.select();
            document.execCommand("copy");

            // Remove the text area
            document.body.removeChild(textArea);

            alert("copied");
        }
    </script>

</div>
