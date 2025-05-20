<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alter-Ex | ANDALANMU</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>

  <?php include 'beranda/components/header.php'; ?>
  <?php include 'beranda/components/navbar.php'; ?>

  <?php
  $page = isset($_GET['page']) ? $_GET['page'] : 'home';

  if ($page === 'mobil') {
    // Tampilan 2 kolom: filter & mobil
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
  } else {
    // Halaman utama (landing page)
    include 'beranda/landingpage/banner.php';
    include 'beranda/landingpage/main-content.php';
  }
  ?>

  <?php include 'beranda/components/footer.php'; ?>
  <script src="js/script.js"></script>
  <script>
    feather.replace();
  </script>
</body>

</html>