@extends('layouts.app')

@section('content')
    <!-- Main container for services list -->
    <div class="container">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-gear"></i> Services</h1>
            <div class="d-flex gap-2">
                <!-- Search form for filtering services -->
                <form action="{{ route('services.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search services..." value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                        @if (request('search'))
                            <a href="{{ route('services.index') }}" class="btn btn-secondary btn-sm"><i
                                    class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>
                <!-- Create new service button -->
                <a href="{{ route('services.create') }}" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i>
                    Create Service</a>
            </div>
        </div>

        <!-- Flash message for success notifications -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Services table section -->
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <!-- Table header with column names -->
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Contact Line</th>
                        <th>Branches</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through services and display their information -->
                    @forelse($services as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->description }}</td>
                            <td>{{ $service->contact_line }}</td>
                            <td>{{ $service->branches->count() }}</td>
                            <td>
                                <!-- Action buttons for each service -->
                                <a href="{{ route('services.show', $service) }}" class="btn btn-info btn-sm"
                                    title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('services.edit', $service) }}" class="btn btn-primary btn-sm"
                                    title="Edit"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $service->id }}" title="Delete"><i
                                        class="bi bi-trash"></i></button>
                                <!-- Delete confirmation modal -->
                                <div class="modal fade" id="deleteModal{{ $service->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $service->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $service->id }}">Confirm
                                                    Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete the service
                                                <strong>{{ $service->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('services.destroy', $service) }}" method="POST"
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
                            <td colspan="5" class="text-center py-4">
                                @if (request('search'))
                                    No services found matching your search criteria.
                                @else
                                    No services found. Click the "Create Service" button to add one.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination and results count -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="me-3">
                    Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }}
                    results
                </span>
            </div>
            <div>
                {{ $services->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
