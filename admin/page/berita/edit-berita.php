<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-berita';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-berita'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type&error=Tipe berita tidak valid");
  exit;
}

// Load model Berita
require_once '../model/Berita.php';
$berita = new Berita();

// Ambil data berdasarkan ID
$tableName = 'berita';
$row = $berita->getById($tableName, $id);

if (!$row) {
  $_SESSION['error'] = "Data berita tidak ditemukan.";
  header("Location: /PWD-Project-Mandiri/dashboard.php?module=berita&page=$type&error=Data berita tidak ditemukan");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Berita</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Edit Berita</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error'];
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post"
      action="/PWD-Project-Mandiri/admin/page/berita/proses-edit-berita.php?type=<?= htmlspecialchars($type) ?>&id=<?= $id ?>"
      enctype="multipart/form-data">

      <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="mb-3">
        <label>Judul Berita</label>
        <input type="text" name="judul" class="form-control" required
          value="<?= htmlspecialchars($row['judul']) ?>">
      </div>

      <div class="mb-3">
        <label>Isi Berita</label>
        <textarea name="isi" class="form-control" rows="5" required><?= htmlspecialchars($row['isi']) ?></textarea>
      </div>

      <div class="mb-3">
        <label>Kategori</label>
        <select name="kategori" class="form-control" required>
          <option value="Otomotif" <?= $row['kategori'] === 'Otomotif' ? 'selected' : '' ?>>Otomotif</option>
          <option value="Promo" <?= $row['kategori'] === 'Promo' ? 'selected' : '' ?>>Promo</option>
          <option value="Event" <?= $row['kategori'] === 'Event' ? 'selected' : '' ?>>Event</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required
          value="<?= htmlspecialchars($row['tanggal']) ?>">
      </div>

      <div class="mb-3">
        <label>Penulis</label>
        <input type="text" name="penulis" class="form-control" required
          value="<?= htmlspecialchars($row['penulis']) ?>">
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="Draft" <?= $row['status'] === 'Draft' ? 'selected' : '' ?>>Draft</option>
          <option value="Publish" <?= $row['status'] === 'Publish' ? 'selected' : '' ?>>Publish</option>
        </select>
      </div>

      <!-- Gambar Saat Ini -->
      <div class="form-group">
        <label>Gambar Saat Ini</label><br>
        <?php if (!empty($row['gambar'])): ?>
          <img src="/PWD-Project-Mandiri/asset/berita/<?= htmlspecialchars($row['gambar']) ?>" width="120" class="img-thumbnail"><br>
          <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar</small>
        <?php else: ?>
          <p class="text-muted">Belum ada gambar</p>
        <?php endif; ?>
      </div>

      <!-- Upload Gambar Baru -->
      <div class="form-group">
        <label>Upload Gambar Baru (Opsional)</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">Upload</span>
          </div>
          <div class="custom-file">
            <input type="file" name="gambar" class="custom-file-input" id="inputGroupFile01" accept="image/*">
            <label class="custom-file-label" for="inputGroupFile01">Pilih gambar</label>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Perbarui</button>
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>