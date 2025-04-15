<div class="sidebar">
    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <a onclick="modalAction('{{ url('/profile') }}')">
                <img src="{{ asset(auth()->user()->profile_picture ? 'storage/' . auth()->user()->profile_picture : 'img/user.jpg') }}"
                class="img-circle elevation-2"
                style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;" alt="User Image">
            </a>
        </div>
        <div class="info">
            <a onclick="modalAction('{{ url('/profile') }}')" class="d-block">{{auth()->user()->nama}}</a>
        </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard') ?
    'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-header">Data Pengguna</li>
            <li class="nav-item">
                <a href="{{ url('/level') }}" class="nav-link {{ ($activeMenu == 'level') ?
    'active' : '' }} ">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>Level User</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user') ?
    'active' : '' }}">
                    <i class="nav-icon far fa-user"></i>
                    <p>Data User</p>
                </a>
            </li>
            <li class="nav-header">Data Barang & Supplier</li>
            <li class="nav-item">
                <a href="{{ url('/kategori') }}" class="nav-link {{ ($activeMenu ==
    'kategori') ? 'active' : '' }} ">
                    <i class="nav-icon far fa-bookmark"></i>
                    <p>Kategori Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu ==
    'barang') ? 'active' : '' }} ">
                    <i class="nav-icon far fa-list-alt"></i>
                    <p>Data Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/supplier') }}" class="nav-link {{ ($activeMenu ==
    'distributor') ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-truck"></i>
                    <p>Data Supplier</p>
                </a>
            </li>
            <li class="nav-header">Data Transaksi</li>
            <li class="nav-item disabled">
                <a href="{{ url('/stok') }}" class="nav-link {{ ($activeMenu == 'stok') ?
    'active' : '' }} ">
                    <i class="nav-icon fas fa-cubes"></i>
                    <p>Stok Barang</p>
                </a>
            </li>
            <li class="nav-item disabled">
                <a href="{{ url('/penjualan') }}" class="nav-link {{ ($activeMenu ==
    'penjualan') ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-cash-register"></i>
                    <p>Transaksi Penjualan</p>
                </a>
            </li>

            <li class="nav-header">Akun</li>
            <li class="nav-item">
                <button class="btn btn-danger" data-toggle="modal" data-target="#logout">Logout</button>
            </li>
        </ul>
    </nav>
</div>