<?php
require_once(__DIR__ . '/../../model/Lokasi.php');
$lokasi = new Lokasi();
$dataLokasi = $lokasi->getAll();
?>

<!-- Header START -->
<header>
  <!-- Top Bar -->
  <div class="top-bar">
    <div class="left-section">
      <a href="#"><i data-feather="smartphone"></i> Download OLX App</a>
    </div>
    <div class="right-section">
      <a href="#">
        <i data-feather="tag"></i> <!-- Ikon untuk Promo -->
        Promo
      </a>
      <a href="#">
        <i data-feather="file-text"></i> <!-- Ikon untuk News -->
        News
      </a>
    </div>
  </div>

  <!-- Main Header -->
  <div class="main-header">
    <div class="logo">
      <a href="index.php?page=home">
        <img src="asset/Logo Alter-Ex.png" alt="Logo Alter-Ex" />
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
      <a href="admin/login.php" class="login-btn"><button>Login/daftar</button></a>
      <button class="sell-button">+ JUAL</button>
    </div>
  </div>
</header>
<!-- Header END -->