@extends('layouts.student-portal')

@section('title', 'Student Change Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-6">
        <div class="portal-card p-4 p-lg-5">
            <div class="mb-4">
                <span class="hero-chip mb-3">
                    <i class="bi bi-shield-lock-fill"></i>
                    Change Password First
                </span>
                <h1 class="h3 fw-bold mb-2">Update your student password</h1>
                <p class="text-secondary mb-0">Enter your current password first, then type and confirm your new password.</p>
            </div>

            <form method="POST" action="{{ route('student.password.update') }}" class="row g-4" novalidate>
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

                <div class="col-12">
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

                <div class="col-12">
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

                @if ($errors->any())
                    <div class="col-12">
                        <div class="alert alert-danger mb-0" role="alert">
                            <strong>Please fix the following:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="col-12 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-student px-4">
                        <i class="bi bi-key-fill me-1"></i>
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @unless ((bool) session('student_password_changed', false))
        <script>
            (() => {
                const lockedUrl = @json(route('student.password.edit'));
                const lockedState = { studentPasswordLocked: true };

                window.history.replaceState(lockedState, '', lockedUrl);
                window.history.pushState(lockedState, '', lockedUrl);

                window.addEventListener('popstate', () => {
                    window.history.pushState(lockedState, '', lockedUrl);
                });
            })();
        </script>
    @endunless
@endpush
