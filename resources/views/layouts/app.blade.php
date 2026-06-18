<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Helpdesk Ticket System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">
                Helpdesk
            </a>

            <div class="d-flex align-items-center gap-3">
                @auth
                <span class="text-white small">
                    {{ auth()->user()->name }}
                    @if(auth()->user()->role)
                    <span class="badge bg-secondary ms-1">
                        {{ auth()->user()->role->display_name }}
                    </span>
                    @endif
                </span>

                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        Logout
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row min-vh-100">
            @auth
            <aside class="col-md-3 col-lg-2 bg-white border-end p-3">
                <div class="list-group list-group-flush">
                    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action active">
                        Dashboard
                    </a>

                    <a href="{{ route('tickets.index') }}" class="list-group-item list-group-item-action">
                        Tickets
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        Users
                    </a>

                    @if(auth()->user()->canManageTickets())
                    <div class="text-muted small text-uppercase px-3 mt-3 mb-2">
                        Admin
                    </div>

                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                        Users
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action">
                        Categories
                    </a>

                    <a href="{{ route('admin.departments.index') }}" class="list-group-item list-group-item-action">
                        Departments
                    </a>

                    <a href="{{ route('admin.priorities.index') }}" class="list-group-item list-group-item-action">
                        Priorities
                    </a>

                    <a href="{{ route('admin.statuses.index') }}" class="list-group-item list-group-item-action">
                        Statuses
                    </a>
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

</html>