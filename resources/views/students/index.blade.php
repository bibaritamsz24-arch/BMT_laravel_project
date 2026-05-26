@extends('layouts.app')

@section('title', 'Students List')

@section('content')
<div
    id="ajaxAccountManager"
    data-list-type="students"
    data-index-url="{{ route('students.index') }}"
    data-student-store-url="{{ route('students.store') }}"
>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Students</h1>
        </div>
        <button type="button" class="btn btn-brand js-open-create" data-account-type="student">
            Add Student
        </button>
    </div>

    <div id="ajaxAlert" class="alert d-none mb-4" role="alert"></div>

    <div class="card app-card border-0">
        <div class="table-responsive">
            <table class="table table-theme table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Contact Number</th>
                        <th scope="col">Degree</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="accountRows">
                    @forelse ($students as $student)
                        <tr>
                            <td class="fw-semibold">{{ $student->full_name }}</td>
                            <td>{{ $student->userAccount->username }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->contact }}</td>
                            <td><span class="badge badge-soft">{{ $student->degree->title }}</span></td>
                            <td class="text-end text-secondary">Loading actions...</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">No students found.</td>
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
