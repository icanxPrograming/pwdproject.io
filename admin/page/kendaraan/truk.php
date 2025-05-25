<?php
require_once '../model/Kendaraan.php';
$kendaraan = new Kendaraan();
$truck = $kendaraan->getTruk();
?>

<h2 class="mt-4 mb-4"><i class="fas fa-truck"></i> Daftar Truk</h2>

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
      <a href="dashboard.php?module=kendaraan&page=tambah-kendaraan&type=truk" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Truk
      </a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 200px;">Nama Truk</th>
          <th scope="col" style="width: 80px;">Tahun</th>
          <th scope="col" style="width: 50px;">Jumlah Unit</th>
          <th scope="col" style="width: 100px;">Harga (Per Unit)</th>
          <th scope="col" style="width: 300px;">Deskripsi</th>
          <th scope="col" style="width: 100px;">Status Posting</th>
          <th scope="col" style="width: 120px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($truck) === 0): ?>
          <tr>
            <td colspan="8" class="text-center">Data belum tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1;
          foreach ($truck as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['nama_truk']); ?></td>
              <td><?= htmlspecialchars($row['tahun']); ?></td>
              <td><?= htmlspecialchars($row['jumlah_unit']); ?></td>
              <td><?= number_format($row['harga_per_unit'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['deskripsi']); ?></td>
              <td><?= htmlspecialchars($row['status_post']); ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-success mr-1" data-toggle="tooltip" data-placement="top" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=kendaraan&page=edit-kendaraan&type=truk&id=<?= $row['id_truk'] ?>" class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/kendaraan/hapus-kendaraan.php?type=truk&id=<?= $row['id_truk'] ?>"
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