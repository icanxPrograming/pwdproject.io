<div class="filter-box">
  <h3>Jenis Kendaraan</h3>
  <ul>
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kategori.php');
    $kategori = new Kategori();
    $dataKategori = $kategori->getAll();

    foreach ($dataKategori as $kat):
    ?>
      <li>
        <label>
          <input type="checkbox" name="jenis[]" value="<?= htmlspecialchars($kat['nama_kategori']); ?>">
          <?= htmlspecialchars($kat['nama_kategori']); ?>
        </label>
      </li>
    <?php endforeach; ?>
  </ul>

  <!-- Sementara Merk masih statis -->
  <h3>Merk</h3>
  <ul>
    <li><label><input type="checkbox" name="merk[]" value="Toyota"> Nissan</label></li>
    <li><label><input type="checkbox" name="merk[]" value="Honda"> Toyota</label></li>
    <li><label><input type="checkbox" name="merk[]" value="Honda"> Honda</label></li>
  </ul>
</div>