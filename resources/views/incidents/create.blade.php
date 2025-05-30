@extends('layouts.app')

@section('content')
    <!-- Header section with title -->
    <h4 class="mb-4"><i class="bi bi-exclamation-triangle"></i> Create Incident</h4>
    <!-- Main container for create incident form -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Card container for the form -->
                <div class="card shadow mx-auto">
                    <div class="card-body">
                        <!-- Incident creation form -->
                        <form action="{{ route('incidents.store') }}" method="POST">
                            @csrf
                            <input type="hidden" id="title" name="title">
                            <div class="row">
                                <!-- Left column: Basic incident information -->
                                <div class="col-md-5">
                                    <!-- Address input field (readonly, populated by geolocation) -->
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Address</label>
                                        <textarea class="form-control @error('location') is-invalid @enderror" 
                                            id="location" name="location" rows="2" readonly>{{ old('location') }}</textarea>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description input field -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                            id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Service selection dropdown -->
                                    <div class="mb-3">
                                        <label for="service_id" class="form-label">Service</label>
                                        <select class="form-select @error('service_id') is-invalid @enderror" 
                                            id="service_id" name="service_id" required>
                                            <option value="">Select a service</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" 
                                                    data-name="{{ $service->name }}"
                                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Branch selection dropdown -->
                                    <div class="mb-3">
                                        <label for="branch_id" class="form-label">Select Branch</label>
                                        <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id"
                                            name="branch_id" required>
                                            <option value="">Select a branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" 
                                                    data-lat="{{ $branch->latitude }}"
                                                    data-lng="{{ $branch->longitude }}" 
                                                    data-address="{{ $branch->address }}"
                                                    data-service="{{ $branch->service_id }}"
                                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('branch_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Right column: Location information -->
                                <div class="col-md-7">
                                    <!-- Interactive map for location selection -->
                                    <div class="mb-3">
                                        <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                                    </div>

                                    <!-- Latitude and longitude input fields (readonly, populated by map) -->
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="latitude" class="form-label">Latitude</label>
                                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                                id="latitude" name="latitude" value="{{ old('latitude') }}" readonly>
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label for="longitude" class="form-label">Longitude</label>
                                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                                id="longitude" name="longitude" value="{{ old('longitude') }}" readonly>
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form action buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('incidents.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Create Incident <i class="bi bi-check-circle"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet map resources -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map centered on Manila
        let map = L.map('map').setView([14.5995, 120.9842], 13);
        let userMarker;
        let branchMarkers = {};
        let selectedBranchMarker = null;

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Custom icon for user location marker
        const userIcon = L.divIcon({
            className: 'user-location-marker',
            html: '<div class="user-marker-pin"></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        // Get current location on page load
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Set user marker on map
                    if (userMarker) {
                        map.removeLayer(userMarker);
                    }
                    userMarker = L.marker([lat, lng], { icon: userIcon })
                        .addTo(map)
                        .bindPopup('<b>Current Location</b>');
                    userMarker.openPopup();

                    // Update hidden input fields with coordinates
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    // Get address from coordinates (reverse geocoding)
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.display_name) {
                                document.getElementById('location').value = data.display_name;
                            }
                        })
                        .catch(error => {
                            console.error('Error getting address:', error);
                        });

                    // Center map on user location
                    map.setView([lat, lng], 16);
                },
                function(error) {
                    console.error('Error getting location:', error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                }
            );
        }

        // Handle service selection change
        document.getElementById('service_id').addEventListener('change', function() {
            const selectedServiceId = this.value;
            const branchSelect = document.getElementById('branch_id');
            const options = branchSelect.options;

            // Reset branch selection
            branchSelect.value = '';
            highlightBranchMarker(null);

            // Filter branch options
            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                if (option.value === '') continue; // Skip the default option

                if (selectedServiceId && option.dataset.service === selectedServiceId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }

            // Update title with selected service name
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('title').value = selectedOption.dataset.name || '';

            // Update markers
            updateBranchMarkers();
        });

        // Function to update branch markers based on selected service
        function updateBranchMarkers() {
            const selectedServiceId = document.getElementById('service_id').value;
            const branchSelect = document.getElementById('branch_id');
            const options = branchSelect.options;

            // Clear existing markers
            Object.values(branchMarkers).forEach(marker => {
                map.removeLayer(marker);
            });
            branchMarkers = {};

            // Only show markers if a service is selected
            if (selectedServiceId) {
                // Add markers for visible branches
                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    if (option.value === '') continue; // Skip the default option

                    const serviceId = option.dataset.service;
                    const lat = parseFloat(option.dataset.lat);
                    const lng = parseFloat(option.dataset.lng);

                    // Show marker if branch belongs to selected service
                    if (serviceId === selectedServiceId) {
                        if (!isNaN(lat) && !isNaN(lng)) {
                            const marker = L.marker([lat, lng])
                                .addTo(map)
                                .bindPopup(`<b>${option.text}</b><br>${option.dataset.address}`);

                            marker.on('click', () => {
                                branchSelect.value = option.value;
                                highlightBranchMarker(marker);
                            });

                            branchMarkers[option.value] = marker;
                        }
                    }
                }

                // Fit map to show all visible markers
                if (Object.keys(branchMarkers).length > 0) {
                    const group = L.featureGroup(Object.values(branchMarkers));
                    if (userMarker) {
                        group.addLayer(userMarker);
                    }
                    map.fitBounds(group.getBounds().pad(0.2));
                }
            } else {
                // If no service is selected, just center on user location
                if (userMarker) {
                    map.setView(userMarker.getLatLng(), 16);
                }
            }
        }

        // Function to highlight the selected branch marker
        function highlightBranchMarker(marker) {
            // Reset all branch markers to default style
            Object.values(branchMarkers).forEach(m => {
                m.setIcon(L.Icon.Default.prototype);
            });

            // Highlight selected marker
            if (marker) {
                const highlightedIcon = L.Icon.Default.prototype;
                highlightedIcon.options.className = 'highlighted-marker';
                marker.setIcon(highlightedIcon);
                marker.openPopup();
            }
        }

        // Handle branch selection change
        document.getElementById('branch_id').addEventListener('change', function() {
            const selectedId = this.value;
            if (selectedId && branchMarkers[selectedId]) {
                const marker = branchMarkers[selectedId];
                highlightBranchMarker(marker);
                map.setView(marker.getLatLng(), 14);
            } else {
                highlightBranchMarker(null);
            }
        });

        // Initial setup - hide all branch options and markers
        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.getElementById('branch_id');
            const options = branchSelect.options;
            
            // Hide all branch options initially
            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                if (option.value !== '') { // Skip the default option
                    option.style.display = 'none';
                }
            }
            
            // Center map on user location
            if (userMarker) {
                map.setView(userMarker.getLatLng(), 16);
            }
        });
    </script>
    <style>
        /* Custom styles for user location marker */
        .user-location-marker {
            position: relative;
        }

        .user-marker-pin {
            width: 20px;
            height: 20px;
            background-color: #ff4444;
            border: 2px solid #fff;
            border-radius: 50%;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .user-marker-pin::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 8px solid #ff4444;
        }

        .highlighted-marker {
            filter: hue-rotate(120deg) brightness(1.2);
        }
    </style>
@endsection 