<!DOCTYPE html>
<html lang="uz" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Mohir Qo'llar - O'zbekiston hunarmandlari marketplace">
    <meta name="theme-color" content="#6366f1">
    <meta property="og:title" content="@yield('title', 'Mohir Qo\'llar')">
    <meta property="og:description" content="Mohir Qo'llar - O'zbekiston hunarmandlari marketplace">
    <meta property="og:image" content="{{ asset('img/logo/logo-horizontal-white-1600.png') }}">
    <title>@yield('title', 'Mohir Qo\'llar') - Marketplace</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logo/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo/app-icon-1024x1024.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    {{-- Mobile Header --}}
    <header class="app-header mobile-only">
        <div class="container-fluid px-3">
            <div class="d-flex align-items-center justify-content-between py-2">
                <a href="{{ route('home') }}" class="app-logo text-decoration-none">
                    <img src="{{ asset('img/logo/icon-transparent-1024x1024.png') }}" alt="Mohir Qollar" class="logo-icon-img">
                    <span class="logo-text">MohirQo'llar</span>
                </a>

                <div class="header-actions d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ route('wishlist.index') }}" class="btn btn-icon position-relative">
                            <i class="bi bi-heart"></i>
                        </a>
                        <a href="{{ route('cart.index') }}" class="btn btn-icon position-relative">
                            <i class="bi bi-bag"></i>
                            @if(auth()->user()->cartItems()->count() > 0)
                                <span class="badge-count">{{ auth()->user()->cartItems()->count() }}</span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="bi bi-person me-1"></i>Kirish
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- Desktop Header --}}
    <header class="desktop-header">
        <div class="container py-3">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('home') }}" class="app-logo text-decoration-none">
                    <img src="{{ asset('img/logo/icon-transparent-1024x1024.png') }}" alt="Mohir Qollar" class="logo-icon-img logo-icon-img-lg">
                    <span class="logo-text fs-4">MohirQo'llar</span>
                </a>

                <nav class="d-flex align-items-center gap-3 gap-xl-4 fw-medium">
                    <a href="{{ route('home') }}" class="text-decoration-none text-dark {{ request()->routeIs('home') ? 'text-primary' : '' }}">Bosh sahifa</a>
                    <a href="{{ route('products.index') }}" class="text-decoration-none text-dark {{ request()->routeIs('products.*') ? 'text-primary' : '' }}">Katalog</a>
                    <a href="{{ route('artisans.index') }}" class="text-decoration-none text-dark {{ request()->routeIs('artisans.*') ? 'text-primary' : '' }}">Ustalar</a>
                    <a href="{{ route('promotions.index') }}" class="text-decoration-none text-dark {{ request()->routeIs('promotions.*') ? 'text-primary' : '' }}">Aksiyalar</a>
                    <a href="{{ route('help.index') }}" class="text-decoration-none text-dark {{ request()->routeIs('help.*') ? 'text-primary' : '' }}">Yordam</a>
                </nav>

                <div class="d-flex align-items-center gap-2 gap-xl-3">
                    <form action="{{ route('products.index') }}" method="GET" class="position-relative d-none d-lg-block">
                        <input type="text" name="search" class="form-control rounded-pill ps-4 header-search" placeholder="Qidirish...">
                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </form>
                    
                    @auth
                        <a href="{{ route('wishlist.index') }}" class="btn btn-icon"><i class="bi bi-heart"></i></a>
                        <a href="{{ route('cart.index') }}" class="btn btn-icon position-relative">
                            <i class="bi bi-bag"></i>
                            @if(auth()->user()->cartItems()->count() > 0)
                                <span class="badge-count">{{ auth()->user()->cartItems()->count() }}</span>
                            @endif
                        </a>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle border" width="32" height="32">
                                <span class="small fw-bold">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2">
                                <li>
                                    <a class="dropdown-item py-2" href="@if(auth()->user()->isAdmin()) {{ route('admin.dashboard') }} @elseif(auth()->user()->isArtisan()) {{ route('artisan.dashboard') }} @else {{ route('user.dashboard') }} @endif">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li><a class="dropdown-item py-2" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Chiqish</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">Kirish</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- Sticky Search (Mobile) --}}
    <div class="sticky-search mobile-only">
        <div class="container-fluid px-3">
            <form action="{{ route('products.index') }}" method="GET" class="search-form">
                <div class="input-group">
                    <span class="input-group-text border-0 bg-transparent">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-0 bg-transparent"
                        placeholder="Mahsulot qidirish..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="container-fluid px-3 mt-2">
            <div class="alert alert-success alert-dismissible fade show py-2 small rounded-3" role="alert">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container-fluid px-3 mt-2">
            <div class="alert alert-danger alert-dismissible fade show py-2 small rounded-3" role="alert">
                <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="container-fluid px-3 mt-2">
            <div class="alert alert-warning alert-dismissible fade show py-2 small rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i>{{ session('warning') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="container-fluid px-3 mt-2">
            <div class="alert alert-danger alert-dismissible fade show py-2 small rounded-3" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="app-content">
        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    @auth
        <nav class="bottom-nav">
            <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house"></i>
                <span>Bosh sahifa</span>
            </a>
            <a href="{{ route('products.index') }}"
                class="bottom-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span>Katalog</span>
            </a>
            <a href="{{ route('cart.index') }}" class="bottom-nav-item {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                <i class="bi bi-bag"></i>
                <span>Savat</span>
            </a>
            <a href="{{ route('promotions.index') }}"
                class="bottom-nav-item {{ request()->routeIs('promotions.*') ? 'active' : '' }}">
                <i class="bi bi-tag"></i>
                <span>Aksiyalar</span>
            </a>
            <a href="{{ route('chat.index') }}" class="bottom-nav-item {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots"></i>
                <span>Chat</span>
            </a>
            <a href="{{ route('help.index') }}" class="bottom-nav-item {{ request()->routeIs('help.*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i>
                <span>Yordam</span>
            </a>
            <a href="@if(auth()->user()->isAdmin()) {{ route('admin.dashboard') }} @elseif(auth()->user()->isArtisan()) {{ route('artisan.dashboard') }} @else {{ route('user.dashboard') }} @endif"
                class="bottom-nav-item {{ request()->routeIs('user.*', 'artisan.*', 'admin.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>Profil</span>
            </a>
        </nav>
    @else
        <nav class="bottom-nav">
            <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house"></i>
                <span>Bosh sahifa</span>
            </a>
            <a href="{{ route('products.index') }}"
                class="bottom-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span>Katalog</span>
            </a>
            <a href="{{ route('artisans.index') }}"
                class="bottom-nav-item {{ request()->routeIs('artisans.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Ustalar</span>
            </a>
            <a href="{{ route('promotions.index') }}"
                class="bottom-nav-item {{ request()->routeIs('promotions.*') ? 'active' : '' }}">
                <i class="bi bi-tag"></i>
                <span>Aksiyalar</span>
            </a>
            <a href="{{ route('help.index') }}" class="bottom-nav-item {{ request()->routeIs('help.*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i>
                <span>Yordam</span>
            </a>
            <a href="{{ route('login') }}" class="bottom-nav-item">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Kirish</span>
            </a>
        </nav>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark mode toggle
        function toggleTheme() {
            const html = document.documentElement;
            const theme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
        }
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);

        // CSRF for AJAX
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    @guest
        <script>
            // Telegram Mini App: auto-login using signed initData, before any page renders a login prompt
            (function () {
                function autoLogin() {
                    var tg = window.Telegram && window.Telegram.WebApp;
                    if (!tg || !tg.initData) return;

                    tg.ready();

                    fetch('{{ route('telegram.webapp-auth') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': window.csrfToken,
                        },
                        body: JSON.stringify({ init_data: tg.initData }),
                    })
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            if (data && data.ok) {
                                window.location.href = data.redirect || window.location.href;
                            }
                        })
                        .catch(function () { /* not inside Telegram or network hiccup */ });
                }

                if (window.Telegram && window.Telegram.WebApp) {
                    autoLogin();
                } else {
                    var script = document.createElement('script');
                    script.src = 'https://telegram.org/js/telegram-web-app.js';
                    script.onload = autoLogin;
                    document.head.appendChild(script);
                }
            })();
        </script>
    @endguest
    @stack('scripts')
</body>

</html>