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
    case 'alat_berat':
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap @4.6.2/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <h2 class="mt-4 mb-4"><?= formatDisplayType($type) ?></h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post" action="/PWD-Project-Mandiri/admin/page/kendaraan/proses-edit-kendaraan.php?type=<?= $type ?>&id=<?= $id ?>" enctype="multipart/form-data">
      <input type="hidden" name="type" value="<?= $type ?>">
      <input type="hidden" name="id" value="<?= $id ?>">

      <!-- Merk -->
      <div class="form-group">
        <label for="merk">Merk</label>
        <select name="merk" id="merk" class="form-control" required>
          <?php if ($dbType === 'mobil'): ?>
            <option value="Toyota" <?= $row['merk'] == 'Toyota' ? 'selected' : '' ?>>Toyota</option>
            <option value="Honda" <?= $row['merk'] == 'Honda' ? 'selected' : '' ?>>Honda</option>
            <option value="Suzuki" <?= $row['merk'] == 'Suzuki' ? 'selected' : '' ?>>Suzuki</option>
            <option value="Mitsubishi" <?= $row['merk'] == 'Mitsubishi' ? 'selected' : '' ?>>Mitsubishi</option>
            <option value="Ford" <?= $row['merk'] == 'Ford' ? 'selected' : '' ?>>Ford</option>
            <option value="BMW" <?= $row['merk'] == 'BMW' ? 'selected' : '' ?>>BMW</option>
            <option value="Mercedes" <?= $row['merk'] == 'Mercedes' ? 'selected' : '' ?>>Mercedes</option>
            <option value="Hyundai" <?= $row['merk'] == 'Hyundai' ? 'selected' : '' ?>>Hyundai</option>
            <option value="Nissan" <?= $row['merk'] == 'Nissan' ? 'selected' : '' ?>>Nissan</option>
            <option value="Chevrolet" <?= $row['merk'] == 'Chevrolet' ? 'selected' : '' ?>>Chevrolet</option>

          <?php elseif ($dbType === 'motor'): ?>
            <option value="Yamaha" <?= $row['merk'] == 'Yamaha' ? 'selected' : '' ?>>Yamaha</option>
            <option value="Honda" <?= $row['merk'] == 'Honda' ? 'selected' : '' ?>>Honda</option>
            <option value="Suzuki" <?= $row['merk'] == 'Suzuki' ? 'selected' : '' ?>>Suzuki</option>
            <option value="Kawasaki" <?= $row['merk'] == 'Kawasaki' ? 'selected' : '' ?>>Kawasaki</option>
            <option value="Ducati" <?= $row['merk'] == 'Ducati' ? 'selected' : '' ?>>Ducati</option>
            <option value="KTM" <?= $row['merk'] == 'KTM' ? 'selected' : '' ?>>KTM</option>
            <option value="Harley_Davidson" <?= $row['merk'] == 'Harley_Davidson' ? 'selected' : '' ?>>Harley Davidson</option>
            <option value="Vespa" <?= $row['merk'] == 'Vespa' ? 'selected' : '' ?>>Vespa</option>

          <?php elseif ($dbType === 'truk'): ?>
            <option value="Isuzu" <?= $row['merk'] == 'Isuzu' ? 'selected' : '' ?>>Isuzu</option>
            <option value="Hino" <?= $row['merk'] == 'Hino' ? 'selected' : '' ?>>Hino</option>
            <option value="Fuso" <?= $row['merk'] == 'Fuso' ? 'selected' : '' ?>>Fuso</option>
            <option value="Volvo" <?= $row['merk'] == 'Volvo' ? 'selected' : '' ?>>Volvo</option>
            <option value="Scania" <?= $row['merk'] == 'Scania' ? 'selected' : '' ?>>Scania</option>

          <?php elseif ($dbType === 'alat_berat'): ?>
            <option value="Komatsu" <?= $row['merk'] == 'Komatsu' ? 'selected' : '' ?>>Komatsu</option>
            <option value="Caterpillar" <?= $row['merk'] == 'Caterpillar' ? 'selected' : '' ?>>Caterpillar</option>
            <option value="Hitachi" <?= $row['merk'] == 'Hitachi' ? 'selected' : '' ?>>Hitachi</option>
            <option value="Doosan" <?= $row['merk'] == 'Doosan' ? 'selected' : '' ?>>Doosan</option>
            <option value="Hyundai" <?= $row['merk'] == 'Hyundai' ? 'selected' : '' ?>>Hyundai</option>

          <?php elseif ($dbType === 'sepeda'): ?>
            <option value="Polygon" <?= $row['merk'] == 'Polygon' ? 'selected' : '' ?>>Polygon</option>
            <option value="United" <?= $row['merk'] == 'United' ? 'selected' : '' ?>>United</option>
            <option value="Wimcycle" <?= $row['merk'] == 'Wimcycle' ? 'selected' : '' ?>>Wimcycle</option>
            <option value="Senator" <?= $row['merk'] == 'Senator' ? 'selected' : '' ?>>Senator</option>
            <option value="Pacific" <?= $row['merk'] == 'Pacific' ? 'selected' : '' ?>>Pacific</option>

          <?php elseif ($dbType === 'kend_khusus'): ?>
            <option value="Suzuki" <?= $row['merk'] == 'Suzuki' ? 'selected' : '' ?>>Suzuki</option>
            <option value="Daihatsu" <?= $row['merk'] == 'Daihatsu' ? 'selected' : '' ?>>Daihatsu</option>
            <option value="Towing" <?= $row['merk'] == 'Towing' ? 'selected' : '' ?>>Towing</option>
            <option value="Isuzu" <?= $row['merk'] == 'Isuzu' ? 'selected' : '' ?>>Isuzu</option>
            <option value="Mitsubishi" <?= $row['merk'] == 'Mitsubishi' ? 'selected' : '' ?>>Mitsubishi</option>
            <option value="Hyundai" <?= $row['merk'] == 'Hyundai' ? 'selected' : '' ?>>Hyundai</option>

          <?php else: ?>
            <option value="Komatsu" <?= $row['merk'] == 'Komatsu' ? 'selected' : '' ?>>Komatsu</option>
            <option value="Isuzu" <?= $row['merk'] == 'Isuzu' ? 'selected' : '' ?>>Isuzu</option>
            <option value="Ford" <?= $row['merk'] == 'Ford' ? 'selected' : '' ?>>Ford</option>
          <?php endif; ?>
        </select>
      </div>

      <!-- Nama Kendaraan -->
      <div class="form-group">
        <label>Nama <?= formatDisplayType($type) ?></label>
        <input type="text" name="nama_<?= $dbType ?>" class="form-control"
          value="<?= htmlspecialchars($row["nama_$dbType"]) ?>" required>
      </div>

      <!-- Jenis Kendaraan -->
      <div class="form-group">
        <label>Jenis <?= formatDisplayType($type) ?></label>
        <select name="jenis_<?= $dbType ?>" class="form-control" required>
          <?php if ($dbType === 'mobil'): ?>
            <option value="MPV" <?= $row['jenis_mobil'] == 'MPV' ? 'selected' : '' ?>>MPV</option>
            <option value="SUV" <?= $row['jenis_mobil'] == 'SUV' ? 'selected' : '' ?>>SUV</option>
            <option value="Sedan" <?= $row['jenis_mobil'] == 'Sedan' ? 'selected' : '' ?>>Sedan</option>
            <option value="Hatchback" <?= $row['jenis_mobil'] == 'Hatchback' ? 'selected' : '' ?>>Hatchback</option>
            <option value="Pickup" <?= $row['jenis_mobil'] == 'Pickup' ? 'selected' : '' ?>>Pickup</option>
            <option value="Sport" <?= $row['jenis_mobil'] == 'Sport' ? 'selected' : '' ?>>Sport</option>
            <option value="Listrik" <?= $row['jenis_mobil'] == 'Listrik' ? 'selected' : '' ?>>Listrik</option>

          <?php elseif ($dbType === 'motor'): ?>
            <option value="Manual" <?= $row['jenis_motor'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
            <option value="Matic" <?= $row['jenis_motor'] == 'Matic' ? 'selected' : '' ?>>Matic</option>
            <option value="Sport" <?= $row['jenis_motor'] == 'Sport' ? 'selected' : '' ?>>Sport</option>
            <option value="Trail" <?= $row['jenis_motor'] == 'Trail' ? 'selected' : '' ?>>Trail</option>
            <option value="Listrik" <?= $row['jenis_motor'] == 'Listrik' ? 'selected' : '' ?>>Listrik</option>
            <option value="Skuter" <?= $row['jenis_motor'] == 'Skuter' ? 'selected' : '' ?>>Skuter</option>
            <option value="Cruiser" <?= $row['jenis_motor'] == 'Cruiser' ? 'selected' : '' ?>>Cruiser</option>

          <?php elseif ($dbType === 'truk'): ?>
            <option value="Pick Up" <?= $row['jenis_truk'] == 'Pick Up' ? 'selected' : '' ?>>Pick Up</option>
            <option value="Tanker" <?= $row['jenis_truk'] == 'Tanker' ? 'selected' : '' ?>>Tanker</option>
            <option value="Tipper" <?= $row['jenis_truk'] == 'Tipper' ? 'selected' : '' ?>>Tipper</option>
            <option value="Box" <?= $row['jenis_truk'] == 'Box' ? 'selected' : '' ?>>Box</option>
            <option value="Mixer" <?= $row['jenis_truk'] == 'Mixer' ? 'selected' : '' ?>>Mixer</option>
            <option value="Deck" <?= $row['jenis_truk'] == 'Deck' ? 'selected' : '' ?>>Deck</option>

          <?php elseif ($dbType === 'alat_berat'): ?>
            <option value="Excavator" <?= $row['jenis_alat_berat'] == 'Excavator' ? 'selected' : '' ?>>Excavator</option>
            <option value="Bulldozer" <?= $row['jenis_alat_berat'] == 'Bulldozer' ? 'selected' : '' ?>>Bulldozer</option>
            <option value="Forklift" <?= $row['jenis_alat_berat'] == 'Forklift' ? 'selected' : '' ?>>Forklift</option>
            <option value="Crane" <?= $row['jenis_alat_berat'] == 'Crane' ? 'selected' : '' ?>>Crane</option>
            <option value="Loader" <?= $row['jenis_alat_berat'] == 'Loader' ? 'selected' : '' ?>>Loader</option>

          <?php elseif ($dbType === 'sepeda'): ?>
            <option value="Balap" <?= $row['jenis_sepeda'] == 'Balap' ? 'selected' : '' ?>>Balap</option>
            <option value="Gunung" <?= $row['jenis_sepeda'] == 'Gunung' ? 'selected' : '' ?>>Gunung</option>
            <option value="Lipat" <?= $row['jenis_sepeda'] == 'Lipat' ? 'selected' : '' ?>>Lipat</option>
            <option value="Anak" <?= $row['jenis_sepeda'] == 'Anak' ? 'selected' : '' ?>>Anak</option>
            <option value="Listrik" <?= $row['jenis_sepeda'] == 'Listrik' ? 'selected' : '' ?>>Listrik</option>

          <?php elseif ($dbType === 'kend_khusus'): ?>
            <option value="Ambulans" <?= $row['jenis_kend_khusus'] == 'Ambulans' ? 'selected' : '' ?>>Ambulans</option>
            <option value="Damkar" <?= $row['jenis_kend_khusus'] == 'Damkar' ? 'selected' : '' ?>>Damkar</option>
            <option value="Patroli" <?= $row['jenis_kend_khusus'] == 'Patroli' ? 'selected' : '' ?>>Patroli</option>
            <option value="Derek" <?= $row['jenis_kend_khusus'] == 'Derek' ? 'selected' : '' ?>>Derek</option>
          <?php endif; ?>
        </select>
      </div>

      <!-- Tahun -->
      <div class="form-group">
        <label>Tahun</label>
        <input type="number" name="tahun" class="form-control"
          value="<?= htmlspecialchars($row['tahun']) ?>" required>
      </div>

      <!-- Kondisi -->
      <div class="form-group">
        <label>Kondisi</label>
        <select name="kondisi" class="form-control" required>
          <option value="Baru" <?= $row['kondisi'] == 'Baru' ? 'selected' : '' ?>>Baru</option>
          <option value="Bekas" <?= $row['kondisi'] == 'Bekas' ? 'selected' : '' ?>>Bekas</option>
        </select>
      </div>

      <!-- Bahan Bakar -->
      <?php if ($dbType !== 'sepeda'): ?>
        <div class="form-group">
          <label>Bahan Bakar</label>
          <select name="bahan_bakar" class="form-control" required>
            <?php if ($dbType === 'mobil' || $dbType === 'motor'): ?>
              <option value="Bensin" <?= $row['bahan_bakar'] == 'Bensin' ? 'selected' : '' ?>>Bensin</option>
              <option value="Listrik" <?= $row['bahan_bakar'] == 'Listrik' ? 'selected' : '' ?>>Listrik</option>
            <?php elseif ($dbType === 'truk'): ?>
              <option value="Diesel" <?= $row['bahan_bakar'] == 'Diesel' ? 'selected' : '' ?>>Diesel</option>
              <option value="Listrik" <?= $row['bahan_bakar'] == 'Listrik' ? 'selected' : '' ?>>Listrik</option>
            <?php elseif ($dbType === 'alat_berat'): ?>
              <option value="Diesel" <?= $row['bahan_bakar'] == 'Diesel' ? 'selected' : '' ?>>Diesel</option>
            <?php elseif ($dbType === 'kend_khusus'): ?>
              <option value="Bensin" <?= $row['bahan_bakar'] == 'Bensin' ? 'selected' : '' ?>>Bensin</option>
              <option value="Diesel" <?= $row['bahan_bakar'] == 'Diesel' ? 'selected' : '' ?>>Diesel</option>
              <option value="Listrik" <?= $row['bahan_bakar'] == 'Listrik' ? 'selected' : '' ?>>Listrik</option>
            <?php endif; ?>
          </select>
        </div>
      <?php endif; ?>

      <!-- Jumlah Unit -->
      <div class="form-group">
        <label>Jumlah Unit</label>
        <input type="number" name="jumlah_unit" class="form-control"
          value="<?= htmlspecialchars($row['jumlah_unit']) ?>" required>
      </div>

      <!-- Harga Per Unit -->
      <div class="form-group">
        <label>Harga Per Unit</label>
        <input type="number" name="harga_per_unit" class="form-control"
          value="<?= htmlspecialchars($row['harga_per_unit']) ?>" required>
      </div>

      <!-- Input Tambahan Spesifik -->
      <?php if ($dbType === 'mobil'): ?>
        <!-- Mobil -->
        <div class="form-group">
          <label>Jumlah Kursi</label>
          <input type="number" name="jumlah_kursi" class="form-control"
            value="<?= htmlspecialchars($row['jumlah_kursi']) ?>">
        </div>

        <div class="form-group">
          <label>Transmisi</label>
          <select name="transmisi" class="form-control">
            <option value="Manual" <?= $row['transmisi'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
            <option value="Matic" <?= $row['transmisi'] == 'Matic' ? 'selected' : '' ?>>Matic</option>
          </select>
        </div>

      <?php elseif ($dbType === 'motor'): ?>
        <!-- Motor -->
        <div class="form-group">
          <label>Transmisi</label>
          <select name="transmisi" class="form-control">
            <option value="Manual" <?= $row['transmisi'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
            <option value="Matic" <?= $row['transmisi'] == 'Matic' ? 'selected' : '' ?>>Matic</option>
          </select>
        </div>

      <?php elseif ($dbType === 'truk'): ?>
        <!-- Truk -->
        <div class="form-group">
          <label>Kapasitas Muatan</label>
          <input type="text" name="kapasitas_muatan" class="form-control"
            value="<?= htmlspecialchars($row['kapasitas_muatan'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label>Transmisi</label>
          <select name="transmisi" class="form-control">
            <option value="Manual" <?= $row['transmisi'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
            <option value="Matic" <?= $row['transmisi'] == 'Matic' ? 'selected' : '' ?>>Matic</option>
          </select>
        </div>

      <?php elseif ($dbType === 'kend_khusus'): ?>
        <!-- Kendaraan Khusus -->
        <div class="form-group">
          <label>Transmisi</label>
          <select name="transmisi" class="form-control">
            <option value="Manual" <?= $row['transmisi'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
            <option value="Matic" <?= $row['transmisi'] == 'Matic' ? 'selected' : '' ?>>Matic</option>
          </select>
        </div>
      <?php endif; ?>

      <!-- Deskripsi -->
      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
      </div>

      <!-- Status Posting -->
      <div class="form-group">
        <label>Status Posting</label>
        <select name="status_post" class="form-control" required>
          <option value="Posting" <?= $row['status_post'] == 'Posting' ? 'selected' : '' ?>>Posting</option>
          <option value="Belum" <?= $row['status_post'] == 'Belum' ? 'selected' : '' ?>>Belum</option>
        </select>
      </div>

      <!-- Gambar Saat Ini -->
      <div class="form-group">
        <label>Gambar Saat Ini</label><br>
        <?php if (!empty($row['gambar'])): ?>
          <img src="/PWD-Project-Mandiri/asset/<?= $type ?>/<?= $row['gambar'] ?>" width="120" class="img-thumbnail"><br>
          <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar</small>
        <?php else: ?>
          <p class="text-muted">Belum ada gambar</p>
        <?php endif; ?>
      </div>

      <!-- Upload Gambar Baru -->
      <div class="form-group">
        <label>Upload Gambar Baru (Opsional)</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">Upload</span>
          </div>
          <div class="custom-file">
            <input type="file" name="gambar" class="custom-file-input" id="inputGroupFile01" accept="image/*">
            <label class="custom-file-label" for="inputGroupFile01">Pilih gambar</label>
          </div>
        </div>
      </div>

      <!-- Tombol Simpan -->
      <button type="submit" class="btn btn-primary">Perbarui</button>
      <a href="/PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <!-- Bootstrap 4 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap @4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>