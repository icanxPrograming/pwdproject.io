<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Berita.php';
$beritaModel = new Berita();

// Ambil filter dari URL
$filters = [];
if (!empty($_GET['kategori'])) {
  $filters['kategori'] = is_array($_GET['kategori']) ? $_GET['kategori'] : [$_GET['kategori']];
}

// Ambil data berita berdasarkan filter
if (!empty($filters['kategori'])) {
  $data = $beritaModel->getBeritaByKategoris($filters['kategori']);
} else {
  $data = $beritaModel->getBerita(); // ambil semua berita
}
?>

<div class="news-list">
  <?php if (empty($data)): ?>
    <p class="text-muted">Tidak ada berita tersedia.</p>
  <?php else: ?>
    <?php foreach ($data as $article): ?>
      <div class="news-card">
        <div class="news-image">
          <img src="/PWD-Project-Mandiri/asset/berita/<?= htmlspecialchars($article['gambar']) ?>" alt="<?= htmlspecialchars($article['judul']) ?>">
        </div>
        <div class="news-content">
          <h3 class="news-title">
            <a href="/PWD-Project-Mandiri/detail-berita.php?id=<?= htmlspecialchars($article['id_berita']) ?>" class="news-title-link">
              <?= htmlspecialchars($article['judul']) ?>
            </a>
          </h3>
          <p class="news-excerpt"><?= htmlspecialchars(substr($article['isi'], 0, 320)) ?>...</p>
          <div class="news-meta">
            <span class="author"><?= htmlspecialchars($article['penulis']) ?></span>
            <span class="date"><?= htmlspecialchars(date('d M Y', strtotime($article['tanggal']))) ?></span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>