<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        {{-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ Auth::user()->unreadNotifications->count() }}
                    Notifikasi</span>

                @foreach (Auth::user()->unreadNotifications as $notification)
                    <div class="dropdown-divider"></div>
                    <a href="{{ $notification->data['url'] }}" class="dropdown-item">
                        <i class="fas fa-info-circle mr-2"></i> {{ $notification->data['message'] }}
                        <span
                            class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                    </a>
                @endforeach

                <div class="dropdown-divider"></div>
                <a href="{{ route('notifications.markAllRead') }}" class="dropdown-item dropdown-footer">Tandai semua telah
                    dibaca</a>
            </div>
        </li> --}}

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-bell"></i>
                <span class="badge badge-warning">{{ auth()->user()->unreadNotifications->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">
                    {{ auth()->user()->unreadNotifications->count() }} Notifikasi
                </span>
                <div class="dropdown-divider"></div>

                @foreach(auth()->user()->unreadNotifications as $notification)
                    <a href="{{ route('notifications.read', $notification->id) }}" class="dropdown-item">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ $notification->data['message'] }}
                        <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach

                <a href="{{ route('notifications.markAllRead') }}" class="dropdown-item dropdown-footer">
                    Tandai semua telah dibaca
                </a>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>
