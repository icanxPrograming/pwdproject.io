<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
$kendaraan = new Kendaraan();
$type = 'kend_khusus';

// Ambil semua data filter dari model
$jenisList = $kendaraan->getJenisByType($type);
$merkList = $kendaraan->getMerkByType($type);
$bahanBakarList = $kendaraan->getBahanBakarByType($type);
$transmisiList = $kendaraan->getTransmisiByType($type);
$kondisiList = $kendaraan->getKondisiByType($type);
?>

<div class="filter-box">
  <h3>Jenis Kendaraan</h3>
  <div class="jenis-grid">
    <?php if (empty($jenisList)): ?>
      <p class="text-muted">Tidak ada jenis tersedia.</p>
    <?php else: ?>
      <?php foreach ($jenisList as $jenis): ?>
        <div class="jenis-item">
          <label>
            <input type="checkbox" name="jenis[]" value="<?= htmlspecialchars($jenis) ?>" hidden>
            <div class="jenis-box">
              <img src="/PWD-Project-Mandiri/asset/jenis/<?= $type ?>/<?= strtolower(str_replace(' ', '-', $jenis)) ?>.png" alt="<?= htmlspecialchars($jenis) ?>">
              <span><?= htmlspecialchars($jenis) ?></span>
            </div>
          </label>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>


  <h3>Merk</h3>
  <ul>
    <?php if (empty($merkList)): ?>
      <li><small class="text-muted">Tidak ada merk tersedia.</small></li>
    <?php else: ?>
      <?php foreach ($merkList as $merk): ?>
        <li>
          <label>
            <input type="checkbox" name="merk[]" value="<?= htmlspecialchars($merk) ?>">
            <?= htmlspecialchars($merk) ?>
          </label>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>

  <h3>Kondisi</h3>
  <ul>
    <?php if (empty($kondisiList)): ?>
      <li><small class="text-muted">Tidak ada kondisi tersedia.</small></li>
    <?php else: ?>
      <?php foreach ($kondisiList as $kondisi): ?>
        <li>
          <label>
            <input type="checkbox" name="kondisi[]" value="<?= htmlspecialchars($kondisi) ?>">
            <?= htmlspecialchars($kondisi) ?>
          </label>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>

  <h3>Bahan Bakar</h3>
  <ul>
    <?php if (empty($bahanBakarList)): ?>
      <li><small class="text-muted">Tidak ada bahan bakar tersedia.</small></li>
    <?php else: ?>
      <?php foreach ($bahanBakarList as $bahan): ?>
        <li>
          <label>
            <input type="checkbox" name="bahan_bakar[]" value="<?= htmlspecialchars($bahan) ?>">
            <?= htmlspecialchars($bahan) ?>
          </label>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>

  <h3>Transmisi</h3>
  <ul>
    <?php if (empty($transmisiList)): ?>
      <li><small class="text-muted">Tidak ada transmisi tersedia.</small></li>
    <?php else: ?>
      <?php foreach ($transmisiList as $transmisi): ?>
        <li>
          <label>
            <input type="checkbox" name="transmisi[]" value="<?= htmlspecialchars($transmisi) ?>">
            <?= htmlspecialchars($transmisi) ?>
          </label>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</div>