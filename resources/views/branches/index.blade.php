@extends('layouts.app')

@section('content')
    <!-- Main container for branches list -->
    <div class="container">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-diagram-3"></i> Branches</h1>
            <div class="d-flex gap-2">
                <!-- Filter and search form -->
                <form action="{{ route('branches.index') }}" method="GET" class="d-flex gap-2">
                    <!-- Service filter dropdown -->
                    <select class="form-select form-select-sm" name="service_id" style="width: 200px;">
                        <option value="">All Services</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"
                                {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Search input and buttons -->
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search branches..." value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                        @if (request('search') || request('service_id'))
                            <a href="{{ route('branches.index') }}" class="btn btn-secondary btn-sm"><i
                                    class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>
                <!-- Create new branch button -->
                <a href="{{ route('branches.create') }}" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i>
                    Create Branch</a>
            </div>
        </div>

        <!-- Flash message for success notifications -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Branches table section -->
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <!-- Table header with column names -->
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Service</th>
                        <th style="max-width: 220px;">Address</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through branches and display their information -->
                    @forelse($branches as $branch)
                        <tr>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->service->name ?? '-' }}</td>
                            <td style="max-width: 220px; word-break: break-word; white-space: normal;">
                                {{ $branch->address }}</td>
                            <td>{{ $branch->contact_number }}</td>
                            <td>
                                <!-- Status badge with dynamic color -->
                                <span
                                    class="badge px-3 py-2 {{ $branch->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($branch->status) }}
                                </span>
                            </td>
                            <td>
                                <!-- Action buttons for each branch -->
                                <a href="{{ route('branches.show', $branch) }}" class="btn btn-info btn-sm"
                                    title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('branches.edit', $branch) }}" class="btn btn-primary btn-sm"
                                    title="Edit"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $branch->id }}" title="Delete"><i
                                        class="bi bi-trash"></i></button>
                                <!-- Delete confirmation modal -->
                                <div class="modal fade" id="deleteModal{{ $branch->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $branch->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $branch->id }}">Confirm
                                                    Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete the branch
                                                <strong>{{ $branch->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('branches.destroy', $branch) }}" method="POST"
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
                            <td colspan="7">No branches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination and results count -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="me-3">
                    Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} of {{ $branches->total() }}
                    results
                </span>
            </div>
            <div>
                {{ $branches->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
