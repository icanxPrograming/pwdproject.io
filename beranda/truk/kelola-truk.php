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

// Ambil data truk
try {
  $data = $kendaraan->getPostedAvailable('truk', $filters);
} catch (Exception $e) {
  error_log("Error fetching data: " . $e->getMessage());
  $data = [];
}
?>

<div class="product-grid">
  <?php if (empty($data)): ?>
    <p class="text-muted">Belum ada truk tersedia.</p>
  <?php else: ?>
    <?php foreach ($data as $index => $truk): ?>
      <?php
      // Siapkan data untuk modal
      $trukData = [
        'id' => $truk['id_truk'] ?? '',
        'nama' => htmlspecialchars($truk['nama_truk'] ?? '', ENT_QUOTES, 'UTF-8'),
        'tahun' => $truk['tahun'] ?? '',
        'harga' => "Rp " . number_format($truk['harga_per_unit'] ?? 0, 0, ',', '.'),
        'jenis' => htmlspecialchars($truk['jenis_truk'] ?? '', ENT_QUOTES, 'UTF-8'),
        'merk' => htmlspecialchars($truk['merk'] ?? '', ENT_QUOTES, 'UTF-8'),
        'transmisi' => htmlspecialchars($truk['transmisi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'bahan_bakar' => htmlspecialchars($truk['bahan_bakar'] ?? '', ENT_QUOTES, 'UTF-8'),
        'kondisi' => htmlspecialchars($truk['kondisi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'deskripsi' => nl2br(htmlspecialchars($truk['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8')),
        'gambar' => "/PWD-Project-Mandiri/asset/truk/" . basename($truk['gambar'] ?? '')
      ];

      // Encode data sebagai JSON string
      $jsonData = json_encode($trukData);
      ?>

      <div class="product-card">
        <img src="/PWD-Project-Mandiri/asset/truk/<?= htmlspecialchars($truk['gambar']) ?>" alt="<?= htmlspecialchars($truk['nama_truk']) ?>">
        <div class="product-info">
          <h4 class="product-name"><?= htmlspecialchars($truk['nama_truk']) ?> <?= htmlspecialchars($truk['tahun']) ?></h4>
          <p class="product-price">Rp <?= number_format($truk['harga_per_unit'], 0, ',', '.') ?></p>
          <a href="/PWD-Project-Mandiri/pesanan/pesanan.php?type=truk&id=<?= $truk['id_truk'] ?>" class="promo-link">
            Beli Sekarang
          </a>
        </div>

        <!-- Tombol Lihat Detail -->
        <div class="product-varian">
          <a href="javascript:void(0)"
            class="product-varian-content"
            data-type="truk"
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