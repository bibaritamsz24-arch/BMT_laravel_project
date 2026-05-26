<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Login Portal</title>

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
            --shadow-lg: 0 24px 60px rgba(118, 0, 29, 0.14);
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
                radial-gradient(circle at top left, rgba(255, 213, 195, 0.9), transparent 26%),
                radial-gradient(circle at 82% 12%, rgba(193, 18, 31, 0.12), transparent 20%),
                linear-gradient(180deg, #fff8f9 0%, #ffffff 100%);
        }

        .login-card {
            width: min(460px, 100%);
            padding: 36px 30px;
            border: 1px solid var(--line);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: var(--shadow-lg);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .brand-mark {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            color: #ffffff;
            font-size: 1.25rem;
            background: linear-gradient(135deg, var(--brand-red), var(--brand-maroon));
        }

        .brand strong {
            display: block;
            font-size: 1rem;
            letter-spacing: -0.02em;
        }

        .brand span {
            display: block;
            font-size: 0.9rem;
            color: var(--muted);
        }

        h1 {
            margin: 0 0 10px;
            font-size: 2rem;
            color: #4f0616;
        }

        .lead {
            margin: 0 0 24px;
            color: var(--muted);
            line-height: 1.7;
        }

        .notice {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            padding: 15px 16px;
            border-radius: 16px;
            border: 1px solid rgba(180, 35, 24, 0.16);
            background: #fff3f2;
            color: var(--danger);
            font-size: 0.95rem;
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
            font-size: 0.92rem;
            font-weight: 600;
        }

        .field input[type="text"],
        .field input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(118, 0, 29, 0.14);
            border-radius: 16px;
            background: #ffffff;
            font: inherit;
            color: var(--ink);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field input[type="text"]:focus,
        .field input[type="password"]:focus {
            outline: none;
            border-color: var(--brand-red);
            box-shadow: 0 0 0 4px rgba(193, 18, 31, 0.12);
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
            color: #ffffff;
            background: linear-gradient(135deg, var(--brand-red), var(--brand-maroon));
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .submit-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(118, 0, 29, 0.18);
        }

        .helper {
            margin: 18px 0 0;
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.7;
        }

        @media (max-width: 640px) {
            body {
                padding: 16px;
            }

            .login-card {
                padding: 28px 20px;
            }
        }
    </style>
    @include('partials.portal-auth-styles')
</head>
<body>
    <section class="login-card">
        <div class="brand">
            <div class="brand-mark">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <strong>Login Portal</strong>
                <span>Admin, Teacher, Student</span>
            </div>
        </div>

        <h1>Welcome</h1>
        <p class="lead">Sign in once. The system will detect if your account is admin, teacher, or student.</p>

        @if (session('success'))
            <div class="notice notice-success" role="status">
                <i class="bi bi-check-circle-fill"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="notice" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ $errors->first('login') ?: 'Incorrect credentials.' }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="form-grid">
            @csrf

            <div class="field">
                <label for="login">Email or Username</label>
                <input
                    id="login"
                    type="text"
                    name="login"
                    value="{{ old('login') }}"
                    autocomplete="username"
                    autofocus
                    required
                    class="{{ $errors->has('login') ? 'invalid' : '' }}"
                >
                @error('login')
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
                Sign In
            </button>
        </form>

        <p class="helper">
            Admin accounts use email. Teacher and student accounts can use username or email.
        </p>
    </section>
</body>
</html>
