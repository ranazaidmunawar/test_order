@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
@endphp

@extends('user-front.layout')
@section('pageHeading')
    {{ $keywords['Profile'] ?? __('Profile') }}
@endsection
@section('content')

    <section class="user-dashboard-area">
        <div class="container">
            <div class="row">
                @include('user-front.client.inc.site_bar')
                <div class="col-lg-9">
                    <div class="dashboard-content">
                        <div class="premium-dashboard-card">
                            <div class="card-header-icon">
                                <i class="fas fa-user-edit"></i>
                                <h3>{{ $keywords['Edit Profile'] ?? __('Edit Profile') }}</h3>
                            </div>

                            @if (session()->has('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session()->get('success') }}
                                </div>
                            @endif

                            <div class="edit-profile-form mt-4">
                                <form action="{{ route('user.client.profile.update', getParam()) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="profile-upload-section mb-5">
                                        <div class="current-avatar">
                                            @if (strpos($customer->photo, 'facebook') || strpos($customer->photo, 'google'))
                                                <img class="showimage"
                                                    src="{{ $customer->photo ?? asset('assets/front/img/user/profile.jpg') }}"
                                                    alt="user-image">
                                            @else
                                                <img class="showimage"
                                                    src="{{ $customer->photo ? Uploader::getImageUrl(Constant::WEBSITE_CUSTOMER_IMAGE, $customer->photo, $userBs) : asset('assets/front/img/user/profile.jpg') }}"
                                                    alt="user-image">
                                            @endif
                                        </div>
                                        <div class="upload-controls">
                                            <label class="upload-label">
                                                <i class="fas fa-camera"></i>
                                                {{ $keywords['Change Photo'] ?? __('Change Photo') }}
                                                <input type="file" name="photo" class="upload-input image"
                                                    style="display:none">
                                            </label>
                                            <p class="upload-hint text-muted small mt-2">
                                                {{ __('Recommended size: 256x256px') }}</p>
                                            @error('photo')
                                                <p class="text-danger small">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['First Name'] ?? __('First Name') }}</label>
                                                <input type="text" class="premium-input" name="fname"
                                                    value="{{ $customer->fname }}" required>
                                                @error('fname')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Last Name'] ?? __('Last Name') }}</label>
                                                <input type="text" class="premium-input" name="lname"
                                                    value="{{ $customer->lname }}" required>
                                                @error('lname')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Username'] ?? __('Username') }}</label>
                                                <input type="text" class="premium-input" name="username"
                                                    value="{{ $customer->username }}" required>
                                                @error('username')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Email'] ?? __('Email') }}</label>
                                                <input type="email" class="premium-input bg-light"
                                                    value="{{ $customer->email }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Phone'] ?? __('Phone') }}</label>
                                                <input type="text" class="premium-input" name="number"
                                                    value="{{ $customer->number }}">
                                                @error('number')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['City'] ?? __('City') }}</label>
                                                <input type="text" class="premium-input" name="city"
                                                    value="{{ $customer->city }}">
                                                @error('city')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['State'] ?? __('State') }}</label>
                                                <input type="text" class="premium-input" name="state"
                                                    value="{{ $customer->state }}">
                                                @error('state')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Country'] ?? __('Country') }}</label>
                                                <input type="text" class="premium-input" name="country"
                                                    value="{{ $customer->country }}">
                                                @error('country')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Address'] ?? __('Address') }}</label>
                                                <textarea name="address" class="premium-textarea" rows="3">{{ $customer->address }}</textarea>
                                                @error('address')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="premium-save-btn">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                {{ $keywords['Save Changes'] ?? __('Save Changes') }}
                                            </button>
                                        </div>
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
            padding: 30px;
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

        /* Profile Upload UI */
        .profile-upload-section {
            display: flex;
            align-items: center;
            gap: 25px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 15px;
        }

        .current-avatar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .upload-label {
            background: var(--elak-primary);
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.2s;
            margin-bottom: 0px;
        }

        .upload-label:hover {
            opacity: 0.9;
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

        .premium-textarea {
            width: 100%;
            padding: 15px 18px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-weight: 600;
            transition: border-color 0.2s;
        }

        .premium-textarea:focus {
            border-color: var(--elak-primary);
            outline: none;
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

        @media (max-width: 576px) {
            .profile-upload-section {
                flex-direction: column;
                text-align: center;
            }

            .premium-save-btn {
                width: 100%;
            }
        }
    </style>
@endsection