<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
$kendaraan = new Kendaraan();

// Ambil semua filter dari GET
$filters = [];
foreach ($_GET as $key => $value) {
  if (in_array($key, ['jenis', 'merk', 'kondisi', 'tahun'])) {
    $filters[$key] = $value;
  }
}

// Ambil data kendaraan (sepeda)
try {
  $data = $kendaraan->getPostedAvailable('sepeda', $filters);
} catch (Exception $e) {
  error_log("Error fetching data: " . $e->getMessage());
  $data = [];
}
?>

<div class="product-grid">
  <?php if (empty($data)): ?>
    <p class="text-muted">Belum ada Sepeda tersedia.</p>
  <?php else: ?>
    <?php foreach ($data as $index => $bike): ?>
      <?php
      // Siapkan data untuk modal
      $sepedaData = [
        'id' => $bike['id_sepeda'] ?? '',
        'nama' => htmlspecialchars($bike['nama_sepeda'] ?? '', ENT_QUOTES, 'UTF-8'),
        'tahun' => $bike['tahun'] ?? '',
        'harga' => "Rp " . number_format($bike['harga_per_unit'] ?? 0, 0, ',', '.'),
        'jenis' => htmlspecialchars($bike['jenis_sepeda'] ?? '', ENT_QUOTES, 'UTF-8'),
        'merk' => htmlspecialchars($bike['merk'] ?? '', ENT_QUOTES, 'UTF-8'),
        'kondisi' => htmlspecialchars($bike['kondisi'] ?? '', ENT_QUOTES, 'UTF-8'),
        'deskripsi' => nl2br(htmlspecialchars($bike['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8')),
        'gambar' => "/PWD-Project-Mandiri/asset/sepeda/" . basename($bike['gambar'] ?? '')
      ];

      // Encode data sebagai JSON string
      $jsonData = json_encode($sepedaData);
      ?>

      <div class="product-card">
        <img src="/PWD-Project-Mandiri/asset/sepeda/<?= htmlspecialchars($bike['gambar']) ?>" alt="<?= htmlspecialchars($bike['nama_sepeda']) ?>">
        <div class="product-info">
          <h4 class="product-name"><?= htmlspecialchars($bike['nama_sepeda']) ?> <?= htmlspecialchars($bike['tahun']) ?></h4>
          <p class="product-price">Rp <?= number_format($bike['harga_per_unit'], 0, ',', '.') ?></p>
          <a href="/PWD-Project-Mandiri/pesanan/pesanan.php?type=sepeda&id=<?= $bike['id_sepeda'] ?>" class="promo-link">
            Beli Sekarang
          </a>
        </div>

        <!-- Tombol Lihat Detail -->
        <div class="product-varian">
          <a href="javascript:void(0)"
            class="product-varian-content"
            data-type="sepeda"
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