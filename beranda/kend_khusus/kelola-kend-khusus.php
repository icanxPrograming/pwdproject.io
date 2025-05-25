<?php
include($_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php');

$kendaraan = new Kendaraan();
$data = $kendaraan->getKendKhusus();
?>

<div class="product-grid">
  <?php foreach ($data as $index => $KendKhusus): ?>
    <div class="product-card">
      <img src="asset/kend_khusus/<?php echo htmlspecialchars($KendKhusus['gambar']); ?>" alt="<?php echo htmlspecialchars($KendKhusus['nama_kend_khusus']); ?>">
      <h4><?php echo htmlspecialchars($KendKhusus['nama_kend_khusus']); ?></h4>
      <p>Rp <?php echo number_format($KendKhusus['harga_per_unit'], 0, ',', '.'); ?></p>
      <a href="#" class="promo-link">LIHAT PROMO</a>
    </div>
  <?php endforeach; ?>
</div>