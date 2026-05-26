@extends('layouts.app')

@section('title', 'User Accounts')

@section('content')
<div
    id="ajaxAccountManager"
    data-index-url="{{ route('admin.dashboard') }}"
    data-student-store-url="{{ route('students.store') }}"
    data-teacher-store-url="{{ route('teachers.store') }}"
>
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">User Accounts</h1>
            <p class="text-secondary mb-0">Manage admin, teacher, and student accounts with AJAX modals.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-brand js-open-create" data-account-type="student">
                <i class="bi bi-person-plus-fill me-1"></i>
                Add Student
            </button>
            <button type="button" class="btn btn-brand js-open-create" data-account-type="teacher">
                <i class="bi bi-person-workspace me-1"></i>
                Add Teacher
            </button>
            <a href="{{ route('degrees.create') }}" class="btn btn-brand">
                <i class="bi bi-journal-plus me-1"></i>
                Add Degree
            </a>
        </div>
    </div>

    <div id="ajaxAlert" class="alert d-none mb-4" role="alert"></div>

    <div class="card app-card">
        <div class="table-responsive">
            <table class="table table-theme align-middle mb-0">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="accountRows">
                    @foreach ($accounts as $account)
                        <tr data-account-type="{{ $account['type'] }}" data-account-id="{{ $account['id'] }}">
                            <td class="fw-semibold">{{ $account['full_name'] }}</td>
                            <td>{{ $account['username'] }}</td>
                            <td>{{ $account['email'] }}</td>
                            <td>{{ $account['contact'] }}</td>
                            <td><span class="badge badge-soft">{{ ucfirst($account['role']) }}</span></td>
                            <td>
                                <span class="badge {{ $account['status'] === 'Active' ? 'badge-soft' : 'text-bg-secondary' }}">
                                    {{ $account['status'] }}
                                </span>
                            </td>
                            <td class="text-end text-secondary">Loading actions...</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="accountDetailsModal" tabindex="-1" aria-labelledby="accountDetailsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="accountDetailsTitle">Account Details</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-0" id="accountDetailsList"></dl>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="accountFormModal" tabindex="-1" aria-labelledby="accountFormTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="accountForm" novalidate>
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="accountFormTitle">Account Form</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="account_type" id="account_type">
                        <input type="hidden" name="action_url" id="action_url">
                        <input type="hidden" name="form_method" id="form_method" value="POST">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="contact" class="form-label">Contact Number</label>
                                <input type="text" name="contact" id="contact" class="form-control" inputmode="numeric" maxlength="11" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 js-student-field">
                                <label for="degree_id" class="form-label">Degree</label>
                                <select name="degree_id" id="degree_id" class="form-select">
                                    <option value="">Select degree</option>
                                    @foreach ($degrees as $degree)
                                        <option value="{{ $degree->id }}">{{ $degree->title }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" id="address" rows="3" class="form-control" required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                                <div class="form-text js-password-help">Required when creating a new account.</div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-brand" id="accountFormSubmit">Save Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="deleteAccountTitle">Delete Account</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Delete <strong id="deleteAccountName"></strong>?</p>
                    <input type="hidden" id="deleteAccountUrl">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteAccount">Delete</button>
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
