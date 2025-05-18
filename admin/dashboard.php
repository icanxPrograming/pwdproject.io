<?php
session_start();
if (!isset($_SESSION["id_pengguna"])) {
  header("location:login.php");
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
      background-color: #f4f6f9;
      color: #333;
    }

    .navbar-light {
      background-color: #fff !important;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .sidebar-heading h4 {
      font-weight: 600;
      color: #ffc107;
    }

    .list-group-item {
      border-radius: 0;
      transition: background 0.2s ease;
    }

    .list-group-item:hover {
      background-color: #f8f9fa;
      color: #0d6efd;
    }

    .card {
      border-radius: 8px !important;
      transition: transform 0.2s;
    }

    .card:hover {
      transform: translateY(-2px);
    }


    #wrapper {
      display: flex;
      width: 100%;
    }

    #wrapper.toggled #sidebar-wrapper {
      margin-left: -250px;
      transition: margin 0.3s ease;
    }

    #sidebar-wrapper {
      width: 250px;
      transition: margin 0.3s ease;
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
        <div class="list-group list-group-flush">
          <a href="dashboard.php" class="list-group-item list-group-item-action">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
          </a>
          <a href="dashboard.php?module=kendaraan&page=kelola-kendaraan" class="list-group-item list-group-item-action">
            <i class="fas fa-car-side mr-2"></i> Kelola Kendaraan
          </a>
          <a href="dashboard.php?module=penjual&page=kelola-penjual" class="list-group-item list-group-item-action">
            <i class="fas fa-user-tie mr-2"></i> Kelola Penjual
          </a>

          <!-- Transaksi with submenu -->
          <a href="#submenuTransaksi" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-toggle="collapse" aria-expanded="false">
            <span><i class="fas fa-file-invoice-dollar mr-2"></i> Transaksi</span>
            <i class="fas fa-chevron-down"></i>
          </a>
          <div class="collapse" id="submenuTransaksi">
            <a href="dashboard.php?module=transaksi&page=kelola-penjualan" class="list-group-item list-group-item-action pl-5">
              Penjualan
            </a>
            <a href="dashboard.php?module=transaksi&page=kelola-pembelian" class="list-group-item list-group-item-action pl-5">
              Pembelian
            </a>
          </div>

          <a href="dashboard.php?module=kategori&page=kelola-kategori" class="list-group-item list-group-item-action">
            <i class="fas fa-tags mr-2"></i> Kategori
          </a>
          <a href="dashboard.php?module=lokasi&page=kelola-lokasi" class="list-group-item list-group-item-action">
            <i class="fas fa-map-marker-alt mr-2"></i> Lokasi
          </a>
          <a href="dashboard.php?module=promo&page=kelola-promo" class="list-group-item list-group-item-action">
            <i class="fas fa-percent mr-2"></i> Promo
          </a>
          <a href="#" class="list-group-item list-group-item-action">
            <i class="fas fa-headset mr-2"></i> Dukungan
          </a>
          <a href="#" class="list-group-item list-group-item-action">
            <i class="fas fa-cog mr-2"></i> Pengaturan
          </a>
        </div>
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
        <?php
        $page = 'page/dashboard-main.php';
        if (isset($_GET['module'])) {
          $page = 'page/' . $_GET['module'] . '/' . $_GET['page'] . '.php';
        }
        require($page);
        ?>
      </div>
    </div>
  </div>

  <!-- JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    // Toggle sidebar
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });

    // Tooltip
    $(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });

    // Fungsi utama untuk menangani active menu dan collapse
    (function() {
      const currentURL = window.location.href;

      // Ambil semua link di sidebar
      const sidebarLinks = document.querySelectorAll('#sidebar-wrapper .list-group-item');

      sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');

        if (href && currentURL.includes(href)) {
          // Hapus semua active terlebih dahulu (jika belum dilakukan)
          document.querySelectorAll('#sidebar-wrapper .list-group-item.active').forEach(activeItem => {
            activeItem.classList.remove('active');
          });

          // Tandai item aktif
          link.classList.add('active');

          // Jika item ini berada di dalam submenu collapse, buka menu induknya
          const collapseParent = link.closest('.collapse');
          if (collapseParent) {
            collapseParent.classList.add('show');

            // Tandai juga link induknya (yang mengontrol collapse)
            const parentToggle = document.querySelector(
              `a[data-toggle="collapse"][href="#${collapseParent.id}"]`
            );
            if (parentToggle) {
              parentToggle.classList.add('active');
            }
          }
        }
      });
    })();
  </script>


</body>

</html>