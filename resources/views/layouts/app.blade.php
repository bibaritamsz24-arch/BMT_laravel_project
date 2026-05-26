<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Student, Degree, and Teacher Management')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --brand-maroon: #76001d;
            --brand-red: #c1121f;
            --brand-red-dark: #9d0f19;
            --brand-red-soft: #fff1f3;
            --brand-text: #2d1116;
            --surface-soft: #fff8f9;
            --border-soft: #eed5d9;
        }

        body {
            font-family: "Poppins", "Segoe UI", sans-serif;
            min-height: 100vh;
            color: var(--brand-text);
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

        .page-shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
        }

        .navbar-theme {
            background: linear-gradient(105deg, var(--brand-maroon), var(--brand-red));
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 14px 28px rgba(118, 0, 29, 0.28);
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
        }

        .navbar-theme .navbar-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            font-size: 1.35rem;
            letter-spacing: 0.02em;
        }

        .navbar-theme .navbar-brand,
        .navbar-theme .nav-link {
            color: #ffffff;
        }

        .navbar-theme .brand-icon {
            width: 2.3rem;
            height: 2.3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.15);
            font-size: 1.2rem;
        }

        .navbar-theme .brand-icon i,
        .dashboard-visual i {
            color: #ffffff !important;
        }

        .navbar-theme .nav-link i {
            color: currentColor !important;
        }

        .btn-brand i,
        .logout-button i {
            color: inherit;
        }

        .navbar-theme .nav-link {
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            transition: all 0.2s ease;
        }

        .navbar-theme .nav-link.active,
        .navbar-theme .nav-link:hover {
            color: var(--brand-red);
            background: #ffffff;
        }

        .navbar-theme .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.35);
        }

        .navbar-theme .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
        }

        .navbar-theme .navbar-toggler-icon {
            filter: invert(1);
        }

        .navbar-theme .navbar-collapse {
            margin-top: 0.65rem;
        }

        .navbar-tools {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .navbar-user {
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

        .logout-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.65rem 1.05rem;
            border: 0;
            border-radius: 999px;
            color: var(--brand-maroon);
            background: #ffffff;
            font: inherit;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .logout-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(118, 0, 29, 0.18);
        }

        .content-wrap {
            position: relative;
            isolation: isolate;
            padding-top: 2.6rem;
            padding-bottom: 2.2rem;
        }

        .content-wrap::before {
            content: "";
            position: absolute;
            inset: 1.5rem 0 auto;
            height: 13.5rem;
            border-radius: 1.5rem;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.84), rgba(255, 241, 243, 0.88)),
                repeating-linear-gradient(135deg, rgba(193, 18, 31, 0.06) 0 1px, transparent 1px 16px);
            border: 1px solid rgba(118, 0, 29, 0.08);
            box-shadow: 0 24px 60px rgba(118, 0, 29, 0.08);
            z-index: -2;
        }

        .content-wrap::after {
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

        .app-card {
            border: 1px solid var(--border-soft);
            border-radius: 0.9rem;
            box-shadow: 0 12px 30px rgba(118, 0, 29, 0.08);
            overflow: hidden;
        }

        .section-students .app-card {
            border-color: rgba(118, 0, 29, 0.08);
            box-shadow: 0 18px 46px rgba(118, 0, 29, 0.1);
        }

        .app-card .card-header {
            border: 0;
            color: #fff;
            background: linear-gradient(105deg, var(--brand-maroon), var(--brand-red));
        }

        .dashboard-panel {
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

        .dashboard-panel::before {
            content: "";
            position: absolute;
            inset: auto -4rem -5rem auto;
            width: 22rem;
            height: 22rem;
            border-radius: 44%;
            border: 3rem solid rgba(193, 18, 31, 0.08);
            transform: rotate(18deg);
        }

        .dashboard-copy {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .dashboard-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            width: fit-content;
            margin-bottom: 1rem;
            padding: 0.55rem 0.85rem;
            border-radius: 999px;
            color: var(--brand-maroon);
            background: var(--brand-red-soft);
            border: 1px solid rgba(193, 18, 31, 0.2);
            font-size: 0.88rem;
            font-weight: 700;
        }

        .dashboard-title {
            margin: 0;
            font-size: clamp(1.85rem, 4.4vw, 3rem);
            line-height: 1.05;
            font-weight: 800;
            color: var(--brand-maroon);
        }

        .dashboard-text {
            max-width: 44rem;
            margin: 0.85rem 0 0;
            color: rgba(45, 17, 22, 0.72);
            font-size: 1rem;
            line-height: 1.75;
        }

        .dashboard-visual {
            position: relative;
            z-index: 1;
            display: grid;
            place-items: center;
            min-height: 10.75rem;
            border-radius: 0.75rem;
            color: #ffffff;
            background:
                repeating-linear-gradient(0deg, rgba(255, 255, 255, 0.12) 0 1px, transparent 1px 18px),
                linear-gradient(135deg, var(--brand-maroon), var(--brand-red));
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.18);
        }

        .dashboard-visual i {
            font-size: clamp(3.25rem, 8vw, 5.75rem);
            opacity: 0.96;
        }

        .dashboard-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1.25rem;
        }

        .dashboard-action {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            min-height: 11rem;
            padding: 1.25rem;
            border: 1px solid var(--border-soft);
            border-radius: 0.75rem;
            background: #ffffff;
            box-shadow: 0 12px 28px rgba(118, 0, 29, 0.08);
        }

        .dashboard-action-icon {
            width: 2.6rem;
            height: 2.6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.7rem;
            color: var(--brand-red);
            background: var(--brand-red-soft);
            font-size: 1.2rem;
        }

        .dashboard-action .btn {
            margin-top: auto;
            width: fit-content;
        }

        .table-theme thead th {
            color: #fff;
            background: var(--brand-red);
            border-color: var(--brand-maroon);
        }

        .table-theme tbody tr:hover {
            background: var(--brand-red-soft);
        }

        .btn-brand {
            color: #fff;
            border: 0;
            background: var(--brand-red);
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-brand:hover {
            color: #fff;
            background: var(--brand-maroon);
            transform: translateY(-1px);
        }

        .btn-outline-brand {
            color: var(--brand-red);
            border-color: var(--brand-red);
            font-weight: 600;
        }

        .btn-outline-brand:hover {
            color: #fff;
            background-color: var(--brand-red);
            border-color: var(--brand-red);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-red);
            box-shadow: 0 0 0 0.2rem rgba(193, 18, 31, 0.15);
        }

        .badge-soft {
            color: var(--brand-maroon);
            background: var(--brand-red-soft);
            border: 1px solid rgba(193, 18, 31, 0.2);
        }

        .bg-light {
            background-color: var(--surface-soft) !important;
        }

        .pagination {
            --bs-pagination-color: var(--brand-red);
            --bs-pagination-hover-color: var(--brand-maroon);
            --bs-pagination-focus-color: var(--brand-maroon);
            --bs-pagination-focus-box-shadow: 0 0 0 0.2rem rgba(193, 18, 31, 0.15);
            --bs-pagination-active-bg: var(--brand-red);
            --bs-pagination-active-border-color: var(--brand-maroon);
        }

        @media (min-width: 992px) {
            .navbar-theme .navbar-collapse {
                margin-top: 0;
            }

            .navbar-tools {
                margin-top: 0;
                margin-left: 1rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-panel {
                grid-template-columns: 1fr;
            }

            .dashboard-actions {
                grid-template-columns: 1fr;
            }

            .content-wrap::before {
                height: 10rem;
                border-radius: 1.4rem;
            }

            .content-wrap::after {
                width: 6.5rem;
                height: 6.5rem;
                border-radius: 1.2rem;
            }

            .navbar-theme .navbar-brand {
                font-size: 1.1rem;
            }

            .navbar-theme .brand-icon {
                width: 2rem;
                height: 2rem;
                font-size: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
@php($isStudentSection = request()->routeIs('students.*'))
<body class="{{ $isStudentSection ? 'section-students' : 'section-default' }}">
    <div class="page-shell">
        <nav class="navbar navbar-expand-lg navbar-theme">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                    <span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
                    <span>Student, Degree, and Teacher Management</span>
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    @auth
                        <ul class="navbar-nav ms-auto gap-2 gap-lg-3">
                            @if (auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-person-lines-fill me-1"></i> Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                                        <i class="bi bi-people-fill me-1"></i> Students
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                                        <i class="bi bi-person-workspace me-1"></i> Teachers
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('degrees.*') ? 'active' : '' }}" href="{{ route('degrees.index') }}">
                                        <i class="bi bi-journal-bookmark-fill me-1"></i> Degrees
                                    </a>
                                </li>
                            @elseif (auth()->user()->role === 'teacher')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endauth
                    @auth
                        <div class="navbar-tools">
                            <span class="navbar-user">
                                <i class="bi bi-person-circle"></i>
                                {{ auth()->user()->name }}
                            </span>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-button">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="container content-wrap {{ $isStudentSection ? 'content-wrap-students' : '' }}">
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
