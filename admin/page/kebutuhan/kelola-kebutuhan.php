<?php
require_once('../model/Kebutuhan.php');
$kebutuhan = new Kebutuhan();
$kebutuhans = $kebutuhan->getKebutuhan();
?>

<h2 class="mt-4 mb-4"><i class="fas fa-wrench"></i> Daftar Kebutuhan</h2>

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
      <a href="dashboard.php?module=kebutuhan&page=tambah-kebutuhan&type=kelola-kebutuhan" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Kebutuhan
      </a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 200px;">Nama Barang</th>
          <th scope="col" style="width: 100px;">Jenis</th>
          <th scope="col" style="width: 100px;">Jumlah</th>
          <th scope="col" style="width: 100px;">Harga</th>
          <th scope="col" style="width: 100px;">Kategori</th>
          <th scope="col" style="width: 80px;">Status</th>
          <th scope="col" style="width: 120px;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($kebutuhans) === 0): ?>
          <tr>
            <td colspan="8" class="text-center">Data belum tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1;
          foreach ($kebutuhans as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['nama_kebutuhan']); ?></td>
              <td><?= htmlspecialchars($row['jenis_kebutuhan']); ?></td>
              <td><?= htmlspecialchars($row['jumlah']); ?></td>
              <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['kategori']); ?></td>
              <td><?= htmlspecialchars($row['status_post']); ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-success mr-1" data-toggle="tooltip" data-placement="top" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=kebutuhan&page=edit-kebutuhan&type=kelola-kebutuhan&id=<?= $row['id_kebutuhan'] ?>" class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/kebutuhan/hapus-kebutuhan.php?type=kelola-kebutuhan&id=<?= $row['id_kebutuhan'] ?>"
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