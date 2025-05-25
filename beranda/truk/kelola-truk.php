<?php
include($_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php');

$kendaraan = new Kendaraan();
$data = $kendaraan->getTruk();
?>

<div class="product-grid">
  <?php foreach ($data as $index => $truk): ?>
    <div class="product-card">
      <img src="asset/truk/<?php echo htmlspecialchars($truk['gambar']); ?>" alt="<?php echo htmlspecialchars($truk['nama_truk']); ?>">
      <h4><?php echo htmlspecialchars($truk['nama_truk']); ?></h4>
      <p>Rp <?php echo number_format($truk['harga_per_unit'], 0, ',', '.'); ?></p>
      <a href="#" class="promo-link">LIHAT PROMO</a>
    </div>
  <?php endforeach; ?>
</div>