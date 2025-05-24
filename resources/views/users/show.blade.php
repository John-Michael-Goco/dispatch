@extends('layouts.app')

@section('content')
<h2 class="mb-4 ms-3" style="font-size:1.5rem;"><i class="bi bi-eye"></i> User Details</h2>
<div class="container" style="max-width: 1100px;">
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name:</label>
                        <div>{{ $user->userInfo->name ?? $user->name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email:</label>
                        <div>{{ $user->userInfo->email ?? $user->email }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number:</label>
                        <div>{{ $user->userInfo->phone_number ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date of Birth:</label>
                        <div>{{ $user->userInfo->date_of_birth ? $user->userInfo->date_of_birth->format('F j, Y') : 'N/A' }}</div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address:</label>
                        <div style="word-break: break-word; white-space: pre-line;">{{ $user->userInfo->address ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description:</label>
                        <div style="word-break: break-word; white-space: pre-line;">{{ $user->userInfo->description ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection 