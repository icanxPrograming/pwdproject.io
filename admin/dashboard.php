<?php
session_start();
if (!isset($_SESSION["id_pengguna"])) {
  header("location:login.php");
  exit;
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alter-Ex Admin Dashboard</title>

  <!-- Bootstrap & Font Awesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }

    #wrapper {
      display: flex;
      width: 100%;
    }

    #sidebar-wrapper {
      min-height: 100vh;
      background-color: #212529;
      color: white;
    }

    #sidebar-wrapper .list-group-item {
      background-color: #212529;
      color: white;
      border: none;
      font-weight: 500;
    }

    #sidebar-wrapper .list-group-item:hover,
    #sidebar-wrapper .list-group-item.active {
      background-color: #0d6efd;
      color: white;
    }

    #page-content-wrapper {
      flex: 1;
      padding: 20px;
      background-color: #ffffff;
    }

    .navbar-light {
      background-color: #ffffff !important;
      border-bottom: 1px solid #dee2e6;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: 600;
    }

    .card i {
      float: right;
    }
  </style>
</head>

<body>

  <div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
      <div class="sidebar-heading text-center py-4 border-bottom">
        <h4><i class="fas fa-car"></i> Alter-Ex</h4>
        <small>Admin Panel</small>
      </div>
      <div class="list-group list-group-flush">
        <a href="#" class="list-group-item active"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
        <a href="#" class="list-group-item"><i class="fas fa-car-side mr-2"></i> Kelola Kendaraan</a>
        <a href="#" class="list-group-item"><i class="fas fa-user-tie mr-2"></i> Kelola Penjual</a>
        <a href="#" class="list-group-item"><i class="fas fa-file-invoice-dollar mr-2"></i> Transaksi</a>
        <a href="#" class="list-group-item"><i class="fas fa-images mr-2"></i> Banner Slider</a>
        <a href="#" class="list-group-item"><i class="fas fa-tags mr-2"></i> Kategori</a>
        <a href="#" class="list-group-item"><i class="fas fa-map-marker-alt mr-2"></i> Lokasi</a>
        <a href="#" class="list-group-item"><i class="fas fa-percent mr-2"></i> Promo</a>
        <a href="#" class="list-group-item"><i class="fas fa-headset mr-2"></i> Dukungan</a>
        <a href="#" class="list-group-item"><i class="fas fa-cog mr-2"></i> Pengaturan</a>
      </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <nav class="navbar navbar-expand-lg navbar-light">
        <button class="btn btn-outline-primary btn-sm" id="menu-toggle"><i class="fas fa-bars"></i></button>

        <div class="collapse navbar-collapse justify-content-end">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                <i class="fas fa-user-circle"></i> Admin
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#"><i class="fas fa-user-cog"></i> Profil</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid">
        <h2 class="mt-4"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h2>

        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
          <strong>Selamat datang!</strong> Anda masuk sebagai admin pengelola Alter-Ex.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>

        <div class="row mt-4">
          <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
              <div class="card-body">
                <div class="card-title">Total Kendaraan</div>
                <h3 class="card-text">124</h3>
                <i class="fas fa-car fa-2x"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-warning text-white">
              <div class="card-body">
                <div class="card-title">Penjual Terdaftar</div>
                <h3 class="card-text">58</h3>
                <i class="fas fa-user-tie fa-2x"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-success text-white">
              <div class="card-body">
                <div class="card-title">Transaksi Sukses</div>
                <h3 class="card-text">32</h3>
                <i class="fas fa-file-invoice-dollar fa-2x"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-info text-white">
              <div class="card-body">
                <div class="card-title">Promo Aktif</div>
                <h3 class="card-text">4</h3>
                <i class="fas fa-tags fa-2x"></i>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>

</body>

</html>