<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alter-Ex | ANDALANMU</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>

  <?php include 'beranda/components/header.php'; ?>
  <?php include 'beranda/components/navbar.php'; ?>

  <?php
  $page = isset($_GET['page']) ? $_GET['page'] : 'home';

  if ($page === 'mobil') {
    echo '<section class="mobil-page">';
    echo '<div class="grid-container">';
    echo '<aside class="filter-area">';
    include 'beranda/mobil/filter-mobil.php';
    echo '</aside>';
    echo '<section class="content-area">';
    include 'beranda/mobil/kelola-mobil.php';
    echo '</section>';
    echo '</div>';
    echo '</section>';
  } else if ($page === 'motor') {
    echo '<section class="motor-page">';
    echo '<div class="grid-container">';
    echo '<aside class="filter-area">';
    include 'beranda/motor/filter-motor.php';
    echo '</aside>';
    echo '<section class="content-area">';
    include 'beranda/motor/kelola-motor.php';
    echo '</section>';
    echo '</div>';
    echo '</section>';
  } else if ($page === 'truk') {
    echo '<section class="truk-page">';
    echo '<div class="grid-container">';
    echo '<aside class="filter-area">';
    include 'beranda/truk/filter-truk.php';
    echo '</aside>';
    echo '<section class="content-area">';
    include 'beranda/truk/kelola-truk.php';
    echo '</section>';
    echo '</div>';
    echo '</section>';
  } else if ($page === 'alat_berat') {
    echo '<section class="alat_berat_page">';
    echo '<div class="grid-container">';
    echo '<aside class="filter-area">';
    include 'beranda/alat_berat/filter-alat_berat.php';
    echo '</aside>';
    echo '<section class="content-area">';
    include 'beranda/alat_berat/kelola-alat_berat.php';
    echo '</section>';
    echo '</div>';
    echo '</section>';
  } else if ($page === 'sepeda') {
    echo '<section class="sepeda-page">';
    echo '<div class="grid-container">';
    echo '<aside class="filter-area">';
    include 'beranda/sepeda/filter-sepeda.php';
    echo '</aside>';
    echo '<section class="content-area">';
    include 'beranda/sepeda/kelola-sepeda.php';
    echo '</section>';
    echo '</div>';
    echo '</section>';
  } else if ($page === 'kend_khusus') {
    echo '<section class="kend_khusus_page">';
    echo '<div class="grid-container">';
    echo '<aside class="filter-area">';
    include 'beranda/kend_khusus/filter-kend_khusus.php';
    echo '</aside>';
    echo '<section class="content-area">';
    include 'beranda/kend_khusus/kelola-kend_khusus.php';
    echo '</section>';
    echo '</div>';
    echo '</section>';
  } else if ($page === 'kebutuhan') {
    echo '<section class="kebutuhan-page">';
    echo '<div class="grid-container">';
    echo '<aside class="filter-area">';
    include 'beranda/kebutuhan/filter-kebutuhan.php';
    echo '</aside>';
    echo '<section class="content-area">';
    include 'beranda/kebutuhan/kelola-kebutuhan.php';
    echo '</section>';
    echo '</div>';
    echo '</section>';
  } else if ($page === 'berita') {
    echo '<section class="news-page">';
    echo '<div class="news-category-filter">';
    include 'beranda/berita/filter-berita.php';
    echo '</div>';
    echo '<div class="news-list-container">';
    include 'beranda/berita/kelola-berita.php';
    echo '</div>';
    echo '</section>';
  } else if ($page === 'promo') {
    echo '<section class="promo-page">';
    echo '<div class="news-list-container">';
    include 'beranda/promo/kelola-promo.php';
    echo '</div>';
    echo '</section>';
  } else if ($page === 'riwayat') {
    echo '<section class="history-section">';
    include 'beranda/riwayat/riwayat-pesanan.php';
    echo '</section>';
  } else if ($page === 'notifikasi') {
    echo '<section class="notification-section">';
    include 'beranda/notifikasi/notifikasi.php';
    echo '</section>';
  } else if ($page === 'video') {
    echo '<section class="video-page">';
    include 'beranda/video/kelola-video.php';
    echo '</section>';
  } else if ($page === 'services') {
    include 'beranda/services/all-services.php';
  } else {
    include 'beranda/landingpage/banner.php';
    include 'beranda/landingpage/main-content.php';
  }
  ?>

  <?php include 'beranda/components/footer.php'; ?>

  <div class="loading-screen" style="display: none;">
    <div class="loading-spinner">
      <div class="spinner"></div>
      <p>Mengambil data...</p>
    </div>
  </div>
  <script src="js/script.js"></script>
  <script>
    const availableLocations = [
      <?php foreach ($dataLokasi as $row): ?> "<?= addslashes(htmlspecialchars($row['nama_lokasi'], ENT_QUOTES, 'UTF-8')) ?>",
      <?php endforeach; ?>
    ];
  </script>
  <script>
    feather.replace();
  </script>
</body>

</html>