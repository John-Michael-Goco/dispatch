@extends('layouts.app')

@section('content')
    <!-- Main container for incidents list -->
    <div class="container">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-exclamation-triangle"></i> Incidents</h2>
            <div class="d-flex gap-2">
                <!-- Filter and search form -->
                <form action="{{ route('incidents.index') }}" method="GET" class="d-flex gap-2">
                    <!-- Status filter dropdown -->
                    <select class="form-select form-select-sm" name="status" style="width: 150px;">
                        <option value="">All Status</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <!-- Search input and buttons -->
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search incidents..." value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                        @if (request('search') || request('status'))
                            <a href="{{ route('incidents.index') }}" class="btn btn-secondary btn-sm"><i
                                    class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>
                <!-- Create new incident button -->
                <a href="{{ route('incidents.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle"></i> Create Incident
                </a>
            </div>
        </div>

        <!-- Flash message for success notifications -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Incidents table section -->
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <!-- Table header with column names -->
                <thead class="table-light">
                    <tr>
                        <th>Service</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Reported By</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through incidents and display their information -->
                    @forelse($incidents as $incident)
                        <tr>
                            <td>{{ $incident->service?->name ?? 'Deleted Service' }}</td>
                            <td>{{ $incident->branch?->name ?? 'Deleted Branch' }}</td>
                            <td>
                                <!-- Status badge with dynamic color based on status -->
                                <span
                                    class="badge bg-{{ $incident->status === 'open' ? 'danger' : ($incident->status === 'in_progress' ? 'warning' : ($incident->status === 'resolved' ? 'success' : 'secondary')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($incident->location, 30) ?: 'No location' }}</td>
                            <td>{{ $incident->reporter->name }}</td>
                            <td>{{ $incident->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <!-- Action buttons for each incident -->
                                <a href="{{ route('incidents.show', $incident->id) }}" class="btn btn-sm btn-info"
                                    title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('incidents.edit', $incident->id) }}" class="btn btn-sm btn-primary"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $incident->id }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <!-- Delete confirmation modal -->
                                <div class="modal fade" id="deleteModal{{ $incident->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $incident->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $incident->id }}">Confirm
                                                    Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this incident?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('incidents.destroy', $incident->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No incidents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination and results count -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="me-3">
                    Showing {{ $incidents->firstItem() }} to {{ $incidents->lastItem() }} of {{ $incidents->total() }} results
                </span>
            </div>
            <div>
                {{ $incidents->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
