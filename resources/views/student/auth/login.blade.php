<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Student Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --student-maroon: #76001d;
            --student-red: #c1121f;
            --student-red-dark: #9d0f19;
            --student-deep: #2d1116;
            --student-danger: #b42318;
            --student-red-soft: #fff1f3;
            --student-border: #eed5d9;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: "Poppins", "Segoe UI", sans-serif;
            color: var(--student-deep);
            background:
                radial-gradient(circle at 8% -15%, #ffe2e7 0%, transparent 40%),
                linear-gradient(180deg, #fffafb 0%, #ffffff 65%);
        }

        .login-card {
            width: min(480px, 100%);
            padding: 34px 30px;
            border-radius: 0.9rem;
            border: 1px solid var(--student-border);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 14px 32px rgba(118, 0, 29, 0.12);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }

        .brand-mark {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            color: #fff;
            font-size: 1.25rem;
            background: linear-gradient(135deg, var(--student-maroon), var(--student-red));
            box-shadow: 0 14px 28px rgba(115, 0, 30, 0.16);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 2rem;
            color: var(--student-maroon);
        }

        .lead {
            margin: 0 0 24px;
            color: rgba(47, 17, 32, 0.72);
            line-height: 1.7;
        }

        .notice {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid rgba(180, 35, 24, 0.18);
            background: var(--student-red-soft);
            color: var(--student-danger);
        }

        .notice-success {
            border-color: rgba(2, 122, 72, 0.16);
            background: #ecfdf3;
            color: #027a48;
        }

        .form-grid {
            display: grid;
            gap: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.94rem;
            font-weight: 600;
        }

        .field input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--student-border);
            border-radius: 16px;
            background: #fff;
            font: inherit;
            color: var(--student-deep);
        }

        .field input:focus {
            outline: none;
            border-color: var(--student-red);
            box-shadow: 0 0 0 4px rgba(193, 18, 31, 0.14);
        }

        .field input.invalid {
            border-color: rgba(180, 35, 24, 0.5);
        }

        .field-error {
            margin-top: 8px;
            color: var(--student-danger);
            font-size: 0.86rem;
        }

        .submit-button {
            width: 100%;
            padding: 15px 20px;
            border: 0;
            border-radius: 999px;
            color: #fff;
            background: var(--student-red);
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(115, 0, 30, 0.18);
            transition: all 0.2s ease;
        }

        .submit-button:hover {
            background: var(--student-maroon);
            transform: translateY(-1px);
        }

        .helper {
            margin-top: 18px;
            font-size: 0.93rem;
            color: rgba(47, 17, 32, 0.76);
            line-height: 1.7;
        }

        .helper a {
            color: var(--student-maroon);
            font-weight: 700;
            text-decoration: none;
        }
    </style>
    @include('partials.portal-auth-styles')
</head>
<body>
    <section class="login-card">
        <div class="brand">
            <div class="brand-mark">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <strong>Student Portal</strong>
                <div>Student Login</div>
            </div>
        </div>

        <h1>Welcome, student</h1>
        <p class="lead">Sign in using the username and password created for your student account.</p>

        @if (session('success'))
            <div class="notice notice-success" role="status">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="notice" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ $errors->first('username') ?: 'Incorrect username or password.' }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('student.login.store') }}" class="form-grid">
            @csrf

            <div class="field">
                <label for="username">Username</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    autofocus
                    required
                    class="{{ $errors->has('username') ? 'invalid' : '' }}"
                >
                @error('username')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    required
                    class="{{ $errors->has('password') ? 'invalid' : '' }}"
                >
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-button">
                <i class="bi bi-box-arrow-in-right"></i>
                Student Sign In
            </button>
        </form>

        <p class="helper">
            Need the admin login instead?
            <a href="{{ route('login') }}">Open admin login</a>
            <br>
            Teacher account?
            <a href="{{ route('teacher.login') }}">Open teacher portal</a>
        </p>
    </section>
</body>
</html>
