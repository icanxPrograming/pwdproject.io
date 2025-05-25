<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'mobil';

// Mapping dari URL ke nama tabel benar
$typeMap = [
  'alat-berat'       => 'alat_berat',
  'kendaraan-khusus' => 'kend_khusus'
];

$dbType = array_key_exists($type, $typeMap) ? $typeMap[$type] : $type;

$allowedTables = ['mobil', 'motor', 'truk', 'alat_berat', 'sepeda', 'kend_khusus'];
if (!in_array($dbType, $allowedTables)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=Jenis kendaraan tidak valid");
  exit;
}

function formatDisplayType(string $type): string
{
  switch ($type) {
    case 'alatberat':
      return 'Alat Berat';
    case 'kend_khusus':
      return 'Kendaraan Khusus';
    default:
      return ucwords(str_replace(['-', '_'], ' ', $type));
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tambah <?= formatDisplayType($type) ?></title>
</head>

<body>
  <div class="container mt-5">
    <h2>Tambah <?= formatDisplayType($type) ?></h2>
    <form method="post" action="/PWD-Project-Mandiri/admin/page/kendaraan/proses-tambah-kendaraan.php?type=<?= $type ?>" enctype="multipart/form-data">

      <div class="mb-3">
        <label>Nama <?= formatDisplayType($type) ?></label>
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

      <div class="form-group mb-3">
        <label for="statusPost" class="form-label">Status Posting</label>
        <select name="status_post" id="statusPost" class="form-control" required>
          <option value="Posting">Posting</option>
          <option value="Belum">Belum</option>
        </select>
      </div>


      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="inputGroupFile01" style="cursor: pointer;">Upload</label>
        </div>
        <div class="custom-file">
          <input type="file" name="gambar" class="custom-file-input" id="inputGroupFile01" accept="image/*" aria-describedby="inputGroupFileAddon01" required>
          <label class="custom-file-label" for="inputGroupFile01">Pilih gambar</label>
        </div>
      </div>


      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="dashboard.php?module=kendaraan&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>