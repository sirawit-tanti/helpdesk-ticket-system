<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Helpdesk Ticket System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="bg-light">
    @auth
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container-fluid">
            <button class="btn app-mobile-menu-btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                ☰
            </button>

            <a class="navbar-brand app-navbar-brand" href="{{ route('dashboard') }}">
                <span class="app-brand-mark" aria-hidden="true">
                    <span class="app-brand-ticket"></span>
                    <span class="app-brand-dot"></span>
                </span>

                <span class="app-brand-text">
                    Helpdesk
                </span>
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="app-user-info d-none d-md-flex">
                    <div class="app-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="app-user-meta">
                        <div class="app-user-name">
                            {{ auth()->user()->name }}
                        </div>

                        <div class="app-user-role">
                            {{ auth()->user()->role?->display_name ?? 'User' }}
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn app-profile-btn">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf

                    <button type="submit" class="btn app-logout-btn">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start app-mobile-sidebar" tabindex="-1" id="mobileSidebar"
        aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileSidebarLabel">
                Helpdesk Menu
            </h5>

            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0">
            @include('layouts.partials.sidebar-menu', ['menuPrefix' => 'mobile'])
        </div>
    </div>
    @endauth

    <div class="container-fluid">
        <div class="row min-vh-100">
            @auth
            <aside class="col-lg-2 d-none d-lg-block app-sidebar">
                @include('layouts.partials.sidebar-menu', ['menuPrefix' => 'desktop'])
            </aside>
            @endauth

            <main class="{{ auth()->check() ? 'col-12 col-lg-10 app-main-content' : 'col-12 app-guest-content' }}">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.querySelectorAll('[data-password-toggle]').forEach(function(button) {
        button.addEventListener('click', function() {
            const inputId = button.getAttribute('data-password-toggle');
            const input = document.getElementById(inputId);

            if (!input) {
                return;
            }

            const isPassword = input.getAttribute('type') === 'password';

            input.setAttribute('type', isPassword ? 'text' : 'password');
            button.textContent = isPassword ? 'Hide' : 'Show';
        });
    });
    </script>
</body>

</html>