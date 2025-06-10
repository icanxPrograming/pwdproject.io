<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

// Ambil type dan id cards
$type = $_GET['type'] ?? 'kelola-cards';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-cards'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&error=Tipe cards tidak valid");
  exit;
}

// Load model cards
require_once '../model/Cards.php';
$cards = new Cards();

// Ambil data berdasarkan ID
$tableName = 'cards';
$row = $cards->getById($tableName, $id);

if (!$row) {
  $_SESSION['error'] = "Data cards tidak ditemukan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&error=Data cards tidak ditemukan");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Card</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Edit Card</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error'];
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post"
      action="/PWD-Project-Mandiri/admin/page/cards/proses-edit-cards.php?type=<?= htmlspecialchars($type) ?>&id=<?= $id ?>"
      enctype="multipart/form-data">

      <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control" required
          value="<?= htmlspecialchars($row['judul']) ?>">
      </div>

      <div class="mb-3">
        <label>Sub Judul</label>
        <input type="text" name="sub_judul" class="form-control" required
          value="<?= htmlspecialchars($row['sub_judul']) ?>">
      </div>

      <div class="mb-3">
        <label>Redirect To</label>
        <input type="text" name="redirect" class="form-control" required
          value="<?= htmlspecialchars($row['redirect']) ?>">
      </div>

      <div class="mb-3">
        <label>Urutan</label>
        <input type="number" name="urutan" class="form-control" required
          value="<?= htmlspecialchars($row['urutan']) ?>">
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="Aktif" <?= $row['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
          <option value="Tidak" <?= $row['status'] === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
      </div>

      <!-- Gambar Saat Ini -->
      <div class="form-group">
        <label>Gambar Saat Ini</label><br>
        <?php if (!empty($row['gambar'])): ?>
          <img src="/PWD-Project-Mandiri/asset/cards/<?= $row['gambar'] ?>" width="120" class="img-thumbnail"><br>
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
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>