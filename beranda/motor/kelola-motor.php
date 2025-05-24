<?php
include($_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php');

$kendaraan = new Kendaraan();
$data = $kendaraan->getMotor();
?>

<div class="product-grid">
  <?php foreach ($data as $index => $mobil): ?>
    <div class="product-card">
      <img src="asset/motor/<?php echo htmlspecialchars($mobil['gambar']); ?>" alt="<?php echo htmlspecialchars($mobil['nama_motor']); ?>">
      <h4><?php echo htmlspecialchars($mobil['nama_motor']); ?></h4>
      <p>Rp <?php echo number_format($mobil['harga_per_unit'], 0, ',', '.'); ?></p>
      <a href="#" class="promo-link">LIHAT PROMO</a>
    </div>
  <?php endforeach; ?>
</div>