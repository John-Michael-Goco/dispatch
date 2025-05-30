@extends('layouts.app') 

@section('content')
<!-- Main container for create service form -->
<div class="container">
    <!-- Header section with title -->
    <h2 class="mb-4" style="font-size:1.5rem;"><i class="bi bi-plus-circle"></i> Create Service</h2>
    <!-- Card container for the form -->
    <div class="card shadow mx-auto" style="max-width: 700px;">
        <div class="card-body">
            <!-- Service creation form -->
            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                <!-- Service name input field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Service description textarea field -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Contact line input field -->
                <div class="mb-3">
                    <label for="contact_line" class="form-label">Contact Line</label>
                    <input type="text" class="form-control @error('contact_line') is-invalid @enderror" id="contact_line" name="contact_line" value="{{ old('contact_line') }}">
                    @error('contact_line')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Form action buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('services.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 