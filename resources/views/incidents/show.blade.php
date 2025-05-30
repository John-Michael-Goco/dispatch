@extends('layouts.app')

@section('content')
    <!-- Main container for incident details -->
    <div class="container">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-info-circle"></i> Incident Details</h2>
            <div>
                <a href="{{ route('incidents.edit', $incident->id) }}" class="btn btn-primary me-2">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="{{ route('incidents.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Incidents
                </a>
            </div>
        </div>

        <!-- Card container for incident information -->
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <!-- Left column: Basic incident information -->
                    <div class="col-md-6">
                        <h5 class="card-title mb-4">Incident Information</h5>
                        <dl class="row">
                            <!-- Description display -->
                            <dt class="col-sm-4">Description</dt>
                            <dd class="col-sm-8">{{ $incident->description ?: 'No description provided' }}</dd>
                            <!-- Status display with dynamic badge color -->
                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
                                <span
                                    class="badge bg-{{ $incident->status === 'open' ? 'danger' : ($incident->status === 'in_progress' ? 'warning' : ($incident->status === 'resolved' ? 'success' : 'secondary')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                </span>
                            </dd>

                            <!-- Service information display -->
                            <dt class="col-sm-4">Service Requested</dt>
                            <dd class="col-sm-8">{{ $incident->service?->name ?? 'Deleted Service' }}</dd>

                            <!-- Branch information display -->
                            <dt class="col-sm-4">Service Branch</dt>
                            <dd class="col-sm-8">{{ $incident->branch?->name ?? 'Deleted Branch' }}</dd>

                            <!-- Location information display -->
                            <dt class="col-sm-4">Location</dt>
                            <dd class="col-sm-8">{{ $incident->location ?: 'No location provided' }}</dd>

                            <!-- Coordinates display -->
                            <dt class="col-sm-4">Coordinates</dt>
                            <dd class="col-sm-8">
                                @if ($incident->latitude && $incident->longitude)
                                    {{ $incident->latitude }}, {{ $incident->longitude }}
                                @else
                                    No coordinates provided
                                @endif
                            </dd>
                        </dl>
                    </div>
                    <!-- Right column: Additional information -->
                    <div class="col-md-6">
                        <h5 class="card-title mb-4">Additional Information</h5>
                        <dl class="row">
                            <!-- Reporter information display -->
                            <dt class="col-sm-4">Reported By</dt>
                            <dd class="col-sm-8">{{ $incident->reporter->name }}</dd>

                            <!-- Creation timestamp display -->
                            <dt class="col-sm-4">Created At</dt>
                            <dd class="col-sm-8">{{ $incident->created_at->format('M d, Y h:i A') }}</dd>

                            <!-- Last update timestamp display -->
                            <dt class="col-sm-4">Last Updated</dt>
                            <dd class="col-sm-8">{{ $incident->updated_at->format('M d, Y h:i A') }}</dd>

                            <!-- Resolution timestamp display (if resolved) -->
                            @if ($incident->resolved_at)
                                <dt class="col-sm-4">Resolved At</dt>
                                <dd class="col-sm-8">{{ $incident->resolved_at->format('M d, Y h:i A') }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Location map section (if coordinates exist) -->
                @if ($incident->latitude && $incident->longitude)
                    <div class="mt-4">
                        <h5 class="card-title mb-3">Location Map</h5>
                        <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Map initialization (if coordinates exist) -->
    @if ($incident->latitude && $incident->longitude)
        <!-- Leaflet map resources -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            // Initialize map with incident coordinates
            let map = L.map('map').setView([{{ $incident->latitude }}, {{ $incident->longitude }}], 13);
            
            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Custom icons for incident and branch locations
            const incidentIcon = L.divIcon({
                className: 'incident-location-marker',
                html: '<div class="incident-marker-pin"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            const branchIcon = L.divIcon({
                className: 'branch-location-marker',
                html: '<div class="branch-marker-pin"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            // Add incident location marker
            const incidentMarker = L.marker([{{ $incident->latitude }}, {{ $incident->longitude }}], {
                    icon: incidentIcon
                })
                .addTo(map)
                .bindPopup('<b>Incident Location</b><br>{{ $incident->location }}');
            incidentMarker.openPopup();

            // Add branch location marker if coordinates are available
            @if ($incident->branch && $incident->branch->latitude && $incident->branch->longitude)
                const branchMarker = L.marker([{{ $incident->branch->latitude }}, {{ $incident->branch->longitude }}], {
                        icon: branchIcon
                    })
                    .addTo(map)
                    .bindPopup('<b>{{ $incident->branch->name }}</b><br>{{ $incident->branch->address }}');
                branchMarker.openPopup();

                // Fit map to show both markers
                const group = L.featureGroup([incidentMarker, branchMarker]);
                map.fitBounds(group.getBounds().pad(0.2));
            @endif
        </script>
        <!-- Custom styles for map markers -->
        <style>
            /* Incident location marker styles */
            .incident-location-marker {
                position: relative;
            }

            .incident-marker-pin {
                width: 20px;
                height: 20px;
                background-color: #ff4444;
                border: 2px solid #fff;
                border-radius: 50%;
                box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
                position: relative;
            }

            .incident-marker-pin::after {
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

            /* Branch location marker styles */
            .branch-location-marker {
                position: relative;
            }

            .branch-marker-pin {
                width: 20px;
                height: 20px;
                background-color: #2196F3;
                border: 2px solid #fff;
                border-radius: 50%;
                box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
                position: relative;
            }

            .branch-marker-pin::after {
                content: '';
                position: absolute;
                bottom: -8px;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 0;
                border-left: 6px solid transparent;
                border-right: 6px solid transparent;
                border-top: 8px solid #2196F3;
            }
        </style>
    @endif
@endsection
