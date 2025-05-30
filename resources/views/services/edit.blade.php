@extends('layouts.app')

@section('content')
<!-- Main container for edit service form -->
<div class="container">
    <!-- Header section with title -->
    <h2 class="mb-4" style="font-size:1.5rem;"><i class="bi bi-pencil"></i> Edit Service</h2>
    <!-- Card container for the form -->
    <div class="card shadow mx-auto" style="max-width: 700px;">
        <div class="card-body">
            <!-- Service edit form with PUT method -->
            <form action="{{ route('services.update', $service) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Service name input field with existing value -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $service->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Service description textarea field with existing value -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $service->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Contact line input field with existing value -->
                <div class="mb-3">
                    <label for="contact_line" class="form-label">Contact Line</label>
                    <input type="text" class="form-control @error('contact_line') is-invalid @enderror" id="contact_line" name="contact_line" value="{{ old('contact_line', $service->contact_line) }}">
                    @error('contact_line')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Form action buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('services.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 