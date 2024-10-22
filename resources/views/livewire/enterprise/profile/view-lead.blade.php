<div>
    <style>
        .lead-detail {
            display: flex;
            /* Use flexbox for inline display */
            align-items: center;
            /* Align items vertically centered */
            margin-bottom: 10px;
            /* Space between items */
        }

        .lead-detail h6 {
            margin: 0;
            /* Remove default margin */
            padding-right: 10px;
            /* Space between h6 and p */
            color: black;
            /* Ensure text is black */
        }

        .lead-detail p {
            padding-top: 15px;
        }
    </style>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h5 class="mb-0">Lead Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="lead-detail">
                            <h6 class="text-black">Name:</h6>
                            <p>{{ $lead->name }}</p>
                        </div>
                        <div class="lead-detail">
                            <h6 class="text-black">Phone:</h6>
                            <p>{{ $lead->phone }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="lead-detail">
                            <h6 class="text-black">Email:</h6>
                            <p>{{ $lead->email }}</p>
                        </div>
                        <div class="lead-detail">
                            <h6 class="text-black">Date:</h6>
                            <p>{{ date('Y-m-d', strtotime($lead->created_at)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
