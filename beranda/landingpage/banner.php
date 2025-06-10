<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Banner.php';
$bannerModel = new Banner();

$data = $bannerModel->getBanner();
?>
<section class="banner">
  <div class="slider-container">
    <button class="slide-btn prev">&lt;</button>
    <div class="slides">
      <?php foreach ($data as $index => $banner): ?>
        <img src="<?= htmlspecialchars($banner['url_gambar']) ?>" alt="Slide <?= $index + 1 ?>" />
      <?php endforeach; ?>
    </div>
    <button class="slide-btn next">&gt;</button>
  </div>
</section>

<!-- Link tambahan -->
<!-- https://imgcdn.oto.com/marketing/oto-landing-page-insurance05x11zon-1748955891.jpg -->