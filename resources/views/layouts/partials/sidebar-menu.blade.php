@php
$menuPrefix = $menuPrefix ?? 'sidebar';
$ticketSubmenuId = $menuPrefix . 'TicketSubmenu';
$adminSubmenuId = $menuPrefix . 'AdminSubmenu';

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
        type="button" data-bs-toggle="collapse" data-bs-target="#{{ $ticketSubmenuId }}"
        aria-expanded="{{ $isTicketMenuOpen ? 'true' : 'false' }}" aria-controls="{{ $ticketSubmenuId }}">
        <span>Ticket</span>
        <span class="sidebar-menu-arrow">▾</span>
    </button>

    <div class="collapse {{ $isTicketMenuOpen ? 'show' : '' }}" id="{{ $ticketSubmenuId }}">
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
        type="button" data-bs-toggle="collapse" data-bs-target="#{{ $adminSubmenuId }}"
        aria-expanded="{{ $isAdminMenuOpen ? 'true' : 'false' }}" aria-controls="{{ $adminSubmenuId }}">
        <span>Admin</span>
        <span class="sidebar-menu-arrow">▾</span>
    </button>

    <div class="collapse {{ $isAdminMenuOpen ? 'show' : '' }}" id="{{ $adminSubmenuId }}">
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