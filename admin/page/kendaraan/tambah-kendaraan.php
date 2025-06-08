<?php
require_once '../model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: login.php");
  exit;
}

$type = $_GET['type'] ?? 'mobil';

// Mapping dari URL ke nama tabel & label tampilan
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
  <title>Tambah <?= formatDisplayType($type) ?></title>
</head>

<body>
  <div class="container mt-5">
    <h2>Tambah <?= formatDisplayType($type) ?></h2>

    <form method="post" action="/PWD-Project-Mandiri/admin/page/kendaraan/proses-tambah-kendaraan.php?type=<?= $type ?>" enctype="multipart/form-data">
      <!-- Merk -->
      <div class="form-group">
        <label for="merk">Merk</label>
        <select name="merk" id="merk" class="form-control" required>
          <?php if ($dbType === 'mobil'): ?>
            <option value="" disabled selected>-- Pilih Merk Mobil --</option>
            <option value="Toyota">Toyota</option>
            <option value="Honda">Honda</option>
            <option value="Suzuki">Suzuki</option>
            <option value="Mitsubishi">Mitsubishi</option>
            <option value="Ford">Ford</option>
            <option value="BMW">BMW</option>
            <option value="Mercedes">Mercedes</option>
            <option value="Hyundai">Hyundai</option>
            <option value="Nissan">Nissan</option>
            <option value="Chevrolet">Chevrolet</option>

          <?php elseif ($dbType === 'motor'): ?>
            <option value="" disabled selected>-- Pilih Merk Motor --</option>
            <option value="Yamaha">Yamaha</option>
            <option value="Honda">Honda</option>
            <option value="Suzuki">Suzuki</option>
            <option value="Kawasaki">Kawasaki</option>
            <option value="Ducati">Ducati</option>
            <option value="KTM">KTM</option>
            <option value="Harley_Davidson">Harley Davidson</option>
            <option value="Vespa">Vespa</option>

          <?php elseif ($dbType === 'truk'): ?>
            <option value="" disabled selected>-- Pilih Merk Truk --</option>
            <option value="Isuzu">Isuzu</option>
            <option value="Hino">Hino</option>
            <option value="Fuso">Fuso</option>
            <option value="Volvo">Volvo</option>
            <option value="Scania">Scania</option>

          <?php elseif ($dbType === 'alat_berat'): ?>
            <option value="" disabled selected>-- Pilih Merk Alat Berat --</option>
            <option value="Komatsu">Komatsu</option>
            <option value="Caterpillar">Caterpillar</option>
            <option value="Hitachi">Hitachi</option>
            <option value="Doosan">Doosan</option>
            <option value="Hyundai">Hyundai</option>

          <?php elseif ($dbType === 'sepeda'): ?>
            <option value="" disabled selected>-- Pilih Merk Sepeda --</option>
            <option value="Polygon">Polygon</option>
            <option value="United">United</option>
            <option value="Wimcycle">Wimcycle</option>
            <option value="Senator">Senator</option>
            <option value="Pacific">Pacific</option>

          <?php elseif ($dbType === 'kend_khusus'): ?>
            <option value="" disabled selected>-- Pilih Merk Kendaraan Khusus --</option>
            <option value="Suzuki">Suzuki</option>
            <option value="Daihatsu">Daihatsu</option>
            <option value="Towing">Towing</option>
            <option value="Isuzu">Isuzu</option>
            <option value="Mitsubishi">Mitsubishi</option>
            <option value="Hyundai">Hyundai</option>

          <?php else: ?>
            <option value="" disabled selected>-- Pilih Merk --</option>
            <option value="Komatsu">Komatsu</option>
            <option value="Isuzu">Isuzu</option>
            <option value="Ford">Ford</option>
          <?php endif; ?>
        </select>
      </div>

      <!-- Nama Kendaraan -->
      <div class="form-group">
        <label for="nama_<?= $dbType ?>">Nama <?= formatDisplayType($type) ?></label>
        <input type="text" name="nama_<?= $dbType ?>" id="nama_<?= $dbType ?>" class="form-control" required>
      </div>

      <!-- Jenis Kendaraan -->
      <div class="form-group">
        <label for="jenis">Jenis <?= formatDisplayType($type) ?></label>
        <select name="jenis_<?= $dbType ?>" id="jenis_<?= $dbType ?>" class="form-control" required>
          <?php if ($dbType === 'mobil'): ?>
            <option value="" disabled selected>-- Pilih Jenis Mobil --</option>
            <option value="MPV">MPV</option>
            <option value="SUV">SUV</option>
            <option value="Sedan">Sedan</option>
            <option value="Hatchback">Hatchback</option>
            <option value="Pickup">Pickup</option>
            <option value="Sport">Sport</option>
            <option value="Listrik">Listrik</option>

          <?php elseif ($dbType === 'motor'): ?>
            <option value="" disabled selected>-- Pilih Jenis Motor --</option>
            <option value="Sport">Sport</option>
            <option value="Trail">Trail</option>
            <option value="Listrik">Listrik</option>
            <option value="Skuter">Skuter</option>
            <option value="Cruiser">Cruiser</option>

          <?php elseif ($dbType === 'truk'): ?>
            <option value="" disabled selected>-- Pilih Jenis Truk --</option>
            <option value="Pickup">Pickup</option>
            <option value="Tanker">Tanker</option>
            <option value="Tipper">Tipper</option>
            <option value="Box">Box</option>
            <option value="Mixer">Mixer</option>
            <option value="Deck">Deck</option>

          <?php elseif ($dbType === 'alat_berat'): ?>
            <option value="" disabled selected>-- Pilih Jenis Alat Berat --</option>
            <option value="Excavator">Excavator</option>
            <option value="Bulldozer">Bulldozer</option>
            <option value="Forklift">Forklift</option>
            <option value="Crane">Crane</option>
            <option value="Loader">Loader</option>

          <?php elseif ($dbType === 'sepeda'): ?>
            <option value="" disabled selected>-- Pilih Jenis Sepeda --</option>
            <option value="Balap">Balap</option>
            <option value="Gunung">Gunung</option>
            <option value="Lipat">Lipat</option>
            <option value="Anak">Anak</option>
            <option value="Listrik">Listrik</option>

          <?php elseif ($dbType === 'kend_khusus'): ?>
            <option value="" disabled selected>-- Pilih Jenis Kendaraan Khusus --</option>
            <option value="Ambulans">Ambulans</option>
            <option value="Pemadam">Pemadam</option>
            <option value="Patroli">Patroli</option>
            <option value="Derek">Derek</option>
          <?php endif; ?>
        </select>
      </div>

      <!-- Tahun -->
      <div class="form-group">
        <label for="tahun">Tahun</label>
        <input type="number" name="tahun" id="tahun" class="form-control" required>
      </div>

      <!-- Kondisi -->
      <div class="form-group">
        <label for="kondisi">Kondisi</label>
        <select name="kondisi" id="kondisi" class="form-control" required>
          <option value="" disabled selected>-- Pilih Kondisi --</option>
          <option value="Baru">Baru</option>
          <option value="Bekas">Bekas</option>
        </select>
      </div>

      <!-- Bahan Bakar -->
      <?php if ($dbType !== 'sepeda'): ?>
        <div class="form-group">
          <label for="bahan_bakar">Bahan Bakar</label>
          <select name="bahan_bakar" id="bahan_bakar" class="form-control" required>
            <option value="" disabled selected>-- Pilih Bahan Bakar --</option>
            <?php if ($dbType === 'mobil' || $dbType === 'motor'): ?>
              <option value="Bensin">Bensin</option>
              <option value="Listrik">Listrik</option>
            <?php elseif ($dbType === 'truk'): ?>
              <option value="Diesel">Diesel</option>
              <option value="Listrik">Listrik</option>
            <?php elseif ($dbType === 'alat_berat'): ?>
              <option value="Diesel">Diesel</option>
            <?php elseif ($dbType === 'kend_khusus'): ?>
              <option value="Bensin">Bensin</option>
              <option value="Diesel">Diesel</option>
              <option value="Listrik">Listrik</option>
            <?php endif; ?>
          </select>
        </div>
      <?php endif; ?>

      <!-- Jumlah Unit -->
      <div class="form-group">
        <label for="jumlah_unit">Jumlah Unit</label>
        <input type="number" name="jumlah_unit" id="jumlah_unit" class="form-control" required>
      </div>

      <!-- Harga Per Unit -->
      <div class="form-group">
        <label for="harga_per_unit">Harga Per Unit</label>
        <input type="number" name="harga_per_unit" id="harga_per_unit" class="form-control" required>
      </div>

      <!-- Input Tambahan Berdasarkan Tabel -->
      <?php if ($dbType === 'mobil'): ?>
        <!-- Jumlah Kursi -->
        <div class="form-group">
          <label for="jumlah_kursi">Jumlah Kursi</label>
          <input type="number" name="jumlah_kursi" id="jumlah_kursi" class="form-control">
        </div>

        <!-- Transmisi Mobil -->
        <div class="form-group">
          <label for="transmisi">Transmisi</label>
          <select name="transmisi" id="transmisi" class="form-control">
            <option value="" disabled selected>-- Pilih Transmisi --</option>
            <option value="Manual">Manual</option>
            <option value="Matic">Matic</option>
          </select>
        </div>

      <?php elseif ($dbType === 'motor'): ?>
        <!-- Transmisi Motor -->
        <div class="form-group">
          <label for="transmisi">Transmisi</label>
          <select name="transmisi" id="transmisi" class="form-control">
            <option value="" disabled selected>-- Pilih Transmisi --</option>
            <option value="Manual">Manual</option>
            <option value="Matic">Matic</option>
          </select>
        </div>

      <?php elseif ($dbType === 'truk'): ?>
        <!-- Kapasitas Muatan -->
        <div class="form-group">
          <label for="kapasitas_muatan">Kapasitas Muatan</label>
          <input type="text" name="kapasitas_muatan" id="kapasitas_muatan" class="form-control">
        </div>

        <!-- Transmisi Truk -->
        <div class="form-group">
          <label for="transmisi">Transmisi</label>
          <select name="transmisi" id="transmisi" class="form-control">
            <option value="" disabled selected>-- Pilih Transmisi --</option>
            <option value="Manual">Manual</option>
            <option value="Matic">Matic</option>
          </select>
        </div>

      <?php elseif ($dbType === 'alat_berat'): ?>
        <!-- Transmisi Alat Berat (default Manual) -->
        <input type="hidden" name="transmisi" value="Manual">

      <?php elseif ($dbType === 'kend_khusus'): ?>
        <!-- Transmisi Kendaraan Khusus -->
        <div class="form-group">
          <label for="transmisi">Transmisi</label>
          <select name="transmisi" id="transmisi" class="form-control">
            <option value="" disabled selected>-- Pilih Transmisi --</option>
            <option value="Manual">Manual</option>
            <option value="Matic">Matic</option>
          </select>
        </div>
      <?php endif; ?>

      <!-- Deskripsi -->
      <div class="form-group">
        <label for="deskripsi">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
      </div>

      <!-- Status Posting -->
      <div class="form-group">
        <label for="status_post">Status Posting</label>
        <select name="status_post" id="status_post" class="form-control" required>
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

      <!-- Tombol Simpan -->
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="dashboard.php?module=kendaraan&page=<?= $type ?>" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap @4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>