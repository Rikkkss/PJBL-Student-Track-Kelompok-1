
@extends('layouts.guest')

@section('title', 'Login')

@push('styles')
<style>
    html, body {
        min-height: 100%;
        margin: 0;
    }

    body {
        background: var(--background);
    }

    .auth-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 18px;
        background:
            radial-gradient(circle at 10% 10%, rgba(101,88,220,.09), transparent 32%),
            radial-gradient(circle at 90% 90%, rgba(101,88,220,.08), transparent 30%),
            var(--background);
    }

    .auth-card {
        width: 100%;
        max-width: 440px;
        padding: 38px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        box-shadow: 0 18px 55px rgba(35, 35, 75, .09);
    }

    .auth-brand {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 34px;
    }

    .auth-logo {
        width: 49px;
        height: 49px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: white;
        background: linear-gradient(135deg, #6558dc, #8c7cf1);
        border-radius: 15px;
        font-size: 23px;
    }

    .auth-brand h2 {
        margin: 0;
        color: var(--text);
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .auth-brand span {
        display: block;
        margin-top: 3px;
        color: var(--muted);
        font-size: 10px;
    }

    .auth-heading {
        margin-bottom: 27px;
    }

    .auth-heading h3 {
        margin-bottom: 8px;
        color: var(--text);
        font-size: 25px;
        font-weight: 800;
        letter-spacing: -.7px;
    }

    .auth-heading p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .auth-card .form-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
    }

    .auth-card .form-control {
        width: 100%;
        min-height: 47px;
        padding: 12px 14px;
        color: var(--text);
        background: var(--background);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-family: inherit;
        font-size: 12px;
    }

    .auth-card .form-control:focus {
        background: var(--surface);
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(101,88,220,.12);
    }

    .auth-card .form-control::placeholder {
        color: var(--muted);
    }

    .auth-card .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: auto;
        padding-left: 0;
    }

    .auth-card .form-check-input {
        float: none;
        flex-shrink: 0;
        width: 16px;
        height: 16px;
        margin: 0;
        cursor: pointer;
    }

    .auth-card .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .auth-card .form-check-label {
        color: var(--muted);
        font-size: 11px;
        cursor: pointer;
    }

    .auth-submit {
        width: 100%;
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        color: white !important;
        background: var(--primary);
        border: 0;
        border-radius: 10px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 800;
        transition: .2s ease;
    }

    .auth-submit:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .auth-footer {
        margin-top: 27px;
        padding-top: 19px;
        border-top: 1px solid var(--border);
        color: var(--muted);
        text-align: center;
        font-size: 10px;
    }

    .auth-theme {
        position: fixed;
        top: 22px;
        right: 22px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        font-size: 17px;
        cursor: pointer;
    }

    .auth-theme:hover {
        background: var(--primary-soft);
    }

    @media (max-width: 480px) {
        .auth-card {
            padding: 28px 23px;
            border-radius: 17px;
        }

        .auth-heading h3 {
            font-size: 23px;
        }

        .auth-theme {
            top: 13px;
            right: 13px;
        }
    }
</style>
@endpush

@section('content')

<div class="auth-page">

    <button type="button"
            class="auth-theme"
            id="themeToggle"
            title="Ganti tema"
            aria-label="Ganti tema">
        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
    </button>

    <div class="auth-card">

        <div class="auth-brand">
            <div class="auth-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>
                <h2>StudentTrack</h2>
                <span>Academic Management System</span>
            </div>
        </div>

        <div class="auth-heading">
            <h3>Selamat Datang!</h3>
            <p>Silakan masuk untuk melanjutkan aktivitas akademikmu.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="nama@email.com"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div class="form-check mb-4">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="form-check-input"
                    id="remember"
                >
                <label class="form-check-label" for="remember">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="auth-submit">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk ke StudentTrack
            </button>
        </form>

        <div class="auth-footer">
            © {{ date('Y') }} StudentTrack. All rights reserved.
        </div>

    </div>

</div>

@endsection
