{{-- @auth
    @php
        $user = auth()->user();
    @endphp

@endauth --}}

<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top bg-maron">
    <button id="sidebarToggleTop" class="btn rounded-circle mr-3">
        <i class="fa fa-bars text-light"></i>
    </button>
    <ul class="navbar-nav ml-auto">
        {{-- <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                @if ($unreadCount > 0)
                    <span class="badge badge-primary badge-counter mb-2">{{ $unreadCount }}</span>
                @endif
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                @foreach ($notifications as $notif)
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="mr-3">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">{{ $notif->created_at }}</div>

                            <span class="font-weight-bold">{{ $notif->message }}</span>
                        </div>
                    </a>
                @endforeach
                <a class="dropdown-item text-center small text-gray-500" href="">
                    Tandai Semua Dibaca
                </a>

                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
        </li> --}}

        <div class="topbar-divider d-none d-sm-block"></div>
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle"
                    src="{{  asset('assets/img/boy.png') }}">
                <div class="d-flex flex-column">
                    @auth
                    <span class="ml-2 d-none d-lg-inline text-white small fw-bold">{{ auth()->user()->name }}</span>
                    <span class="ml-2 d-none d-lg-inline text-white"
                    style="font-size: 12px;">{{ ucwords(auth()->user()->roles->first()->name) }}</span>
                    @endauth
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.index') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-maron"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-maron"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelLogout"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-light" id="exampleModalLabelLogout">Logout</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin keluar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal">Cancel</button>
                <form action="{{ Route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
