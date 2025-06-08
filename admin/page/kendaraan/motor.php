<?php
require_once '../model/Kendaraan.php';
$motor = new Kendaraan();
$motors = $motor->getMotor();
?>

<h2 class="mt-4 mb-4"><i class="fas fa-motorcycle"></i> Daftar Motor</h2>

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
      <!-- Tombol Tambah -->
      <a href="dashboard.php?module=kendaraan&page=tambah-kendaraan&type=motor" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Motor
      </a>
    </div>

    <!-- Tabel Daftar Motor -->
    <table class="table table-hover table-bordered table-sm">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">No</th>
          <th scope="col" style="width: 150px;">Nama Motor</th>
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
        <?php if (empty($motors)): ?>
          <tr>
            <td colspan="11" class="text-center text-muted">Belum ada data motor.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1; ?>
          <?php foreach ($motors as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['nama_motor']) ?></td>
              <td><?= htmlspecialchars($row['merk']) ?></td>
              <td><?= htmlspecialchars($row['jenis_motor']) ?></td>
              <td><?= htmlspecialchars($row['tahun']) ?></td>
              <td><?= htmlspecialchars($row['bahan_bakar'] ?? '-') ?></td>
              <td><?= htmlspecialchars($row['transmisi'] ?? '-') ?></td>
              <td><?= htmlspecialchars($row['jumlah_unit']) ?></td>
              <td>Rp<?= number_format($row['harga_per_unit'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['status_post']) ?></td>
              <td class="text-center">
                <a href="#" class="btn btn-sm btn-outline-success mr-1" data-toggle="tooltip" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=kendaraan&page=edit-kendaraan&type=motor&id=<?= $row['id_motor'] ?>" class="btn btn-sm btn-outline-warning mr-1" data-toggle="tooltip" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/kendaraan/hapus-kendaraan.php?type=motor&id=<?= $row['id_motor'] ?>"
                  onclick="return confirm('Yakin ingin menghapus <?= addslashes(htmlspecialchars($row['nama_motor'])) ?>?');"
                  class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Hapus">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>