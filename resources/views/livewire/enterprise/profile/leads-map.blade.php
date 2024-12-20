<div>
    <link rel="stylesheet" href="{{ asset('assets/map/leaflet.css') }}">
    <script src="{{ asset('assets/map/leaflet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/map/MarkerCluster.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/map/MarkerCluster.Default.css') }}">
    <script src="{{ asset('assets/map/leaflet.markercluster.js') }}"></script>

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
                <div id="map" wire:ignore style="height: 500px; width: 100%; border-radius: 15px 0 0 15px;"></div>
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
                                            <div
                                                style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; display: flex; justify-content: center; align-items: center; background: {{ $lead->viewer_photo && file_exists(public_path('storage/' . $lead->viewer_photo)) ? 'none' : '#000' }};">
                                                @if ($lead->viewer_photo && file_exists(public_path('storage/' . $lead->viewer_photo)))
                                                    <img src="{{ Storage::url($lead->viewer_photo) }}"
                                                        alt="Viewer Photo" class="img-fluid"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <span style="color: #fff; font-weight: bold; font-size: 16px;">
                                                        {{ $lead->name ? strtoupper(substr($lead->name, 0, 1)) : 'No' }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div style="margin-left: 20px;">
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
    @include('livewire.admin.confirm-modal')
    <script>
        // Initialize the map
        var map = L.map('map', {
            center: [20.0, 5.0],
            zoom: 4,
            maxZoom: 18 // Set the maximum zoom level
        });

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
        }).addTo(map);

        // Initialize marker cluster group
        var markers = L.markerClusterGroup();

        var customTextControl = L.control({
            position: 'topright'
        });

        customTextControl.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'custom-text-control'); // Create a div for the custom control
            div.innerHTML =
                'Leads only show on the map if location was allowed upon connecting'; // Add your custom text here
            div.style.backgroundColor = 'rgba(255, 255, 255, 0.8)'; // Optional: background for readability
            div.style.padding = '5px'; // Optional: padding for styling
            div.style.borderRadius = '15px'; // Optional: round the corners
            div.style.color = 'black';
            return div;
        };

        // Add the custom text control to the map
        customTextControl.addTo(map);

        // Create an object to store markers by user ID
        var markerDictionary = {};

        @foreach ($leads as $user)
            @if ($user->latitude && $user->longitude)
                // Use the viewer's photo if it exists, otherwise use a default image
                @php
                    $hasPhoto = $user->viewer_photo && Storage::disk('public')->exists($user->viewer_photo);
                    $photoUrl = $hasPhoto ? Storage::url($user->viewer_photo) : null;
                    $initial = strtoupper(substr($user->name, 0, 1));
                @endphp
                var hasPhoto = @json($hasPhoto); // Pass boolean to JavaScript
                var imageUrl = "{{ $photoUrl ?? '' }}";
                var userInitial = "{{ $initial }}";
                var customIcon;
                if (hasPhoto && imageUrl) {
                    customIcon = L.divIcon({
                        html: `<div style="width: 30px;height: 30px;border-radius: 50%;background-image: url('${imageUrl}');background-size: cover;background-position: center;"></div>`,
                        className: 'custom-marker',
                        iconSize: [20, 20]
                    });
                } else {
                    customIcon = L.divIcon({
                        html: `<div style="width: 30px;height: 30px;border-radius: 50%;background-color: black;color: white;display: flex;align-items: center;justify-content: center;font-size: 16px;font-weight: bold;">${userInitial}</div>`,
                        className: 'custom-marker',
                        iconSize: [20, 20]
                    });
                }

                // Create a custom divIcon with the photo
                // var customIcon = L.divIcon({
                //     html: `<div style="width: 30px; height: 30px; border-radius: 50%; background-image: url('${imageUrl}'); background-size: cover; background-position: center;"></div>`,
                //     className: 'custom-marker',
                //     iconSize: [20, 20]
                // });

                // Create a marker using the custom icon
                var marker = L.marker([{{ $user->latitude }}, {{ $user->longitude }}], {
                        icon: customIcon
                    })
                    .bindPopup(`
                        <div style="width: 200px; text-align: center; border: 1px solid #ddd; border-radius: 10px; padding: 10px;">
                            <!-- Profile Section -->
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <strong>{{ $user->name }}</strong>
                                <img src="{{ asset($user->viewing_photo && Storage::disk('public')->exists($user->viewing_photo) ? Storage::url($user->viewing_photo) : 'user.png') }}"
                                    alt="Profile Photo"
                                    style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                            </div>

                            <!-- Divider Line -->
                            <hr style="border: 1px solid #ddd; margin: 10px 0;">

                            <!-- Icons Section -->
                            <div style="display: flex; justify-content: space-around;">
                                <!-- View Profile Icon -->
                                <a href="{{ route('enterprise.leads.view', $user->id) }}" title="View Profile"
                                style="text-decoration: none; color: inherit;">
                                    <i class="bx bx-show" style="font-size: 18px;"></i>
                                </a>

                                <!-- Download Contact Icon -->
                                <a href="{{ route('lead.download', $user->id) }}"  title="Download Contact"
                                    style="text-decoration: none; color: inherit;">
                                        <i class="bx bx-download" style="font-size: 18px;"></i>
                                </a>

                                <!-- Delete Contact Icon -->
                                <a href="javascript:void(0);" onclick="deleteContact({{ $user->id }})" title="Delete Contact"
                                style="text-decoration: none; color: red;">
                                    <i class="bx bx-trash" style="font-size: 18px;"></i>
                                </a>
                            </div>
                        </div>
                    `);

                // Add marker to the cluster group
                markers.addLayer(marker);

                // Store marker in the dictionary by user ID
                markerDictionary[{{ $user->id }}] = marker;
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

        // Livewire event to handle marker removal
        document.addEventListener('livewire:load', function() {
            Livewire.on('contactDeleted', (userId) => {
                // Remove the marker from the map
                if (markerDictionary[userId]) {
                    markers.removeLayer(markerDictionary[
                        userId]); // Remove the marker from the cluster group
                    delete markerDictionary[userId]; // Remove from the dictionary
                    console.log(`Marker removed for userId: ${userId}`);
                } else {
                    console.log(`No marker found for userId: ${userId}`);
                }
            });
        });

        // Function to delete a contact (emit Livewire event)
        function deleteContact(userId) {
            Livewire.emit('confirmModal', userId); // Trigger Livewire event to delete the contact
        }
    </script>

    <script>
        const leafletControl = document.querySelector('.leaflet-bottom.leaflet-right');
        if (leafletControl) {
            leafletControl.style.display = 'none';
        }
        window.addEventListener('confirm-modal', event => {
            $('#confirmModal').modal('show');
            setTimeout(function() {
                map.invalidateSize();
            }, 200);
        });

        window.addEventListener('close-modal', event => {
            $('#confirmModal').modal('hide');
            setTimeout(function() {
                map.invalidateSize();
            }, 200);
        });
    </script>

</div>
