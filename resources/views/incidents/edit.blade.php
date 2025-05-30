@extends('layouts.app')

@section('content')
    <!-- Header section with title -->
    <h4 class="mb-4"><i class="bi bi-exclamation-triangle"></i> Edit Incident</h4>
    <!-- Main container for edit incident form -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Card container for the form -->
                <div class="card shadow mx-auto">
                    <div class="card-body">
                        <!-- Incident edit form with PUT method -->
                        <form action="{{ route('incidents.update', $incident) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="title" name="title">
                            <div class="row">
                                <!-- Left column: Basic incident information -->
                                <div class="col-md-5">
                                    <!-- Address input field (readonly, populated by geolocation) -->
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Address</label>
                                        <textarea class="form-control @error('location') is-invalid @enderror" 
                                            id="location" name="location" rows="2" readonly>{{ old('location', $incident->location) }}</textarea>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description input field with existing value -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                            id="description" name="description" rows="3">{{ old('description', $incident->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Service selection dropdown with existing value -->
                                    <div class="mb-3">
                                        <label for="service_id" class="form-label">Service</label>
                                        <select class="form-select @error('service_id') is-invalid @enderror" 
                                            id="service_id" name="service_id" required>
                                            <option value="">Select a service</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" 
                                                    data-name="{{ $service->name }}"
                                                    {{ old('service_id', $incident->service_id) == $service->id ? 'selected' : '' }}>
                                                    {{ $service->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Branch selection dropdown with existing value -->
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
                                                    {{ old('branch_id', $incident->branch_id) == $branch->id ? 'selected' : '' }}>
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
                                                id="latitude" name="latitude" value="{{ old('latitude', $incident->latitude) }}" readonly>
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label for="longitude" class="form-label">Longitude</label>
                                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                                id="longitude" name="longitude" value="{{ old('longitude', $incident->longitude) }}" readonly>
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
                                    Update Incident <i class="bi bi-check-circle"></i>
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
        // Initialize map with incident coordinates
        let map = L.map('map').setView([{{ $incident->latitude }}, {{ $incident->longitude }}], 13);
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

        // Set initial user marker with incident coordinates
        userMarker = L.marker([{{ $incident->latitude }}, {{ $incident->longitude }}], { icon: userIcon })
            .addTo(map)
            .bindPopup('<b>Current Location</b>');
        userMarker.openPopup();

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
            
            // Set initial title from selected service
            const serviceSelect = document.getElementById('service_id');
            const selectedService = serviceSelect.options[serviceSelect.selectedIndex];
            if (selectedService && selectedService.dataset.name) {
                document.getElementById('title').value = selectedService.dataset.name;
            }

            // Show markers for initially selected service
            updateBranchMarkers();
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