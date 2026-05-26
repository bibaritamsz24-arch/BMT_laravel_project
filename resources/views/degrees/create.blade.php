@extends('layouts.app')

@section('title', 'Create Degree')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7 col-xl-6">
        <div class="card app-card border-0">
            <div class="card-header py-3">
                <h1 class="h4 mb-0 fw-bold">Add New Degree</h1>
            </div>
            <div class="card-body p-4 p-md-5 bg-white">
                <form action="{{ route('degrees.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">Degree Title</label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="e.g. Bachelor of Science in Information Technology"
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-brand px-4">Create Degree</button>
                        <a href="{{ route('degrees.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
