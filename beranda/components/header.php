<?php
// Load model Lokasi
require_once(__DIR__ . '/../../model/Lokasi.php');
$lokasi = new Lokasi();
$dataLokasi = $lokasi->getAll();

// Load AppSession
require_once __DIR__ . '/../../model/Session.php';
$session = new AppSession(); // Otomatis start session jika belum aktif

// Ambil data user jika login
$userData = $session->isLoggedIn() ? $session->getUserData() : null;
?>

<!-- Header START -->
<header>
  <!-- Top Bar -->
  <div class="top-bar">
    <div class="left-section">
      <a href="#"><i data-feather="smartphone"></i> Download Alter-Ex App</a>
    </div>
    <div class="right-section">
      <a href="#">
        <i data-feather="tag"></i> Promo
      </a>
      <a href="#">
        <i data-feather="file-text"></i> News
      </a>
    </div>
  </div>

  <!-- Main Header -->
  <div class="main-header">
    <div class="logo">
      <a href="index.php?page=home">
        <img src="asset/logo-banner/Logo Alter-Ex.png" alt="Logo Alter-Ex" />
      </a>
    </div>
    <div class="search-bar">
      <!-- Location Dropdown -->
      <div class="combined-search">
        <div class="location-select">
          <i class="fas fa-map-marker-alt"></i>
          <select id="location-dropdown" name="lokasi">
            <?php foreach ($dataLokasi as $row): ?>
              <option value="<?= htmlspecialchars($row['id_lokasi']) ?>">
                <?= htmlspecialchars($row['nama_lokasi']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="search-input">
          <input type="text" id="search-input" placeholder="">
          <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
      </div>

      <!-- Input Search -->
      <div class="input-group">
        <input type="text" placeholder="Temukan Mobil, Handphone, dan lainnya..." />
        <button class="filter-button"><i class="fas fa-filter"></i> Filter</button>
        <button class="search-icon"><i class="fas fa-search"></i></button>
      </div>
    </div>

    <div class="auth-buttons">
      <?php if ($userData): ?>
        <!-- Tampilan saat sudah login -->
        <div class="user-menu">
          <button class="user-btn">
            <i class="fas fa-user-circle"></i>
            <span><?= htmlspecialchars($userData['username']) ?></span>
            <i class="fas fa-chevron-down"></i>
          </button>
          <div class="dropdown-user">
            <?php if ($session->isAdmin()): ?>
              <a href="admin/dashboard.php" class="dropdown-item">
                <i class="fas fa-tachometer-alt"></i> Dashboard Admin
              </a>
            <?php endif; ?>
            <a href="#" class="dropdown-item">
              <i class="fas fa-user-cog"></i> Profil
            </a>
            <div class="dropdown-divider"></div>
            <a href="admin/logout.php" class="dropdown-item">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </div>
        </div>
      <?php endif; ?>

      <?php if (!$userData): ?>
        <!-- Tampilkan login hanya jika belum login -->
        <a href="admin/login.php" class="login-btn">
          <button>Login/daftar</button>
        </a>
      <?php endif; ?>
      <!-- Tombol JUAL tetap muncul baik login maupun tidak -->
      <button class="sell-button">+ JUAL</button>
    </div>
  </div>
</header>
<!-- Header END -->