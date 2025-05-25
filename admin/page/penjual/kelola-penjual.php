<?php
require_once('../model/Penjual.php');
$penjual = new Penjual();
$penjuals = $penjual->getPenjual();
?>

<h2 class="mt-4 mb-4"><i class="fas fa-user-tie"></i> Daftar Penjual</h2>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
  <?php unset($_SESSION['success']); ?>
<?php elseif (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row">
  <div class="col">
    <div class="mb-2">
      <a href="dashboard.php?module=penjual&page=tambah-penjual&type=kelola-penjual" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Penjual
      </a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 200px;">Nama Penjual</th>
          <th scope="col" style="width: 200px;">Email</th>
          <th scope="col" style="width: 100px;">No HP</th>
          <th scope="col" style="width: 250px;">Alamat</th>
          <th scope="col" style="width: 80px;">Tipe Penjual</th>
          <th scope="col" style="width: 80px;">Status</th>
          <th scope="col" style="width: 120px;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($penjuals) === 0): ?>
          <tr>
            <td colspan="8" class="text-center">Data belum tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1;
          foreach ($penjuals as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['nama_penjual']); ?></td>
              <td><?= htmlspecialchars($row['email']); ?></td>
              <td><?= htmlspecialchars($row['no_hp']); ?></td>
              <td><?= htmlspecialchars($row['alamat']); ?></td>
              <td><?= htmlspecialchars($row['tipe_penjual']); ?></td>
              <td><?= htmlspecialchars($row['status']); ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-success mr-1" data-toggle="tooltip" data-placement="top" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=penjual&page=edit-penjual&type=kelola-penjual&id=<?= $row['id_penjual'] ?>" class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/penjual/hapus-penjual.php?type=kelola-penjual&id=<?= $row['id_penjual'] ?>"
                  onclick="return confirm('Yakin ingin menghapus data ini?');"
                  class="btn btn-sm btn-outline-danger">
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