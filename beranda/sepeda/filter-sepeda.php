<div class="filter-box">
  <h3>Jenis Kendaraan</h3>
  <ul>
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kategori.php');
    $kategori = new Kategori();
    $dataKategori = $kategori->getByJenis('sepeda');

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

  <h3>Merk</h3>
  <ul>
    <li><label><input type="checkbox" name="merk[]" value="Toyota"> Senator</label></li>
    <li><label><input type="checkbox" name="merk[]" value="Honda"> United</label></li>
  </ul>

  <h3>Kondisi</h3>
  <ul>
    <li><label><input type="checkbox" name="merk[]" value="Toyota"> Baru</label></li>
    <li><label><input type="checkbox" name="merk[]" value="Honda"> Bekas</label></li>
  </ul>
</div>