@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
@endphp

@extends('user-front.layout')
@section('pageHeading')
    {{ $keywords['Billing Details'] ?? __('Billing Details') }}
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
                                <i class="fas fa-file-invoice-dollar"></i>
                                <h3>{{ $keywords['Billing Details'] ?? __('Billing Details') }}</h3>
                            </div>

                            @if (session()->has('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session()->get('success') }}
                                </div>
                            @endif

                            <div class="edit-profile-form mt-4">
                                <form action="{{ route('user.client.billing.update', getParam()) }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['First Name'] ?? __('First Name') }}</label>
                                                <input type="text" class="premium-input" name="billing_fname"
                                                    value="{{ $customer->billing_fname }}" required>
                                                @error('billing_fname')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Last Name'] ?? __('Last Name') }}</label>
                                                <input type="text" class="premium-input" name="billing_lname"
                                                    value="{{ $customer->billing_lname }}" required>
                                                @error('billing_lname')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Email'] ?? __('Email') }}</label>
                                                <input type="email" class="premium-input" name="billing_email"
                                                    value="{{ $customer->billing_email }}" required>
                                                @error('billing_email')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Phone'] ?? __('Phone') }}</label>
                                                <div class="premium-phone-input">
                                                    <div class="input-group">
                                                        <input type="hidden" name="billing_country_code"
                                                            value="{{ !empty($customer->billing_country_code) ? $customer->billing_country_code : null }}">
                                                        <div class="input-group-prepend">
                                                            <button
                                                                class="premium-input btn-outline-secondary dropdown-toggle billing_country_code"
                                                                type="button" data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ !empty($customer->billing_country_code) ? $customer->billing_country_code : ($keywords['Select'] ?? __('Select')) }}
                                                            </button>
                                                            <div class="dropdown-menu country-codes"
                                                                id="billing_country_code">
                                                                @foreach ($ccodes as $ccode)
                                                                    <a class="dropdown-item"
                                                                        data-billing_country_code="{{ $ccode['code'] }}"
                                                                        href="javascript:void(0)">{{ $ccode['name'] }}
                                                                        ({{ $ccode['code'] }})</a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <input type="text" name="billing_number" class="premium-input"
                                                            placeholder="{{ $keywords['Phone'] ?? __('Phone') }}"
                                                            value="{{ $customer->billing_number }}">
                                                    </div>
                                                </div>
                                                @error('billing_country_code')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                                @error('billing_number')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['City'] ?? __('City') }}</label>
                                                <input type="text" class="premium-input" name="billing_city"
                                                    value="{{ $customer->billing_city }}">
                                                @error('billing_city')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['State'] ?? __('State') }}</label>
                                                <input type="text" class="premium-input" name="billing_state"
                                                    value="{{ $customer->billing_state }}">
                                                @error('billing_state')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Country'] ?? __('Country') }}</label>
                                                <input type="text" class="premium-input" name="billing_country"
                                                    value="{{ $customer->billing_country }}">
                                                @error('billing_country')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <div class="premium-input-group">
                                                <label class="premium-label">{{ $keywords['Address'] ?? __('Address') }}</label>
                                                <textarea name="billing_address" class="premium-textarea" rows="3">{{ $customer->billing_address }}</textarea>
                                                @error('billing_address')
                                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="premium-save-btn">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                {{ $keywords['Submit'] ?? __('Update Details') }}
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

        /* Phone Input Group Fix */
        .premium-phone-input .input-group {
            display: flex;
            align-items: center;
        }

        .premium-phone-input .input-group-prepend {
            margin-right: -1px;
        }

        .premium-phone-input .dropdown-toggle {
            width: auto;
            min-width: 100px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            background: #f8fafc;
        }

        .premium-phone-input .premium-input[name="billing_number"] {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            flex: 1;
        }

        .premium-phone-input .dropdown-item {
            cursor: pointer;
            font-weight: 600;
            padding: 10px 20px;
        }
        
        .premium-phone-input .dropdown-item:hover {
            background: var(--elak-primary);
            color: #fff;
        }

        @media (max-width: 991px) {
            .dashboard-content {
                margin-top: 20px;
            }
        }

        @media (max-width: 576px) {
            .premium-save-btn {
                width: 100%;
            }
        }
    </style>
    <script>
        $(document).ready(function() {
            $('.country-codes .dropdown-item').on('click', function() {
                var code = $(this).data('billing_country_code');
                $(this).closest('.input-group').find('input[name="billing_country_code"]').val(code);
                $(this).closest('.input-group').find('.dropdown-toggle').text(code);
            });
        });
    </script>
@endsection
