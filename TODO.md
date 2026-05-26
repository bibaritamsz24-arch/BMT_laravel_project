# TODO - Middleware, Session, Logout, and Role-Based Access

- [ ] Step 1: Inspect existing auth/session/guards usage and confirm admin/teacher role storage (already checked: `UserAccount.role` exists, `auth` guard is `web`).
- [x] Step 2: Add `RoleMiddleware` to enforce role-based access.
- [x] Step 3: Add admin/teacher routes + dashboards (views), and role-based redirects from `/`.
- [ ] Step 4: Ensure logout works correctly for both guards (web + student) and routes referenced by layouts exist.
- [ ] Step 5: Implement minimal “add student/teacher” for admin:
  - [x] Students already supported via existing `students` resource.
  - [ ] Teacher support: add as needed (if schema/controller missing).
- [x] Step 6: Run tests / quick verification:
  - [x] `php artisan test`
  - [ ] Manual role login checks (student/admin/teacher)


