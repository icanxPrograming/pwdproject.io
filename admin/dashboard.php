<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: ../index.php");
  exit;
}

$user = $session->getUserData(); ?>
<!DOCTYPE html>
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
      transition: margin 0.3s ease;
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
      margin-left: 250px;
      /* Sesuaikan dengan lebar sidebar */
      flex-grow: 1;
      min-height: 100vh;
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

    #scrollTopBtn {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1050;
      background-color: #0d6efd;
      color: white;
      width: 40px;
      height: 40px;
      padding: 8px;
      border: none;
      border-radius: 50%;
      transition: opacity 0.3s ease, transform 0.3s ease;
    }

    #scrollTopBtn:hover {
      transform: scale(1.1);
    }
  </style>
</head>

<body>

  <div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="text-white d-flex flex-column position-fixed h-100" style="width: 250px;">
      <div class="sidebar-heading text-center py-4 border-bottom">
        <h4><i class="fas fa-car"></i> Alter-Ex</h4>
        <small>Admin Panel</small>
      </div>

      <div class="list-group list-group-flush flex-grow-1">
        <a href="dashboard.php" class="list-group-item list-group-item-action">
          <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>
        <a href="#submenuKendaraan" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-toggle="collapse" aria-expanded="false">
          <span><i class="fas fa-car-side mr-2"></i> Kelola Kendaraan</span>
          <i class="fas fa-chevron-down"></i>
        </a>
        <div class="collapse" id="submenuKendaraan">
          <a href="dashboard.php?module=kendaraan&page=mobil" class="list-group-item list-group-item-action pl-5">Mobil</a>
          <a href="dashboard.php?module=kendaraan&page=motor" class="list-group-item list-group-item-action pl-5">Motor</a>
          <a href="dashboard.php?module=kendaraan&page=truk" class="list-group-item list-group-item-action pl-5">Truk</a>
          <a href="dashboard.php?module=kendaraan&page=alat_berat" class="list-group-item list-group-item-action pl-5">Alat Berat</a>
          <a href="dashboard.php?module=kendaraan&page=sepeda" class="list-group-item list-group-item-action pl-5">Sepeda</a>
          <a href="dashboard.php?module=kendaraan&page=kend_khusus" class="list-group-item list-group-item-action pl-5">Khusus</a>
        </div>

        <a href="dashboard.php?module=kebutuhan&page=kelola-kebutuhan" class="list-group-item list-group-item-action">
          <i class="fas fa-wrench mr-2"></i> Kebutuhan Kendaraan
        </a>

        <a href="dashboard.php?module=banner&page=kelola-banner" class="list-group-item list-group-item-action">
          <i class="fas fa-map mr-2"></i> Banner
        </a>

        <a href="dashboard.php?module=cards&page=kelola-cards" class="list-group-item list-group-item-action">
          <i class="fas fa-window-maximize mr-2"></i> Cards
        </a>

        <a href="dashboard.php?module=transaksi&page=kelola-transaksi" class="list-group-item list-group-item-action">
          <i class="fas fa-file-invoice-dollar mr-2"></i> Transaksi
        </a>

        <a href="dashboard.php?module=lokasi&page=kelola-lokasi" class="list-group-item list-group-item-action">
          <i class="fas fa-map-marker-alt mr-2"></i> Lokasi
        </a>
        <a href="dashboard.php?module=promo&page=kelola-promo" class="list-group-item list-group-item-action">
          <i class="fas fa-percent mr-2"></i> Promo
        </a>
        <a href="dashboard.php?module=berita&page=kelola-berita" class="list-group-item list-group-item-action">
          <i class="fas fa-newspaper mr-2"></i> Berita
        </a>
        <a href="dashboard.php?module=video&page=kelola-video" class="list-group-item list-group-item-action">
          <i class="fas fa-video mr-2"></i> Kelola Video
        </a>
        <a href="dashboard.php?module=services&page=kelola-services" class="list-group-item list-group-item-action">
          <i class="fas fa-cogs mr-2"></i> Services
        </a>
      </div>

      <!-- Tombol ke Landing Page / Aktifkan jika ingin ke halaman utama -->
      <!-- <a href="../index.php" class="list-group-item list-group-item-action text-center text-white">
        <i class="fas fa-home mr-2"></i> Kembali ke Halaman Utama
      </a> -->
    </div>


    <!-- Page Content -->
    <div id="page-content-wrapper">
      <nav class="navbar navbar-expand-lg navbar-light">
        <button class="btn btn-outline-primary btn-sm" id="menu-toggle"><i class="fas fa-bars"></i></button>

        <div class="collapse navbar-collapse justify-content-end">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['email'] ?? 'Admin'); ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <!-- <a class="dropdown-item" href="#"><i class="fas fa-user-cog"></i> Profil</a> -->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid">
        <?php
        $page = 'dashboard-main';
        if (isset($_GET['module']) && isset($_GET['page'])) {
          $page = "{$_GET['module']}/{$_GET['page']}";
        }
        require "page/" . $page . ".php";
        ?>
      </div>
    </div>
  </div>

  <!-- Scroll to Top Button -->
  <button id="scrollTopBtn" class="btn btn-primary rounded-circle shadow-sm" style="position: fixed; bottom: 20px; right: 20px; z-index: 1050;">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: 'textarea[name=konten]',
      height: 300,
      menubar: false,
      plugins: [
        'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
        'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
        'media', 'table', 'emoticons', 'help'
      ],
      toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image | preview media fullscreen | ' +
        'forecolor backcolor emoticons | help',
      setup: function(editor) {
        editor.on('change', function() {
          editor.save();
        });
      }
    });
  </script>

  <script>
    // Atur jarak scroll sebelum tombol muncul (misalnya 300px)
    const scrollThreshold = 500;
    const scrollTopBtn = document.getElementById("scrollTopBtn");

    // Tampilkan/menghilangkan tombol saat scroll
    window.addEventListener("scroll", function() {
      if (window.pageYOffset > scrollThreshold) {
        scrollTopBtn.style.display = "block";
      } else {
        scrollTopBtn.style.display = "none";
      }
    });

    // Fungsi smooth scroll ke atas
    scrollTopBtn.addEventListener("click", function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    });

    // Toggle sidebar
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });

    // Tooltip
    $(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });

    // Highlight aktif + buka submenu jika aktif
    (function() {
      const currentURL = window.location.href;
      const sidebarLinks = document.querySelectorAll('#sidebar-wrapper .list-group-item');

      sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentURL.includes(href)) {
          document.querySelectorAll('#sidebar-wrapper .list-group-item.active').forEach(activeItem => {
            activeItem.classList.remove('active');
          });

          link.classList.add('active');

          const collapseParent = link.closest('.collapse');
          if (collapseParent) {
            collapseParent.classList.add('show');
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

    // Accordion behavior: hanya 1 submenu terbuka
    document.querySelectorAll('a[data-toggle="collapse"]').forEach(toggle => {
      toggle.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        const targetCollapse = document.querySelector(targetId);

        document.querySelectorAll('.collapse.show').forEach(openCollapse => {
          if (openCollapse !== targetCollapse) {
            $(openCollapse).collapse('hide');
          }
        });
      });
    });

    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
      var fileName = this.files[0] ? this.files[0].name : '';
      var label = this.nextElementSibling;
      label.innerText = fileName || 'Pilih gambar';
    });
  </script>
</body>

</html>