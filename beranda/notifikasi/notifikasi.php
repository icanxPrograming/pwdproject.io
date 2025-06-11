<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn()) {
  die('Anda harus login terlebih dahulu untuk mengakses halaman ini.');
}

$user = $session->getUserData();

// Hanya user biasa yang boleh akses
if ($session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php");
  exit;
}

// Pagination
$limit = 3; // Jumlah notifikasi per halaman
$page = isset($_GET['hal']) ? intval($_GET['hal']) : 1;
$start = ($page > 1) ? ($page * $limit - $limit) : 0;

// Load model notifikasi
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Notifikasi.php';
$notifikasiModel = new Notifikasi();

// Ambil jumlah total notifikasi untuk pagination
$totalNotifikasi = count($notifikasiModel->getAllByUserId($user['id_pengguna']));
$daftarNotifikasi = $notifikasiModel->getPaginatedByUserId($user['id_pengguna'], $start, $limit);

// Hitung jumlah halaman
$totalPages = ceil($totalNotifikasi / $limit);
?>
<div class="notification-container">
  <h2 class="notification-title">Arsip Notifikasi</h2>

  <?php if (empty($daftarNotifikasi)): ?>
    <p class="notification-empty">Belum ada notifikasi.</p>
  <?php else: ?>
    <div class="notification-list">
      <?php foreach ($daftarNotifikasi as $notif):
        $tanggal = date('d M Y', strtotime($notif['tanggal_dibuat']));
      ?>
        <div class="notification-card">
          <div class="notification-content"><?= htmlspecialchars($notif['isi']) ?></div>
          <div class="notification-footer">
            <small><?= $tanggal ?></small>
            <?php if ($notif['status'] !== 'diarsip'): ?>
              <a href="/PWD-Project-Mandiri/beranda/notifikasi/archive-notifikasi.php?id=<?= $notif['id_notifikasi'] ?>"
                onclick="return confirm('Arsip notifikasi ini?')"
                class="notification-archive">Arsip</a>
            <?php else: ?>
              <span class="text-muted">(Diarsip)</span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="notification-pagination">
      <?php if ($page > 1): ?>
        <a href="/PWD-Project-Mandiri/index.php?page=notifikasi&hal=<?= $page - 1 ?>" class="btn btn-sm btn-light">&laquo; Sebelumnya</a>
      <?php endif; ?>

      <div class="notification-page-info">
        Halaman <?= $page ?> dari <?= $totalPages ?>
      </div>

      <?php if ($page < $totalPages): ?>
        <a href="/PWD-Project-Mandiri/index.php?page=notifikasi&hal=<?= $page + 1 ?>" class="btn btn-sm btn-light">Selanjutnya &raquo;</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <a href="index.php" class="user-back-link">Kembali</a>
</div>