@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1100px;">
    <h2 class="mb-4 ms-3" style="font-size:1.5rem;"><i class="bi bi-eye"></i> Branch Details</h2>
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name of Branch:</label>
                        <div>{{ $branch->name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Service:</label>
                        <div>{{ $branch->service->name ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address:</label>
                        <div style="word-break: break-word; white-space: pre-line;">{{ $branch->address }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Number:</label>
                        <div>{{ $branch->contact_number }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        <div>
                            <span class="badge px-3 py-2 {{ $branch->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($branch->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
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
                    <div class="mb-3">
                        <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('branches.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
                <a href="{{ route('branches.edit', $branch) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>
</div>
<!-- Leaflet CSS/JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let lat = {{ $branch->latitude ?? 14.5995 }};
        let lng = {{ $branch->longitude ?? 120.9842 }};
        let map = L.map('map').setView([lat, lng], (lat && lng) ? 16 : 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);
        if (lat && lng) {
            L.marker([lat, lng]).addTo(map);
        }
    });
</script>
@endsection 