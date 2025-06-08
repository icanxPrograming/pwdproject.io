<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Promo.php';
$promoModel = new Promo();

// Auto update status promo yang sudah lewat masa berlakunya
$promoModel->autoUpdateStatus();

// Ambil semua promo aktif
$data = $promoModel->getPromo(); // atau tambahkan filter hanya "Aktif"
?>

<div class="promo-list">
  <?php if (empty($data)): ?>
    <p class="text-muted">Tidak ada promo tersedia.</p>
  <?php else: ?>
    <?php foreach ($data as $item): ?>
      <!-- Hanya tampilkan jika status 'Aktif' -->
      <?php if ($item['status'] === 'Aktif'): ?>
        <div class="promo-card">
          <div class="promo-image">
            <img src="/PWD-Project-Mandiri/asset/promo/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['judul_promo']) ?>">
          </div>
          <div class="promo-content">
            <h3 class="promo-title">
              <a href="/PWD-Project-Mandiri/detail-promo.php?id=<?= htmlspecialchars($item['id_promo']) ?>" class="promo-title-link">
                <?= htmlspecialchars($item['judul_promo']) ?>
              </a>
            </h3>
            <p class="promo-excerpt"><?= htmlspecialchars(substr($item['deskripsi'], 0, 320)) ?>...</p>
            <div class="promo-meta">
              <span class="date">Berlaku: <?= htmlspecialchars(date('d M Y', strtotime($item['tanggal_mulai']))) ?> - <?= htmlspecialchars(date('d M Y', strtotime($item['tanggal_selesai']))) ?></span>
            </div>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>