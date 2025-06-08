<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

// Ambil type dan id kebutuhan
$type = $_GET['type'] ?? 'kelola-kebutuhan';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-kebutuhan'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&error=Tipe kebutuhan tidak valid");
  exit;
}

// Load model kebutuhan
require_once '../model/Kebutuhan.php';
$kebutuhan = new Kebutuhan();

// Ambil data berdasarkan ID
$tableName = 'kebutuhan';
$row = $kebutuhan->getById($tableName, $id);

if (!$row) {
  $_SESSION['error'] = "Data kebutuhan tidak ditemukan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&error=Data kebutuhan tidak ditemukan");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Kebutuhan</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Edit Kebutuhan</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error'];
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post"
      action="/PWD-Project-Mandiri/admin/page/kebutuhan/proses-edit-kebutuhan.php?type=<?= htmlspecialchars($type) ?>&id=<?= $id ?>"
      enctype="multipart/form-data">

      <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="mb-3">
        <label>Kebutuhan</label>
        <input type="text" name="nama_kebutuhan" class="form-control" required
          value="<?= htmlspecialchars($row['nama_kebutuhan']) ?>">
      </div>

      <div class="mb-3">
        <label>Jenis Kebutuhan</label>
        <select name="jenis_kebutuhan" class="form-control" required>
          <option value="Sparepart" <?= $row['jenis_kebutuhan'] === 'Sparepart' ? 'selected' : '' ?>>Sparepart</option>
          <option value="Oli_Pelumas" <?= $row['jenis_kebutuhan'] === 'Oli_Pelumas' ? 'selected' : '' ?>>Oli & Pelumas</option>
          <option value="Aksesoris" <?= $row['jenis_kebutuhan'] === 'Aksesoris' ? 'selected' : '' ?>>Aksesoris</option>
        </select>
      </div>

      <div class="form-group">
        <label>Jumlah</label>
        <input type="number" name="jumlah" class="form-control"
          value="<?= htmlspecialchars($row['jumlah']) ?>" required>
      </div>

      <!-- <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
      </div> -->

      <!-- Harga Per Unit -->
      <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control"
          value="<?= htmlspecialchars($row['harga']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Kategori</label>
        <select name="kategori" class="form-control" required>
          <option value="Mobil" <?= $row['kategori'] === 'Mobil' ? 'selected' : '' ?>>Mobil</option>
          <option value="Motor" <?= $row['kategori'] === 'Motor' ? 'selected' : '' ?>>Motor</option>
          <option value="Truk" <?= $row['kategori'] === 'Truk' ? 'selected' : '' ?>>Truk</option>
          <option value="Sepeda" <?= $row['kategori'] === 'Sepeda' ? 'selected' : '' ?>>Sepeda</option>
          <option value="Heavy" <?= $row['kategori'] === 'Heavy' ? 'selected' : '' ?>>Alat Berat</option>
          <option value="Khusus" <?= $row['kategori'] === 'Khusus' ? 'selected' : '' ?>>Khusus</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Status Ketersediaan</label>
        <select name="status_kebutuhan" class="form-control" required>
          <option value="Tersedia" <?= $row['status_kebutuhan'] === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
          <option value="Terjual" <?= $row['status_kebutuhan'] === 'Terjual' ? 'selected' : '' ?>>Terjual</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Status Posting</label>
        <select name="status_post" class="form-control" required>
          <option value="Posting" <?= $row['status_post'] === 'Posting' ? 'selected' : '' ?>>Posting</option>
          <option value="Belum" <?= $row['status_post'] === 'Belum' ? 'selected' : '' ?>>Belum</option>
        </select>
      </div>

      <!-- Gambar Saat Ini -->
      <div class="form-group">
        <label>Gambar Saat Ini</label><br>
        <?php if (!empty($row['gambar'])): ?>
          <img src="/PWD-Project-Mandiri/asset/kebutuhan/<?= $row['gambar'] ?>" width="120" class="img-thumbnail"><br>
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
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>