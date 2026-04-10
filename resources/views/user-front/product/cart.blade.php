@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
    use App\Models\User\Product;
    $direction = 'direction:'
@endphp
@extends('user-front.layout')
@section('pageHeading')
    {{ $keywords['Cart'] ?? __('Cart') }}
@endsection
@section('meta-keywords', !empty($userSeo) ? $userSeo->cart_meta_keywords : '')
@section('meta-description', !empty($userSeo) ? $userSeo->cart_meta_description : '')
@section('pagename')
    -
    {{ $keywords['Product'] ?? __('Product') }}
@endsection

<link rel="stylesheet" href="{{ asset('assets/restaurant/seabbq-desifoodie-desices') }}/css/custom.css">
@section('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --elak-primary: #0f5156;
            --elak-bg: #f4f7f6;
            --elak-font-serif: 'Playfair Display', serif;
            --elak-font-sans: 'Inter', sans-serif;
        }

        .toast-message {
            background: #0f5156 !important;
        }

        body {
            background-color: #fff !important;
            font-family: var(--elak-font-sans);
        }

        .cart-page-container {
            max-width: 500px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
            float: none !important;
        }

        .coupon-banner {
            background: #eef7f6;
            border-radius: 12px;
            color: #0f5156;
            font-weight: 600;
            transition: all 0.2s;
            border: 1px dashed rgba(15, 81, 86, 0.2);
        }

        .coupon-banner:hover {
            background: #dceceb;
        }

        .cart-item-card {
            background: white !important;
            border-radius: 20px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
            border: 1px solid #f1f5f9 !important;
            padding: 15px 20px !important;
            transition: all 0.2s ease;
        }

        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 15px;
            background: #f8fafc;
        }

        .delete-btn {
            color: #70757a !important;
            font-size: 0.9rem !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0;
            border: none;
            background: transparent;
        }

        .qty-stepper {
            display: flex;
            align-items: center;
            background: #f1f4f9;
            border-radius: 12px;
            padding: 2px;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            border: none;
            background: white;
            color: #0f5156;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .qty-input {
            width: 35px;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 700;
            font-size: 0.95rem;
            color: #000;
        }

        .product-title {
            font-family: inherit;
            font-weight: 700;
            font-size: 1.15rem;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .product-price {
            font-family: inherit;
            font-weight: 700;
            font-size: 1.1rem;
            color: #0f5156;
        }

        .sticky-action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            max-width: 600px !important;
            margin: 0 auto !important;
            background: white;
            padding: 20px 15px 15px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.08);
            z-index: 1050;
            border: 1px solid #f1f5f9;
            border-bottom: none;
        }

        .btn-primary {
            background-color: var(--elak-primary) !important;
            border-color: var(--elak-primary) !important;
        }

        .btn-outline-primary {
            color: var(--elak-primary) !important;
            border-color: var(--elak-primary) !important;
        }

        /* Coupon Modal */
        .coupon-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .coupon-overlay.active {
            display: flex;
        }

        .coupon-popup {
            background: white;
            width: 90%;
            max-width: 400px;
            padding: 35px;
            border-radius: 28px;
            position: relative;
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .coupon-close {
            position: absolute;
            top: 20px;
            right: 20px;
            border: none;
            background: #f1f5f9;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 0.9rem;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .coupon-input {
            width: 100%;
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            background: #f8fafc;
        }

        .coupon-apply-btn {
            width: 100%;
            padding: 14px;
            background: var(--elak-primary);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            transition: opacity 0.2s;
        }

        .coupon-apply-btn:hover {
            opacity: 0.9;
        }

        .text-primary {
            color: var(--elak-primary) !important;
        }
    </style>
@endsection


@section('content')
    <div style="background-color: #fff; min-height: 100vh;">
        <div class="cart-page-container"
            style="max-width: 600px !important; margin: 0 auto !important; padding: 20px 15px 150px !important; background: #fff; position: relative;">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4 position-relative" style="width: 100%;">
                <h4 class="mb-0 mx-auto text-dark"
                    style="font-family: 'Playfair Display', serif; font-weight: 700; letter-spacing: 0.5px;">
                    {{ __('Shopping cart') }}
                </h4>
                <a href="{{ route('user.front.index', getParam()) }}"
                    class="btn btn-sm btn-light rounded-circle shadow-sm border-0"
                    style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #fff; position: absolute; right: 0;">
                    <i class="fas fa-times text-muted" style="font-size: 12px;"></i>
                </a>
            </div>

            <!-- Coupon Banner -->
            <div class="coupon-banner p-3 mb-4 d-flex align-items-center justify-content-center gap-2 cursor-pointer shadow-sm border-0"
                onclick="openCouponModal()" style="background: #eef7f6; color: #0f5156;">
                <i class="fas fa-ticket-alt"></i>
                <span class="fw-bold">{{ __('Use the discount coupon') }}</span>
            </div>

            <!-- Cart List -->
            <div id="cart-list" class="mb-4">
                @php $totalPrice = 0; @endphp
                @if ($cart != null && count($cart) > 0)
                    @foreach ($cart as $key => $item)
                        @php $totalPrice += (float) $item['total']; @endphp
                        <div class="cart-item-card mb-3 d-flex align-items-center justify-content-between gap-3">
                            <!-- User Requested Action Group -->
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <!-- Delete Icon -->
                                <button class="btn btn-link text-muted p-0 text-decoration-none small"
                                    onclick="removeCartItem('{{ $key }}')">
                                    <i class="far fa-trash-alt"></i> Delete
                                </button>

                                <!-- Quantity Stepper -->
                                <div class="qty-stepper">
                                    <button class="qty-btn" onclick="updateCartQty('{{ $key }}', 1)"><i
                                            class="fas fa-plus"></i></button>
                                    <input type="text" class="qty-input" value="{{ $item['qty'] }}" readonly="">
                                    <button class="qty-btn" onclick="updateCartQty('{{ $key }}', -1)"><i
                                            class="fas fa-minus"></i></button>
                                </div>
                            </div>

                            <!-- Product Info (Pushed to Right) -->
                            <div class="ms-auto text-end pe-2 mx-2">
                                <h6 class="product-title mb-1">{{ $item['name'] }}</h6>
                                <div class="product-price">
                                    {{ number_format($item['total'], 2) }} {{ $userBe->base_currency_symbol }}
                                </div>
                            </div>

                            <!-- Product Image -->
                            <div class="flex-shrink-0 mx-2">
                                <img src="{{ Uploader::getImageUrl(Constant::WEBSITE_PRODUCT_FEATURED_IMAGE, $item['photo'], $userBs) }}"
                                    class="cart-item-img shadow-sm" alt="{{ $item['name'] }}"
                                    onerror="this.src='{{ asset('assets/front/img/default.png') }}'">
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-5 mt-5">
                        <i class="fas fa-shopping-basket fa-3x mb-3 text-secondary opacity-25"></i>
                        <p class="fs-5">{{ __('Your cart is empty') }}</p>
                        <a href="{{ route('user.front.index', getParam()) }}" class="btn btn-primary rounded-pill px-4 mt-3"
                            style="background: #0f5156; border: none;">
                            {{ __('Browse Menu') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Coupon Modal -->
        <div id="coupon-overlay" class="coupon-overlay" onclick="closeCouponModal(event)">
            <div class="coupon-popup shadow-lg border-0" onclick="event.stopPropagation()" style="border-radius: 25px;">
                <button class="coupon-close" onclick="closeCouponModal()"><i class="fas fa-times"></i></button>
                <div class="text-center mb-4 pt-3">
                    <img src="https://armani.nemo.ps/wp-content/themes/noqta-menu-theme/assets/svgs/coupon.gif"
                        style="max-width: 140px; border-radius: 15px;">
                </div>
                <p class="text-center fw-bold mb-3" style="font-size:1.1rem; color: #333;">{{ __('Enter the code') }}</p>
                <form onsubmit="applyCoupon(event)">
                    <input type="text" id="coupon-input" class="coupon-input shadow-sm mb-3"
                        placeholder="{{ __('Coupon code') }}" autocomplete="off"
                        style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                    <button type="submit" class="coupon-apply-btn shadow-sm py-3"
                        style="background: #0f5156; border-radius: 12px;">{{ __('Apply discount') }}</button>
                </form>
                <div id="coupon-msg" class="mt-3 text-center fw-bold small" style="display:none;"></div>
            </div>
        </div>

        <!-- Sticky Bottom Action Bar -->
        @php
            $discount = session()->has('coupon') && !empty(session()->get('coupon')) ? session()->get('coupon') : 0;
            $grandTotal = $totalPrice - (float) $discount;
            if ($grandTotal < 0) {
                $grandTotal = 0;
            }
        @endphp
        @if ($cart != null && count($cart) > 0)
            <div class="sticky-action-bar border-0 shadow-lg"
                style="border-top-left-radius: 30px; border-top-right-radius: 30px; padding: 25px 20px 15px;">
                <div class="d-flex flex-column gap-1 mb-4">
                    @if ($discount > 0)
                        <div class="d-flex justify-content-between align-items-center px-2 small">
                            <span class="text-danger fw-bold">-{{ number_format($discount, 2) }}
                                {{ $userBe->base_currency_symbol }}</span>
                            <span class="text-muted opacity-75">Coupon Discount</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center px-2">
                        <span class="fs-3 fw-bold" id="cart-total-display"
                            style="color: #0f5156; font-family: 'Playfair Display', serif;">
                            {{ number_format($grandTotal, 2) }} {{ $userBe->base_currency_symbol }}
                        </span>
                        <span class="text-muted fw-bold small opacity-75">:Total</span>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('user.front.index', getParam()) }}"
                        class="btn btn-outline-primary flex-grow-1 py-3 rounded-pill fw-bold"
                        style="border-color: #0f5156; color: #0f5156; border-width: 2px; font-size: 0.9rem;">Add Another
                        Product</a>
                    @php
                        $checkoutUrl = route('user.product.front.checkout', getParam());
                        if (!Auth::guard('client')->check()) {
                            $checkoutUrl = route('user.client.login', [getParam(), 'redirected' => 'checkout']);
                            Session::put('link', route('user.product.front.checkout', getParam()));
                        }
                    @endphp
                    <a href="{{ $checkoutUrl }}"
                        class="btn btn-primary flex-grow-1 py-3 rounded-pill fw-bold border-0 shadow-sm"
                        style="background: #0f5156; font-size: 0.9rem;">The Next</a>
                </div>
            </div>
        @endif

        <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
        <script>
            function openCouponModal() {
                document.getElementById('coupon-overlay').classList.add('active');
                document.getElementById('coupon-input').focus();
            }

            function closeCouponModal() {
                document.getElementById('coupon-overlay').classList.remove('active');
                document.getElementById('coupon-msg').style.display = 'none';
            }

            function applyCoupon(e) {
                e.preventDefault();
                const code = document.getElementById('coupon-input').value.trim();
                const msg = document.getElementById('coupon-msg');
                msg.style.display = 'block';
                msg.style.color = '#333';
                msg.textContent = 'Applying...';

                if (!code) {
                    msg.style.color = '#ef4444';
                    msg.textContent = 'Please enter a coupon code.';
                    return;
                }

                $.ajax({
                    url: "{{ route('user.front.coupon', getParam()) }}",
                    type: 'POST',
                    data: {
                        coupon: code,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            toastr["success"](data.message);
                            location.reload();
                        } else {
                            msg.style.color = '#ef4444';
                            msg.textContent = data.message;
                        }
                    },
                    error: function () {
                        msg.style.color = '#ef4444';
                        msg.textContent = 'An error occurred. Please try again.';
                    }
                });
            }

            function updateCartQty(key, delta) {
                let url = delta > 0 ?
                    "{{ route('user.front.cart.item.add.quantity', [getParam(), ':key']) }}" :
                    "{{ route('user.front.cart.item.less.quantity', [getParam(), ':key']) }}";

                url = url.replace(':key', key);

                $.get(url, function (data) {
                    if (data.message) {
                        location.reload();
                    } else if (data.error) {
                        toastr.error(data.error);
                    }
                }).fail(function () {
                    toastr.error('Failed to update quantity');
                });
            }

            function removeCartItem(key) {
                if (confirm('{{ __('Are you sure you want to remove this item?') }}')) {
                    let url = "{{ route('user.front.cart.item.remove', [getParam(), ':key']) }}".replace(':key', key);
                    $.get(url, function (data) {
                        if (data.message) {
                            toastr.success(data.message);
                            location.reload();
                        }
                    });
                }
            }
        </script>






@endsection