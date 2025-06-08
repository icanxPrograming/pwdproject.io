<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-video';
$allowedTypes = ['kelola-video'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=Tipe tidak valid");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tambah Video</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Tambah Video</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post"
      action="/PWD-Project-Mandiri/admin/page/video/proses-tambah-video.php?type=<?= htmlspecialchars($type) ?>">

      <!-- Judul Video -->
      <div class="form-group">
        <label>Judul Video</label>
        <input type="text" name="judul_video" class="form-control" required>
      </div>

      <!-- URL Video -->
      <div class="form-group">
        <label>URL Video</label>
        <input type="url" name="url" class="form-control" placeholder="https://youtube.com/embed/..." required>
        <small class="form-text text-muted">Masukkan URL embed seperti: https://youtube.com/embed/abc123xyz</small>
      </div>

      <!-- Kategori Video -->
      <div class="form-group">
        <label>Kategori</label>
        <select name="kategori" class="form-control" required>
          <option value="" disabled selected>-- Pilih Kategori --</option>
          <option value="mobil">Mobil</option>
          <option value="motor">Motor</option>
          <option value="sepeda">Sepeda</option>
          <option value="truk">Truk</option>
          <option value="alat_berat">Alat berat</option>
          <option value="kend_khusus">Khusus</option>
        </select>
      </div>

      <!-- Status Posting -->
      <div class="form-group">
        <label>Status Posting</label>
        <select name="status_post" class="form-control" required>
          <option value="Posting">Posting</option>
          <option value="Belum" selected>Belum</option>
        </select>
      </div>

      <!-- Tombol Submit -->
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=video&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>