@extends('layouts.app')

@section('title', 'Degrees List')

@section('content')
<div
    id="ajaxDegreeManager"
    data-index-url="{{ route('degrees.index') }}"
    data-store-url="{{ route('degrees.store') }}"
>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Degrees</h1>
            <p class="text-secondary mb-0">Organize degree programs with AJAX modals.</p>
        </div>
        <button type="button" class="btn btn-brand js-degree-create">
            Add Degree
        </button>
    </div>

    <div id="degreeAjaxAlert" class="alert d-none mb-4" role="alert"></div>

    <div class="card app-card border-0">
        <div class="table-responsive">
            <table class="table table-theme table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Students</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="degreeRows">
                    @forelse ($degrees as $degree)
                        <tr>
                            <td class="fw-semibold">{{ $degree->title }}</td>
                            <td><span class="badge badge-soft">{{ $degree->students_count }}</span></td>
                            <td class="text-end text-secondary">Loading actions...</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-secondary py-5">No degrees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="degreeDetailsModal" tabindex="-1" aria-labelledby="degreeDetailsTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="degreeDetailsTitle">Degree Details</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-4" id="degreeDetailsList"></dl>
                    <h3 class="h6 fw-bold mb-3">Enrolled Students</h3>
                    <div class="table-responsive">
                        <table class="table table-theme align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>
                            <tbody id="degreeStudentsRows"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="degreeFormModal" tabindex="-1" aria-labelledby="degreeFormTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="degreeForm" novalidate>
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="degreeFormTitle">Degree Form</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="degree_action_url">
                        <input type="hidden" id="degree_form_method" value="POST">

                        <label for="degree_title" class="form-label fw-semibold">Degree Title</label>
                        <input
                            type="text"
                            name="title"
                            id="degree_title"
                            class="form-control"
                            placeholder="e.g. Bachelor of Science in Information Technology"
                            required
                        >
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-brand" id="degreeFormSubmit">Save Degree</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteDegreeModal" tabindex="-1" aria-labelledby="deleteDegreeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="deleteDegreeTitle">Delete Degree</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Delete <strong id="deleteDegreeName"></strong>?</p>
                    <input type="hidden" id="deleteDegreeUrl">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteDegree">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
@endpush
