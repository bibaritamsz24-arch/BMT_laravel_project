<style>
    :root {
        --portal-maroon: #8b0024;
        --portal-red: #c1121f;
        --portal-red-dark: #76001d;
        --portal-red-soft: #fff1f3;
        --portal-text: #2d1116;
        --portal-muted: rgba(45, 17, 22, 0.72);
        --portal-border: rgba(118, 0, 29, 0.12);
        --portal-shadow: 0 24px 70px rgba(118, 0, 29, 0.14);
        --portal-danger: #b42318;
    }

    body {
        margin: 0;
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 24px;
        font-family: "Poppins", "Segoe UI", sans-serif;
        color: var(--portal-text);
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
        top: 5rem;
        left: -8rem;
        width: 24rem;
        height: 32rem;
        border: 1px solid rgba(118, 0, 29, 0.08);
        border-radius: 2rem;
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.5), rgba(255, 241, 243, 0.84)),
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

    .login-card {
        position: relative;
        z-index: 1;
        width: min(480px, 100%);
        padding: 34px 30px;
        border: 1px solid var(--portal-border);
        border-radius: 0.9rem;
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.97), rgba(255, 248, 249, 0.95)),
            repeating-linear-gradient(135deg, rgba(118, 0, 29, 0.04) 0 1px, transparent 1px 14px);
        box-shadow: var(--portal-shadow);
        overflow: hidden;
    }

    .login-card::before {
        content: "";
        position: absolute;
        inset: auto -5rem -6rem auto;
        width: 18rem;
        height: 18rem;
        border-radius: 44%;
        border: 2.6rem solid rgba(193, 18, 31, 0.08);
        transform: rotate(18deg);
    }

    .login-card > * {
        position: relative;
        z-index: 1;
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
        border-radius: 0.9rem;
        color: #fff;
        font-size: 1.25rem;
        background:
            repeating-linear-gradient(0deg, rgba(255, 255, 255, 0.12) 0 1px, transparent 1px 12px),
            linear-gradient(135deg, var(--portal-red-dark), var(--portal-red));
        box-shadow: 0 14px 28px rgba(118, 0, 29, 0.16);
    }

    .brand-mark i,
    .submit-button i {
        color: #ffffff !important;
    }

    .brand strong {
        display: block;
        color: var(--portal-text);
        font-size: 1rem;
        font-weight: 800;
    }

    .brand span,
    .brand div {
        color: var(--portal-muted);
        font-size: 0.9rem;
    }

    h1 {
        margin: 0 0 8px;
        color: var(--portal-maroon);
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .lead {
        margin: 0 0 24px;
        color: var(--portal-muted);
        line-height: 1.7;
    }

    .notice {
        display: flex;
        gap: 12px;
        margin-bottom: 18px;
        padding: 14px 16px;
        border-radius: 0.9rem;
        border: 1px solid rgba(180, 35, 24, 0.18);
        background: var(--portal-red-soft);
        color: var(--portal-danger);
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
        color: var(--portal-text);
        font-size: 0.94rem;
        font-weight: 700;
    }

    .field input {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid var(--portal-border);
        border-radius: 0.9rem;
        background: #fff;
        font: inherit;
        color: var(--portal-text);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .field input:focus {
        outline: none;
        border-color: var(--portal-red);
        box-shadow: 0 0 0 4px rgba(193, 18, 31, 0.14);
    }

    .field input.invalid {
        border-color: rgba(180, 35, 24, 0.5);
    }

    .field-error {
        margin-top: 8px;
        color: var(--portal-danger);
        font-size: 0.86rem;
    }

    .remember {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--portal-muted);
        font-size: 0.94rem;
    }

    .remember input {
        width: 18px;
        height: 18px;
        accent-color: var(--portal-red);
    }

    .submit-button {
        width: 100%;
        padding: 15px 20px;
        border: 0;
        border-radius: 999px;
        color: #fff;
        background: var(--portal-red);
        font: inherit;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 12px 24px rgba(118, 0, 29, 0.16);
        transition: all 0.2s ease;
    }

    .submit-button:hover {
        color: #fff;
        background: var(--portal-red-dark);
        transform: translateY(-1px);
    }

    .helper {
        margin: 18px 0 0;
        color: var(--portal-muted);
        font-size: 0.93rem;
        line-height: 1.7;
    }

    .helper a {
        color: var(--portal-maroon) !important;
        font-weight: 800 !important;
        text-decoration: none !important;
    }

    .helper a:hover {
        color: var(--portal-red) !important;
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
