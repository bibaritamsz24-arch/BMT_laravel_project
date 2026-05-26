@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card app-card">
            <div class="card-header py-3">
                <h1 class="h4 mb-0 fw-bold">Change Password</h1>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('password.update') }}" class="row g-4" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="col-12">
                        <label for="current_password" class="form-label fw-semibold">Current Password</label>
                        <input
                            type="password"
                            name="current_password"
                            id="current_password"
                            autocomplete="current-password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            required
                        >
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label fw-semibold">New Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="new-password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            autocomplete="new-password"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-brand px-4">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
