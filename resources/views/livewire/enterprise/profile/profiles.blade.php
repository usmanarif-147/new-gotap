<div>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="card-header">
                {{-- {{ $heading }} --}}
                <span>
                    <h5 style="margin-top:10px"> Total: {{ $total }} </h4>
                </span>
            </h2>
            <h5 class="card-header">
                <a class="btn btn-dark" href="{{ route('enterprise.profile.create') }}">
                    Create User
                </a>
            </h5>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                {{-- <div class="col-md-3 offset-3">
                    <label for=""> Select status </label>
                    <select wire:model="filterByStatus" class="form-control form-select me-2">
                        <option value="" selected> Select Status </option>
                        @foreach ($statuses as $val => $status)
                            <option value="{{ $val }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-3 ms-auto">
                    <label for=""> Sort by </label>
                    <select wire:model="sortBy" class="form-control form-select me-2">
                        <option value="" selected> Select Sort </option>
                        <option value="created_asc"> Created Date (Low to High)</option>
                        <option value="created_desc"> Created Date (High to Low)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for=""> Search </label>
                    <input class="form-control me-2" type="search" wire:model.debounce.500ms="search"
                        placeholder="Search" aria-label="Search">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive text-nowrap">
                    <table class="table admin-table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th> Profile </th>
                                <th> User </th>
                                <th> Uuid Link </th>
                                <th> Card Status </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($profiles as $profile)
                                <tr>
                                    <td style="width: 30%;">
                                        <div class="d-flex align-items-center">
                                            <!-- Profile Image -->
                                            <div
                                                style="width: 30px; height: 30px; border-radius: 50%; background-size: cover; background-position: center; overflow: hidden;">
                                                <img src="{{ asset($profile->photo && file_exists(public_path('storage/' . $profile->photo)) ? Storage::url($profile->photo) : 'user.png') }}"
                                                    alt="Viewer Photo" class="img-fluid"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                            <!-- Name and Email -->
                                            <div style="margin-left: 5%;">
                                                <span class="font-weight-bold text-dark"
                                                    style="font-size: 15px;">{{ $profile->username ? $profile->username : 'N/A' }}</span>
                                                <p class="mb-0" style="font-size: 12px;">
                                                    {{ $profile->email ? $profile->email : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    @if ($profile->user_id != null)
                                        <td style="width: 30%;">
                                            <div class="d-flex align-items-center">
                                                <!-- Profile Image -->
                                                <div
                                                    style="width: 30px; height: 30px; border-radius: 50%; background-size: cover; background-position: center; overflow: hidden;">
                                                    <img src="{{ asset($profile->user_photo && file_exists(public_path('storage/' . $profile->user_photo)) ? Storage::url($profile->user_photo) : 'user.png') }}"
                                                        alt="Viewer Photo" class="img-fluid"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <!-- Name and Email -->
                                                <div style="margin-left: 5%;">
                                                    <span class="font-weight-bold text-dark"
                                                        style="font-size: 15px;">{{ $profile->user_name ? $profile->user_name : 'N/A' }}</span>
                                                    <p class="mb-0" style="font-size: 12px;">
                                                        {{ $profile->user_email ? $profile->user_email : 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    @else
                                        <td class=" text-center">
                                            <span>
                                                No User Attach
                                            </span>
                                        </td>
                                    @endif
                                    <td>
                                        @if (!empty($profile->card_uuid))
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input id="card-{{ $profile->card_id }}" type="hidden"
                                                        value="{{ $profile->card_uuid }}">
                                                    {{-- {{ $profile->uuid }} --}}
                                                </div>
                                                <div class="col-md-2 position-relative">
                                                    <a href="javascript:void(0)"
                                                        onclick="copy('{{ $profile->card_id }}')">
                                                        <i class="bx bx-clipboard" data-bs-toggle="tooltip"
                                                            data-bs-offset="0,4" data-bs-placement="top"
                                                            title="Copy Link" aria-hidden="true"></i>
                                                    </a>
                                                    <div id="copy-notification{{ $profile->card_id }}"
                                                        style="display: none; position: absolute; top: 0; left: 50px; color: blue;">
                                                        Copied!
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            N/A
                                        @endif

                                    </td>
                                    <td>
                                        @if (!empty($profile->card_uuid))
                                            <span
                                                class="badge {{ $profile->cardStatus ? 'bg-label-success' : 'bg-label-danger' }} me-1">
                                                {{ $profile->cardStatus ? 'Active' : 'Inactive' }}
                                            </span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if ($profile->card_uuid != null)
                                            <button
                                                class="btn {{ $profile->cardStatus ? 'btn-danger' : 'btn-success' }} btn-sm"
                                                data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                                data-bs-html="true"
                                                title="{{ $profile->cardStatus ? 'card inactivate' : 'card activate' }}"
                                                wire:click="confirmCardStatus({{ $profile->card_id }},{{ $profile->id }})">
                                                <i class='bx bx-card'></i>
                                            </button>
                                        @else
                                            <button class="btn btn-danger btn-sm" data-id={{ $profile->id }}
                                                type="button" data-bs-dismiss="modal" data-bs-toggle="modal"
                                                data-bs-target="#qr_scan" class="btn btn-custom btn-sm">
                                                {{-- Activate by QR --}}
                                                <i class='bx bx-qr-scan'></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('enterprise.profile.manage', [$profile->id]) }}"
                                            class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                            data-bs-placement="top" data-bs-html="true" title="Manage">
                                            {{-- Manage Profile --}}
                                            <i class='bx bx-street-view'></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                            title="delete" wire:click="confirmModal({{ $profile->id }})">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                        @if ($profile->user_id != null)
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                                title="User Disconnect"
                                                wire:click="confirmUserDactivate({{ $profile->id }},{{ $profile->user_id }})">
                                                <i class='bx bx-user-x'></i>
                                            </button>
                                        @endif
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
                        @if ($profiles->count() > 0)
                            {{ $profiles->links() }}
                        @else
                            <p class="text-center"> No Record Found </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="qr_scan" tabindex="-1" aria-labelledby="qr_scan" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qr_scan">Scan QR to activate Tag</h5>
                    <button type="button" class="btn-close cursor-pointer" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row g-2 text-center">
                        <div id="reader" width="600px"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('livewire.admin.confirm-modal')

    <script>
        var QrScanModal = document.getElementById('qr_scan');
        var html5QrcodeScanner;
        let profile_id;

        QrScanModal.addEventListener('shown.bs.modal', function(event) {
            profile_id = event.relatedTarget.dataset.id;
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: {
                        width: 300,
                        height: 300
                    }
                },
                false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });

        QrScanModal.addEventListener('hide.bs.modal', function(event) {
            html5QrcodeScanner.clear();
        });


        function onScanSuccess(decodedText, decodedResult) {

            const urlObject = new URL(decodedText);
            let exist = urlObject.pathname.includes('/card_id/');

            if (exist) {
                const uuid = decodedText.split('/').pop();
                Livewire.emit('activateTag', uuid, profile_id);
            } else {
                alert('not valid url');
            }

            // if (decodedText && (decodedText.includes(
            //         'localhost') || (domainName && decodedText.includes(domainName[0]))) && (decodedText.toLowerCase()
            //         .includes(
            //             '/p'))) {
            //     const uuid = decodedText.split('/').pop();
            //     // Livewire.emit('activateTag', uuid);

            // } else {
            //     // alert('Invalid card');
            // }
            html5QrcodeScanner.clear();
            $('#qr_scan').modal('hide');
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // console.warn(`Code scan error = ${error}`);
        }

        const nfcScanBtn = document.querySelector('#nfcScanBtn');

        if ('NDEFReader' in window) {

        } else {
            nfcScanBtn.disabled = true;
            console.log('NFC is not available on this device');
        }

        var ndef;
        nfcScanBtn.addEventListener("click", async () => {

            console.log("User clicked scan button");

            try {
                ndef = new NDEFReader();
                await ndef.scan();
                console.log("> Scan started");

                ndef.addEventListener("readingerror", () => {
                    alert(
                        "Argh! Cannot read data from the NFC tag. Try another one?"
                    );
                });

                ndef.addEventListener("reading", ({
                    message,
                    serialNumber
                }) => {

                    let url = '';
                    for (const record of message.records) {
                        if (record.recordType == 'url' || record.recordType ==
                            'text') {

                            const decoder = record.recordType == 'text' ?
                                new TextDecoder(record
                                    .encoding) : new TextDecoder();
                            url = decoder.decode(record.data);
                        }
                    }

                    if (url && (url.includes('localhost') || (domainName && url.includes(domainName[
                            0]))) && (url.toLowerCase().includes('/p'))) {
                        const uuid = url.split('/').pop();
                        Livewire.emit('activateTag', uuid);
                    } else {
                        alert('Invalid Card');
                        location.reload();
                        return;
                    }
                });
            } catch (error) {
                console.log("Argh! " + error);
            }
        });

        const scanNfcModal = document.getElementById('ScanNFC');

        scanNfcModal.addEventListener('hide.bs.offcanvas', event => {
            console.log('ndef scan abort')
            if (ndef)
                ndef.abort();
        });
    </script>

    <script>
        function copy(id) {
            let url = window.location.origin + '/card_id' + '/' + $('#card-' + id).val();
            var tempInput = document.createElement("input");
            tempInput.value = url;
            document.body.appendChild(tempInput);

            // Select the text inside the input
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text to the clipboard
            document.execCommand("copy");

            // Remove the temporary input
            document.body.removeChild(tempInput);

            // Show the notification
            let notification = document.getElementById('copy-notification' + id);
            notification.style.display = 'block';

            // Hide the notification after 2 seconds
            setTimeout(function() {
                notification.style.display = 'none';
            }, 2000); // 2000 ms = 2 seconds
        }
    </script>

</div>

@section('script')
    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
        window.addEventListener('confirm-modal', event => {
            $('#confirmModal').modal('show')
        });

        window.addEventListener('close-modal', event => {
            $('#confirmModal').modal('hide')
        });
    </script>
    {{-- <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });

        window.addEventListener('show-create-modal', event => {
            $('#createMerchantModal').modal('show')
        });

        window.addEventListener('show-edit-modal', event => {
            $('#editMerchantModal').modal('show')
        });

        window.addEventListener('edit-password-modal', event => {
            $('#editPasswordModal').modal('show')
        });

        window.addEventListener('edit-balance-modal', event => {
            $('#editBalanceModal').modal('show')
        });

        window.addEventListener('close-modal', event => {
            $('#createMerchantModal').modal('hide');
            $('#editMerchantModal').modal('hide')
            $('#confirmModal').modal('hide');
            $('#editPasswordModal').modal('hide')
            $('#editBalanceModal').modal('hide')
        });

        window.addEventListener('open-confirm-modal', event => {
            $('#confirmModal').modal('show');
        });
    </script> --}}
@endsection
