<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'mobil';
$id = intval($_GET['id']);

require_once '../model/Kendaraan.php';
$kendaraan = new Kendaraan();

$row = $kendaraan->getById($type, $id);
if (!$row || !in_array($type, ['mobil', 'motor'])) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $updateData = [
    "nama_$type" => $_POST["nama_$type"],
    'tahun' => $_POST['tahun'],
    'jumlah_unit' => $_POST['jumlah_unit'],
    'harga_per_unit' => $_POST['harga_per_unit'],
    'deskripsi' => $_POST['deskripsi'],
    'status_post' => $_POST['status_post']
  ];

  $projectRoot = realpath(dirname(__DIR__, 1)); // PWD-Project-Mandiri/admin .. satu folder ke atas
  $target_dir = $projectRoot . "/asset/$type/";

  if (!empty($_FILES["gambar"]["name"])) {
    $new_gambar = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $new_gambar;

    // Validasi gambar (boleh kamu tambah sesuai kebutuhan)
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, $allowedExtensions)) {
      die("<div class='alert alert-danger'>Format gambar tidak didukung.</div>");
    }

    if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
      die("<div class='alert alert-danger'>Gagal mengupload gambar.</div>");
    }

    // Hapus gambar lama jika ada
    if (!empty($row['gambar']) && file_exists($target_dir . $row['gambar'])) {
      unlink($target_dir . $row['gambar']);
    }

    $updateData['gambar'] = $new_gambar;
  } else {
    $updateData['gambar'] = $row['gambar'];
  }

  if ($kendaraan->update($type, $id, $updateData)) {
    header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&success=Data berhasil diperbarui");
  } else {
    header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Gagal memperbarui data");
  }
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit <?= ucfirst($type) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <h2>Edit <?= ucfirst($type) ?></h2>
    <form method="post" action="" enctype="multipart/form-data">
      <input type="hidden" name="type" value="<?= $type ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="mb-3">
        <label>Nama <?= ucfirst($type) ?></label>
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

      <div class="mb-3">
        <label>Status Posting</label>
        <select name="status_post" class="form-select" required>
          <option value="Posting" <?= $row['status_post'] == 'Posting' ? 'selected' : '' ?>>Posting</option>
          <option value="Belum" <?= $row['status_post'] == 'Belum' ? 'selected' : '' ?>>Belum</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Gambar Saat Ini</label><br>
        <?php if (!empty($row['gambar'])): ?>
          <img src="/PWD-Project-Mandiri/asset/<?= $type ?>/<?= $row['gambar'] ?>" width="100"><br><br>
        <?php endif; ?>
        <label>Ubah Gambar</label>
        <input type="file" name="gambar" class="form-control">
      </div>

      <button type="submit" class="btn btn-primary">Perbarui</button>
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>