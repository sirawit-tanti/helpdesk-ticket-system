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
        <i class="bi bi-speedometer2 me-2"></i>
        Dashboard
    </a>

    @if(auth()->user()->canManageTickets())
    <a href="{{ route('reports.index') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line me-2"></i>
        Reports
    </a>
    @endif

    <button
        class="list-group-item list-group-item-action sidebar-menu-toggle {{ $isTicketMenuOpen ? 'active-parent' : '' }}"
        type="button" data-bs-toggle="collapse" data-bs-target="#{{ $ticketSubmenuId }}"
        aria-expanded="{{ $isTicketMenuOpen ? 'true' : 'false' }}" aria-controls="{{ $ticketSubmenuId }}">
        <span>
            <i class="bi bi-ticket-perforated me-2"></i>
            Ticket
        </span>
        <span class="sidebar-menu-arrow">▾</span>
    </button>

    <div class="collapse {{ $isTicketMenuOpen ? 'show' : '' }}" id="{{ $ticketSubmenuId }}">

        @if(auth()->user()->canManageTickets())
        <a href="{{ route('tickets.index') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('tickets.index') ? 'active' : '' }}">
            <i class="bi bi-list-ul me-2"></i>
            All Tickets
        </a>

        <a href="{{ route('tickets.my') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('tickets.my') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill me-2"></i>
            My Tickets
        </a>

        <a href="{{ route('tickets.assigned-to-me') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('tickets.assigned-to-me') ? 'active' : '' }}">
            <i class="bi bi-person-check me-2"></i>
            Assigned to Me
        </a>

        <a href="{{ route('tickets.unassigned') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('tickets.unassigned') ? 'active' : '' }}">
            <i class="bi bi-inbox me-2"></i>
            Unassigned Queue
        </a>
        @else
        <a href="{{ route('tickets.my') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('tickets.my') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill me-2"></i>
            My Tickets
        </a>
        @endif
    </div>

    @if(auth()->user()->canAccessAdminArea())
    <button
        class="list-group-item list-group-item-action sidebar-menu-toggle {{ $isAdminMenuOpen ? 'active-parent' : '' }}"
        type="button" data-bs-toggle="collapse" data-bs-target="#{{ $adminSubmenuId }}"
        aria-expanded="{{ $isAdminMenuOpen ? 'true' : 'false' }}" aria-controls="{{ $adminSubmenuId }}">
        <span>
            <i class="bi bi-shield-lock me-2"></i>
            Admin
        </span>
        <span class="sidebar-menu-arrow">▾</span>
    </button>

    <div class="collapse {{ $isAdminMenuOpen ? 'show' : '' }}" id="{{ $adminSubmenuId }}">
        <a href="{{ route('admin.users.index') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i>
            Users
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags me-2"></i>
            Categories
        </a>

        <a href="{{ route('admin.departments.index') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
            <i class="bi bi-building me-2"></i>
            Departments
        </a>

        <a href="{{ route('admin.priorities.index') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.priorities.*') ? 'active' : '' }}">
            <i class="bi bi-flag me-2"></i>
            Priorities
        </a>

        <a href="{{ route('admin.statuses.index') }}"
            class="list-group-item list-group-item-action sidebar-submenu-item {{ request()->routeIs('admin.statuses.*') ? 'active' : '' }}">
            <i class="bi bi-circle-half me-2"></i>
            Statuses
        </a>
    </div>
    @endif
</div>