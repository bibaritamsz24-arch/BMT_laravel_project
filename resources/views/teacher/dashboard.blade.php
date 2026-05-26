@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div
    id="profileAjaxManager"
    data-profile-url="{{ route('teacher.profile.show') }}"
    data-update-url="{{ route('teacher.profile.update') }}"
>
<div id="profileAjaxAlert" class="alert d-none mb-4" role="alert"></div>
<div class="dashboard-panel">
    <div class="dashboard-copy">
        <span class="dashboard-kicker">
            <i class="bi bi-person-workspace"></i>
            Role: teacher
        </span>
        <h1 class="dashboard-title">Welcome, {{ auth()->user()->name }}</h1>
        <p class="dashboard-text">
            Your teacher account is ready. You can now continue using the teacher dashboard.
        </p>
        <div class="d-flex gap-2 flex-wrap mt-4">
            <button type="button" class="btn btn-brand js-profile-view">
                <i class="bi bi-eye me-1"></i>
                View Profile
            </button>
            <button type="button" class="btn btn-outline-brand js-profile-edit">
                <i class="bi bi-pencil me-1"></i>
                Edit Profile
            </button>
        </div>
    </div>
    <div class="dashboard-visual" aria-hidden="true">
        <i class="bi bi-mortarboard-fill"></i>
    </div>
</div>

@include('partials.profile-ajax-modals')
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
@endpush
