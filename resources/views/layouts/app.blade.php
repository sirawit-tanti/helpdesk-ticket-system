<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Helpdesk Ticket System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="bg-light">
    @auth
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container-fluid">
            <button class="btn app-mobile-menu-btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i class="bi bi-list"></i>
            </button>

            <a class="navbar-brand app-navbar-brand" href="{{ route('dashboard') }}">
                <span class="app-brand-mark" aria-hidden="true">
                    <!-- <span class="app-brand-ticket"></span> -->
                    <i class="bi bi-headset"></i>
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

    const bulkActionForm = document.getElementById('bulkActionForm');
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    const selectAllTickets = document.getElementById('selectAllTickets');

    function refreshBulkTicketCheckboxes() {
        if (!bulkActionForm || !bulkActionSelect) {
            return;
        }

        const action = bulkActionSelect.value;
        const currentUserId = bulkActionForm.getAttribute('data-current-user-id');

        document.querySelectorAll('.ticket-checkbox').forEach(function(checkbox) {
            const assigneeId = checkbox.getAttribute('data-assignee-id');
            const isClosed = checkbox.getAttribute('data-is-closed') === '1';

            let shouldDisable = false;

            if (action === 'assign_to_me' && assigneeId === currentUserId) {
                shouldDisable = true;
            }

            if (action === 'close' && isClosed) {
                shouldDisable = true;
            }

            checkbox.disabled = shouldDisable;

            if (shouldDisable) {
                checkbox.checked = false;
            }
        });

        if (selectAllTickets) {
            selectAllTickets.checked = false;
        }
    }

    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', refreshBulkTicketCheckboxes);
        refreshBulkTicketCheckboxes();
    }

    if (selectAllTickets) {
        selectAllTickets.addEventListener('change', function() {
            document.querySelectorAll('.ticket-checkbox:not(:disabled)').forEach(function(checkbox) {
                checkbox.checked = selectAllTickets.checked;
            });
        });
    }

    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(event) {
            const checkedTickets = document.querySelectorAll('.ticket-checkbox:checked');

            if (checkedTickets.length === 0) {
                event.preventDefault();
                alert('Please select at least one available ticket.');
                return;
            }

            if (!confirm('Are you sure you want to apply this bulk action?')) {
                event.preventDefault();
            }
        });
    }

    document.querySelectorAll('[data-auto-submit]').forEach(function(element) {
        element.addEventListener('change', function() {
            element.closest('form')?.submit();
        });
    });
    </script>
</body>

</html>