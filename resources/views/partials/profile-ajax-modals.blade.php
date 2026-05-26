<div class="modal fade" id="profileDetailsModal" tabindex="-1" aria-labelledby="profileDetailsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="profileDetailsTitle">My Profile</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0" id="profileDetailsList"></dl>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileFormModal" tabindex="-1" aria-labelledby="profileFormTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="profileForm" novalidate>
                <div class="modal-header">
                    <h2 class="modal-title h5" id="profileFormTitle">Edit My Profile</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="profile_first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="profile_first_name" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="profile_middle_name" class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" id="profile_middle_name" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="profile_last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="profile_last_name" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="profile_username" class="form-label">Username</label>
                            <input type="text" name="username" id="profile_username" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="profile_email" class="form-label">Email</label>
                            <input type="email" name="email" id="profile_email" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="profile_contact" class="form-label">Contact Number</label>
                            <input type="text" name="contact" id="profile_contact" class="form-control" inputmode="numeric" maxlength="11" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label for="profile_address" class="form-label">Address</label>
                            <textarea name="address" id="profile_address" rows="3" class="form-control" required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="profile_password" class="form-label">Password</label>
                            <input type="password" name="password" id="profile_password" class="form-control" autocomplete="new-password">
                            <div class="form-text">Leave blank to keep the current password.</div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="profile_password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="profile_password_confirmation" class="form-control" autocomplete="new-password">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand btn-student" id="profileFormSubmit">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
