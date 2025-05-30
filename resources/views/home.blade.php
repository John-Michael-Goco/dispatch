@extends('layouts.app')

@section('content')
<!-- Main Dashboard Container -->
<div class="container">
    <!-- Dashboard Header -->
    <h4 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h4>

    <!-- Statistics Cards Section -->
    <div class="row g-4 mb-4">
        <!-- Total Incidents Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Incidents</h6>
                            <h3 class="mb-0">{{ $stats['total_incidents'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Open Incidents Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Open Incidents</h6>
                            <h3 class="mb-0">{{ $stats['open_incidents'] }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-circle text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress Incidents Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">In Progress</h6>
                            <h3 class="mb-0">{{ $stats['in_progress_incidents'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clock text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Services Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Services</h6>
                            <h3 class="mb-0">{{ $stats['total_services'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-gear text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Branches Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Branches</h6>
                            <h3 class="mb-0">{{ $stats['total_branches'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-diagram-3 text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Users</h6>
                            <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-secondary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maps and Table Section -->
    <div class="row mb-4">
        <!-- Branch Locations Map Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-geo-alt"></i> Branch Locations</h5>
                </div>
                <div class="card-body">
                    <div id="branches-map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                </div>
            </div>
        </div>

        <!-- Recent Incidents Table Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-clock-history"></i> Recent Incidents (Last 3 Days)</h5>
                    <a href="{{ route('incidents.index') }}" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover">
                            <thead class="sticky-top bg-white">
                                <tr>
                                    <th>Title</th>
                                    <th>Service</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_incidents'] as $incident)
                                    <tr>
                                        <td>{{ $incident->title }}</td>
                                        <td>{{ $incident->service->name }}</td>
                                        <td>{{ $incident->branch->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $incident->status === 'open' ? 'danger' : ($incident->status === 'in_progress' ? 'warning' : ($incident->status === 'resolved' ? 'success' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $incident->created_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No recent incidents</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet Map Integration -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Map Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map with a default view of the Philippines
    const branchesMap = L.map('branches-map').setView([14.5995, 120.9842], 6);
    
    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(branchesMap);

    // Add markers for each branch location
    const branches = {!! json_encode($stats['branches']) !!};
    const branchMarkers = [];
    branches.forEach(branch => {
        if (branch.latitude && branch.longitude) {
            const marker = L.marker([branch.latitude, branch.longitude])
                .addTo(branchesMap)
                .bindPopup(branch.name);
            branchMarkers.push(marker);
        }
    });

    // Adjust map view to show all branch markers with padding
    if (branchMarkers.length > 0) {
        const branchGroup = L.featureGroup(branchMarkers);
        branchesMap.fitBounds(branchGroup.getBounds().pad(0.2));
    }
});
</script>
@endsection
