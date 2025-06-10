<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-cards';
$allowedTypes = ['kelola-cards'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=Tipe tidak valid");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tambah Card</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Tambah Card</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post"
      action="/PWD-Project-Mandiri/admin/page/cards/proses-tambah-cards.php?type=<?= htmlspecialchars($type) ?>"
      enctype="multipart/form-data">

      <!-- Judul -->
      <div class="form-group">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control" required>
      </div>

      <!-- Sub Judul -->
      <div class="form-group">
        <label>Sub Judul</label>
        <input type="text" name="sub_judul" class="form-control" required>
      </div>

      <!-- Redirect -->
      <div class="form-group">
        <label>Redirect To</label>
        <input type="text" name="redirect" class="form-control" required>
      </div>

      <!-- Urutan -->
      <div class="form-group">
        <label>Urutan</label>
        <input type="number" name="urutan" class="form-control" required>
      </div>

      <!-- Status Posting -->
      <div class="form-group">
        <label>Status</label>
        <select name="status_post" class="form-control" required>
          <option value="Aktif">Aktif</option>
          <option value="Tidak">Tidak</option>
        </select>
      </div>

      <!-- Upload Gambar -->
      <div class="form-group">
        <label>Upload Gambar</label>
        <div class="custom-file">
          <input type="file" name="gambar" class="custom-file-input" id="inputGroupFile01" accept="image/*" required>
          <label class="custom-file-label" for="inputGroupFile01">Pilih gambar</label>
        </div>
      </div>

      <!-- Tombol Submit -->
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="dashboard.php?module=cards&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>