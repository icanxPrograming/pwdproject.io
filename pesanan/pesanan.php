<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn()) {
  die('Anda harus login terlebih dahulu untuk mengakses halaman ini.');
}

$user = $session->getUserData();
$type = $_GET['type'] ?? '';
$id_produk = intval($_GET['id'] ?? 0);

// Validasi type kendaraan
$allowedTypes = ['mobil', 'motor', 'truk', 'alat_berat', 'sepeda', 'kend_khusus', 'kebutuhan'];
if (!in_array($type, $allowedTypes)) {
  die("Jenis produk tidak dikenali.");
}

// Load model utama
if ($type === 'kebutuhan') {
  require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
  $produkModel = new Kebutuhan();
  $dataProduk = $produkModel->getById('kebutuhan', $id_produk);
} else {
  require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
  $produkModel = new Kendaraan();
  $dataProduk = $produkModel->getById($type, $id_produk);
}

if (!$dataProduk) {
  die("Produk tidak ditemukan.");
}

// Ambil & bersihkan harga satuan
$rawHarga = $dataProduk['harga_per_unit'] ?? $dataProduk['harga'] ?? '0';

if (is_numeric($rawHarga)) {
  // Jika dari DB (DECIMAL), hapus desimal
  $hargaSatuan = floatval($rawHarga);
  $hargaSatuan = intval($hargaSatuan); // Pastikan jadi integer
} else {
  // Jika dari web (dengan titik/koma), bersihkan
  $bersih = preg_replace('/[^0-9]/', '', $rawHarga);
  $hargaSatuan = intval($bersih);
}

// Gambar produk
$basePath = "/PWD-Project-Mandiri/asset/{$type}/" . ($dataProduk['gambar'] ?? 'no-image.png');

// Ambil stok sesuai type
$stok = ($type === 'kebutuhan') ? ($dataProduk['jumlah'] ?? 1) : ($dataProduk['jumlah_unit'] ?? 1);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Pesan <?= htmlspecialchars(ucfirst($type)) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f9f9f9;
      padding: 40px 20px;
      margin: 0;
    }

    h2.page-title {
      text-align: center;
      color: #333;
      margin-bottom: 40px;
      font-weight: 600;
    }

    .pesan-container {
      display: flex;
      gap: 40px;
      max-width: 1100px;
      margin: 0 auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      flex-wrap: wrap;
    }

    .form-pesanan {
      flex: 1;
      min-width: 300px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      background-color: #fafafa;
    }

    .form-group small {
      display: block;
      margin-top: 4px;
      font-size: 12px;
      color: #888;
    }

    .btn-submit {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 14px 20px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
      background-color: #0056b3;
    }

    .btn-kembali {
      text-decoration: none;
      background-color: #495057;
      color: white;
      padding: 14px 20px;
      font-size: 16px;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .btn-kembali:hover {
      background-color: #212529;
    }

    .produk-detail {
      flex: 1;
      min-width: 300px;
      background-color: #f1f1f1;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .product-image {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 16px;
    }

    .price {
      font-size: 18px;
      color: #28a745;
      font-weight: bold;
    }

    .product-info p {
      margin: 8px 0;
      color: #555;
    }

    @media (max-width: 768px) {
      .pesan-container {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>

  <h2 class="page-title">Pemesanan Produk</h2>

  <div class="pesan-container">
    <!-- Form Pesanan -->
    <div class="form-pesanan">
      <form action="/PWD-Project-Mandiri/pesanan/proses-pesanan.php" method="POST">
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
        <input type="hidden" name="id_produk" value="<?= $id_produk ?>">
        <input type="hidden" name="id_user" value="<?= htmlspecialchars($user['id_pengguna']) ?>">

        <div class="form-group">
          <label>Nama Pemesan</label>
          <input type="text"
            value="<?= htmlspecialchars($user['username']) ?>"
            readonly>
        </div>

        <div class="form-group">
          <label>Jumlah Unit</label>
          <input type="number"
            name="jumlah_unit"
            id="jumlah_unit"
            value="1"
            min="1"
            max="<?= $stok ?>"
            required>
          <small>Maksimal: <?= $stok ?></small>
        </div>

        <div class="form-group">
          <label>Total Harga</label>
          <input type="text" id="total_harga"
            value="Rp <?= number_format($hargaSatuan, 0, ',', '.') ?>" readonly>
        </div>

        <div class="form-group">
          <label>Metode Bayar</label>
          <select name="metode_bayar" required>
            <option value="" disabled selected>--Pilih Metode--</option>
            <option value="Transfer">Transfer</option>
            <option value="Tunai">Tunai</option>
            <option value="Kredit">Kredit</option>
            <option value="Debit">Debit</option>
          </select>
        </div>

        <div class="form-group">
          <label for="pesan_tambahan">Pesan Tambahan (opsional)</label>
          <textarea name="pesan_tambahan" id="pesan_tambahan" rows="4" placeholder="Misal: Warna putih jika tersedia"></textarea>
        </div>

        <button type="button" class="btn-submit" onclick="konfirmasiPesanan(event)">
          <i class="fa fa-shopping-cart"></i> Kirim Pesanan
        </button>
        <a href="/PWD-Project-Mandiri/index.php?page=<?= $type ?>" class="btn-kembali">Batal</a>
      </form>
    </div>

    <!-- Detail Produk -->
    <div class="produk-detail">
      <img src="<?= $basePath ?>" alt="<?= htmlspecialchars($dataProduk["nama_$type"] ?? '') ?>" class="product-image">
      <h3><?= htmlspecialchars($dataProduk["nama_$type"] ?? $dataProduk['judul_promo'] ?? $dataProduk['nama_kebutuhan'] ?? 'Tidak tersedia') ?></h3>
      <p class="price">Harga Per Unit: Rp <?= number_format($hargaSatuan, 0, ',', '.') ?></p>

      <div class="product-info">
        <?php if ($type === 'kebutuhan'): ?>
          <p><strong>Kategori:</strong> <?= htmlspecialchars($dataProduk['kategori'] ?? '-') ?></p>
          <p><strong>Stok Tersisa:</strong> <?= $dataProduk['jumlah'] ?? '-' ?></p>
        <?php else: ?>
          <p><strong>Tahun:</strong> <?= htmlspecialchars($dataProduk['tahun'] ?? '-') ?></p>
          <p><strong>Bahan Bakar:</strong> <?= htmlspecialchars($dataProduk['bahan_bakar'] ?? '-') ?></p>
          <p><strong>Stok Tersisa:</strong> <?= $stok ?></p>
          <p><strong>Kondisi:</strong> <?= htmlspecialchars($dataProduk['kondisi'] ?? '-') ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    const jumlahInput = document.getElementById("jumlah_unit");
    const totalHargaEl = document.getElementById("total_harga");
    const hargaSatuan = <?= $hargaSatuan ?>; // Sekarang pasti integer → 200000000
    const maxUnit = parseInt(jumlahInput.getAttribute("max"));

    if (jumlahInput && totalHargaEl) {
      jumlahInput.addEventListener("input", () => {
        let jumlah = parseInt(jumlahInput.value);
        if (isNaN(jumlah) || jumlah <= 0) {
          jumlahInput.value = 1;
          jumlah = 1;
        } else if (jumlah > maxUnit) {
          alert("Jumlah melebihi stok yang tersedia!");
          jumlahInput.value = maxUnit;
          jumlah = maxUnit;
        }

        const total = hargaSatuan * jumlah;

        // Format untuk tampilan user
        totalHargaEl.value = "Rp " + total.toLocaleString('id-ID');
      });
    }

    function konfirmasiPesanan(e) {
      const jumlahUnit = document.querySelector("input[name='jumlah_unit']").value;
      const metodeBayar = document.querySelector("select[name='metode_bayar']").value;
      const pesanTambahan = document.querySelector("textarea[name='pesan_tambahan']").value.trim();
      const namaProduk = "<?= addslashes(htmlspecialchars($dataProduk["nama_$type"] ?? $dataProduk['judul_promo'] ?? $dataProduk['nama_kebutuhan'] ?? 'Tidak tersedia')) ?>";

      let message = "Cek kembali pesanan Anda:\n\n";
      message += "Nama Produk: " + namaProduk + "\n";
      message += "Jumlah Unit: " + jumlahUnit + "\n";
      message += "Harga Satuan: Rp <?= number_format($hargaSatuan, 0, ',', '.') ?>\n"; // Tidak ada .00
      message += "Total Harga: Rp " + (hargaSatuan * jumlahUnit).toLocaleString('id-ID') + "\n";
      message += "Metode Bayar: " + (metodeBayar || '-') + "\n";

      if (pesanTambahan !== "") {
        message += "Pesan Tambahan: " + pesanTambahan + "\n";
      }

      message += "\nApakah semua sudah benar?";

      const isConfirmed = confirm(message);
      if (isConfirmed) {
        e.target.form.submit(); // Submit form
      } else {
        return false; // Batal kirim
      }
    }
  </script>

</body>

</html>