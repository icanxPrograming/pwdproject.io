<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-promo';
$allowedTypes = ['kelola-promo'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=tidak valid");
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
    <h2>Tambah Promo</h2>
    <form method="post" action="/PWD-Project-Mandiri/admin/page/promo/proses-tambah-promo.php?type=<?= $type ?>" enctype="multipart/form-data">

      <div class="form-group">
        <label>Judul Promo</label>
        <input type="text" name="judul_promo" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="Aktif">Aktif</option>
          <option value="Nonaktif">Nonaktif</option>
        </select>
      </div>

      <div class="form-group">
        <label>Upload Gambar</label>
        <div class="custom-file">
          <input type="file" name="gambar" class="custom-file-input" id="inputGroupFile01" accept="image/*" required>
          <label class="custom-file-label" for="inputGroupFile01">Pilih gambar</label>
        </div>
      </div>

      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="dashboard.php?module=promo&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>