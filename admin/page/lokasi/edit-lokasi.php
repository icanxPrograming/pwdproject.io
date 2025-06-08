<?php
require_once '../model/Session.php';
require_once '../model/Lokasi.php';
$session = new AppSession();
$lokasi = new Lokasi();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-lokasi';
$id = intval($_GET['id']);


$tableName = 'lokasi';
$row = $lokasi->getById($tableName, $id);
if (!$row) {
  $_SESSION['error'] = "Data lokasi tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=lokasi&page=$type");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Lokasi</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Edit Lokasi</h2>

    <?php
    if (isset($_SESSION['error'])) {
      echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
      unset($_SESSION['error']);
    }
    ?>

    <form method="post" action="/PWD-Project-Mandiri/admin/page/lokasi/proses-edit-lokasi.php?type=<?= $type ?>&id=<?= $id ?>" enctype="multipart/form-data">
      <input type="hidden" name="type" value="<?= $type ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="mb-3">
        <label>Nama lokasi</label>
        <input type="text" name="nama_lokasi" class="form-control"
          value="<?= htmlspecialchars($row['nama_lokasi']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="Aktif" <?= $row['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
          <option value="Nonaktif" <?= $row['status'] == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Perbarui</button>
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=lokasi&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>