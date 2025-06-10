<?php
require_once('../model/Services.php');
$services = new Services();
$list_services = $services->getAllFromTable('services');
?>

<h2 class="mt-4 mb-4"><i class="fas fa-cogs"></i> Daftar Services</h2>

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
      <a href="dashboard.php?module=services&page=tambah-services&type=kelola-services" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Service
      </a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 80px;">Icon</th>
          <th scope="col" style="width: 250px;">Judul Section</th>
          <th scope="col" style="width: 250px;">Konten (preview)</th>
          <th scope="col" style="width: 60px;">Urutan</th>
          <th scope="col" style="width: 80px;">Status</th>
          <th scope="col" style="width: 120px;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($list_services) === 0): ?>
          <tr>
            <td colspan="7" class="text-center">Data belum tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1;
          foreach ($list_services as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td style="font-size: 24px;"><?= htmlspecialchars($row['icon']); ?></td>
              <td><?= htmlspecialchars($row['judul_section']); ?></td>
              <td><?= mb_strimwidth(strip_tags($row['konten']), 0, 80, '...'); ?></td>
              <td><?= (int) $row['urutan']; ?></td>
              <td><?= htmlspecialchars($row['status']); ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-success mr-1" data-toggle="tooltip" data-placement="top" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=services&page=edit-services&type=kelola-services&id=<?= $row['id_services'] ?>" class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/services/hapus-services.php?type=kelola-services&id=<?= $row['id_services'] ?>"
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