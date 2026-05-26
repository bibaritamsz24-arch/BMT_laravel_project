(function ($) {
    'use strict';

    const manager = $('#ajaxAccountManager');
    const profileManager = $('#profileAjaxManager');
    const degreeManager = $('#ajaxDegreeManager');
    const tabSyncKey = 'bmtproject-data-sync';
    const tabSyncId = Date.now().toString(36) + Math.random().toString(36).slice(2);
    const tabSyncChannel = window.BroadcastChannel ? new BroadcastChannel(tabSyncKey) : null;
    const tabSyncListeners = [];
    const tabSyncSeen = {};

    setupTabSync();

    if (!manager.length && !profileManager.length && !degreeManager.length) {
        return;
    }

    if (!manager.length) {
        if (degreeManager.length) {
            initDegreeManager(degreeManager);
            return;
        }

        initProfileManager(profileManager);
        return;
    }

    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const accountRows = $('#accountRows');
    const alertBox = $('#ajaxAlert');
    const form = $('#accountForm');
    const pageLoadedWithSuccess = $('.alert-success').not(alertBox).length > 0;
    moveModalsToBody('#ajaxAccountManager .modal');
    const formModal = new bootstrap.Modal(document.getElementById('accountFormModal'));
    const detailsModal = new bootstrap.Modal(document.getElementById('accountDetailsModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
    const accountsByKey = {};
    const listType = manager.data('list-type') || 'accounts';
    const accountColumnCount = listType === 'students' ? 6 : 7;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
        },
    });

    loadAccounts();

    if (pageLoadedWithSuccess) {
        notifyTabs('all');
    }

    $('.js-open-create').on('click', function () {
        const type = $(this).data('account-type');
        resetForm();
        configureForm(type, 'create');
        formModal.show();
    });

    accountRows.on('click', '.js-view-account', function () {
        const account = getAccount($(this));

        if (!account.view_url) {
            showDetails(account);
            return;
        }

        $.get(account.view_url)
            .done(function (response) {
                showDetails(response.student || response.teacher || account);
            })
            .fail(function () {
                showAlert('Unable to load account details.', 'danger');
            });
    });

    accountRows.on('click', '.js-edit-account', function () {
        const account = getAccount($(this));

        if (!account.edit_url) {
            showAlert('This account cannot be edited here.', 'warning');
            return;
        }

        $.get(account.edit_url)
            .done(function (response) {
                const record = response.student || response.teacher || account;
                resetForm();
                configureForm(record.type, 'edit', record);
                fillForm(record);
                formModal.show();
            })
            .fail(function () {
                showAlert('Unable to load the edit form data.', 'danger');
            });
    });

    accountRows.on('click', '.js-delete-account', function () {
        const account = getAccount($(this));

        if (!account.delete_url) {
            showAlert('This account cannot be deleted here.', 'warning');
            return;
        }

        $('#deleteAccountName').text(account.full_name);
        $('#deleteAccountUrl').val(account.delete_url);
        deleteModal.show();
    });

    $('#confirmDeleteAccount').on('click', function () {
        const button = $(this);
        const url = $('#deleteAccountUrl').val();

        button.prop('disabled', true).text('Deleting...');

        $.ajax({
            url: url,
            method: 'POST',
            data: { _method: 'DELETE' },
        })
            .done(function (response) {
                deleteModal.hide();
                showAlert(response.message || successMessage('deleted'), 'success');
                loadAccounts();
                notifyTabs('accounts');
            })
            .fail(function () {
                showAlert('Unable to delete the account.', 'danger');
            })
            .always(function () {
                button.prop('disabled', false).text('Delete');
            });
    });

    form.on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        const submitButton = $('#accountFormSubmit');
        const url = $('#action_url').val();
        const method = $('#form_method').val();
        const data = form.serializeArray();

        if (method !== 'POST') {
            data.push({ name: '_method', value: method });
        }

        submitButton.prop('disabled', true).text('Saving...');

        $.ajax({
            url: url,
            method: 'POST',
            data: $.param(data),
        })
            .done(function (response) {
                formModal.hide();
                showAlert(response.message || successMessage(method === 'POST' ? 'added' : 'updated'), 'success');
                loadAccounts();
                notifyTabs('accounts');
            })
            .fail(function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    showErrors(xhr.responseJSON.errors);
                    return;
                }

                showAlert('Unable to save the account.', 'danger');
            })
            .always(function () {
                submitButton.prop('disabled', false).text('Save Account');
            });
    });

    function loadAccounts() {
        accountRows.html(`<tr><td colspan="${accountColumnCount}" class="text-center text-secondary py-5">Loading accounts...</td></tr>`);

        $.get(manager.data('index-url'))
            .done(function (response) {
                renderAccounts(response.accounts || response.students || response.teachers || []);
            })
            .fail(function () {
                accountRows.html(`<tr><td colspan="${accountColumnCount}" class="text-center text-danger py-5">Unable to load accounts.</td></tr>`);
            });
    }

    onTabSync(function (payload) {
        if (payload.area === 'accounts' || payload.area === 'degrees' || payload.area === 'profile' || payload.area === 'all') {
            loadAccounts();
        }
    });

    function renderAccounts(accounts) {
        Object.keys(accountsByKey).forEach(function (key) {
            delete accountsByKey[key];
        });

        if (!accounts.length) {
            accountRows.html('<tr><td colspan="7" class="text-center text-secondary py-5">No user accounts found.</td></tr>');
            return;
        }

        const rows = accounts.map(function (account) {
            const key = account.type + ':' + account.id;
            accountsByKey[key] = account;

            if (listType === 'students') {
                return `
                    <tr data-account-key="${escapeHtml(key)}">
                        <td class="fw-semibold">${escapeHtml(account.full_name || '')}</td>
                        <td>${escapeHtml(account.username || '')}</td>
                        <td>${escapeHtml(account.email || '')}</td>
                        <td>${escapeHtml(account.contact || 'Not set')}</td>
                        <td><span class="badge badge-soft">${escapeHtml(account.degree || 'No degree assigned')}</span></td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                ${actionButtons(account)}
                            </div>
                        </td>
                    </tr>
                `;
            }

            return `
                <tr data-account-key="${escapeHtml(key)}">
                    <td class="fw-semibold">${escapeHtml(account.full_name || '')}</td>
                    <td>${escapeHtml(account.username || '')}</td>
                    <td>${escapeHtml(account.email || '')}</td>
                    <td>${escapeHtml(account.contact || 'Not set')}</td>
                    <td><span class="badge badge-soft">${escapeHtml(titleCase(account.role || ''))}</span></td>
                    <td><span class="badge ${account.status === 'Active' ? 'badge-soft' : 'text-bg-secondary'}">${escapeHtml(account.status || '')}</span></td>
                    <td>
                        <div class="d-flex justify-content-end gap-2">
                            ${actionButtons(account)}
                        </div>
                    </td>
                </tr>
            `;
        });

        accountRows.html(rows.join(''));
    }

    function actionButtons(account) {
        return `
            <button type="button" class="btn btn-sm btn-outline-brand js-view-account" title="View" aria-label="View">
                <i class="bi bi-eye"></i>
            </button>
            ${account.edit_url ? `
                <button type="button" class="btn btn-sm btn-outline-secondary js-edit-account" title="Edit" aria-label="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
            ` : `
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit not available" aria-label="Edit not available" disabled>
                    <i class="bi bi-pencil"></i>
                </button>
            `}
            ${account.delete_url ? `
                <button type="button" class="btn btn-sm btn-outline-danger js-delete-account" title="Delete" aria-label="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            ` : `
                <button type="button" class="btn btn-sm btn-outline-danger" title="Delete not available here" aria-label="Delete not available here" disabled>
                    <i class="bi bi-trash"></i>
                </button>
            `}
        `;
    }

    function configureForm(type, mode, account) {
        const isStudent = type === 'student';
        const isCreate = mode === 'create';
        const title = (isCreate ? 'Add ' : 'Edit ') + titleCase(type);

        $('#accountFormTitle').text(title);
        $('#account_type').val(type);
        $('#form_method').val(isCreate ? 'POST' : 'PUT');
        $('#action_url').val(isCreate ? manager.data(type + '-store-url') : account.update_url);
        $('#accountFormSubmit').text(isCreate ? 'Create Account' : 'Update Account');
        $('.js-student-field').toggleClass('d-none', !isStudent);
        $('#degree_id').prop('disabled', !isStudent);
        $('.js-password-help').text(isCreate ? 'Required when creating a new account.' : 'Leave blank to keep the current password.');
    }

    function fillForm(account) {
        $('#first_name').val(account.first_name || '');
        $('#middle_name').val(account.middle_name || '');
        $('#last_name').val(account.last_name || '');
        $('#username').val(account.username || '');
        $('#email').val(account.email || '');
        $('#contact').val(account.contact === 'Not set' ? '' : account.contact || '');
        $('#address').val(account.address === 'Not set' ? '' : account.address || '');
        $('#degree_id').val(account.degree_id || '');
    }

    function resetForm() {
        form[0].reset();
        clearErrors();
        $('#password').val('');
        $('#password_confirmation').val('');
    }

    function showDetails(account) {
        const fields = [
            ['Full Name', account.full_name],
            ['Username', account.username],
            ['Email', account.email],
            ['Contact Number', account.contact],
            ['Address', account.address],
            ['Degree', account.degree],
            ['Role', titleCase(account.role || account.type || '')],
            ['Status', account.status],
        ].filter(function (field) {
            return field[1] !== undefined && field[1] !== null && field[1] !== '';
        });

        $('#accountDetailsTitle').text((account.full_name || 'Account') + ' Details');
        $('#accountDetailsList').html(fields.map(function (field) {
            return `
                <dt class="col-sm-4 text-secondary">${escapeHtml(field[0])}</dt>
                <dd class="col-sm-8 fw-semibold">${escapeHtml(field[1])}</dd>
            `;
        }).join(''));
        detailsModal.show();
    }

    function getAccount(button) {
        const key = button.closest('tr').data('account-key');

        return accountsByKey[key] || {};
    }

    function showErrors(errors) {
        Object.keys(errors).forEach(function (field) {
            const input = form.find(`[name="${field}"]`);
            input.addClass('is-invalid');
            input.siblings('.invalid-feedback').first().text(errors[field][0]);
        });
    }

    function clearErrors() {
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');
    }

    function showAlert(message, type) {
        alertBox
            .removeClass('d-none alert-success alert-danger alert-warning')
            .addClass('alert-' + type)
            .text(message);
    }

    function successMessage(action) {
        return (listType === 'students' ? 'Student' : 'Account') + ' ' + action + ' successfully.';
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function titleCase(value) {
        value = String(value);

        return value.charAt(0).toUpperCase() + value.slice(1);
    }

    function moveModalsToBody(selector) {
        $(selector).each(function () {
            if (this.parentElement !== document.body) {
                document.body.appendChild(this);
            }
        });
    }

    function initProfileManager(profileManager) {
        moveModalsToBody('#profileAjaxManager .modal');
        const profileDetailsModal = new bootstrap.Modal(document.getElementById('profileDetailsModal'));
        const profileFormModal = new bootstrap.Modal(document.getElementById('profileFormModal'));
        const profileForm = $('#profileForm');
        const profileAlert = $('#profileAjaxAlert');
        let currentProfile = null;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                Accept: 'application/json',
            },
        });

        $('.js-profile-view').on('click', function () {
            loadProfile(function (profile) {
                showProfileDetails(profile);
                profileDetailsModal.show();
            });
        });

        $('.js-profile-edit').on('click', function () {
            loadProfile(function (profile) {
                fillProfileForm(profile);
                profileFormModal.show();
            });
        });

        profileForm.on('submit', function (event) {
            event.preventDefault();
            clearProfileErrors();

            const submitButton = $('#profileFormSubmit');
            submitButton.prop('disabled', true).text('Updating...');

            $.ajax({
                url: profileManager.data('update-url'),
                method: 'POST',
                data: profileForm.serialize() + '&_method=PUT',
            })
                .done(function (response) {
                    currentProfile = response.account || currentProfile;
                    profileFormModal.hide();
                    showProfileAlert(response.message || 'Profile updated successfully.', 'success');
                    notifyTabs('profile');
                })
                .fail(function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        showProfileErrors(xhr.responseJSON.errors);
                        return;
                    }

                    showProfileAlert('Unable to update the profile.', 'danger');
                })
                .always(function () {
                    submitButton.prop('disabled', false).text('Update Profile');
                });
        });

        function loadProfile(callback) {
            $.get(profileManager.data('profile-url'))
                .done(function (response) {
                    currentProfile = response.student || response.teacher || response.account;
                    callback(currentProfile);
                })
                .fail(function () {
                    showProfileAlert('Unable to load the profile.', 'danger');
                });
        }

        function showProfileDetails(profile) {
            const fields = [
                ['Full Name', profile.full_name],
                ['Username', profile.username],
                ['Email', profile.email],
                ['Contact Number', profile.contact],
                ['Address', profile.address],
                ['Degree', profile.degree],
                ['Role', titleCase(profile.role || profile.type || '')],
                ['Status', profile.status],
            ].filter(function (field) {
                return field[1] !== undefined && field[1] !== null && field[1] !== '';
            });

            $('#profileDetailsTitle').text((profile.full_name || 'My Profile') + ' Details');
            $('#profileDetailsList').html(fields.map(function (field) {
                return `
                    <dt class="col-sm-4 text-secondary">${escapeHtml(field[0])}</dt>
                    <dd class="col-sm-8 fw-semibold">${escapeHtml(field[1])}</dd>
                `;
            }).join(''));
        }

        function fillProfileForm(profile) {
            clearProfileErrors();
            $('#profile_first_name').val(profile.first_name || '');
            $('#profile_middle_name').val(profile.middle_name || '');
            $('#profile_last_name').val(profile.last_name || '');
            $('#profile_username').val(profile.username || '');
            $('#profile_email').val(profile.email || '');
            $('#profile_contact').val(profile.contact === 'Not set' ? '' : profile.contact || '');
            $('#profile_address').val(profile.address === 'Not set' ? '' : profile.address || '');
            $('#profile_password').val('');
            $('#profile_password_confirmation').val('');
        }

        function showProfileErrors(errors) {
            Object.keys(errors).forEach(function (field) {
                const input = profileForm.find(`[name="${field}"]`);
                input.addClass('is-invalid');
                input.siblings('.invalid-feedback').first().text(errors[field][0]);
            });
        }

        function clearProfileErrors() {
            profileForm.find('.is-invalid').removeClass('is-invalid');
            profileForm.find('.invalid-feedback').text('');
        }

        function showProfileAlert(message, type) {
            profileAlert
                .removeClass('d-none alert-success alert-danger alert-warning')
                .addClass('alert-' + type)
                .text(message);
        }

        onTabSync(function (payload) {
            if (payload.area === 'accounts' || payload.area === 'profile' || payload.area === 'all') {
                loadProfile(function (profile) {
                    currentProfile = profile;
                });
            }
        });
    }

    function initDegreeManager(degreeManager) {
        const degreeRows = $('#degreeRows');
        const degreeAlert = $('#degreeAjaxAlert');
        const degreeForm = $('#degreeForm');
        moveModalsToBody('#ajaxDegreeManager .modal');
        const degreeDetailsModal = new bootstrap.Modal(document.getElementById('degreeDetailsModal'));
        const degreeFormModal = new bootstrap.Modal(document.getElementById('degreeFormModal'));
        const deleteDegreeModal = new bootstrap.Modal(document.getElementById('deleteDegreeModal'));
        const degreesById = {};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                Accept: 'application/json',
            },
        });

        loadDegrees();

        $('.js-degree-create').on('click', function () {
            resetDegreeForm();
            $('#degreeFormTitle').text('Add Degree');
            $('#degree_action_url').val(degreeManager.data('store-url'));
            $('#degree_form_method').val('POST');
            $('#degreeFormSubmit').text('Create Degree');
            degreeFormModal.show();
        });

        degreeRows.on('click', '.js-degree-view', function () {
            const degree = getDegree($(this));

            $.get(degree.view_url)
                .done(function (response) {
                    showDegreeDetails(response.degree || degree);
                })
                .fail(function () {
                    showDegreeAlert('Unable to load degree details.', 'danger');
                });
        });

        degreeRows.on('click', '.js-degree-edit', function () {
            const degree = getDegree($(this));

            $.get(degree.edit_url)
                .done(function (response) {
                    const record = response.degree || degree;
                    resetDegreeForm();
                    $('#degreeFormTitle').text('Edit Degree');
                    $('#degree_action_url').val(record.update_url);
                    $('#degree_form_method').val('PUT');
                    $('#degree_title').val(record.title || '');
                    $('#degreeFormSubmit').text('Update Degree');
                    degreeFormModal.show();
                })
                .fail(function () {
                    showDegreeAlert('Unable to load the degree edit data.', 'danger');
                });
        });

        degreeRows.on('click', '.js-degree-delete', function () {
            const degree = getDegree($(this));
            $('#deleteDegreeName').text(degree.title);
            $('#deleteDegreeUrl').val(degree.delete_url);
            deleteDegreeModal.show();
        });

        $('#confirmDeleteDegree').on('click', function () {
            const button = $(this);
            button.prop('disabled', true).text('Deleting...');

            $.ajax({
                url: $('#deleteDegreeUrl').val(),
                method: 'POST',
                data: { _method: 'DELETE' },
            })
                .done(function (response) {
                    deleteDegreeModal.hide();
                    showDegreeAlert(response.message || 'Degree deleted successfully.', 'success');
                    loadDegrees();
                    notifyTabs('degrees');
                })
                .fail(function (xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Unable to delete the degree.';
                    showDegreeAlert(message, 'danger');
                })
                .always(function () {
                    button.prop('disabled', false).text('Delete');
                });
        });

        degreeForm.on('submit', function (event) {
            event.preventDefault();
            clearDegreeErrors();

            const submitButton = $('#degreeFormSubmit');
            const method = $('#degree_form_method').val();
            const data = degreeForm.serializeArray();

            if (method !== 'POST') {
                data.push({ name: '_method', value: method });
            }

            submitButton.prop('disabled', true).text('Saving...');

            $.ajax({
                url: $('#degree_action_url').val(),
                method: 'POST',
                data: $.param(data),
            })
                .done(function (response) {
                    degreeFormModal.hide();
                    showDegreeAlert(response.message || 'Degree saved successfully.', 'success');
                    loadDegrees();
                    notifyTabs('degrees');
                })
                .fail(function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        showDegreeErrors(xhr.responseJSON.errors);
                        return;
                    }

                    showDegreeAlert('Unable to save the degree.', 'danger');
                })
                .always(function () {
                    submitButton.prop('disabled', false).text('Save Degree');
                });
        });

        function loadDegrees() {
            degreeRows.html('<tr><td colspan="3" class="text-center text-secondary py-5">Loading degrees...</td></tr>');

            $.get(degreeManager.data('index-url'))
                .done(function (response) {
                    renderDegrees(response.degrees || []);
                })
                .fail(function () {
                    degreeRows.html('<tr><td colspan="3" class="text-center text-danger py-5">Unable to load degrees.</td></tr>');
                });
        }

        function renderDegrees(degrees) {
            Object.keys(degreesById).forEach(function (key) {
                delete degreesById[key];
            });

            if (!degrees.length) {
                degreeRows.html('<tr><td colspan="3" class="text-center text-secondary py-5">No degrees found.</td></tr>');
                return;
            }

            const rows = degrees.map(function (degree) {
                degreesById[degree.id] = degree;

                return `
                    <tr data-degree-id="${escapeHtml(degree.id)}">
                        <td class="fw-semibold">${escapeHtml(degree.title || '')}</td>
                        <td><span class="badge badge-soft">${escapeHtml(degree.students_count || 0)}</span></td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-sm btn-outline-brand js-degree-view" title="View" aria-label="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary js-degree-edit" title="Edit" aria-label="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger js-degree-delete" title="Delete" aria-label="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            degreeRows.html(rows.join(''));
        }

        function showDegreeDetails(degree) {
            $('#degreeDetailsTitle').text(degree.title || 'Degree Details');
            $('#degreeDetailsList').html(`
                <dt class="col-sm-4 text-secondary">Degree Title</dt>
                <dd class="col-sm-8 fw-semibold">${escapeHtml(degree.title || '')}</dd>
                <dt class="col-sm-4 text-secondary">Students Enrolled</dt>
                <dd class="col-sm-8 fw-semibold">${escapeHtml(degree.students_count || 0)}</dd>
            `);

            const students = degree.students || [];

            if (!students.length) {
                $('#degreeStudentsRows').html('<tr><td colspan="3" class="text-center text-secondary py-4">No students enrolled.</td></tr>');
            } else {
                $('#degreeStudentsRows').html(students.map(function (student) {
                    return `
                        <tr>
                            <td class="fw-semibold">${escapeHtml(student.full_name || '')}</td>
                            <td>${escapeHtml(student.email || '')}</td>
                            <td>${escapeHtml(student.contact || '')}</td>
                        </tr>
                    `;
                }).join(''));
            }

            degreeDetailsModal.show();
        }

        function getDegree(button) {
            const degreeId = button.closest('tr').data('degree-id');

            return degreesById[degreeId] || {};
        }

        function resetDegreeForm() {
            degreeForm[0].reset();
            clearDegreeErrors();
        }

        function showDegreeErrors(errors) {
            Object.keys(errors).forEach(function (field) {
                const input = degreeForm.find(`[name="${field}"]`);
                input.addClass('is-invalid');
                input.siblings('.invalid-feedback').first().text(errors[field][0]);
            });
        }

        function clearDegreeErrors() {
            degreeForm.find('.is-invalid').removeClass('is-invalid');
            degreeForm.find('.invalid-feedback').text('');
        }

        function showDegreeAlert(message, type) {
            degreeAlert
                .removeClass('d-none alert-success alert-danger alert-warning')
                .addClass('alert-' + type)
                .text(message);
        }

        onTabSync(function (payload) {
            if (payload.area === 'degrees' || payload.area === 'all') {
                loadDegrees();
            }
        });
    }

    function notifyTabs(area, details) {
        const payload = Object.assign({
            id: tabSyncId,
            area: area,
            time: Date.now(),
        }, details || {});

        if (tabSyncChannel) {
            tabSyncChannel.postMessage(payload);
        }

        try {
            localStorage.setItem(tabSyncKey, JSON.stringify(payload));
            localStorage.removeItem(tabSyncKey);
        } catch (error) {
            // Some browsers can block storage; BroadcastChannel already covered modern tabs.
        }
    }

    function onTabSync(callback) {
        tabSyncListeners.push(callback);
    }

    function handleTabSync(payload) {
        if (!payload || payload.id === tabSyncId) {
            return;
        }

        const eventKey = [payload.id, payload.area, payload.time].join(':');

        if (tabSyncSeen[eventKey]) {
            return;
        }

        tabSyncSeen[eventKey] = true;

        tabSyncListeners.forEach(function (callback) {
            callback(payload);
        });
    }

    function setupTabSync() {
        if (tabSyncChannel) {
            tabSyncChannel.onmessage = function (event) {
                handleTabSync(event.data);
            };
        }

        window.addEventListener('storage', function (event) {
            if (event.key !== tabSyncKey || !event.newValue) {
                return;
            }

            try {
                handleTabSync(JSON.parse(event.newValue));
            } catch (error) {
                // Ignore malformed sync events.
            }
        });
    }
})(jQuery);
