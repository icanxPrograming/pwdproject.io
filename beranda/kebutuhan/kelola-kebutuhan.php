<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
$kebutuhanModel = new Kebutuhan();

$type = 'kebutuhan';

// Ambil semua filter dari GET
$filters = [];
foreach ($_GET as $key => $value) {
  if (in_array($key, ['jenis', 'kategori', 'status'])) {
    $filters[$key] = $value;
  }
}

// Ambil data kebutuhan
try {
  $data = $kebutuhanModel->getPostedAvailable($type, $filters);
} catch (Exception $e) {
  error_log("Error fetching data: " . $e->getMessage());
  $data = [];
}
?>
<div class="product-grid">
  <?php if (empty($data)): ?>
    <p class="text-muted">Belum ada <?= ucfirst($type) ?> tersedia.</p>
  <?php else: ?>
    <?php foreach ($data as $index => $item): ?>
      <?php
      // Siapkan data untuk modal
      $kebutuhanData = [
        'id' => $item['id_kebutuhan'] ?? '',
        'nama' => htmlspecialchars($item['nama_kebutuhan'] ?? '', ENT_QUOTES, 'UTF-8'),
        'harga' => "Rp " . number_format($item['harga'] ?? 0, 0, ',', '.'),
        'jumlah' => $item['jumlah'] ?? '',
        'jenis' => htmlspecialchars($item['jenis_kebutuhan'] ?? '', ENT_QUOTES, 'UTF-8'),
        'kategori' => htmlspecialchars($item['kategori'] ?? '', ENT_QUOTES, 'UTF-8'),
        'gambar' => "/PWD-Project-Mandiri/asset/kebutuhan/" . basename($item['gambar'] ?? '')
      ];

      // Encode data sebagai JSON string
      $jsonData = json_encode($kebutuhanData);
      ?>

      <div class="product-card">
        <img src="/PWD-Project-Mandiri/asset/kebutuhan/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama_kebutuhan']) ?>">
        <div class="product-info">
          <h4 class="product-name"><?= htmlspecialchars($item['nama_kebutuhan']) ?></h4>
          <p class="product-price">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
          <a href="/PWD-Project-Mandiri/pesanan/pesanan.php?type=kebutuhan&id=<?= $item['id_kebutuhan'] ?>" class="promo-link">
            Beli Sekarang
          </a>
        </div>

        <!-- Tombol Lihat Detail -->
        <div class="product-varian">
          <a href="javascript:void(0)"
            class="product-varian-content"
            data-type="kebutuhan"
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
          <a id="modalBeliLink" class="btn-buy" href="#">Pesan Sekarang</a>
          <a id="modalChatLink" class="btn-chat" href="#" target="_blank">Chat Admin</a>
        </div>
      </div>
    </div>
  </div>
</div>