<?php
require_once('../model/Kendaraan.php');
$motor = new Kendaraan();
$motors = $motor->getMotor();
?>

<h2 class="mt-4 mb-4"><i class="fas fa-motorcycle"></i> Daftar Motor</h2>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php elseif (isset($_GET['error'])): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <div class="mb-2">
      <!-- Tombol Tambah -->
      <a href="dashboard.php?module=kendaraan&page=tambah&type=motor" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Motor
      </a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 200px;">Nama Motor</th>
          <th scope="col" style="width: 80px;">Tahun</th>
          <th scope="col" style="width: 50px;">Jumlah Unit</th>
          <th scope="col" style="width: 100px;">Harga (Per Unit)</th>
          <th scope="col" style="width: 300px;">Deskripsi</th>
          <th scope="col" style="width: 100px;">Status Posting</th>
          <th scope="col" style="width: 120px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($motors) === 0): ?>
          <tr>
            <td colspan="8" class="text-center text-muted">Belum ada data motor yang tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1; ?>
          <?php foreach ($motors as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['nama_motor']) ?></td>
              <td><?= htmlspecialchars($row['tahun']) ?></td>
              <td><?= htmlspecialchars($row['jumlah_unit']) ?></td>
              <td><?= number_format($row['harga_per_unit'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['deskripsi']) ?></td>
              <td><?= htmlspecialchars($row['status_post']) ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-success mr-1" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=kendaraan&page=edit&type=motor&id=<?= $row['id_motor'] ?>"
                  class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/kendaraan/hapus.php?type=motor&id=<?= $row['id_motor'] ?>"
                  onclick="return confirm('Yakin ingin menghapus motor <?= addslashes($row['nama_motor']) ?>?');"
                  class="btn btn-sm btn-outline-danger" title="Hapus">
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