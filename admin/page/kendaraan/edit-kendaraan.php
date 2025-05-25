<?php
require_once '../model/Session.php';
require_once '../model/Kendaraan.php';

$session = new AppSession();
$kendaraan = new Kendaraan();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'mobil';
$id = intval($_GET['id']);

$typeMap = [
  'alat-berat'       => 'alat_berat',
  'kendaraan-khusus' => 'kend_khusus'
];

$dbType = array_key_exists($type, $typeMap) ? $typeMap[$type] : $type;

$allowedDbTypes = ['mobil', 'motor', 'truk', 'alat_berat', 'sepeda', 'kend_khusus'];

if (!in_array($dbType, $allowedDbTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
  exit;
}

$row = $kendaraan->getById($dbType, $id);

if (!$row || empty($row)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
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
  <title>Edit <?= formatDisplayType($type) ?></title>
</head>

<body>
  <div class="container mt-5">
    <h2>Edit <?= formatDisplayType($type) ?></h2>

    <?php
    if (isset($_SESSION['error'])) {
      echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
      unset($_SESSION['error']);
    }
    ?>

    <form method="post" action="/PWD-Project-Mandiri/admin/page/kendaraan/proses-edit-kendaraan.php?type=<?= $type ?>&id=<?= $id ?>" enctype="multipart/form-data">
      <input type="hidden" name="type" value="<?= $type ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="mb-3">
        <label>Nama <?= formatDisplayType($type) ?></label>
        <input type="text" name="nama_<?= $type ?>" class="form-control"
          value="<?= htmlspecialchars($row["nama_$type"]) ?>" required>
      </div>

      <div class="mb-3">
        <label>Tahun</label>
        <input type="number" name="tahun" class="form-control"
          value="<?= htmlspecialchars($row['tahun']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Jumlah Unit</label>
        <input type="number" name="jumlah_unit" class="form-control"
          value="<?= htmlspecialchars($row['jumlah_unit']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Harga Per Unit</label>
        <input type="number" name="harga_per_unit" class="form-control"
          value="<?= htmlspecialchars($row['harga_per_unit']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="5"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
      </div>

      <div class="form-group mb-3">
        <label for="status_post">Status Posting</label>
        <select name="status_post" id="status_post" class="form-control" required>
          <option value="Posting" <?= $row['status_post'] == 'Posting' ? 'selected' : '' ?>>Posting</option>
          <option value="Belum" <?= $row['status_post'] == 'Belum' ? 'selected' : '' ?>>Belum</option>
        </select>
      </div>

      <div class="form-group mb-3">
        <label>Gambar Saat Ini</label><br>
        <?php if (!empty($row['gambar'])): ?>
          <img src="/PWD-Project-Mandiri/asset/<?= $type ?>/<?= $row['gambar'] ?>" width="120" class="img-thumbnail mb-3"><br>
          <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar</small>
        <?php else: ?>
          <p class="text-muted">Belum ada gambar</p>
        <?php endif; ?>
        <br>
        <label for="inputGroupFile01">Ubah Gambar (Opsional)</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <label class="input-group-text" for="inputGroupFile01" style="cursor: pointer;">Upload</label>
          </div>
          <div class="custom-file">
            <input type="file" name="gambar" class="custom-file-input" id="inputGroupFile01" accept="image/*">
            <label class="custom-file-label" for="inputGroupFile01">Pilih gambar</label>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Perbarui</button>
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>