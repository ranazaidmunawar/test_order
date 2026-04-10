@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
@endphp

@extends('user-front.layout')
@section('pageHeading')
    {{ $keywords['Change Password'] ?? __('Change Password') }}
@endsection
@section('content')

    <section class="user-dashboard-area">
        <div class="container">
            <div class="row">
                @include('user-front.client.inc.site_bar')
                <div class="col-lg-9">
                    <div class="dashboard-content">
                        <div class="premium-dashboard-card" style="max-width: 600px; margin: 0 auto;">
                            <div class="card-header-icon">
                                <i class="fas fa-key"></i>
                                <h3>{{ $keywords['Change Password'] ?? __('Change Password') }}</h3>
                            </div>

                            @if (session()->has('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session()->get('success') }}
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div class="alert alert-danger mt-3">
                                    {{ session()->get('error') }}
                                </div>
                            @endif

                            <div class="edit-profile-form mt-4">
                                <form action="{{ route('user.client.reset.submit', getParam()) }}" method="POST">
                                    @csrf

                                    <div class="premium-input-group mb-4">
                                        <label class="premium-label">{{ $keywords['Current Password'] ?? __('Current Password') }}</label>
                                        <input type="password" class="premium-input" name="current_password" required>
                                        @error('current_password')
                                            <p class="text-danger small mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="premium-input-group mb-4">
                                        <label class="premium-label">{{ $keywords['New Password'] ?? __('New Password') }}</label>
                                        <input type="password" class="premium-input" name="new_password" required>
                                        @error('new_password')
                                            <p class="text-danger small mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="premium-input-group mb-4">
                                        <label class="premium-label">{{ $keywords['Confirm Password'] ?? __('Confirm Password') }}</label>
                                        <input type="password" class="premium-input" name="confirmation_password" required>
                                        @error('confirmation_password')
                                            <p class="text-danger small mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-5">
                                        <button type="submit" class="premium-save-btn w-100">
                                            <i class="fas fa-lock mr-2"></i>
                                            {{ $keywords['Update Password'] ?? __('Update Password') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        :root {
            --elak-primary: #0f5156;
            --elak-bg: #f8fafc;
            --elak-text: #1e293b;
            --elak-muted: #64748b;
        }

        .user-dashboard-area {
            background: var(--elak-bg);
            padding: 40px 0 100px;
            min-height: 80vh;
        }

        .premium-dashboard-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
        }

        .card-header-icon {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 20px;
        }

        .card-header-icon i {
            font-size: 1.5rem;
            color: var(--elak-primary);
            background: rgba(15, 81, 86, 0.08);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .card-header-icon h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--elak-text);
            margin: 0;
        }

        /* Form Inputs */
        .premium-input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .premium-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--elak-muted);
            margin-bottom: 0px;
        }

        .premium-input {
            width: 100%;
            padding: 12px 18px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #fff;
            font-weight: 600;
            color: var(--elak-text);
            transition: all 0.2s;
        }

        .premium-input:focus {
            border-color: var(--elak-primary);
            outline: none;
            box-shadow: 0 0 0 4px rgba(15, 81, 86, 0.05);
        }

        .premium-save-btn {
            background: var(--elak-primary);
            color: #fff;
            border: none;
            padding: 14px 30px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s, opacity 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .premium-save-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .premium-save-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 991px) {
            .dashboard-content {
                margin-top: 20px;
            }
        }
    </style>
@endsection