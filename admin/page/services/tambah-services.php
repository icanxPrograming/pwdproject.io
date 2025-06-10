<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-services';
$allowedTypes = ['kelola-services'];
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
    <h2>Tambah Service</h2>
    <form method="post" action="/PWD-Project-Mandiri/admin/page/services/proses-tambah-services.php?type=<?= $type ?>">

      <div class="form-group">
        <label>Icon (emoji atau kode icon)</label>
        <input type="text" name="icon" class="form-control" placeholder="(contoh: 📚 🔒 💸)" required>
      </div>

      <div class="form-group">
        <label>Judul Section</label>
        <input type="text" name="judul_section" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Konten (CATATAN : Disarankan menggunakan format paragraf atau list)</label>
        <textarea name="konten" class="form-control" rows="5" required></textarea>
      </div>

      <div class="form-group">
        <label>Urutan</label>
        <input type="number" name="urutan" class="form-control" min="0" value="0" required>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="Aktif">Aktif</option>
          <option value="Tidak">Tidak</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="dashboard.php?module=services&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>