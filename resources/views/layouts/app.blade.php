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
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container-fluid">
            <a class="navbar-brand app-navbar-brand" href="{{ route('dashboard') }}">
                <span class="app-brand-mark" aria-hidden="true">
                    <span class="app-brand-ticket"></span>
                    <span class="app-brand-dot"></span>
                </span>
                <span>Helpdesk</span>
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

                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf

                    <button type="submit" class="btn app-logout-btn">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row min-vh-100">
            @auth
            <aside class="col-md-3 col-lg-2 bg-white border-end p-3">
                @php
                $isTicketMenuOpen = request()->routeIs('tickets.*');
                $isAdminMenuOpen = request()->routeIs('admin.*');
                @endphp

                <div class="list-group list-group-flush">
                    <a href="{{ route('dashboard') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    @if(auth()->user()->canManageTickets())
                    <a href="{{ route('reports.index') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        Reports
                    </a>
                    @endif

                    <button
                        class="list-group-item list-group-item-action sidebar-menu-toggle {{ $isTicketMenuOpen ? 'active-parent' : '' }}"
                        type="button" data-bs-toggle="collapse" data-bs-target="#ticketSubmenu"
                        aria-expanded="{{ $isTicketMenuOpen ? 'true' : 'false' }}" aria-controls="ticketSubmenu">
                        <span>Ticket</span>
                        <span class="sidebar-menu-arrow">▾</span>
                    </button>

                    <div class="collapse {{ $isTicketMenuOpen ? 'show' : '' }}" id="ticketSubmenu">
                        <a href="{{ route('tickets.index') }}"
                            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('tickets.index') ? 'active' : '' }}">
                            All Tickets
                        </a>

                        <a href="{{ route('tickets.my') }}"
                            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('tickets.my') ? 'active' : '' }}">
                            My Tickets
                        </a>

                        @if(auth()->user()->canManageTickets())
                        <a href="{{ route('tickets.assigned-to-me') }}"
                            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('tickets.assigned-to-me') ? 'active' : '' }}">
                            Assigned to Me
                        </a>

                        <a href="{{ route('tickets.unassigned') }}"
                            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('tickets.unassigned') ? 'active' : '' }}">
                            Unassigned Queue
                        </a>
                        @endif
                    </div>

                    @if(auth()->user()->canAccessAdminArea())
                    <button
                        class="list-group-item list-group-item-action sidebar-menu-toggle {{ $isAdminMenuOpen ? 'active-parent' : '' }}"
                        type="button" data-bs-toggle="collapse" data-bs-target="#adminSubmenu"
                        aria-expanded="{{ $isAdminMenuOpen ? 'true' : 'false' }}" aria-controls="adminSubmenu">
                        <span>Admin</span>
                        <span class="sidebar-menu-arrow">▾</span>
                    </button>

                    <div class="collapse {{ $isAdminMenuOpen ? 'show' : '' }}" id="adminSubmenu">
                        <a href="{{ route('admin.users.index') }}"
                            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            Users
                        </a>

                        <a href="{{ route('admin.categories.index') }}"
                            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            Categories
                        </a>

                        <a href="{{ route('admin.departments.index') }}"
                            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                            Departments
                        </a>

                        <a href="{{ route('admin.priorities.index') }}"
                            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.priorities.*') ? 'active' : '' }}">
                            Priorities
                        </a>

                        <a href="{{ route('admin.statuses.index') }}"
                            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.statuses.*') ? 'active' : '' }}">
                            Statuses
                        </a>
                    </div>
                    @endif
                </div>
            </aside>
            @endauth

            <main class="@auth col-md-9 col-lg-10 @else col-12 @endauth p-4">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>dn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>