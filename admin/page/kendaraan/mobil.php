<?php
require_once '../model/Kendaraan.php';
$kendaraan = new Kendaraan();
$cars = $kendaraan->getMobil();
?>

<h2 class="mt-4 mb-4"><i class="fas fa-car"></i> Daftar Mobil</h2>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
  <?php unset($_SESSION['success']); ?>
<?php elseif (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row">
  <div class="col">
    <div class="mb-3">
      <a href="dashboard.php?module=kendaraan&page=tambah-kendaraan&type=mobil" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Mobil
      </a>
    </div>
    <table class="table table-hover table-bordered table-sm">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">No</th>
          <th scope="col" style="width: 150px;">Nama Mobil</th>
          <th scope="col" style="width: 90px;">Merk</th>
          <th scope="col" style="width: 80px;">Jenis</th>
          <th scope="col" style="width: 60px;">Tahun</th>
          <th scope="col" style="width: 70px;">Bahan Bakar</th>
          <th scope="col" style="width: 80px;">Transmisi</th>
          <th scope="col" style="width: 50px;">Unit</th>
          <th scope="col" style="width: 100px;">Harga/Unit</th>
          <th scope="col" style="width: 100px;">Status</th>
          <th scope="col" style="width: 120px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($cars)): ?>
          <tr>
            <td colspan="11" class="text-center text-muted">Belum ada data mobil.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1; ?>
          <?php foreach ($cars as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
              <td><?= htmlspecialchars($row['merk']) ?></td>
              <td><?= htmlspecialchars($row['jenis_mobil']) ?></td>
              <td><?= htmlspecialchars($row['tahun']) ?></td>
              <td><?= htmlspecialchars($row['bahan_bakar'] ?? '-') ?></td>
              <td><?= htmlspecialchars($row['transmisi'] ?? '-') ?></td>
              <td><?= htmlspecialchars($row['jumlah_unit']) ?></td>
              <td>Rp<?= number_format($row['harga_per_unit'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['status_post']) ?></td>
              <td class="text-center">
                <a href="#" class="btn btn-sm btn-outline-success mr-1" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=kendaraan&page=edit-kendaraan&type=mobil&id=<?= $row['id_mobil'] ?>" class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/kendaraan/hapus-kendaraan.php?type=mobil&id=<?= $row['id_mobil'] ?>"
                  onclick="return confirm('Yakin ingin menghapus <?= addslashes(htmlspecialchars($row['nama_mobil'])) ?>?');"
                  class="btn btn-sm btn-outline-danger" title="Hapus">
                  <i class="fas fa-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>