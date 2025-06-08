<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/admin/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-video';
$id_video = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-video'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type&error=Tipe tidak valid");
  exit;
}

// Load model video
require_once '../model/Video.php';
$videoModel = new Video();
$data = $videoModel->getById($id_video);

if (!$data) {
  $_SESSION['error'] = "Video tidak ditemukan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type&error=Data tidak ditemukan");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Video</title>
</head>

<body>

  <div class="container mt-5">
    <h2>Edit Video</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error'];
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post"
      action="/PWD-Project-Mandiri/admin/page/video/proses-edit-video.php?type=<?= htmlspecialchars($type) ?>&id=<?= $id_video ?>">

      <!-- Judul Video -->
      <div class="form-group">
        <label>Judul Video</label>
        <input type="text" name="judul_video" class="form-control" required
          value="<?= htmlspecialchars($data['judul_video']) ?>">
      </div>

      <!-- URL Video -->
      <div class="form-group">
        <label>URL Video</label>
        <input type="url" name="url" class="form-control" required
          value="<?= htmlspecialchars($data['url']) ?>">
        <small class="form-text text-muted">Contoh: https://youtube.com/embed/abc123xyz</small>
      </div>

      <!-- Kategori Video -->
      <div class="form-group">
        <label>Kategori</label>
        <select name="kategori" class="form-control" required>
          <option value="mobil" <?= $data['kategori'] === 'mobil' ? 'selected' : '' ?>>Mobil</option>
          <option value="motor" <?= $data['kategori'] === 'motor' ? 'selected' : '' ?>>Motor</option>
          <option value="sepeda" <?= $data['kategori'] === 'sepeda' ? 'selected' : '' ?>>Sepeda</option>
          <option value="truk" <?= $data['kategori'] === 'truk' ? 'selected' : '' ?>>Truk</option>
          <option value="alat_berat" <?= $data['kategori'] === 'alat_berat' ? 'selected' : '' ?>>Alat berat</option>
          <option value="kend_khusus" <?= $data['kategori'] === 'kend_khusus' ? 'selected' : '' ?>>Khusus</option>
        </select>
      </div>

      <!-- Status Posting -->
      <div class="form-group">
        <label>Status Posting</label>
        <select name="status_post" class="form-control" required>
          <option value="Posting" <?= $data['status_post'] === 'Posting' ? 'selected' : '' ?>>Posting</option>
          <option value="Belum" <?= $data['status_post'] === 'Belum' ? 'selected' : '' ?>>Belum</option>
        </select>
      </div>

      <!-- Tombol Submit -->
      <button type="submit" class="btn btn-primary">Perbarui Video</button>
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=video&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>