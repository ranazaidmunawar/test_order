<div class="col-lg-3">
    <div class="premium-user-sidebar">
        <div class="sidebar-header d-lg-none">
            <h5>{{ $keywords['Dashboard'] ?? __('Account Menu') }}</h5>
            <hr>
        </div>
        <ul class="nav-links">
            <li class="nav-item">
                <a class="nav-link @if(request()->routeIs('user.client.dashboard')) active @endif"
                    href="{{route('user.client.dashboard', getParam())}}">
                    <i class="fas fa-th-large"></i> {{ $keywords['Dashboard'] ?? __('Dashboard') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(request()->routeIs('user.client.orders') || request()->routeIs('user.client.orders.details')) active @endif"
                    href="{{route('user.client.orders', getParam())}}">
                    <i class="fas fa-shopping-bag"></i> {{ $keywords['My Orders'] ?? __('My Orders') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(request()->routeIs('user.client.profile')) active @endif"
                    href="{{route('user.client.profile', getParam())}}">
                    <i class="fas fa-user-edit"></i> {{ $keywords['Edit Profile'] ?? __('Edit Profile') }}
                </a>
            </li>
            @if(!empty($packagePermissions) && in_array('Home Delivery', $packagePermissions))
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('user.client.shipping.details')) active @endif"
                        href="{{route('user.client.shipping.details', getParam())}}">
                        <i class="fas fa-truck"></i> {{ $keywords['Shipping Details'] ?? __('Shipping Details') }}
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link @if(request()->routeIs('user.client.billing.details')) active @endif"
                    href="{{route('user.client.billing.details', getParam())}}">
                    <i class="fas fa-file-invoice-dollar"></i>
                    {{ $keywords['Billing Details'] ?? __('Billing Details') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if(request()->routeIs('user.client.reset')) active @endif"
                    href="{{route('user.client.reset', getParam())}}">
                    <i class="fas fa-key"></i> {{ $keywords['Change Password'] ?? __('Change Password') }}
                </a>
            </li>
            <li class="nav-item logout-link">
                <a class="nav-link" href="{{route('user.client.logout', getParam())}}">
                    <i class="fas fa-sign-out-alt"></i> {{ $keywords['Logout'] ?? __('Logout') }}
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    .premium-user-sidebar {
        background: #fff;
        border-radius: 15px;
        padding: 20px 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 30px;
        border: 1px solid #f0f0f0;
    }

    .nav-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        margin-bottom: 8px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        border-radius: 12px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .nav-link i {
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
        opacity: 0.8;
    }

    .nav-link:hover {
        background: #f8fafc;
        color: var(--elak-primary, #0f5156);
    }

    .nav-link.active {
        background: var(--elak-primary, #0f5156);
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(15, 81, 86, 0.2);
    }

    .nav-link.active i {
        opacity: 1;
    }

    .logout-link .nav-link:hover {
        background: #fff1f2;
        color: #e11d48;
    }
</style>