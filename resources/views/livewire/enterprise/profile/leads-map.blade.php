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
        <div class="row" style="background-color: white;border-radius:15px;">
            <!-- Map Section -->
            <div class="col-md-8">
                <div id="map" style="height: 500px; width: 100%; border-radius: 15px 0 0 15px;"></div>
            </div>

            <!-- Leads Section -->
            <div class="col-md-4">
                <div class="text-center mt-4 mb-3">
                    <p style="font-size: 14px;font-weight: bold;color:black;">
                        Showing all {{ count($leads) }} leads
                    </p>
                </div>
                <div class="leads-scrollable">
                    @if (count($leads) > 0)
                        @foreach ($leads as $lead)
                            @if ($lead->latitude && $lead->longitude)
                                <div class="card mb-2 lead-card" data-lat="{{ $lead->latitude }}"
                                    data-lng="{{ $lead->longitude }}" style="border-radius: 10px;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($lead->viewer_photo && Storage::disk('public')->exists($lead->viewer_photo) ? Storage::url($lead->viewer_photo) : 'user.png') }}"
                                                alt="Lead Photo" class="rounded-circle"
                                                style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                                            <div>
                                                <h6 class="card-title" style="font-size: 14px;">{{ $lead->name }}
                                                </h6>
                                                <p class="card-text" style="font-size: 12px; margin-bottom: 0;">
                                                    {{ $lead->city }}, {{ $lead->state }}, {{ $lead->country }}
                                                </p>
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
                                            No leads found</p>
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
        }).addTo(map);

        // Initialize marker cluster group
        var markers = L.markerClusterGroup();

        @foreach ($leads as $user)
            @if ($user->latitude && $user->longitude)
                // Use the viewer's photo if it exists, otherwise use a default image
                var imageUrl =
                    "{{ asset($user->viewer_photo && Storage::disk('public')->exists($user->viewer_photo) ? Storage::url($user->viewer_photo) : 'user.png') }}";

                // Create a custom divIcon with the photo
                var customIcon = L.divIcon({
                    html: `<div style="width: 30px; height: 30px; border-radius: 50%; background-image: url('${imageUrl}'); background-size: cover; background-position: center;"></div>`,
                    className: 'custom-marker',
                    iconSize: [20, 20]
                });

                // Create a marker using the custom icon
                var marker = L.marker([{{ $user->latitude }}, {{ $user->longitude }}], {
                        icon: customIcon
                    })
                    .bindPopup(`
                    <div style="text-align: center;">
                        <b>{{ $user->name }}</b><br>{{ $user->city }}, {{ $user->state }}
                    </div>
                `);

                markers.addLayer(marker);
            @endif
        @endforeach

        // Add markers to the map
        map.addLayer(markers);

        // Fit map to bounds of all markers
        var bounds = L.latLngBounds();
        markers.eachLayer(function(layer) {
            bounds.extend(layer.getLatLng());
        });
        map.fitBounds(bounds);

        document.querySelectorAll('.lead-card').forEach(function(card) {
            card.addEventListener('click', function() {
                var lat = this.getAttribute('data-lat');
                var lng = this.getAttribute('data-lng');
                var latLng = L.latLng(lat, lng);
                document.querySelectorAll('.lead-card').forEach(function(c) {
                    c.style.border = '';
                });

                // Add black border to the clicked card
                this.style.border = '2px solid black';

                // Pan and zoom the map to the selected lead's location
                map.setView(latLng, 12); // Adjust the zoom level as needed

                // Optionally open the marker's popup
                markers.eachLayer(function(marker) {
                    if (marker.getLatLng().equals(latLng)) {
                        marker.openPopup();
                    }
                });
            });
        });

        const leafletControl = document.querySelector('.leaflet-bottom.leaflet-right');

        // Check if the element exists and then hide it
        if (leafletControl) {
            leafletControl.style.display = 'none';
        }
    </script>

</div>
