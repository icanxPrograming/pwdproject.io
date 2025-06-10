<?php
require_once '../model/Session.php';
require_once '../model/Services.php';
$session = new AppSession();
$service = new Services();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-services';
$id = intval($_GET['id']);


$tableName = 'services';
$row = $service->getById($tableName, $id);
if (!$row) {
  $_SESSION['error'] = "Data services tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit services</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Edit Services</h2>

    <?php
    if (isset($_SESSION['error'])) {
      echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
      unset($_SESSION['error']);
    }
    ?>

    <form method="post" action="/PWD-Project-Mandiri/admin/page/services/proses-edit-services.php?type=<?= $type ?>&id=<?= $id ?>" enctype="multipart/form-data">
      <input type="hidden" name="type" value="<?= $type ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <!-- Icon -->
      <div class="mb-3">
        <label>Icon (contoh: 📚 🔒 💸)</label>
        <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($row['icon']) ?>" required>
      </div>

      <!-- Judul Section -->
      <div class="mb-3">
        <label>Judul Section</label>
        <input type="text" name="judul_section" class="form-control" value="<?= htmlspecialchars($row['judul_section']) ?>" required>
      </div>

      <!-- Konten -->
      <div class="mb-3">
        <label>Konten (gunakan format HTML, list, dsb)</label>
        <textarea name="konten" class="form-control" rows="5" required><?= htmlspecialchars($row['konten']) ?></textarea>
      </div>

      <!-- Urutan -->
      <div class="mb-3">
        <label>Urutan</label>
        <input type="number" name="urutan" class="form-control" value="<?= htmlspecialchars($row['urutan']) ?>" required>
      </div>

      <!-- Status -->
      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="Aktif" <?= $row['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
          <option value="Tidak" <?= $row['status'] == 'Tidak' ? 'selected' : '' ?>>Tidak</option>
        </select>
      </div>

      <!-- Tombol Submit -->
      <button type="submit" class="btn btn-primary">Perbarui</button>
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=services&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>