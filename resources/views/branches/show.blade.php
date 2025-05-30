@extends('layouts.app')

@section('content')
<!-- Header section with title -->
<h2 class="mb-4 ms-3" style="font-size:1.5rem;"><i class="bi bi-eye"></i> Branch Details</h2>

<!-- Main container for branch details -->
<div class="container" style="max-width: 1100px;">
    <!-- Card container for branch information -->
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <!-- Left column: Basic branch information -->
                <div class="col-md-5">
                    <!-- Branch name display -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name of Branch:</label>
                        <div>{{ $branch->name }}</div>
                    </div>
                    <!-- Associated service display -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Service:</label>
                        <div>{{ $branch->service->name ?? '-' }}</div>
                    </div>
                    <!-- Address display with word wrapping -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address:</label>
                        <div style="word-break: break-word; white-space: pre-line;">{{ $branch->address }}</div>
                    </div>
                    <!-- Contact number display -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Number:</label>
                        <div>{{ $branch->contact_number }}</div>
                    </div>
                    <!-- Status display with dynamic badge color -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        <div>
                            <span class="badge px-3 py-2 {{ $branch->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($branch->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Right column: Location information -->
                <div class="col-md-7">
                    <!-- Coordinates display -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label fw-bold">Latitude:</label>
                            <div>{{ $branch->latitude }}</div>
                        </div>
                        <div class="col">
                            <label class="form-label fw-bold">Longitude:</label>
                            <div>{{ $branch->longitude }}</div>
                        </div>
                    </div>
                    <!-- Interactive map display -->
                    <div class="mb-3">
                        <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                    </div>
                </div>
            </div>
            <!-- Action buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('branches.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
                <a href="{{ route('branches.edit', $branch) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet map resources -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Map initialization script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get branch coordinates or default to Manila
        let lat = {{ $branch->latitude ?? 14.5995 }};
        let lng = {{ $branch->longitude ?? 120.9842 }};
        
        // Initialize map with appropriate zoom level
        let map = L.map('map').setView([lat, lng], (lat && lng) ? 16 : 13);
        
        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);
        
        // Add marker if coordinates exist
        if (lat && lng) {
            L.marker([lat, lng]).addTo(map);
        }
    });
</script>
@endsection 