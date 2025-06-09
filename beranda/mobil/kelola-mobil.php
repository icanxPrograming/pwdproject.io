<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
$kendaraan = new Kendaraan();
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

// Ambil semua filter dari GET
$filters = [];
foreach ($_GET as $key => $value) {
  if (in_array($key, ['jenis', 'merk', 'kondisi', 'bahan_bakar', 'transmisi', 'tahun'])) {
    $filters[$key] = $value;
  }
}

// Ambil data kendaraan
try {
  $data = $kendaraan->getPostedAvailable('mobil', $filters);
} catch (Exception $e) {
  error_log("Error fetching data: " . $e->getMessage());
  $data = [];
}
?>

<div class="product-grid">
  <?php if (empty($data)): ?>
    <p class="text-muted">Belum ada mobil tersedia.</p>
  <?php else: ?>
    <?php foreach ($data as $index => $mobil): ?>
      <?php
      // Siapkan data untuk modal
      $mobilData = [
        'id' => $mobil['id_mobil'] ?? '',
        'nama' => htmlspecialchars($mobil['nama_mobil'] ?? '', ENT_QUOTES, 'UTF-8'),
        'tahun' => $mobil['tahun'] ?? '',
        'harga' => "Rp " . number_format($mobil['harga_per_unit'] ?? 0, 0, ',', '.'),
        'jenis' => htmlspecialchars($mobil['jenis_mobil'] ?? '', ENT_QUOTES, 'UTF-8'),
        'merk' => htmlspecialchars($mobil['merk'] ?? '', ENT_QUOTES, 'UTF-8'),
        'transmisi' => htmlspecialchars($mobil['transmisi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'bahan_bakar' => htmlspecialchars($mobil['bahan_bakar'] ?? '', ENT_QUOTES, 'UTF-8'),
        'kondisi' => htmlspecialchars($mobil['kondisi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'jumlah_kursi' => htmlspecialchars($mobil['jumlah_kursi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'deskripsi' => nl2br(htmlspecialchars($mobil['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8')),
        'gambar' => "/PWD-Project-Mandiri/asset/mobil/" . basename($mobil['gambar'] ?? '')
      ];

      // Encode data sebagai JSON string
      $jsonData = json_encode($mobilData);
      ?>

      <div class="product-card">
        <img src="/PWD-Project-Mandiri/asset/mobil/<?= htmlspecialchars($mobil['gambar']) ?>" alt="<?= htmlspecialchars($mobil['nama_mobil']) ?>">
        <div class="product-info">
          <h4 class="product-name"><?= htmlspecialchars($mobil['nama_mobil']) ?> <?= htmlspecialchars($mobil['tahun']) ?></h4>
          <p class="product-price">Rp <?= number_format($mobil['harga_per_unit'], 0, ',', '.') ?></p>
          <a href="/PWD-Project-Mandiri/pesanan/pesanan.php?type=mobil&id=<?= $mobil['id_mobil'] ?>" class="promo-link">
            Beli Sekarang
          </a>
        </div>

        <!-- Tombol Lihat Detail dengan data JSON -->
        <div class="product-varian">
          <a href="javascript:void(0)"
            class="product-varian-content"
            data-type="mobil"
            data-detail='<?= $jsonData ?>'>
            Lihat Detail <span class="arrow">›</span>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<!-- Modal Box -->
<div id="detailModal" class="modal-box">
  <div class="modal-content">
    <span class="close-btn" onclick="hideDetailModal()">&times;</span>
    <div class="modal-body">
      <div class="modal-image">
        <img id="modalGambar" src="" alt="">
      </div>
      <div class="modal-text">
        <h2 id="modalJudul"></h2>
        <p id="modalHarga" class="price"></p>

        <div class="spec-table" id="modalSpecTable"></div>

        <div class="description-section" id="modalDeskripsi"></div>

        <div class="action-buttons">
          <a id="modalBeliLink" class="btn-buy" href="#">Beli Sekarang</a>
          <a id="modalChatLink" class="btn-chat" href="#" target="_blank">Chat Admin</a>
        </div>
      </div>
    </div>
  </div>
</div>