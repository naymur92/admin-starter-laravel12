<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <div class="float-left mr-auto ml-3 pl-1 topbar-title">
        <h1 class="page-title text-truncate text-primary font-weight-medium mb-1">@yield('title')</h1>
    </div>

    <ul class="navbar-nav float-end ml-auto">
        <div class="first-topbar-divider topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                @if (Auth::user()->profilePicture)
                    <img class="img-profile rounded-circle" src="{{ asset('/') }}{{ Auth::user()->profilePicture->path . '/' . Auth::user()->profilePicture->name }}">
                @else
                    <img class="img-profile rounded-circle" src="{{ asset('/') }}assets/uploads/users/user.png">
                @endif
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('user-profile.show') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="{{ route('user-profile.change-password') }}">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Change Password
                </a>
                {{-- <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a> --}}
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST" onsubmit="swalConfirmationOnSubmit(event, 'Are you sure to logout?');">
                    @csrf

                    <button class="dropdown-item dropdown-logout-button btn btn-sm btn-link btn-outline-danger" type="submit">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </button>
                </form>
            </div>
        </li>

    </ul>

</nav>
