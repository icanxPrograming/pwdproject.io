<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Berita.php';
$beritaModel = new Berita();

// Ambil semua kategori unik
$kategoris = $beritaModel->getAllFromTable('berita');
$uniqueKategoris = array_unique(array_column($kategoris, 'kategori'));

// Menambahkan "Semua" sebagai kategori pertama
$uniqueKategoris = array_merge(['Semua'], $uniqueKategoris);
?>

<div class="news-category-filter">
  <div class="category-tabs">
    <?php foreach ($uniqueKategoris as $kategori): ?>
      <button class="category-tab <?= $_GET['kategori'] === $kategori ? 'active' : '' ?>" data-kategori="<?= htmlspecialchars($kategori) ?>">
        <?= htmlspecialchars($kategori) ?>
      </button>
    <?php endforeach; ?>
  </div>
</div>