{{-- <div class="col-lg-4 col-md-12 col-6 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                    <span class="badge {{ $background }} me-1">
                        <i class="{{ $icon }}"></i>
                    </span>
                </div>
            </div>
            <span class="fw-semibold d-block mb-1">{{ $title }}</span>
            <h3 class="card-title mb-2"> {{ $total }} </h3>
        </div>
    </div>
</div> --}}
<div class="col-lg-4 col-md-12 col-6 mb-4">
    <div class="card h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
            <!-- Icon -->
            <div>
                <span class="badge {{ $background }}">
                    <i class="{{ $icon }}"></i>
                </span>
            </div>
            <!-- Title and Total -->
            <div class="text-end">
                <span class="fw-semibold d-block">{{ $title }}</span>
                <h3 class="card-title mb-0">{{ $total }}</h3>
            </div>
        </div>
    </div>
</div>
