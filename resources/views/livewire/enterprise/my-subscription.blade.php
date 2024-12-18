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
    <div class="container mt-4">
        <!-- Current Plan Section -->
        <div class="card custom-card mb-4">
            <div class="card-header">
                <h6 class="text-uppercase fw-bold">Current GOtaps Plan</h6>
            </div>
            <div class="card-body d-flex justify-content-between align-items-start">
                <div class="card position-relative" style="width: 60%; height: 200px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                        <h4 class="fw-bold">Connect</h4>
                        <p class="mb-0">$9.00 USD</p>
                        <small class="text-gray">Avg per user/month paid monthly</small>
                    </div>
                    <div class="position-absolute top-0 end-0 p-2">
                        <button class="btn btn-switch px-4">Switch to Yearly</button>
                    </div>
                </div>

                <div class="text-center">
                    <h5 class="fw-bold">$45.00 USD</h5>
                    <p class="text-gray">Total billed monthly</p>
                    <small class="text-gray">
                        For the first 1 - 5<br>
                        Price per member per month: $9.00
                    </small>
                </div>
            </div>

        </div>

        <!-- Members and Billing Information -->
        <div class="card custom-card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Billing Information Card -->
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-uppercase fw-bold">Billing Information
                                <a href="#" class="text-decoration-none">Edit</a>
                            </h6>
                            <div class="mt-3">
                                <p class="mb-1"><strong>Country:</strong> United States</p>
                                <p class="mb-1"><strong>Email:</strong>
                                    <a href="mailto:Marketing@wheelsndeals.us" class="text-decoration-none">
                                        Marketing@wheelsndeals.us
                                    </a>
                                </p>
                                <p class="mb-0"><strong>ZIP Code:</strong> —</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Card -->
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded shadow-sm h-100">
                            <h6 class="text-uppercase fw-bold">Payment Method
                                <a href="#" class="text-decoration-none">Edit</a>
                            </h6>
                            <div class="mt-3">
                                <p class="mb-1">💳 <strong>....8310</strong></p>
                                <p class="mb-1 text-muted">Visa-Expires 01/2028</p>
                                <p class="mb-1 text-muted">Billed on the 26th of every month</p>
                                <p class="mb-0"><strong>Next billing on October 26, 2024</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h6 class="text-uppercase fw-bold">Invoice History</h6>
                    <div class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-2" id="sendInvoices">
                        <label for="sendInvoices" class="form-check-label">Send email invoices</label>
                    </div>
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th class="text-end">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">September 26, 2024</td>
                                <td>Free trial</td>
                                <td class="text-end">
                                    <a href="#" class="icon-download">
                                        <span>⬇️</span>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-sm btn-light">
                        Download all invoices
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
