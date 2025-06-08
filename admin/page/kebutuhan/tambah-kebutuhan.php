<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-kebutuhan';
$allowedTypes = ['kelola-kebutuhan'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=Tipe tidak valid");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tambah Kebutuhan</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Tambah Kebutuhan</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post"
      action="/PWD-Project-Mandiri/admin/page/kebutuhan/proses-tambah-kebutuhan.php?type=<?= htmlspecialchars($type) ?>"
      enctype="multipart/form-data">

      <!-- Nama Kebutuhan -->
      <div class="form-group">
        <label>Nama Kebutuhan</label>
        <input type="text" name="nama_kebutuhan" class="form-control" required>
      </div>

      <!-- Jenis Kebutuhan -->
      <div class="form-group">
        <label>Jenis Kebutuhan</label>
        <select name="jenis_kebutuhan" class="form-control" required>
          <option value="" disabled selected>-- Pilih Jenis --</option>
          <option value="Sparepart">Sparepart</option>
          <option value="Oli_Pelumas">Oli & Pelumas</option>
          <option value="Aksesoris">Aksesoris</option>
        </select>
      </div>

      <!-- Jumlah -->
      <div class="form-group">
        <label>Jumlah</label>
        <input type="number" name="jumlah" class="form-control" required min="1">
      </div>

      <!-- Harga -->
      <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" required min="0">
      </div>

      <!-- Kategori Kendaraan -->
      <div class="form-group">
        <label>Kategori Kendaraan</label>
        <select name="kategori" class="form-control" required>
          <option value="" disabled selected>-- Pilih Kategori --</option>
          <option value="Mobil">Mobil</option>
          <option value="Motor">Motor</option>
          <option value="Truk">Truk</option>
          <option value="Sepeda">Sepeda</option>
          <option value="Heavy">Alat Berat</option>
          <option value="Khusus">Kendaraan Khusus</option>
        </select>
      </div>

      <!-- Status Kebutuhan -->
      <div class="form-group">
        <label>Status Ketersediaan</label>
        <select name="status_kebutuhan" class="form-control" required>
          <option value="" disabled selected>-- Pilih Status --</option>
          <option value="Tersedia">Tersedia</option>
          <option value="Terjual">Terjual</option>
        </select>
      </div>

      <!-- Status Posting -->
      <div class="form-group">
        <label>Status Posting</label>
        <select name="status_post" class="form-control" required>
          <option value="Posting">Posting</option>
          <option value="Belum">Belum</option>
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
      <a href="dashboard.php?module=kebutuhan&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>