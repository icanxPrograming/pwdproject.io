<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-lokasi';
$allowedTypes = ['kelola-lokasi'];
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
</head>

<body>
  <div class="container mt-5">
    <h2>Tambah Lokasi</h2>
    <form method="post" action="/PWD-Project-Mandiri/admin/page/lokasi/proses-tambah-lokasi.php?type=<?= $type ?>" enctype="multipart/form-data">

      <div class="form-group">
        <label>Nama lokasi</label>
        <input type="text" name="nama_lokasi" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="Aktif">Aktif</option>
          <option value="Nonaktif">Nonaktif</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="dashboard.php?module=lokasi&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap @4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>