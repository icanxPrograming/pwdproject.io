<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn()) {
  header("Location: /PWD-Project-Mandiri/admin/login.php");
  exit;
}

// Jika user adalah admin → redirect ke dashboard admin
if ($session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php");
  exit;
}

$user = $session->getUserData();
$id_user = $user['id_pengguna'];

// Pagination
$limit = 3; // Maksimal 5 pesanan per halaman
$page = isset($_GET['hal']) ? intval($_GET['hal']) : 1;
$start = ($page > 1) ? ($page * $limit - $limit) : 0;

// Ambil jumlah total pesanan
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Transaksi.php';
$transaksi = new Transaksi();
$totalPesanan = count($transaksi->getByUserIdWithImage($id_user));
$pesananList = $transaksi->getByUserIdLimit($id_user, $start, $limit);

// Hitung jumlah halaman
$totalPages = ceil($totalPesanan / $limit);
?>
<div class="user-history-container">
  <h2 class="user-history-title">Riwayat Pesanan</h2>

  <?php if (empty($pesananList)): ?>
    <p class="user-history-empty">Belum ada pesanan.</p>
  <?php else: ?>
    <div class="user-order-list">
      <?php foreach ($pesananList as $pesanan):
        // Warna badge sesuai status
        $status = strtolower($pesanan['status']);
        switch ($status) {
          case 'pending':
            $statusClass = 'badge-warning';
            break;
          case 'dikonfirmasi':
            $statusClass = 'badge-success';
            break;
          case 'dibatalkan':
            $statusClass = 'badge-danger';
            break;
          case 'selesai':
            $statusClass = 'badge-primary';
            break;
          default:
            $statusClass = 'badge-secondary';
        }

        // Load model untuk ambil gambar dari tabel asli (misal: mobil, motor)
        $type = $pesanan['type'];
        $id_produk = $pesanan['id_produk'];

        if (!in_array($type, ['mobil', 'motor', 'truk', 'alat_berat', 'sepeda', 'kend_khusus', 'kebutuhan'])) {
          die("Tipe produk tidak valid.");
        }

        // Load model dinamis
        require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
        $produkModel = new Kendaraan();

        if ($type === 'kebutuhan') {
          require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
          $produkModel = new Kebutuhan();
          $dataProduk = $produkModel->getById('kebutuhan', $id_produk);
        } else {
          $dataProduk = $produkModel->getById($type, $id_produk);
        }

        // Ambil nama file gambar dari tabel asli
        $gambar = '';
        if ($type === 'kebutuhan' && isset($dataProduk['nama_kebutuhan'])) {
          $gambar = $dataProduk['gambar'] ?? 'no-image.png';
        } elseif (isset($dataProduk["nama_$type"])) {
          $gambar = $dataProduk['gambar'] ?? 'no-image.png';
        } else {
          $gambar = 'no-image.png';
        }

        // Path gambar
        $folder = ($type === 'kebutuhan') ? 'kebutuhan' : $type;
        $gambarPath = "/PWD-Project-Mandiri/asset/{$folder}/" . basename($gambar);

        // Cek apakah file benar-benar tersedia
        $fullGambarPath = $_SERVER['DOCUMENT_ROOT'] . $gambarPath;
        if (!file_exists($fullGambarPath)) {
          $gambarPath = "/PWD-Project-Mandiri/asset/no-image.png";
        }
      ?>
        <div class="user-order-card">
          <img src="<?= $gambarPath ?>" alt="<?= htmlspecialchars($pesanan['nama_produk']) ?>" class="user-order-img">

          <div class="user-order-info">
            <div class="user-order-name"><?= htmlspecialchars($pesanan['nama_produk']) ?></div>
            <div class="user-order-details">
              <span><strong>Invoice:</strong> <?= htmlspecialchars($pesanan['no_invoice']) ?></span>
              <span><strong>Jumlah:</strong> <?= htmlspecialchars($pesanan['jumlah_unit']) ?> unit</span>
              <span><strong>Total:</strong> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></span>
              <span><strong>Metode Bayar:</strong> <?= htmlspecialchars($pesanan['metode_bayar']) ?></span>
              <span><strong>Tanggal:</strong> <?= date('d M Y', strtotime($pesanan['tanggal_dipesan'])) ?></span>
              <span><strong>Status:</strong> <span class="user-badge <?= $statusClass ?>"><?= ucfirst(htmlspecialchars($pesanan['status'])) ?></span></span>
            </div>
            <!-- Tombol Aksi -->
            <div class="user-order-actions">
              <?php if ($status === 'pending'): ?>
                <!-- Tombol Batalkan -->
                <button class="action-btn cancel-btn" onclick="batalkanPesanan(<?= $pesanan['id_transaksi'] ?>)">
                  Batalkan Pesanan
                </button>
              <?php endif; ?>
              <!-- Tombol Hubungi Admin -->
              <button class="action-btn contact-btn"
                onclick="hubungiAdmin('<?= htmlspecialchars($pesanan['no_invoice'], ENT_QUOTES, 'UTF-8') ?>', '<?= addslashes($pesanan['nama_produk']) ?>')">
                Hubungi Admin
              </button>
              <?php if ($status === 'dikonfirmasi'): ?>

                <!-- Tombol Selesaikan Pesanan -->
                <button class="action-btn complete-btn" onclick="selesaikanPesanan(<?= $pesanan['id_transaksi'] ?>)">
                  Selesaikan Pesanan
                </button>
              <?php endif; ?>
            </div>
            <!-- Hidden input untuk nama user -->
            <input type="hidden" id="username" value="<?= htmlspecialchars($user['username']) ?>" />
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="user-pagination">
      <?php if ($page > 1): ?>
        <a href="/PWD-Project-Mandiri/index.php?page=riwayat&hal=<?= $page - 1 ?>" class="user-page-link">&laquo; Sebelumnya</a>
      <?php endif; ?>

      <div class="user-page-number">
        Halaman <?= $page ?> dari <?= $totalPages ?>
      </div>

      <?php if ($page < $totalPages): ?>
        <a href="/PWD-Project-Mandiri/index.php?page=riwayat&hal=<?= $page + 1 ?>" class="user-page-link">Selanjutnya &raquo;</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <a href="index.php" class="user-back-link">Kembali</a>
</div>