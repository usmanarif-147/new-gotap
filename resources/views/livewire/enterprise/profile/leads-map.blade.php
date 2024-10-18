<div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <style>
        .leads-scrollable {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <!-- Map Section -->
            <div class="col-md-8">
                <div id="map" style="height: 500px; width: 100%; border-radius: 10px;"></div>
            </div>

            <!-- Leads Section -->
            <div class="col-md-4">
                <div class="leads-scrollable" style="max-height: 400px; overflow-y: auto;">
                    @if (count($leads) > 0)
                        @foreach ($leads as $lead)
                            @if ($lead->latitude && $lead->longitude)
                                <div class="card mb-2" style="border-radius: 10px;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($lead->viewer_photo && Storage::disk('public')->exists($lead->viewer_photo) ? Storage::url($lead->viewer_photo) : 'user.png') }}"
                                                alt="Lead Photo" class="rounded-circle"
                                                style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                                            <div>
                                                <h6 class="card-title" style="font-size: 14px;">{{ $lead->name }}</h6>
                                                <p class="card-text" style="font-size: 12px; margin-bottom: 0;">
                                                    {{ $lead->city }}, {{ $lead->state }}, {{ $lead->country }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="card mb-2" style="border-radius: 10px;">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="card-text" style="font-size: 12px; margin-bottom: 0;">
                                            no leads found</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <script>
        // Initialize the map
        var map = L.map('map', {
            center: [20.0, 5.0],
            zoom: 4,
            maxZoom: 18 // Set the maximum zoom level
        });

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Initialize marker cluster group
        var markers = L.markerClusterGroup();

        @foreach ($leads as $user)
            var marker = L.marker([{{ $user->latitude }}, {{ $user->longitude }}])
                .bindPopup("<b>{{ $user->name }}</b><br>{{ $user->city }}, {{ $user->state }}");
            markers.addLayer(marker);
        @endforeach

        // Add markers to the map
        map.addLayer(markers);
        var bounds = L.latLngBounds();
        markers.eachLayer(function(layer) {
            bounds.extend(layer.getLatLng());
        });
        map.fitBounds(bounds);
    </script>


</div>
