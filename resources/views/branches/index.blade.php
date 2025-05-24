@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="font-size:1.5rem;"><i class="bi bi-diagram-3"></i> Branches</h1>
            <a href="{{ route('branches.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Create
                Branch</a>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
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
                    @forelse($branches as $branch)
                        <tr>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->service->name ?? '-' }}</td>
                            <td style="max-width: 220px; word-break: break-word; white-space: normal;">{{ $branch->address }}</td>
                            <td>{{ $branch->contact_number }}</td>
                            <td>
                                <span class="badge px-3 py-2 {{ $branch->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($branch->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('branches.show', $branch) }}" class="btn btn-info btn-sm"
                                    title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('branches.edit', $branch) }}" class="btn btn-primary btn-sm"
                                    title="Edit"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $branch->id }}" title="Delete"><i
                                        class="bi bi-trash"></i></button>
                                <!-- Delete Modal -->
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
    </div>
@endsection
