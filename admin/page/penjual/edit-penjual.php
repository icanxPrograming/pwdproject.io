<?php
require_once '../model/Session.php';
require_once '../model/Penjual.php';
$session = new AppSession();
$penjual = new Penjual();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-penjual';
$id = intval($_GET['id']);


$tableName = 'penjual';
$row = $penjual->getById($tableName, $id);
if (!$row) {
  $_SESSION['error'] = "Data penjual tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Penjual</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Edit Penjual</h2>

    <?php
    if (isset($_SESSION['error'])) {
      echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
      unset($_SESSION['error']);
    }
    ?>

    <form method="post" action="/PWD-Project-Mandiri/admin/page/penjual/proses-edit-penjual.php?type=<?= $type ?>&id=<?= $id ?>" enctype="multipart/form-data">
      <input type="hidden" name="type" value="<?= $type ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="mb-3">
        <label>Nama Penjual</label>
        <input type="text" name="nama_penjual" class="form-control"
          value="<?= htmlspecialchars($row['nama_penjual']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control"
          value="<?= htmlspecialchars($row['email']) ?>" required>
      </div>

      <div class="mb-3">
        <label>No HP</label>
        <input type="text" name="no_hp" class="form-control"
          value="<?= htmlspecialchars($row['no_hp']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($row['alamat']) ?></textarea>
      </div>

      <div class="mb-3">
        <label>Tipe Penjual</label>
        <select name="tipe_penjual" class="form-select" required>
          <option value="dealer" <?= $row['tipe_penjual'] == 'dealer' ? 'selected' : '' ?>>Dealer</option>
          <option value="perorangan" <?= $row['tipe_penjual'] == 'perorangan' ? 'selected' : '' ?>>Perorangan</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select" required>
          <option value="Aktif" <?= $row['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
          <option value="Nonaktif" <?= $row['status'] == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Perbarui</button>
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>