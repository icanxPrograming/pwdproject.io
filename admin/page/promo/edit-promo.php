<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

// Ambil type dan id promo
$type = $_GET['type'] ?? 'kelola-promo';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-promo'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Tipe promo tidak valid");
  exit;
}

// Load model Promo
require_once '../model/Promo.php';
$promo = new Promo();

// Ambil data berdasarkan ID
$tableName = 'promo';
$row = $promo->getById($tableName, $id);

if (!$row) {
  $_SESSION['error'] = "Data promo tidak ditemukan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Data promo tidak ditemukan");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Promo</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Edit Promo</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error'];
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post"
      action="/PWD-Project-Mandiri/admin/page/promo/proses-edit-promo.php?type=<?= htmlspecialchars($type) ?>&id=<?= $id ?>"
      enctype="multipart/form-data">

      <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="mb-3">
        <label>Judul Promo</label>
        <input type="text" name="judul_promo" class="form-control" required
          value="<?= htmlspecialchars($row['judul_promo']) ?>">
      </div>

      <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
      </div>

      <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" required
          value="<?= htmlspecialchars($row['tanggal_mulai']) ?>">
      </div>

      <div class="mb-3">
        <label>Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" class="form-control" required
          value="<?= htmlspecialchars($row['tanggal_selesai']) ?>">
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="Aktif" <?= $row['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
          <option value="Nonaktif" <?= $row['status'] === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
        </select>
      </div>

      <!-- Gambar Saat Ini -->
      <div class="form-group">
        <label>Gambar Saat Ini</label><br>
        <?php if (!empty($row['gambar'])): ?>
          <img src="/PWD-Project-Mandiri/asset/promo/<?= $row['gambar'] ?>" width="120" class="img-thumbnail"><br>
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
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>