<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'mobil';
$allowedTypes = ['mobil', 'motor'];
if (!in_array($type, $allowedTypes)) {
  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tambah <?= ucfirst($type) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <h2>Tambah <?= ucfirst($type) ?></h2>
    <form method="post" action="/PWD-Project-Mandiri/admin/page/kendaraan/proses-tambah.php?type=<?= $type ?>" enctype="multipart/form-data">

      <div class="mb-3">
        <label>Nama <?= ucfirst($type) ?></label>
        <input type="text" name="nama_<?= $type ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Tahun</label>
        <input type="number" name="tahun" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Jumlah Unit</label>
        <input type="number" name="jumlah_unit" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Harga Per Unit</label>
        <input type="number" name="harga_per_unit" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="5"></textarea>
      </div>

      <div class="mb-3">
        <label>Status Posting</label>
        <select name="status_post" class="form-select" required>
          <option value="Posting">Posting</option>
          <option value="Belum">Belum</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="gambar" class="form-control" accept="image/*" required>
      </div>

      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="dashboard.php?module=kendaraan&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>