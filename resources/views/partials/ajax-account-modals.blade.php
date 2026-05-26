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
                                @foreach (($degrees ?? collect()) as $degree)
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
