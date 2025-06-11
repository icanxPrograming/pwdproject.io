<?php
// Load model Lokasi
require_once(__DIR__ . '/../../model/Lokasi.php');
$lokasi = new Lokasi();
$dataLokasi = $lokasi->getLokasi();

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
      <a href="https://play.google.com/store/apps/details?id=com.app.tokobagus.betterb&hl=id&utm_source=HomepageHeader&utm_medium=NAU&utm_campaign=ID%7CMIX%7CCTX%7CPD%7CHH%7CPROS%7CWEB%7CNAU%7CDownloads%7CDownloadWidget%7CCTx-All%7CPlaystore%7C20240807&utm_term=NA&utm_content=NA&pli=1" target="_blank"><i data-feather="smartphone"></i> Download Alter-Ex App</a>
    </div>
    <div class="right-section">
      <a href="index.php?page=promo">
        <i data-feather="tag"></i> Promo
      </a>
      <a href="index.php?page=berita">
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
      <div class="combined-search" id="search-input">
        <!-- Ikon Pencarian -->
        <i class="fas fa-search search-icon btn-search"></i>

        <!-- Input Lokasi -->
        <input
          type="text"
          class="search-location"
          placeholder="Temukan Mobil di Jakarta..."
          autocomplete="off" />

        <!-- Ikon Chevron (tidak dipakai lagi, disembunyikan) -->
        <i class="fas fa-chevron-down chevron-icon"></i>
        <!-- Dropdown History + Lokasi Teratas -->
        <div class="location-dropdown" id="dropdown">
          <div class="dropdown-header">Riwayat</div>
          <div class="history-list"></div>

          <div class="dropdown-divider"></div>
          <div class="dropdown-header">Lokasi Populer</div>
          <div class="location-options"></div>
        </div>
      </div>


      <!-- Input Search -->
      <div class="input-group">
        <input type="text" id="search-produk" placeholder="Temukan Mobil, Motor, dan lainnya..." />
        <button class="search-icon" onclick="handleSearch()">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>

    <div class="auth-buttons">
      <?php if ($userData): ?>
        <!-- Tampilan saat sudah login -->
        <div class="user-menu">
          <button class="user-btn">
            <i class="fas fa-user-circle"></i>
            <span><?= htmlspecialchars($userData['email']) ?></span>
            <i class="fas fa-chevron-down"></i>
          </button>
          <div class="dropdown-user">
            <?php if ($session->isAdmin()): ?>
              <a href="admin/dashboard.php" class="dropdown-item">
                <i class="fas fa-tachometer-alt"></i> Dashboard Admin
              </a>
            <?php endif; ?>
            <?php if (!$session->isAdmin()): ?>
              <a href="/PWD-Project-Mandiri/index.php?page=riwayat" class="dropdown-item">
                <i class="fas fa-history"></i> Riwayat Pesanan
              </a>
            <?php endif; ?>
            <!-- Tombol Notifikasi -->
            <?php if (!$session->isAdmin()): ?>
              <a href="/PWD-Project-Mandiri/index.php?page=notifikasi" class="dropdown-item">
                <i class="fas fa-bell"></i> Notifikasi
              </a>
            <?php endif; ?>
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
          Login/daftar
        </a>
      <?php endif; ?>
      <!-- Tombol JUAL tetap muncul baik login maupun tidak -->
      <!-- <button class="sell-button">+ JUAL</button> -->
    </div>
    <div class="sell-button">
      <a href="index.php?page=mobil"><img src="asset/beli.jpg" alt=""></a>
    </div>
  </div>
</header>
<!-- Header END -->