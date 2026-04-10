@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
    use Illuminate\Support\Facades\Auth;

    $cartCount = 0;
    if (Session::has($user->username . "_cart")) {
        $cart = Session::get($user->username . "_cart");
        if (is_array($cart)) {
            foreach ($cart as $item) {
                $cartCount += (int) $item['qty'];
            }
        }
    }
    $links = json_decode($userMenus, true);
@endphp

<header class="header-main shadow-sm">
    <div class="container-fluid header-container py-2 px-3 px-md-4">
        <div class="row align-items-center g-0">
            <!-- Left: Icons (Cart & Search) -->
            <div class="col-4 d-flex align-items-center">
                <a href="{{ route('user.front.cart', getParam()) }}"
                    class="header-icon-btn position-relative me-3 mx-3">
                    <i class="fas fa-shopping-cart"></i>
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <a href="javascript:void(0)" class="header-icon-btn" onclick="toggleSearchHeader(true)">
                    <i class="fas fa-search"></i>
                </a>
            </div>

            <!-- Center: Logo/Title -->
            <div class="col-4 text-center">
                <a class="header-logo" href="{{ route('user.front.index', getParam()) }}">
                    <i class="fas fa-utensils me-2"></i> {{ $userBs->website_title }}
                </a>
            </div>

            <!-- Right: Languages & Menu Toggle -->
            <div class="col-4 d-flex justify-content-end align-items-center">
                <div class="lang-switcher d-flex me-3">
                    @foreach ($allLanguageInfos as $lang)
                        <a href="{{ route('user.front.change.language', [getParam(), $lang->code]) }}"
                            class="lang-btn {{ $lang->code == $userCurrentLang->code ? 'active' : '' }}">
                            {{ strtoupper($lang->code) }}
                        </a>
                    @endforeach
                </div>
                <a href="javascript:void(0)" class="header-icon-btn" onclick="toggleSidebarHeader(true)">
                    <i class="fas fa-bars"></i>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Search Area Overlay (Slides down) -->
<div id="searchHeaderModal" class="search-header-overlay">
    <div class="search-header-content p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0 fw-bold">{{ __('Search Product') }}</h5>
            <button type="button" class="btn-close" onclick="toggleSearchHeader(false)"></button>
        </div>
        <div class="position-relative">
            <div class="input-group search-input-group shadow-sm">
                <input type="text" id="globalSearchInput" class="form-control border-0 px-4"
                    placeholder="{{ __('Search delicious food...') }}" autocomplete="off">
                <button class="btn btn-search-go" type="button" onclick="executeHeaderSearch()">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Live Results Container -->
            <div id="liveSearchResults" class="search-results-dropdown d-none shadow-lg">
                <div class="results-list"></div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Sidebar (Drawer) -->
<div id="sideDrawerHeader" class="side-drawer-header">
    <div class="drawer-top p-4 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="m-0 fw-bold text-dark">{{ __('Menu') }}</h5>
        <button type="button" class="drawer-close-btn" onclick="toggleSidebarHeader(false)">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="drawer-content p-3">
        <div class="menu-nav-list">
            <!-- Dynamic Main Menu Items -->
            @foreach ($links as $link)
                @php $href = getUserHref($link, $userCurrentLang->id); @endphp
                @if (!array_key_exists('children', $link))
                    <a href="{{ $href }}" class="drawer-menu-item shadow-sm">
                        <span class="text-dark fw-bold">{{ $link['text'] }}</span>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>
                @else
                    <div class="drawer-menu-collapse shadow-sm">
                        <a href="javascript:void(0)" class="drawer-menu-item border-0" data-bs-toggle="collapse"
                            data-bs-target="#menu-child-{{ $loop->index }}">
                            <span class="text-dark fw-bold">{{ $link['text'] }}</span>
                            <i class="fas fa-chevron-down text-muted small"></i>
                        </a>
                        <div class="collapse ps-3 pb-2" id="menu-child-{{ $loop->index }}">
                            @foreach ($link['children'] as $child)
                                @php $childHref = getUserHref($child, $userCurrentLang->id); @endphp
                                <a href="{{ $childHref }}" class="drawer-child-item">{{ $child['text'] }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="mt-4 border-top pt-4">
                <!-- Dashboard / Login -->
                <!-- Dashboard / Account -->
                @if (Auth::guard('client')->check())
                    <a href="{{ route('user.client.dashboard', getParam()) }}"
                        class="drawer-action-card shadow-sm account-bg">
                        <div class="d-flex align-items-center gap-3">
                            <div class="action-icon"><i class="fas fa-user-circle"></i></div>
                            <div>
                                <span class="fw-bold d-block">{{ __('Dashboard') }}</span>
                                <small class="text-muted">{{ __('Welcome') }},
                                    {{ Auth::guard('client')->user()->firstname }}</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>
                @else
                    <a href="{{ route('user.client.login', getParam()) }}" class="drawer-action-card shadow-sm account-bg">
                        <div class="d-flex align-items-center gap-3">
                            <div class="action-icon"><i class="fas fa-sign-in-alt"></i></div>
                            <div>
                                <span class="fw-bold d-block">{{ __('Login') }}</span>
                                <small class="text-muted">{{ __('Access your account') }}</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('user.client.register', getParam()) }}"
                        class="drawer-action-card shadow-sm account-bg mt-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="action-icon" style="background: #f0f9ff; color: #0ea5e9;"><i
                                    class="fas fa-user-plus"></i></div>
                            <div>
                                <span class="fw-bold d-block">{{ __('Sign Up') }}</span>
                                <small class="text-muted">{{ __('Create a new account') }}</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>
                @endif

                <!-- Cart Action -->
                <a href="{{ route('user.front.cart', getParam()) }}"
                    class="drawer-action-card shadow-sm cart-action-bg">
                    <div class="d-flex align-items-center gap-3">
                        <div class="action-icon"><i class="fas fa-shopping-bag"></i></div>
                        <div>
                            <span class="fw-bold d-block">{{ __('My Cart') }}</span>
                            @if($cartCount > 0)
                                <small class="text-success fw-bold">{{ $cartCount }} {{ __('Items') }}</small>
                            @else
                                <small class="text-muted">{{ __('Empty') }}</small>
                            @endif
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-muted small"></i>
                </a>

                @if ($userBs->website_call_waiter == 1)
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#callWaiterModal"
                        class="drawer-action-card shadow-sm waiter-bg" onclick="toggleSidebarHeader(false)">
                        <div class="d-flex align-items-center gap-3">
                            <div class="action-icon"><i class="fas fa-bell"></i></div>
                            <div>
                                <span class="fw-bold d-block">{{ __('Call Waiter') }}</span>
                                <small class="text-muted">{{ __('Need help?') }}</small>
                            </div>
                        </div>
                    </a>
                @endif

                {{-- Logout at the last --}}
                @if (Auth::guard('client')->check())
                    <a href="{{ route('user.client.logout', getParam()) }}"
                        class="drawer-action-card shadow-sm mt-3 border-0" style="background: #fff1f2; color: #e11d48;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="action-icon" style="background: #ffe4e6; color: #e11d48;"><i
                                    class="fas fa-power-off"></i></div>
                            <div>
                                <span class="fw-bold d-block">{{ __('Logout') }}</span>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="drawerHeaderOverlay" class="drawer-overlay" onclick="toggleSidebarHeader(false)"></div>

<style>
    :root {
        --header-bg: #0a4a4f;
        --header-text: #ffffff;
        --drawer-width: 330px;
    }

    .header-main {
        background-color: var(--header-bg) !important;
        z-index: 1050;
    }

    .header-logo {
        color: var(--header-text) !important;
        font-weight: 800;
        font-size: 1.4rem;
        text-decoration: none !important;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .header-icon-btn {
        color: var(--header-text) !important;
        font-size: 1.3rem;
        text-decoration: none !important;
        opacity: 0.9;
        transition: opacity 0.2s;
    }

    .header-icon-btn:hover {
        opacity: 1;
    }

    .cart-badge {
        position: absolute;
        top: -6px;
        right: -10px;
        background: #ef4444;
        color: white;
        font-size: 0.65rem;
        font-weight: 800;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--header-bg);
    }

    .lang-switcher .lang-btn {
        color: rgba(255, 255, 255, 0.7);
        font-weight: 700;
        text-decoration: none !important;
        font-size: 0.85rem;
        padding: 4px 6px;
        transition: all 0.2s;
    }

    .lang-switcher .lang-btn.active {
        color: #fff;
    }

    /* Search Overlay */
    .search-header-overlay {
        position: fixed;
        top: -100%;
        left: 0;
        width: 100%;
        background: white;
        z-index: 2100;
        transition: top 0.4s ease-in-out;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-bottom-left-radius: 25px;
        border-bottom-right-radius: 25px;
    }

    .search-header-overlay.active {
        top: 0;
    }

    .search-input-group {
        background: #f8f9fa;
        border-radius: 12px;
        overflow: hidden;
    }

    .btn-search-go {
        background: var(--header-bg);
        color: #fff;
        padding: 10px 20px;
    }

    .btn-search-go:hover {
        color: #fff;
        opacity: 0.9;
    }

    .search-results-dropdown {
        position: absolute;
        top: 110%;
        left: 0;
        width: 100%;
        background: white;
        border-radius: 15px;
        max-height: 400px;
        overflow-y: auto;
        z-index: 2110;
        border: 1px solid #f0f0f0;
    }

    .search-result-row {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        text-decoration: none;
        color: #333;
        border-bottom: 1px solid #f8f9fa;
    }

    .search-result-row:hover {
        background: #f8f9fa;
    }

    .search-result-row img {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        margin-right: 15px;
        object-fit: cover;
    }

    /* Side Drawer */
    .side-drawer-header {
        position: fixed;
        top: 0;
        right: calc(-1 * var(--drawer-width) - 20px);
        width: var(--drawer-width);
        height: 100%;
        background: #ffffff;
        z-index: 2200;
        transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
    }

    .side-drawer-header.active {
        right: 0;
    }

    .drawer-content {
        flex: 1;
        overflow-y: auto;
        padding-bottom: 50px !important;
        /* Space at bottom for last items */
    }

    /* Modern Scrollbar for Drawer */
    .drawer-content::-webkit-scrollbar {
        width: 5px;
    }

    .drawer-content::-webkit-scrollbar-track {
        background: transparent;
    }

    .drawer-content::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .drawer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        z-index: 2150;
        display: none;
        backdrop-filter: blur(2px);
    }

    .drawer-overlay.active {
        display: block;
    }

    .drawer-close-btn {
        background: #f1f5f9;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
    }

    .drawer-menu-item,
    .drawer-action-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: white;
        border-radius: 14px;
        margin-bottom: 12px;
        text-decoration: none !important;
        border: 1px solid rgba(0, 0, 0, 0.03);
        transition: transform 0.2s;
    }

    .drawer-menu-item:active,
    .drawer-action-card:active {
        transform: scale(0.98);
    }

    .drawer-child-item {
        display: block;
        padding: 10px 15px;
        color: #64748b;
        font-weight: 500;
        text-decoration: none !important;
        border-left: 2px solid #e2e8f0;
        margin-left: 20px;
        font-size: 0.95rem;
    }

    .drawer-action-card {
        background: #f8fafc;
        border: none;
    }

    .action-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .account-bg .action-icon {
        background: #eff6ff;
        color: #3b82f6;
    }

    .cart-action-bg .action-icon {
        background: #f0fdf4;
        color: #22c55e;
    }

    .waiter-bg .action-icon {
        background: #fff7ed;
        color: #f97316;
    }

    @media (max-width: 500px) {
        :root {
            --drawer-width: 85%;
        }

        .header-logo {
            font-size: 1.2rem;
        }
    }
</style>

<script>
    function toggleSearchHeader(show) {
        const modal = document.getElementById('searchHeaderModal');
        if (show) {
            modal.classList.add('active');
            setTimeout(() => document.getElementById('globalSearchInput').focus(), 400);
        } else {
            modal.classList.remove('active');
            document.getElementById('liveSearchResults').classList.add('d-none');
        }
    }

    function toggleSidebarHeader(show) {
        document.getElementById('sideDrawerHeader').classList.toggle('active', show);
        document.getElementById('drawerHeaderOverlay').classList.toggle('active', show);
        document.body.style.overflow = show ? 'hidden' : 'auto';
    }

    function executeHeaderSearch() {
        const q = document.getElementById('globalSearchInput').value.trim();
        if (!q) return;
        window.location.href = "{{ route('user.front.items', [getParam()]) }}?search=" + encodeURIComponent(q);
    }

    document.getElementById('globalSearchInput')?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') executeHeaderSearch();
    });
</script>