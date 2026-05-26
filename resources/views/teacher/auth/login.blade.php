<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Teacher Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --brand-maroon: #76001d;
            --brand-red: #c1121f;
            --ink: #31131a;
            --muted: rgba(49, 19, 26, 0.72);
            --line: rgba(118, 0, 29, 0.12);
            --danger: #b42318;
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
            color: var(--ink);
            background:
                radial-gradient(circle at 8% -15%, #ffe2e7 0%, transparent 40%),
                linear-gradient(180deg, #fffafb 0%, #ffffff 65%);
        }

        .login-card {
            width: min(460px, 100%);
            padding: 34px 30px;
            border: 1px solid var(--line);
            border-radius: 0.9rem;
            background: rgba(255, 255, 255, 0.96);
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
            background: linear-gradient(135deg, var(--brand-maroon), var(--brand-red));
        }

        h1 {
            margin: 0 0 8px;
            font-size: 2rem;
            color: var(--brand-maroon);
        }

        .lead {
            margin: 0 0 24px;
            color: var(--muted);
            line-height: 1.7;
        }

        .notice {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid rgba(180, 35, 24, 0.18);
            background: #fff1f3;
            color: var(--danger);
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
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fff;
            font: inherit;
            color: var(--ink);
        }

        .field input:focus {
            outline: none;
            border-color: var(--brand-red);
            box-shadow: 0 0 0 4px rgba(193, 18, 31, 0.14);
        }

        .field input.invalid {
            border-color: rgba(180, 35, 24, 0.5);
        }

        .field-error {
            margin-top: 8px;
            color: var(--danger);
            font-size: 0.86rem;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 0.94rem;
        }

        .remember input {
            width: 18px;
            height: 18px;
            accent-color: var(--brand-red);
        }

        .submit-button {
            width: 100%;
            padding: 15px 20px;
            border: 0;
            border-radius: 999px;
            color: #fff;
            background: var(--brand-red);
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .submit-button:hover {
            background: var(--brand-maroon);
            transform: translateY(-1px);
        }

        .helper {
            margin-top: 18px;
            color: var(--muted);
            font-size: 0.93rem;
            line-height: 1.7;
        }

        .helper a {
            color: var(--brand-maroon);
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
                <i class="bi bi-person-workspace"></i>
            </div>
            <div>
                <strong>Teacher Portal</strong>
                <div>Teacher Login</div>
            </div>
        </div>

        <h1>Welcome, teacher</h1>
        <p class="lead">Sign in using your teacher email and password.</p>

        @if (session('success'))
            <div class="notice notice-success" role="status">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="notice" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ $errors->first('email') ?: 'Incorrect teacher credentials.' }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.login.store') }}" class="form-grid">
            @csrf

            <div class="field">
                <label for="email">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    autofocus
                    required
                    class="{{ $errors->has('email') ? 'invalid' : '' }}"
                >
                @error('email')
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

            <label class="remember" for="remember">
                <input id="remember" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                <span>Keep me signed in</span>
            </label>

            <button type="submit" class="submit-button">
                <i class="bi bi-box-arrow-in-right"></i>
                Teacher Sign In
            </button>
        </form>

        <p class="helper">
            Need the admin login?
            <a href="{{ route('login') }}">Open admin login</a>
        </p>
    </section>
</body>
</html>
