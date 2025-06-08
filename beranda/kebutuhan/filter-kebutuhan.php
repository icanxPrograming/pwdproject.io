<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
$kebutuhan = new Kebutuhan();
$type = 'kebutuhan';

// Ambil semua data filter dari model
$jenisList = $kebutuhan->getJenisKebutuhan(); // ['Sparepart', 'Oli_Pelumas', 'Aksesoris']
$kategoriList = $kebutuhan->getKategoriList(); // ['Mobil', 'Motor', 'Truk', 'Sepeda', 'Alat_Berat', 'Kend_Khusus']
// Ambil filter dari URL
$filters = [];
foreach ($_GET as $key => $value) {
  if (in_array($key, ['jenis', 'kategori'])) {
    $filters[$key] = is_array($value) ? $value : [$value];
  }
}
?>
<div class="filter-box">
  <h3>Jenis Kebutuhan</h3>
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
            <div class="jenis-box <?= in_array($jenis, $_GET['jenis'] ?? []) ? 'active' : '' ?>">
              <img src="/PWD-Project-Mandiri/asset/jenis/<?= $type ?>/<?= strtolower(str_replace(' ', '-', $jenis)) ?>.png"
                alt="<?= htmlspecialchars($jenis) ?>">
              <span class="filter-text">
                <?= $jenis === 'Oli_Pelumas' ? 'Oli&Pelumas' : htmlspecialchars($jenis) ?>
              </span>
            </div>
          </label>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <h3>Kategori Kendaraan</h3>
  <ul>
    <?php if (empty($kategoriList)): ?>
      <li><small class="text-muted">Tidak ada kategori kendaraan tersedia.</small></li>
    <?php else: ?>
      <?php foreach ($kategoriList as $kategori): ?>
        <li>
          <label>
            <input type="checkbox" name="kategori[]" value="<?= htmlspecialchars($kategori) ?>"
              <?= in_array($kategori, $filters['kategori'] ?? []) ? 'checked' : '' ?>>
            <span class="filter-text">
              <?= $kategori === 'Heavy' ? 'Alat Berat' : ($kategori === 'Khusus' ? 'Kendaraan Khusus' : htmlspecialchars($kategori)) ?>
            </span>
          </label>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</div>