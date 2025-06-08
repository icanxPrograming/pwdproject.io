<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-berita';
$allowedTypes = ['kelola-berita'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=tidak valid");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tambah <?= ucfirst(htmlspecialchars($type)) ?></title>
</head>

<body>
  <div class="container mt-5">
    <h2>Tambah Berita</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post"
      action="/PWD-Project-Mandiri/admin/page/berita/proses-tambah-berita.php?type=<?= htmlspecialchars($type) ?>"
      enctype="multipart/form-data">

      <div class="form-group">
        <label>Judul Berita</label>
        <input type="text" name="judul" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Isi Berita</label>
        <textarea name="isi" class="form-control" rows="5" required></textarea>
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <select name="kategori" class="form-control" required>
          <option value="Otomotif">Otomotif</option>
          <option value="Promo">Promo</option>
          <option value="Event">Event</option>
        </select>
      </div>

      <div class="form-group">
        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Penulis</label>
        <input type="text" name="penulis" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="Draft">Draft</option>
          <option value="Publish">Publish</option>
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
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>