@php
    use App\Models\User;
    $id = auth()->user();
    // $notifications = DB::table('notifications')->orderBy('created_at', 'desc')->limit(5)->get();
    // $unreadCount = DB::table('notifications')->where('is_read', false)->count();
    $foto = User::find($id->id);
@endphp

<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top bg-maron">
    <button id="sidebarToggleTop" class="btn rounded-circle mr-3">
        <i class="fa fa-bars text-light"></i>
    </button>
    <ul class="navbar-nav ml-auto">
        {{-- <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-search fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
          aria-labelledby="searchDropdown">
          <form class="navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-1 small" placeholder="What do you want to look for?"
                aria-label="Search" aria-describedby="basic-addon2" style="border-color: #3f51b5;">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> --}}
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                {{-- @if ($unreadCount > 0)
                    <span class="badge badge-primary badge-counter mb-2">{{ $unreadCount }}</span>
                @endif --}}
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                {{-- @foreach ($notifications as $notif)
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
                @endforeach --}}
                <a class="dropdown-item text-center small text-gray-500" href="">
                    Tandai Semua Dibaca
                </a>

                {{-- <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a> --}}
            </div>
        </li>
        {{-- <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-envelope fa-fw"></i>
          <span class="badge badge-warning badge-counter mb-2">2</span>
        </a>
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
          aria-labelledby="messagesDropdown">
          <h6 class="dropdown-header">
            Message Center
          </h6>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="dropdown-list-image mr-3">
              <img class="rounded-circle" src="{{ url('assets/img/man.png')}}" style="max-width: 60px" alt="">
              <div class="status-indicator bg-success"></div>
            </div>
            <div class="font-weight-bold">
              <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been
                having.</div>
              <div class="small text-gray-500">Udin Cilok · 58m</div>
            </div>
          </a>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="dropdown-list-image mr-3">
              <img class="rounded-circle" src="{{ url('assets/img/girl.png')}}" style="max-width: 60px" alt="">
              <div class="status-indicator bg-default"></div>
            </div>
            <div>
              <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people
                say this to all dogs, even if they aren't good...</div>
              <div class="small text-gray-500">Jaenab · 2w</div>
            </div>
          </a>
          <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
        </div>
      </li> --}}
        {{-- <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-tasks fa-fw "></i>
          <span class="badge badge-success badge-counter mb-2">3</span>
        </a>
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
          aria-labelledby="messagesDropdown">
          <h6 class="dropdown-header">
            Task
          </h6>
          <a class="dropdown-item align-items-center" href="#">
            <div class="mb-3">
              <div class="small text-gray-500">Design Button
                <div class="small float-right"><b>50%</b></div>
              </div>
              <div class="progress" style="height: 12px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50"
                  aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </a>
          <a class="dropdown-item align-items-center" href="#">
            <div class="mb-3">
              <div class="small text-gray-500">Make Beautiful Transitions
                <div class="small float-right"><b>30%</b></div>
              </div>
              <div class="progress" style="height: 12px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30"
                  aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </a>
          <a class="dropdown-item align-items-center" href="#">
            <div class="mb-3">
              <div class="small text-gray-500">Create Pie Chart
                <div class="small float-right"><b>75%</b></div>
              </div>
              <div class="progress" style="height: 12px;">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75"
                  aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </a>
          <a class="dropdown-item text-center small text-gray-500" href="#">View All Taks</a>
        </div>
      </li> --}}
        <div class="topbar-divider d-none d-sm-block"></div>
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle"
                    src="{{ $foto->foto ?  asset('storage/' . $foto->foto) : asset('assets/img/boy.png') }}">
                <div class="d-flex flex-column">
                    <span class="ml-2 d-none d-lg-inline text-white small fw-bold">{{ $id->name }}</span>

                    <span class="ml-2 d-none d-lg-inline text-white"
                        style="font-size: 12px;">{{ ucwords(auth()->user()->roles->first()->name) }}</span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.index') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-maron"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-maron"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-maron"></i>
                    Activity Log
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
