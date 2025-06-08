<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
$kendaraan = new Kendaraan();

$type = 'kend_khusus';

// Ambil semua filter dari GET
$filters = [];
foreach ($_GET as $key => $value) {
  if (in_array($key, ['jenis', 'merk', 'kondisi', 'bahan_bakar', 'transmisi'])) {
    $filters[$key] = $value;
  }
}

// Ambil data kendaraan
try {
  $data = $kendaraan->getPostedAvailable($type, $filters);
} catch (Exception $e) {
  error_log("Error fetching data: " . $e->getMessage());
  $data = [];
}
?>

<div class="product-grid">
  <?php if (empty($data)): ?>
    <p class="text-muted">Belum ada Kendaraan Khusus tersedia.</p>
  <?php else: ?>
    <?php foreach ($data as $index => $khusus): ?>
      <?php
      // Siapkan data untuk modal
      $khususData = [
        'id' => $khusus['id_kend_khusus'] ?? '',
        'nama' => htmlspecialchars($khusus['nama_kend_khusus'] ?? '', ENT_QUOTES, 'UTF-8'),
        'tahun' => $khusus['tahun'] ?? '',
        'harga' => "Rp " . number_format($khusus['harga_per_unit'] ?? 0, 0, ',', '.'),
        'jenis' => htmlspecialchars($khusus['jenis_kend_khusus'] ?? '', ENT_QUOTES, 'UTF-8'),
        'merk' => htmlspecialchars($khusus['merk'] ?? '', ENT_QUOTES, 'UTF-8'),
        'transmisi' => htmlspecialchars($khusus['transmisi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'bahan_bakar' => htmlspecialchars($khusus['bahan_bakar'] ?? '', ENT_QUOTES, 'UTF-8'),
        'kondisi' => htmlspecialchars($khusus['kondisi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'deskripsi' => nl2br(htmlspecialchars($khusus['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8')),
        'gambar' => "/PWD-Project-Mandiri/asset/kend_khusus/" . basename($khusus['gambar'] ?? '')
      ];

      // Encode data sebagai JSON string
      $jsonData = json_encode($khususData);
      ?>

      <div class="product-card">
        <img src="/PWD-Project-Mandiri/asset/kend_khusus/<?= htmlspecialchars($khusus['gambar']) ?>" alt="<?= htmlspecialchars($khusus['nama_kend_khusus']) ?>">
        <div class="product-info">
          <h4 class="product-name"><?= htmlspecialchars($khusus['nama_kend_khusus']) ?> <?= htmlspecialchars($khusus['tahun']) ?></h4>
          <p class="product-price">Rp <?= number_format($khusus['harga_per_unit'], 0, ',', '.') ?></p>
          <a href="/PWD-Project-Mandiri/pesanan/pesanan.php?type=kend_khusus&id=<?= $khusus['id_kend_khusus'] ?>" class="promo-link">
            Beli Sekarang
          </a>
        </div>

        <!-- Tombol Lihat Detail -->
        <div class="product-varian">
          <a href="javascript:void(0)"
            class="product-varian-content"
            data-type="kend_khusus"
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