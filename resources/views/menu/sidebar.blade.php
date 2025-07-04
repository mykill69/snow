@php
    $current_route=request()->route()->getName();
    $user_role = auth()->user()->role;
    use App\Models\Log;
@endphp
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    
        <li class="nav-item">
            @if($user_role == 'Administrator')
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Dashboard</p>
                </a>
            @endif
        </li>
        <li class="nav-item">
            <a href="{{ route('allTickets') }}" class="nav-link {{ request()->routeIs('allTickets') ? 'active' : '' }}">
                <i class="fas fa-tags nav-icon"></i>
                <p>All Tickets</p>
            </a>
        </li>
        <li class="nav-item">
           <a href="{{ route('myTickets') }}" class="nav-link {{ request()->routeIs('myTickets') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt nav-icon"></i>
                <p>My Tickets</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ticketReports') }}" class="nav-link {{ request()->routeIs('ticketReports') ? 'active' : '' }}" class="nav-link">
                <i class="fas fa-chart-bar nav-icon"></i>
                <p>Reports</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('userView') }}" class="nav-link {{ request()->routeIs('userView') ? 'active' : '' }}">
                <i class="fas fa-users-cog nav-icon"></i>
                <p>User Management</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('articles') }}" class="nav-link {{ request()->routeIs('articles') ? 'active' : '' }}" class="nav-link">
                <i class="fas fa-book nav-icon"></i>
                <p>Articles & FAQs</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-file-alt nav-icon"></i>
                <p>Forms</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('auditLogs') }}" class="nav-link {{ request()->routeIs('auditLogs') ? 'active' : '' }}" class="nav-link">
                <i class="fas fa-clipboard-list nav-icon"></i>
                <p>Audit Trails & Logs</p>
            </a>
        </li>


</nav>