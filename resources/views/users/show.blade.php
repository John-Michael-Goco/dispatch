@extends('layouts.app')

@section('content')
<!-- User details view header -->
<h2 class="mb-4 ms-3" style="font-size:1.5rem;"><i class="bi bi-eye"></i> User Details</h2>
<!-- Main container for user details -->
<div class="container" style="max-width: 1100px;">
    <!-- Card container -->
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <!-- Left column: Basic user information -->
                <div class="col-md-5">
                    <!-- Name display -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name:</label>
                        <div>{{ $user->userInfo->name ?? $user->name }}</div>
                    </div>
                    <!-- Email display -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email:</label>
                        <div>{{ $user->userInfo->email ?? $user->email }}</div>
                    </div>
                    <!-- Phone number display -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number:</label>
                        <div>{{ $user->userInfo->phone_number ?? 'N/A' }}</div>
                    </div>
                    <!-- Date of birth display with formatting -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date of Birth:</label>
                        <div>{{ $user->userInfo->date_of_birth ? $user->userInfo->date_of_birth->format('F j, Y') : 'N/A' }}</div>
                    </div>
                </div>
                <!-- Right column: Additional user information -->
                <div class="col-md-7">
                    <!-- Address display with word wrapping -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address:</label>
                        <div style="word-break: break-word; white-space: pre-line;">{{ $user->userInfo->address ?? 'N/A' }}</div>
                    </div>
                    <!-- Description display with word wrapping -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description:</label>
                        <div style="word-break: break-word; white-space: pre-line;">{{ $user->userInfo->description ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            <!-- Action buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection 