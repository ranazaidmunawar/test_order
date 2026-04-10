@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
@endphp
@extends('user-front.layout')
@section('pageHeading')
    {{ $keywords['Login'] ?? __('Login') }}
@endsection
@section('meta-keywords', !empty($userSeo) ? $userSeo->login_meta_keywords : '')
@section('meta-description', !empty($userSeo) ? $userSeo->login_meta_description : '')

<style>
    :root {
        --elak-primary: #0f5156;
        --elak-accent: #ffa726;
        --elak-bg: #f4f6f9;
        --elak-card: #ffffff;
        --elak-text: #333333;
    }

    body {
        background-color: var(--elak-bg);
        color: var(--elak-text);
        margin: 0;
        padding: 0;
    }

    .login-page-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Standard elak Header (matching Screenshot 17) */
    .elak-header {
        background: var(--elak-primary);
        color: #fff;
        padding: 12px 0;
        text-align: center;
        position: relative;
    }

    .elak-header .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .elak-header .logo {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-size: 1.4rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .elak-header .logo i {
        font-size: 1.2rem;
    }

    /* Center Section */
    .login-content-center {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .login-container {
        width: 100%;
        max-width: 420px;
    }

    .login-form-card {
        background: var(--elak-card);
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .login-branding {
        margin-bottom: 25px;
    }

    .login-branding i {
        font-size: 2.5rem;
        color: var(--elak-primary);
        margin-bottom: 10px;
    }

    .login-branding h2 {
        font-weight: 700;
        font-size: 1.5rem;
        color: #333;
        margin: 0;
    }

    .guest-checkout-btn {
        background: #28a745;
        color: #fff;
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        display: block;
        margin-bottom: 25px;
        text-decoration: none;
        transition: background 0.2s;
        border: none;
    }

    .guest-checkout-btn:hover {
        background: #218838;
        color: #fff;
    }

    .or-divider {
        text-align: center;
        margin: 20px 0;
        position: relative;
    }

    .or-divider::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 1px;
        background: #eee;
    }

    .or-divider span {
        background: var(--elak-card);
        padding: 0 15px;
        position: relative;
        color: #999;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .social-btns {
        display: flex;
        gap: 12px;
        margin-bottom: 25px;
    }

    .social-btn {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.85rem;
        text-decoration: none;
    }

    .btn-facebook {
        background: #3b5998;
    }

    .btn-google {
        background: #db4437;
    }

    .elak-input-group {
        text-align: left;
        margin-bottom: 15px;
    }

    .elak-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 6px;
    }

    .elak-input {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background: #fff;
        transition: border-color 0.2s;
    }

    .elak-input:focus {
        border-color: var(--elak-primary);
        outline: none;
    }

    .login-submit-btn {
        background: var(--elak-primary);
        color: #fff;
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        font-weight: 700;
        border: none;
        margin-top: 10px;
        cursor: pointer;
    }

    .login-submit-btn:hover {
        opacity: 0.9;
    }

    .form-footer-links {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
        font-size: 0.8rem;
    }

    .form-footer-links a {
        color: #007bff;
        text-decoration: none;
        font-weight: 600;
    }
</style>


@section('content')

    <link rel="stylesheet" href="{{ asset('assets/restaurant/seabbq-desifoodie-desices') }}/css/custom.css">
    <div class="login-page-wrapper">


        <!-- Main Content -->
        <div class="login-content-center">
            <div class="login-container">
                <div class="login-form-card">
                    <div class="login-branding">
                        <i class="fas fa-user-circle"></i>
                        <h2>{{ $keywords['Login'] ?? __('Login') }}</h2>
                    </div>

                    <form id="loginForm" action="{{ route('user.client.login.submit', getParam()) }}" method="POST">
                        @csrf
                        <div class="elak-input-group">
                            <label class="elak-label">{{ $keywords['Email_Address'] ?? __('Email Address') }}</label>
                            <input type="email" name="email" class="elak-input" placeholder="email@example.com" required>
                            @if (Session::has('err'))
                                <p class="text-danger mb-2 mt-2">{{ Session::get('err') }}</p>
                            @endif
                            @error('email')
                                <p class="text-danger mb-2 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="elak-input-group">
                            <label class="elak-label">{{ $keywords['Password'] ?? __('Password') }}</label>
                            <input type="password" name="password" class="elak-input" placeholder="••••••••" required>
                        </div>
                        <div class="input-box">
                            @if ($userBs->is_recaptcha == 1)
                                <div class="d-block mb-4">
                                    <div id="g-recaptcha" class="g-recaptcha d-inline-block"></div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        @php
                                            $errmsg = $errors->first('g-recaptcha-response');
                                        @endphp
                                        <p class="text-danger mb-0 mt-2">{{ __("$errmsg") }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="login-submit-btn">
                            {{ $keywords['LOG_IN'] ?? __('LOG IN') }}
                        </button>
                    </form>

                    <div class="form-footer-links">
                        <a
                            href="{{ route('user.client.forgot', getParam()) }}">{{ $keywords['Lost_your_password'] ?? __('Forgot Password?') }}</a>
                        <a
                            href="{{ route('user.client.register', getParam()) }}">{{ $keywords['Register'] ?? __('Register') }}</a>
                    </div>
                </div>
            </div>
        </div>


    </div>












@endsection