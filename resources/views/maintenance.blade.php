<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Maintenance</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-maroon: #76001d;
            --brand-red: #c1121f;
            --brand-deep: #2f0510;
            --brand-gold: #f5c26b;
            --surface: rgba(255, 255, 255, 0.9);
            --surface-strong: rgba(255, 251, 252, 0.96);
            --surface-dark: rgba(255, 255, 255, 0.08);
            --text-main: #2f1117;
            --text-soft: rgba(47, 17, 23, 0.72);
            --text-light: rgba(255, 244, 246, 0.82);
            --line-soft: rgba(118, 0, 29, 0.14);
            --line-bright: rgba(255, 255, 255, 0.14);
            --shadow-deep: 0 36px 120px rgba(18, 0, 6, 0.42);
            --shadow-soft: 0 18px 48px rgba(118, 0, 29, 0.14);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;
            overflow-x: hidden;
            font-family: "Poppins", "Segoe UI", sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at 15% 18%, rgba(255, 193, 124, 0.16), transparent 18%),
                radial-gradient(circle at 85% 15%, rgba(255, 255, 255, 0.08), transparent 16%),
                radial-gradient(circle at 78% 75%, rgba(193, 18, 31, 0.3), transparent 28%),
                linear-gradient(135deg, #24040d 0%, #5a091a 36%, #8e1128 66%, #b9142d 100%);
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
        }

        body::before {
            opacity: 0.28;
            background:
                linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.85), transparent 92%);
        }

        body::after {
            background:
                radial-gradient(circle at center, transparent 0 52%, rgba(0, 0, 0, 0.26) 100%);
        }

        .ambient {
            position: fixed;
            border-radius: 999px;
            filter: blur(18px);
            pointer-events: none;
            opacity: 0.5;
            z-index: 0;
            animation: drift 16s ease-in-out infinite;
        }

        .ambient.one {
            top: 8%;
            left: -8%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 220, 166, 0.7), rgba(255, 220, 166, 0));
        }

        .ambient.two {
            right: -10%;
            bottom: 4%;
            width: 340px;
            height: 340px;
            background: radial-gradient(circle, rgba(255, 122, 122, 0.42), rgba(255, 122, 122, 0));
            animation-delay: -6s;
        }

        .page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px;
        }

        .shell {
            position: relative;
            width: min(1120px, 100%);
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(320px, 0.92fr);
            border: 1px solid var(--line-bright);
            border-radius: 32px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-deep);
            backdrop-filter: blur(18px);
        }

        .shell::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            border: 1px solid rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        .content-panel {
            position: relative;
            padding: 54px 48px;
            background:
                linear-gradient(180deg, var(--surface-strong) 0%, rgba(255, 245, 247, 0.94) 100%);
        }

        .content-panel::before {
            content: "";
            position: absolute;
            inset: auto auto -90px -60px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 196, 150, 0.5), rgba(255, 196, 150, 0));
            pointer-events: none;
        }

        .content-panel::after {
            content: "";
            position: absolute;
            top: 24px;
            right: 24px;
            width: 90px;
            height: 90px;
            border-radius: 24px;
            border: 1px solid rgba(118, 0, 29, 0.1);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7), rgba(255, 241, 243, 0.35));
            transform: rotate(12deg);
            pointer-events: none;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 241, 243, 0.92);
            border: 1px solid rgba(193, 18, 31, 0.14);
            color: var(--brand-maroon);
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .eyebrow .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand-red), var(--brand-maroon));
            box-shadow: 0 0 0 6px rgba(193, 18, 31, 0.08);
        }

        .icon-wrap {
            width: 86px;
            height: 86px;
            display: grid;
            place-items: center;
            margin: 26px 0 22px;
            border-radius: 28px;
            color: #ffffff;
            font-size: 2rem;
            background: linear-gradient(135deg, var(--brand-red), var(--brand-maroon));
            box-shadow: 0 18px 40px rgba(118, 0, 29, 0.22);
        }

        h1 {
            max-width: 11ch;
            margin: 0 0 16px;
            font-size: clamp(2.5rem, 5vw, 4.4rem);
            line-height: 0.96;
            letter-spacing: -0.04em;
        }

        .lead {
            max-width: 34rem;
            margin: 0;
            line-height: 1.8;
            color: var(--text-soft);
            font-size: 1rem;
        }

        .user {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 26px;
            padding: 12px 16px;
            border-radius: 18px;
            border: 1px solid rgba(118, 0, 29, 0.1);
            background: rgba(255, 255, 255, 0.66);
            color: var(--text-soft);
            font-size: 0.92rem;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 30px;
        }

        .action-link,
        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-width: 190px;
            padding: 15px 24px;
            border: 0;
            border-radius: 999px;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
        }

        .action-link:hover,
        .action-button:hover {
            transform: translateY(-2px);
        }

        .action-link.primary {
            color: #ffffff;
            background: linear-gradient(135deg, var(--brand-red), var(--brand-maroon));
            box-shadow: 0 16px 30px rgba(118, 0, 29, 0.2);
        }

        .action-button.secondary {
            color: var(--brand-maroon);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid var(--line-soft);
            box-shadow: var(--shadow-soft);
        }

        .action-form {
            margin: 0;
        }

        .note {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 28px;
            padding: 12px 16px;
            border-radius: 18px;
            background: rgba(118, 0, 29, 0.05);
            color: var(--text-soft);
            font-size: 0.92rem;
        }

        .visual-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 42px 34px 34px;
            overflow: hidden;
            color: #ffffff;
            background:
                radial-gradient(circle at top right, rgba(255, 210, 151, 0.18), transparent 28%),
                radial-gradient(circle at 30% 10%, rgba(255, 255, 255, 0.12), transparent 24%),
                linear-gradient(180deg, rgba(54, 5, 18, 0.84) 0%, rgba(20, 2, 8, 0.96) 100%);
        }

        .visual-panel::before,
        .visual-panel::after {
            content: "";
            position: absolute;
            pointer-events: none;
        }

        .visual-panel::before {
            inset: 18px;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                linear-gradient(145deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.01));
        }

        .visual-panel::after {
            right: -50px;
            bottom: -58px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 194, 107, 0.28), rgba(245, 194, 107, 0));
            filter: blur(12px);
        }

        .visual-top,
        .status-stack {
            position: relative;
            z-index: 1;
        }

        .visual-top {
            max-width: 360px;
            padding: 8px 6px 0;
        }

        .visual-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 244, 246, 0.9);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            backdrop-filter: blur(6px);
        }

        .visual-panel h2 {
            margin: 18px 0 12px;
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            line-height: 1.05;
            letter-spacing: -0.03em;
        }

        .visual-panel p {
            margin: 0;
            line-height: 1.8;
            color: var(--text-light);
        }

        .status-stack {
            display: grid;
            gap: 14px;
            margin-top: 28px;
        }

        .status-card {
            position: relative;
            padding: 18px 18px 16px;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            box-shadow: 0 16px 28px rgba(0, 0, 0, 0.12);
            animation: floatCard 10s ease-in-out infinite;
        }

        .status-card:nth-child(2) {
            animation-delay: -3s;
        }

        .status-card:nth-child(3) {
            animation-delay: -6s;
        }

        .status-card.highlight {
            background:
                linear-gradient(140deg, rgba(255, 255, 255, 0.16), rgba(255, 255, 255, 0.06));
        }

        .status-label {
            display: inline-block;
            margin-bottom: 10px;
            color: rgba(245, 194, 107, 0.95);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .status-card strong {
            display: block;
            font-size: 1.04rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .status-card small {
            display: block;
            margin-top: 8px;
            color: rgba(255, 244, 246, 0.74);
            line-height: 1.65;
            font-size: 0.88rem;
        }

        @keyframes drift {
            0%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            50% {
                transform: translate3d(16px, -14px, 0);
            }
        }

        @keyframes floatCard {
            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        @media (max-width: 980px) {
            .shell {
                grid-template-columns: 1fr;
            }

            .visual-panel {
                min-height: 360px;
            }
        }

        @media (max-width: 640px) {
            .page {
                padding: 18px;
            }

            .content-panel,
            .visual-panel {
                padding: 28px 22px;
            }

            .content-panel::after {
                width: 72px;
                height: 72px;
                top: 18px;
                right: 18px;
            }

            .icon-wrap {
                width: 76px;
                height: 76px;
                margin-top: 22px;
            }

            h1 {
                max-width: none;
                font-size: clamp(2.2rem, 11vw, 3.2rem);
            }

            .actions {
                flex-direction: column;
            }

            .action-link,
            .action-button {
                width: 100%;
            }

            .visual-panel {
                min-height: auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .ambient,
            .status-card {
                animation: none;
            }

            .action-link,
            .action-button {
                transition: none;
            }
        }
    </style>
</head>
<body>
    <div class="ambient one"></div>
    <div class="ambient two"></div>

    <main class="page">
        <section class="shell">
            <div class="content-panel">
                <div class="eyebrow">
                    <span class="dot"></span>
                    Under Maintenance
                </div>

                <div class="icon-wrap">
                    <i class="bi bi-tools"></i>
                </div>

                <h1>Student module is temporarily unavailable.</h1>

                <p class="lead">
                    We are currently updating this section. Please check back later while we complete a cleaner and more polished experience.
                </p>

                @auth
                    <div class="user">
                        <i class="bi bi-person-circle"></i>
                        Signed in as {{ auth()->user()->email }}
                    </div>
                @endauth

                @auth
                    <div class="actions">
                        <a href="{{ route('degrees.index') }}" class="action-link primary">
                            <i class="bi bi-journal-bookmark-fill"></i>
                            Open Degrees
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="action-form">
                            @csrf
                            <button type="submit" class="action-button secondary">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth

                <div class="note">
                    <i class="bi bi-info-circle"></i>
                    Only the student pages are affected.
                </div>
            </div>

            <aside class="visual-panel">
                <div class="visual-top">
                    <div class="visual-tag">
                        <i class="bi bi-stars"></i>
                        Portal refresh in progress
                    </div>

                    <h2>A cleaner student portal is on the way.</h2>

                    <p>
                        The student side is taking a short pause while updates are being applied behind the scenes for a smoother and more polished experience.
                    </p>
                </div>

                <div class="status-stack">
                    <article class="status-card highlight">
                        <span class="status-label">Current status</span>
                        <strong>Student pages are temporarily paused.</strong>
                        <small>This notice will stay visible while the student module is unavailable.</small>
                    </article>

                    <article class="status-card">
                        <span class="status-label">Update in progress</span>
                        <strong>Visual and usability improvements are being applied.</strong>
                        <small>The student module is being refreshed so the next visit feels cleaner and easier to navigate.</small>
                    </article>

                    @auth
                        <article class="status-card">
                            <span class="status-label">Quick access</span>
                            <strong>You can still open the degrees section.</strong>
                            <small>Use the action button on the left if you need to continue from the available module.</small>
                        </article>
                    @endauth
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
