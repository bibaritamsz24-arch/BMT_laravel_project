@extends('layouts.app')

@section('title', 'Teachers')

@section('content')
<div
    id="ajaxAccountManager"
    data-list-type="teachers"
    data-index-url="{{ route('teachers.index') }}"
    data-teacher-store-url="{{ route('teachers.store') }}"
>
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Teachers</h1>
            <p class="text-secondary mb-0">Manage teacher login accounts with AJAX modals.</p>
        </div>
        <button type="button" class="btn btn-brand js-open-create" data-account-type="teacher">
            <i class="bi bi-person-plus-fill me-1"></i>
            Add Teacher
        </button>
    </div>

    <div id="ajaxAlert" class="alert d-none mb-4" role="alert"></div>

    <div class="card app-card">
        <div class="table-responsive">
            <table class="table table-theme align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="accountRows">
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td class="fw-semibold">{{ $teacher->name }}</td>
                            <td>{{ $teacher->username ?: str($teacher->email)->before('@') }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->contact ?: 'Not set' }}</td>
                            <td><span class="badge badge-soft">{{ $teacher->role }}</span></td>
                            <td><span class="badge badge-soft">Active</span></td>
                            <td class="text-end text-secondary">Loading actions...</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-5">No teachers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.ajax-account-modals')
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
@endpush
