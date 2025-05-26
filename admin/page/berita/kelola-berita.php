<?php
require_once('../model/Berita.php');
$new = new Berita();
$news = $new->getBerita();
?>

<h2 class="mt-4 mb-4"><i class="fas fa-newspaper"></i> Daftar Berita</h2>

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
      <!-- Tombol Tambah -->
      <a href="dashboard.php?module=berita&page=tambah-berita&type=berita" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Berita
      </a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 130px;">Judul Berita</th>
          <th scope="col" style="width: 220px;">Isi</th>
          <th scope="col" style="width: 50px;">Kategori Berita</th>
          <th scope="col" style="width: 100px;">Tanggal</th>
          <th scope="col" style="width: 50px;">Penulis</th>
          <th scope="col" style="width: 50px;">Status</th>
          <th scope="col" style="width: 120px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($news) === 0): ?>
          <tr>
            <td colspan="8" class="text-center text-muted">Belum ada data berita yang tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1; ?>
          <?php foreach ($news as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['judul']) ?></td>
              <td><?= htmlspecialchars($row['isi']) ?></td>
              <td><?= htmlspecialchars($row['kategori']) ?></td>
              <td><?= htmlspecialchars($row['tanggal']) ?></td>
              <td><?= htmlspecialchars($row['penulis']) ?></td>
              <td><?= htmlspecialchars($row['status']) ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-success mr-1" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=berita&page=edit-berita&type=berita&id=<?= $row['id_berita'] ?>"
                  class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/berita/hapus-berita.php?type=berita&id=<?= $row['id_berita'] ?>"
                  onclick="return confirm('Yakin ingin menghapus motor <?= addslashes($row['judul']) ?>?');"
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