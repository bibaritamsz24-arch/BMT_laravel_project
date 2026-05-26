@extends('layouts.student-portal')

@section('title', 'Student Dashboard')

@section('content')
<div
    id="profileAjaxManager"
    data-profile-url="{{ route('student.profile.show') }}"
    data-update-url="{{ route('student.profile.update') }}"
>
<div id="profileAjaxAlert" class="alert d-none mb-4" role="alert"></div>
<div class="student-dashboard-panel">
    <div class="student-dashboard-copy">
        <span class="hero-chip mb-3">
            <i class="bi bi-stars"></i>
            Role: student
        </span>
        <h1 class="student-dashboard-title">Welcome, {{ $studentAccount->student?->full_name ?: $studentAccount->username }}</h1>
        <p class="student-dashboard-text">
            Your student account is ready. You can now view your student information and continue using the student portal.
        </p>
        <div class="d-flex gap-2 flex-wrap mt-4">
            <button type="button" class="btn btn-student js-profile-view">
                <i class="bi bi-eye me-1"></i>
                View Profile
            </button>
            <button type="button" class="btn btn-outline-danger js-profile-edit">
                <i class="bi bi-pencil me-1"></i>
                Edit Profile
            </button>
        </div>
    </div>
    <div class="student-dashboard-visual" aria-hidden="true">
        <i class="bi bi-person-badge-fill"></i>
    </div>
</div>

@include('partials.profile-ajax-modals')
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
@endpush
