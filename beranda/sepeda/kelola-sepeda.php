<?php
include($_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php');

$kendaraan = new Kendaraan();
$data = $kendaraan->getSepeda();
?>

<div class="product-grid">
  <?php foreach ($data as $index => $sepeda): ?>
    <div class="product-card">
      <img src="asset/sepeda/<?php echo htmlspecialchars($sepeda['gambar']); ?>" alt="<?php echo htmlspecialchars($sepeda['nama_sepeda']); ?>">
      <h4><?php echo htmlspecialchars($sepeda['nama_sepeda']); ?></h4>
      <p>Rp <?php echo number_format($sepeda['harga_per_unit'], 0, ',', '.'); ?></p>
      <a href="#" class="promo-link">LIHAT PROMO</a>
    </div>
  <?php endforeach; ?>
</div>