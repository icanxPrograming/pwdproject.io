<?php
include($_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php');

$kendaraan = new Kendaraan();
$data = $kendaraan->getAlatBerat();
?>

<div class="product-grid">
  <?php foreach ($data as $index => $alatberat): ?>
    <div class="product-card">
      <img src="asset/alat_berat/<?php echo htmlspecialchars($alatberat['gambar']); ?>" alt="<?php echo htmlspecialchars($alatberat['nama_alat_berat']); ?>">
      <h4><?php echo htmlspecialchars($alatberat['nama_alat_berat']); ?></h4>
      <p>Rp <?php echo number_format($alatberat['harga_per_unit'], 0, ',', '.'); ?></p>
      <a href="#" class="promo-link">LIHAT PROMO</a>
    </div>
  <?php endforeach; ?>
</div>