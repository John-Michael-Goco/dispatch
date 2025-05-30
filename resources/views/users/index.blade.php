@extends('layouts.app')

@section('content')
    <!-- Main container for users list -->
    <div class="container">
        <!-- Header section with title and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-people"></i> Users</h1>
            <div class="d-flex gap-2">
                <!-- Search form for filtering users -->
                <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            placeholder="Search users..." value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                        @if (request('search'))
                            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm"><i
                                    class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>
                <!-- Create new user button -->
                <a href="{{ route('users.create') }}" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i>
                    Create User</a>
            </div>
        </div>

        <!-- Flash message alerts for success/error notifications -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Users table section -->
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <!-- Table header with column names -->
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th style="max-width: 220px;">Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through users and display their information -->
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->userInfo->name ?? $user->name }}</td>
                            <td>{{ $user->userInfo->email ?? $user->email }}</td>
                            <td>{{ $user->userInfo->phone_number ?? 'N/A' }}</td>
                            <td style="max-width: 220px; word-break: break-word; white-space: normal;">{{ $user->userInfo->address ?? 'N/A' }}</td>
                            <td>
                                <!-- Action buttons for each user -->
                                <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm"
                                    title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm"
                                    title="Edit"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $user->id }}" title="Delete"><i
                                        class="bi bi-trash"></i></button>
                                <!-- Delete confirmation modal -->
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Confirm
                                                    Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete the user
                                                <strong>{{ $user->userInfo->name ?? $user->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST"
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
                            <td colspan="5">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination and results count -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="me-3">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                </span>
            </div>
            <div>
                {{ $users->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection 