<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('adminlte/dist/img/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Adhits And Partners</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if (Auth::user()->role === 'Superuser' || Auth::user()->role === 'Direktur' || Auth::user()->role === 'Manager' || Auth::user()->role === 'Supervisor')
                    <li
                        class="nav-item {{ Request::is('users*') || Request::is('departments*') || Request::is('budget-departments*') || Request::is('units*') || Request::is('suppliers*') || Request::is('po-approvals*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::is('users*') || Request::is('departments*') || Request::is('budget-departments*') || Request::is('suppliers*') || Request::is('po-approvals*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                MASTER
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                    class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                                    <i class="fas fa-users nav-icon"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('departments.index') }}"
                                    class="nav-link {{ Request::is('departments*') ? 'active' : '' }}">
                                    <i class="far fa-building nav-icon"></i>
                                    <p>Departments</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('budget-departments.index') }}"
                                    class="nav-link {{ Request::is('budget-departments*') ? 'active' : '' }}">
                                    <i class="fas fa-wallet nav-icon"></i>
                                    <p>Budget Departments</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('units.index') }}"
                                    class="nav-link {{ Request::is('units*') ? 'active' : '' }}">
                                    <i class="fas fa-balance-scale nav-icon"></i>
                                    <p>Units</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('suppliers.index') }}"
                                    class="nav-link {{ Request::is('suppliers*') ? 'active' : '' }}">
                                    <i class="fas fa-id-card nav-icon"></i>
                                    <p>Supplier</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('purchase-orders.index') }}"
                        class="nav-link {{ Request::is('purchase-orders*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Purchase Order</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
