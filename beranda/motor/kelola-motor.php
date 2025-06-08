<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
$kendaraan = new Kendaraan();

// Ambil semua filter dari GET
$filters = [];
foreach ($_GET as $key => $value) {
  if (in_array($key, ['jenis', 'merk', 'kondisi', 'bahan_bakar', 'transmisi', 'tahun'])) {
    $filters[$key] = $value;
  }
}

// Ambil data kendaraan (motor)
try {
  $data = $kendaraan->getPostedAvailable('motor', $filters);
} catch (Exception $e) {
  error_log("Error fetching data: " . $e->getMessage());
  $data = [];
}
?>

<div class="product-grid">
  <?php if (empty($data)): ?>
    <p class="text-muted">Belum ada motor tersedia.</p>
  <?php else: ?>
    <?php foreach ($data as $index => $motor): ?>
      <?php
      // Siapkan data untuk modal
      $motorData = [
        'id' => $motor['id_motor'] ?? '',
        'nama' => htmlspecialchars($motor['nama_motor'] ?? '', ENT_QUOTES, 'UTF-8'),
        'tahun' => $motor['tahun'] ?? '',
        'harga' => "Rp " . number_format($motor['harga_per_unit'] ?? 0, 0, ',', '.'),
        'jenis' => htmlspecialchars($motor['jenis_motor'] ?? '', ENT_QUOTES, 'UTF-8'),
        'merk' => htmlspecialchars($motor['merk'] ?? '', ENT_QUOTES, 'UTF-8'),
        'transmisi' => htmlspecialchars($motor['transmisi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'bahan_bakar' => htmlspecialchars($motor['bahan_bakar'] ?? '', ENT_QUOTES, 'UTF-8'),
        'kondisi' => htmlspecialchars($motor['kondisi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'deskripsi' => nl2br(htmlspecialchars($motor['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8')),
        'gambar' => "/PWD-Project-Mandiri/asset/motor/" . basename($motor['gambar'] ?? '')
      ];

      // Encode data sebagai JSON string
      $jsonData = json_encode($motorData);
      ?>

      <div class="product-card">
        <img src="/PWD-Project-Mandiri/asset/motor/<?= htmlspecialchars($motor['gambar']) ?>" alt="<?= htmlspecialchars($motor['nama_motor']) ?>">
        <div class="product-info">
          <h4 class="product-name"><?= htmlspecialchars($motor['nama_motor']) ?> <?= htmlspecialchars($motor['tahun']) ?></h4>
          <p class="product-price">Rp <?= number_format($motor['harga_per_unit'], 0, ',', '.') ?></p>
          <a href="/PWD-Project-Mandiri/pesanan/pesanan.php?type=motor&id=<?= $motor['id_motor'] ?>" class="promo-link">
            Beli Sekarang
          </a>
        </div>

        <!-- Tombol Lihat Detail -->
        <div class="product-varian">
          <a href="javascript:void(0)"
            class="product-varian-content"
            data-type="motor"
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