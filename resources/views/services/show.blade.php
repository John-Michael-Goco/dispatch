@extends('layouts.app')

@section('content')
    <!-- Header section with title and back button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-gear"></i> Service Details</h1>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Services
        </a>
    </div>

    <!-- Main container for service details -->
    <div class="container" style="max-width: 900px;">
        <!-- Service information card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">{{ $service->name }}</h4>
                <p class="mb-2"><strong>Description:</strong><br>{{ $service->description }}</p>
                <p class="mb-2"><strong>Contact Line:</strong><br>{{ $service->contact_line }}</p>
            </div>
        </div>

        <!-- Branch locations map section -->
        <div class="mt-5">
            <h4 class="mb-3"><i class="bi bi-geo-alt"></i> Branch Locations</h4>
            <div id="branches-map" style="height: 600px; width: 100%; border-radius: 8px;"></div>
        </div>
    </div>

    <!-- Leaflet map resources -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Map initialization script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get active branches with valid coordinates
            const branches = {!! json_encode(
                $service->branches()->where('status', 'active')->whereNotNull('latitude')->whereNotNull('longitude')->get(['name', 'latitude', 'longitude']),
            ) !!};

            // Set default map center to Manila
            let mapCenter = [14.5995, 120.9842];
            if (branches.length > 0) {
                mapCenter = [branches[0].latitude, branches[0].longitude];
            }

            // Initialize map with appropriate zoom level
            let map = L.map('branches-map').setView(mapCenter, branches.length > 0 ? 8 : 6);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Add markers for each branch
            branches.forEach(function(branch) {
                if (branch.latitude && branch.longitude) {
                    L.marker([branch.latitude, branch.longitude])
                        .addTo(map)
                        .bindPopup('<b>' + branch.name.replace(/'/g, "&#39;") + '</b>');
                }
            });

            // Adjust map bounds if multiple branches exist
            if (branches.length > 1) {
                let group = L.featureGroup(branches.map(b => L.marker([b.latitude, b.longitude])));
                map.fitBounds(group.getBounds().pad(0.2));
            }
        });
    </script>
@endsection
