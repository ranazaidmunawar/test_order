@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
@endphp
@extends('user-front.layout')
@section('pageHeading')
    {{ $keywords['New Password'] ?? __('New Password') }}
@endsection
@section('meta-keywords', !empty($userSeo) ? $userSeo->forget_password_meta_keywords : '')
@section('meta-description', !empty($userSeo) ? $userSeo->forget_password_meta_description : '')


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


    <div class="login-page-wrapper">


        <!-- Main Content -->
        <div class="login-content-center">
            <div class="login-container">
                <div class="login-form-card">
                    <div class="login-branding">
                        <i class="fas fa-user-circle"></i>
                        <h2>{{ $keywords['Login'] ?? __('Login') }}</h2>
                    </div>

                    @if (session()->has('success'))
                        <div class="alert alert-success fade show" role="alert">
                            <strong>{{ __('Success') }}!</strong> {{ session('success') }} <a
                                href="{{ route('user.client.login', getParam()) }}">Login Now</a>
                        </div>
                    @endif
                    @if (session('link_error'))
                        <div class="alert alert-danger">
                            {{ session('link_error') }}
                        </div>
                    @endif

                    <form action="{{ route('user.client.password.create.submit', getParam()) }}" method="POST">
                        @csrf
                        <input type="hidden" name="pass_token" value="{{ request('pass_token') }}">

                        <div class="elak-input-group">
                            <label class="elak-label"> <span>{{ $keywords['New Password'] ?? __('New Password') }}
                                    *</span></label>
                            <input type="password" name="password" name="password_confirmation"
                                placeholder="{{ $keywords['password_confirmation'] ?? __('Confirm Password') }}"
                                value="{{ old('password_confirmation') }}" required>
                            @error('password_confirmation')
                                <p class="text-danger text-left">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="input-box">
                            <span>{{ $keywords['Confirm Password'] ?? __('Confirm Password') }} *</span>
                            <input type="password" name="password_confirmation"
                                placeholder="{{ $keywords['password_confirmation'] ?? __('Confirm Password') }}"
                                value="{{ old('password_confirmation') }}">
                            @error('password_confirmation')
                                <p class="text-danger text-left">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="login-submit-btn">
                            {{ $keywords['Submit'] ?? __('Submit') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>

@endsection