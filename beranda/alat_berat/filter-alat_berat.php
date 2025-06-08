<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
$kendaraan = new Kendaraan();
$type = 'alat_berat';

// Ambil semua data filter dari model
$jenisList = $kendaraan->getJenisByType($type);
$merkList = $kendaraan->getMerkByType($type);
$bahanBakarList = $kendaraan->getBahanBakarByType($type);
$transmisiList = $kendaraan->getTransmisiByType($type);
$kondisiList = $kendaraan->getKondisiByType($type);

// Ambil filter dari URL
$filters = [];
foreach ($_GET as $key => $value) {
  if (in_array($key, ['jenis', 'merk', 'kondisi', 'bahan_bakar', 'transmisi', 'tahun'])) {
    $filters[$key] = is_array($value) ? $value : [$value];
  }
}
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
            <!-- Hidden checkbox -->
            <input type="checkbox" name="jenis[]" value="<?= htmlspecialchars($jenis) ?>"
              <?= in_array($jenis, $filters['jenis'] ?? []) ? 'checked' : '' ?> hidden>

            <!-- UI tampilan jenis dengan gambar -->
            <div class="jenis-box <?= in_array($jenis, $filters['jenis'] ?? []) ? 'active' : '' ?>">
              <img src="/PWD-Project-Mandiri/asset/jenis/<?= $type ?>/<?= strtolower(str_replace(' ', '-', $jenis)) ?>.png"
                alt="<?= htmlspecialchars($jenis) ?>">
              <span><?= htmlspecialchars($jenis) ?></span>
            </div>
          </label>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Merk -->
  <h3>Merk</h3>
  <ul>
    <?php foreach ($merkList as $merk): ?>
      <li>
        <label>
          <input type="checkbox" name="merk[]" value="<?= htmlspecialchars($merk) ?>"
            <?= in_array($merk, $filters['merk'] ?? []) ? 'checked' : '' ?>>
          <?= htmlspecialchars($merk) ?>
        </label>
      </li>
    <?php endforeach; ?>
  </ul>

  <!-- Kondisi -->
  <h3>Kondisi</h3>
  <ul>
    <?php foreach ($kondisiList as $kondisi): ?>
      <li>
        <label>
          <input type="checkbox" name="kondisi[]" value="<?= htmlspecialchars($kondisi) ?>"
            <?= in_array($kondisi, $filters['kondisi'] ?? []) ? 'checked' : '' ?>>
          <?= htmlspecialchars($kondisi) ?>
        </label>
      </li>
    <?php endforeach; ?>
  </ul>
</div>