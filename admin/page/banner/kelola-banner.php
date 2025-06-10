<?php
require_once('../model/Banner.php');
$banner = new Banner();
$banners = $banner->getAllFromTable('banner');
?>

<h2 class="mt-4 mb-4"><i class="fas fa-map"></i> Daftar Banner</h2>

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
      <a href="dashboard.php?module=banner&page=tambah-banner&type=kelola-banner" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Banner
      </a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 200px;">URL Banner</th>
          <th scope="col" style="width: 80px;">Urutan</th>
          <th scope="col" style="width: 80px;">Status Aktif</th>
          <th scope="col" style="width: 120px;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($banners) === 0): ?>
          <tr>
            <td colspan="8" class="text-center">Data belum tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1;
          foreach ($banners as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['url_gambar']); ?></td>
              <td><?= htmlspecialchars($row['urutan']); ?></td>
              <td><?= htmlspecialchars($row['status']); ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-success mr-1" data-toggle="tooltip" data-placement="top" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=banner&page=edit-banner&type=kelola-banner&id=<?= $row['id_banner'] ?>" class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/banner/hapus-banner.php?type=kelola-banner&id=<?= $row['id_banner'] ?>"
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