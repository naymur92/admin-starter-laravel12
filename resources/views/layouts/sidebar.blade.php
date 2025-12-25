<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            {{-- <i class="fas fa-laugh-wink"></i> --}}
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Laravel') }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>


    <!-- Divider -->
    {{-- <hr class="sidebar-divider"> --}}
    <!-- Heading -->
    {{-- <div class="sidebar-heading mb-2">
        Product Management
    </div> --}}

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading mb-2">
        Configuration
    </div>

    {{-- Nav Item - User & Role Management Menu --}}
    @canany(['user-list', 'permission-list', 'role-list'])
        @php
            $isActiveUsrRlMngMenu = request()->routeIs('users.*', 'permissions.*', 'roles.*');
        @endphp
        <li class="nav-item {{ $isActiveUsrRlMngMenu ? 'active' : '' }}">
            <a class="nav-link {{ $isActiveUsrRlMngMenu ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#usrRlMngMenu"
                aria-expanded="{{ $isActiveUsrRlMngMenu ? 'true' : 'false' }}" aria-controls="usrRlMngMenu">
                <i class="fas fa-users-cog"></i>
                <span>Users &amp; Roles</span>
            </a>
            <div id="usrRlMngMenu" class="collapse {{ $isActiveUsrRlMngMenu ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    {{-- <h6 class="collapse-header">:</h6> --}}

                    @can('user-list')
                        <a class="collapse-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="fa-solid fa-users mr-2"></i>Users
                        </a>
                    @endcan

                    @can('permission-list')
                        <a class="collapse-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                            <i class="fas fa-shield-alt mr-2"></i>Permissions
                        </a>
                    @endcan

                    @can('role-list')
                        <a class="collapse-item {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                            <i class="fas fa-user-shield mr-2"></i>Roles
                        </a>
                    @endcan

                </div>
            </div>
        </li>
    @endcan


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
