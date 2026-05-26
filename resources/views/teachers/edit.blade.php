@extends('layouts.app')

@section('title', 'Edit ' . $teacher->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card app-card">
            <div class="card-header py-3">
                <h1 class="h4 mb-0 fw-bold">Edit Teacher</h1>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('teachers.update', $teacher) }}" method="POST" class="row g-4" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="col-12 col-md-4">
                        <label for="first_name" class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $teacher->first_name ?: $teacher->name) }}" class="form-control @error('first_name') is-invalid @enderror" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $teacher->middle_name) }}" class="form-control @error('middle_name') is-invalid @enderror">
                        @error('middle_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="last_name" class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $teacher->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $teacher->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="username" class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $teacher->username ?: str($teacher->email)->before('@')) }}" class="form-control @error('username') is-invalid @enderror" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="contact" class="form-label fw-semibold">Contact Number</label>
                        <input type="text" name="contact" id="contact" value="{{ old('contact', $teacher->contact) }}" class="form-control @error('contact') is-invalid @enderror" inputmode="numeric" maxlength="11" required>
                        @error('contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $teacher->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label fw-semibold">New Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-brand px-4">Update Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
