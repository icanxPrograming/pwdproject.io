<?php
require_once('../model/Promo.php');
$promo = new Promo();
$promos = $promo->getPromo();
?>

<h2 class="mt-4 mb-4"><i class="fas fa-percent"></i> Daftar Promo</h2>

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
      <a href="dashboard.php?module=promo&page=tambah-promo&type=kelola-promo" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Promo
      </a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 200px;">Promo</th>
          <th scope="col" style="width: 300px;">Deskripsi</th>
          <th scope="col" style="width: 100px;">Tanggal Mulai</th>
          <th scope="col" style="width: 100px;">Tanggal Selesai</th>
          <th scope="col" style="width: 80px;">Status</th>
          <th scope="col" style="width: 120px;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($promos) === 0): ?>
          <tr>
            <td colspan="8" class="text-center">Data belum tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1;
          foreach ($promos as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['judul_promo']); ?></td>
              <td><?= htmlspecialchars($row['deskripsi']); ?></td>
              <td><?= htmlspecialchars($row['tanggal_mulai']); ?></td>
              <td><?= htmlspecialchars($row['tanggal_selesai']); ?></td>
              <td><?= htmlspecialchars($row['status']); ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-success mr-1" data-toggle="tooltip" data-placement="top" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=promo&page=edit-promo&type=kelola-promo&id=<?= $row['id_promo'] ?>" class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/promo/hapus-promo.php?type=kelola-promo&id=<?= $row['id_promo'] ?>"
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