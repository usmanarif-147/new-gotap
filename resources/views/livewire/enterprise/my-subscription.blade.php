<div>
    <style>
        .custom-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-switch {
            background-color: #000;
            color: #fff;
            border-radius: 20px;
        }

        .text-gray {
            color: #6c757d;
        }

        .billing-section {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
        }

        .icon-download {
            font-size: 18px;
            color: #6c757d;
        }
    </style>
    @if ($subscription)
        <div class="container mt-4">
            <!-- Current Plan Section -->
            <div class="card custom-card mb-4">
                <div class="card-header">
                    <h6 class="text-uppercase fw-bold">Current GOtaps Plan</h6>
                </div>
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div class="card position-relative" style="width: 60%; height: 150px;background-color:#EDF6FD;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                            <h4 class="fw-bold">
                                @if ($subscription->enterprise_type == '1')
                                    Basic Plan
                                @elseif ($subscription->enterprise_type == '2')
                                    Gold Plan
                                @elseif ($subscription->enterprise_type == '3')
                                    Premium Plan
                                @endif
                            </h4>
                            <small class="text-gray">{{ $user->company_name ? $user->company_name : 'Bussiness Name' }}
                            </small>
                            @if (\Carbon\Carbon::parse($subscription->end_date)->isPast())
                                <small class="badge bg-label-danger">
                                    Your Plan is Expired . Renew or Upgrade Plan.
                                </small>
                            @endif
                        </div>
                        <div class="position-absolute top-0 end-0 p-2">
                            <span
                                class="btn btn-switch px-4">{{ $subscription->description ? $subscription->description : 'Plan' }}</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <h5 class="fw-bold">Contact Information</h5>
                        <p class="text-gray">Total billed monthly</p>
                        <small class="text-gray">
                            For the first 1 - 5<br>
                            Price per member per month: $9.00
                        </small>
                    </div>
                </div>

                <div class="ml-3 mb-2">
                    <h6 class="text-uppercase fw-bold px-3">
                        PROFILES Quantity
                    </h6>
                    <span class=" fw-bold p-3 text-center">
                        @if ($subscription->enterprise_type == '1')
                            1-6 Profiles
                        @elseif ($subscription->enterprise_type == '2')
                            1-20 Profiles
                        @elseif ($subscription->enterprise_type == '3')
                            20+ Profiles
                        @endif
                    </span>
                </div>

            </div>

            <!-- Members and Billing Information -->
            <div class="card custom-card mb-4">
                <div class="card-body">
                    <div class="row">
                        <h6 class="text-uppercase fw-bold">Invoice History</h6>
                        {{-- <div class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-2" id="sendInvoices">
                        <label for="sendInvoices" class="form-check-label">Send email invoices</label>
                    </div> --}}
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Date Start</th>
                                    <th>Date End</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    {{-- <th>Download</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">
                                        {{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}
                                    </td>
                                    <td class="fw-bold">
                                        {{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}
                                    </td>
                                    <td>{{ $subscription->description ? $subscription->description : 'Trail' }}</td>
                                    <td>
                                        <span
                                            class="badge 
                                            {{ $subscription
                                                ? (\Carbon\Carbon::parse($subscription->end_date)->isPast()
                                                    ? 'bg-label-danger'
                                                    : 'bg-label-success')
                                                : 'bg-label-danger' }} 
                                            me-1">
                                            {{ $subscription
                                                ? (\Carbon\Carbon::parse($subscription->end_date)->isPast()
                                                    ? 'Expired'
                                                    : 'Active')
                                                : 'Inactive' }}
                                        </span>
                                    </td>
                                    {{-- <td>
                                        <a href="#" class="icon-download">
                                            <span>⬇️</span>
                                        </a>
                                    </td> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container mt-4">
            <!-- Current Plan Section -->
            <div class="card custom-card mb-4">
                <div class="card-header">
                    <h6 class="text-uppercase fw-bold">Current GOtaps Plan</h6>
                </div>
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div class="card position-relative" style="width: 60%; height: 150px;background-color:#EDF6FD;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                            <h4 class="fw-bold">Activate Your Plan</h4>
                            <p>First Active your Plan by contact With Admin</p>
                            <small class="text-gray">Bussiness Name</small>
                        </div>
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="btn btn-switch px-4">None</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <h5 class="fw-bold">Contact Information</h5>
                        <p class="text-gray">Total billed monthly</p>
                        <small class="text-gray">
                            For the first 1 - 5<br>
                            Price per member per month: $9.00
                        </small>
                    </div>
                </div>

                <div class="ml-3 mb-2">
                    <h6 class="text-uppercase fw-bold px-3">
                        PROFILES Quantity
                    </h6>
                    <span class=" fw-bold p-3 text-center">
                        1-10
                    </span>
                </div>

            </div>
        </div>
    @endif
</div>
