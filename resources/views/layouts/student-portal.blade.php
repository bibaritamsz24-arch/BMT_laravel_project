<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Student Portal')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --student-maroon: #76001d;
            --student-red: #c1121f;
            --student-red-dark: #9d0f19;
            --student-red-soft: #fff1f3;
            --student-text: #2d1116;
            --student-surface: #fff8f9;
            --student-border: #eed5d9;
        }

        body {
            min-height: 100vh;
            font-family: "Poppins", "Segoe UI", sans-serif;
            color: var(--student-text);
            background:
                linear-gradient(rgba(118, 0, 29, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(118, 0, 29, 0.035) 1px, transparent 1px),
                linear-gradient(180deg, #fffafb 0%, #ffffff 65%);
            background-size: 72px 72px, 72px 72px, auto;
            background-attachment: fixed;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            pointer-events: none;
            z-index: 0;
        }

        body::before {
            top: 6.5rem;
            left: -7rem;
            width: 24rem;
            height: 32rem;
            border: 1px solid rgba(118, 0, 29, 0.08);
            border-radius: 2rem;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.5), rgba(255, 241, 243, 0.8)),
                repeating-linear-gradient(135deg, rgba(193, 18, 31, 0.08) 0 1px, transparent 1px 18px);
            transform: rotate(-13deg);
            box-shadow: 0 28px 70px rgba(118, 0, 29, 0.08);
        }

        body::after {
            right: -8rem;
            bottom: -8rem;
            width: 34rem;
            height: 24rem;
            border: 1px solid rgba(118, 0, 29, 0.08);
            border-radius: 2rem;
            background:
                linear-gradient(135deg, rgba(255, 248, 249, 0.88), rgba(255, 255, 255, 0.44)),
                repeating-linear-gradient(90deg, rgba(118, 0, 29, 0.07) 0 1px, transparent 1px 20px);
            transform: rotate(9deg);
            box-shadow: 0 28px 70px rgba(118, 0, 29, 0.08);
        }

        .portal-shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
        }

        .portal-content-wrap {
            position: relative;
            isolation: isolate;
        }

        .portal-content-wrap::before {
            content: "";
            position: absolute;
            inset: 1.5rem 0 auto;
            height: 13.5rem;
            border-radius: 1.5rem;
            border: 1px solid rgba(118, 0, 29, 0.08);
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.84), rgba(255, 241, 243, 0.88)),
                repeating-linear-gradient(135deg, rgba(193, 18, 31, 0.06) 0 1px, transparent 1px 16px);
            box-shadow: 0 24px 60px rgba(118, 0, 29, 0.08);
            z-index: -2;
        }

        .portal-content-wrap::after {
            content: "";
            position: absolute;
            top: 3rem;
            right: 1.4rem;
            width: 9rem;
            height: 9rem;
            border: 1px solid rgba(118, 0, 29, 0.08);
            border-radius: 1.4rem;
            background: rgba(255, 255, 255, 0.34);
            transform: rotate(14deg);
            z-index: -1;
        }

        .portal-nav {
            background: linear-gradient(105deg, var(--student-maroon), var(--student-red));
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 14px 28px rgba(118, 0, 29, 0.28);
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
        }

        .portal-nav .navbar-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            font-size: 1.35rem;
        }

        .portal-nav .nav-link,
        .portal-user-name {
            font-weight: 600;
        }

        .portal-nav .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 999px;
            transition: all 0.2s ease;
        }

        .portal-nav .nav-link.portal-link-active {
            color: var(--student-red) !important;
            background: #ffffff;
        }

        .portal-nav .nav-link:hover {
            color: var(--student-red);
            background: #ffffff;
        }

        .portal-brand-icon {
            width: 2.3rem;
            height: 2.3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.15);
            font-size: 1.2rem;
        }

        .portal-brand-icon i,
        .portal-logout-button i,
        .student-dashboard-visual i,
        .btn-student i {
            color: #ffffff !important;
        }

        .portal-logout-button i {
            color: var(--student-maroon) !important;
        }

        .portal-tools {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .portal-user-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.65rem 0.95rem;
            border-radius: 999px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            font-size: 0.92rem;
            font-weight: 600;
        }

        .portal-logout-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.65rem 1.05rem;
            border: 0;
            border-radius: 999px;
            color: var(--student-maroon);
            background: #ffffff;
            font: inherit;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .portal-logout-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(118, 0, 29, 0.18);
        }

        .portal-card {
            border: 1px solid var(--student-border);
            border-radius: 0.9rem;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 12px 30px rgba(118, 0, 29, 0.08);
        }

        .student-dashboard-panel {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(220px, 0.75fr);
            gap: 1.75rem;
            align-items: stretch;
            max-width: 76rem;
            min-height: 14.5rem;
            margin: 0 auto;
            padding: clamp(1.35rem, 2.5vw, 2.15rem);
            border: 1px solid rgba(118, 0, 29, 0.1);
            border-radius: 0.9rem;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.96), rgba(255, 248, 249, 0.94)),
                repeating-linear-gradient(135deg, rgba(118, 0, 29, 0.05) 0 1px, transparent 1px 14px);
            box-shadow: 0 18px 46px rgba(118, 0, 29, 0.1);
        }

        .student-dashboard-panel::before {
            content: "";
            position: absolute;
            inset: auto -4rem -5rem auto;
            width: 22rem;
            height: 22rem;
            border-radius: 44%;
            border: 3rem solid rgba(193, 18, 31, 0.08);
            transform: rotate(18deg);
        }

        .student-dashboard-copy,
        .student-dashboard-visual {
            position: relative;
            z-index: 1;
        }

        .student-dashboard-copy {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .student-dashboard-title {
            margin: 0;
            font-size: clamp(1.85rem, 4.4vw, 3rem);
            line-height: 1.05;
            font-weight: 800;
            color: var(--student-maroon);
        }

        .student-dashboard-text {
            max-width: 44rem;
            margin: 0.85rem 0 0;
            color: rgba(45, 17, 22, 0.72);
            font-size: 1rem;
            line-height: 1.75;
        }

        .student-dashboard-visual {
            display: grid;
            place-items: center;
            min-height: 10.75rem;
            border-radius: 0.75rem;
            color: #ffffff;
            background:
                repeating-linear-gradient(0deg, rgba(255, 255, 255, 0.12) 0 1px, transparent 1px 18px),
                linear-gradient(135deg, var(--student-maroon), var(--student-red));
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.18);
        }

        .student-dashboard-visual i {
            font-size: clamp(3.25rem, 8vw, 5.75rem);
            opacity: 0.96;
        }

        .btn-student {
            border: 0;
            color: #fff;
            background: var(--student-red);
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-student:hover {
            color: #fff;
            background: var(--student-maroon);
            transform: translateY(-1px);
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 0.9rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--student-maroon);
            background: var(--student-red-soft);
            border: 1px solid rgba(193, 18, 31, 0.2);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--student-red);
            box-shadow: 0 0 0 0.2rem rgba(193, 18, 31, 0.15);
        }

        @media (min-width: 992px) {
            .portal-tools {
                margin-top: 0;
                margin-left: 1rem;
            }
        }

        @media (max-width: 768px) {
            .portal-nav .navbar-brand {
                font-size: 1.1rem;
            }

            .portal-brand-icon {
                width: 2rem;
                height: 2rem;
                font-size: 1rem;
            }

            .student-dashboard-panel {
                grid-template-columns: 1fr;
            }

            .portal-content-wrap::before {
                height: 10rem;
                border-radius: 1.4rem;
            }

            .portal-content-wrap::after {
                width: 6.5rem;
                height: 6.5rem;
                border-radius: 1.2rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="portal-shell">
        @php($studentUser = auth('student')->user())
        @php($studentPasswordChanged = (bool) session('student_password_changed', false))

    <nav class="navbar navbar-expand-lg portal-nav">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-inline-flex align-items-center gap-2" href="{{ $studentPasswordChanged ? route('student.dashboard') : route('student.password.edit') }}">
                <span class="portal-brand-icon">
                    <i class="bi bi-person-badge-fill"></i>
                </span>
                <span>Student Portal</span>
            </a>

            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#studentPortalNavbar" aria-controls="studentPortalNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse" id="studentPortalNavbar">
                <ul class="navbar-nav ms-auto me-lg-3 gap-2">
                    @if ($studentPasswordChanged)
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('student.dashboard', 'student.welcome', 'student.welcome-page') ? 'portal-link-active' : '' }}" href="{{ route('student.dashboard') }}">Dashboard</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('student.password.*') ? 'portal-link-active' : '' }}" href="{{ route('student.password.edit') }}">Change Password</a>
                    </li>
                </ul>

                @if ($studentUser)
                    <div class="portal-tools flex-column flex-lg-row align-items-lg-center">
                        <span class="portal-user-pill">
                            <i class="bi bi-person-circle"></i>
                            {{ $studentUser->display_name }}
                        </span>
                        <form method="POST" action="{{ route('student.logout') }}">
                            @csrf
                            <button type="submit" class="portal-logout-button">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <main class="container portal-content-wrap py-4 py-lg-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
