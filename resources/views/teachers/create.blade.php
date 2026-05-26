@extends('layouts.app')

@section('title', 'Add Teacher')

@push('styles')
    <style>
        .teacher-create-card {
            min-height: 54.5rem;
        }
    </style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card app-card border-0 teacher-create-card">
            <div class="card-header py-3">
                <h1 class="h4 mb-0 fw-bold">Add New Teacher</h1>
            </div>
            <div class="card-body p-4 p-md-5 bg-white">
                <form action="{{ route('teachers.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label for="first_name" class="form-label fw-semibold">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', old('name')) }}" class="form-control @error('first_name') is-invalid @enderror" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="form-control @error('middle_name') is-invalid @enderror">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="last_name" class="form-label fw-semibold">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold">Address</label>
                            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="contact" class="form-label fw-semibold">Contact Number</label>
                            <input type="text" name="contact" id="contact" value="{{ old('contact') }}" class="form-control @error('contact') is-invalid @enderror" inputmode="numeric" maxlength="11" required>
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="border rounded-4 p-3 bg-light">
                                <h2 class="h6 fw-bold mb-3">Teacher Login Account</h2>
                                <p class="text-secondary small mb-3">Set the teacher's username and password here. The teacher will use these credentials on the main login page.</p>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="username" class="form-label fw-semibold">Username</label>
                                        <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="password" class="form-label fw-semibold">Password</label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-brand px-4">Save Teacher</button>
                        <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-4 mb-0">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
