<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-penjual';
$allowedTypes = ['kelola-penjual'];
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
    <h2>Tambah Penjual</h2>
    <form method="post" action="/PWD-Project-Mandiri/admin/page/penjual/proses-tambah-penjual.php?type=<?= $type ?>" enctype="multipart/form-data">

      <div class="mb-3">
        <label>Nama Penjual</label>
        <input type="text" name="nama_penjual" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>No HP</label>
        <input type="text" name="no_hp" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label>Tipe Penjual</label>
        <select name="tipe_penjual" class="form-select" required>
          <option value="dealer">Dealer</option>
          <option value="perorangan">Perorangan</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select" required>
          <option value="Aktif">Aktif</option>
          <option value="Nonaktif">Nonaktif</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="dashboard.php?module=penjual&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>