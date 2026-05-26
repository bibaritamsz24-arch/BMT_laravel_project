@extends('layouts.app')

@section('title', 'Edit Degree')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7 col-xl-6">
        <div class="card app-card border-0">
            <div class="card-header py-3">
                <h1 class="h4 mb-0 fw-bold">Edit Degree</h1>
            </div>
            <div class="card-body p-4 p-md-5 bg-white">
                <form action="{{ route('degrees.update', $degree) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">Degree Title</label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title', $degree->title) }}"
                            class="form-control @error('title') is-invalid @enderror"
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-brand px-4">Save Changes</button>
                        <a href="{{ route('degrees.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
