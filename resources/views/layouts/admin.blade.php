<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Presensi BPS</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>

/* Rapikan posisi pagination */
.pagination{
    margin-top:12px !important;
}

.pagination .page-link{
    padding:6px 12px;
    font-size:14px;
}

.pagination .page-item{
    margin-left:10px;
}

        body{
            background:#f4f7fc;
        }

        .navbar-bps{
            background:#0d6efd;
        }

        .navbar-bps .navbar-brand,
        .navbar-bps .nav-link{
            color:white !important;
            font-weight:500;
        }

        .navbar-bps .nav-link:hover{
            color:#dbeafe !important;
        }

        .navbar-bps .nav-link.active{
            font-weight:bold;
            border-bottom:2px solid white;
        }

        .card-dashboard{
            border:none;
            border-radius:15px;
            box-shadow:0 3px 10px rgba(0,0,0,.1);
        }

/* Pagination */
.pagination{
    margin-bottom:0;
}

.pagination .page-link{
    padding:6px 12px;
    font-size:14px;
}

.pagination svg{
    width:14px;
    height:14px;
}

    </style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-bps">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="{{ url('/admin/home') }}">
            BPS Presensi
        </a>

        <button class="navbar-toggler bg-white"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav me-auto">

                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/home') ? 'active' : '' }}"
                       href="{{ url('/admin/home') }}">
                        <i class="bi bi-house-door"></i>
                        Home
                    </a>
                </li>

                <!-- Mahasiswa -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/mahasiswa*') ? 'active' : '' }}"
                       href="{{ url('/admin/mahasiswa') }}">
                        <i class="bi bi-people"></i>
                        Mahasiswa
                    </a>
                </li>

                <!-- Rekap -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/rekap') ? 'active' : '' }}"
                       href="{{ url('/admin/rekap') }}">
                        <i class="bi bi-calendar-check"></i>
                        Rekap Presensi
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/kelola-pendaftar*') ? 'active' : '' }}"
                    href="{{ url('/admin/kelola-pendaftar') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        Kelola Pendaftar
                    </a>
                </li>

                <!-- Sertifikat -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/sertifikat') ? 'active' : '' }}"
                       href="{{ url('/admin/sertifikat') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        Sertifikat
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </a>
                </li>

            </ul>

            <span class="text-white">
                <i class="bi bi-person-circle"></i>
                Admin
            </span>

        </div>

    </div>

</nav>

<div class="container mt-4">

    @yield('content')

</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')

</body>

</html>