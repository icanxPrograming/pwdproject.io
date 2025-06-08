<?php
require_once '../model/Video.php';
$video = new Video();
$videos = $video->getAll(); // Pastikan fungsi ini sudah tersedia
?>

<h2 class="mt-4 mb-4">
  <i class="fas fa-video"></i> Daftar Video
</h2>

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
      <a href="dashboard.php?module=video&page=tambah-video&type=kelola-video" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Video
      </a>
    </div>

    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 250px;">Judul Video</th>
          <th scope="col" style="width: 150px;">Kategori</th>
          <th scope="col" style="width: 100px;">Tanggal</th>
          <th scope="col" style="width: 100px;">Status</th>
          <th scope="col" style="width: 120px;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($videos) === 0): ?>
          <tr>
            <td colspan="6" class="text-center">Data belum tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1;
          foreach ($videos as $row):
            $tanggal = date('d M Y', strtotime($row['tanggal']));
          ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['judul_video']); ?></td>
              <td><?= htmlspecialchars($row['kategori']); ?></td>
              <td><?= $tanggal ?></td>
              <td>
                <?php
                $status = strtolower($row['status_post']);
                $badgeClass = '';
                switch ($status) {
                  case 'posting':
                    $badgeClass = 'badge badge-success';
                    break;
                  case 'draft':
                    $badgeClass = 'badge badge-warning';
                    break;
                  default:
                    $badgeClass = 'badge badge-secondary';
                    break;
                }
                ?>
                <span class="<?= $badgeClass ?>"><?= ucfirst($status); ?></span>
              </td>
              <td>
                <!-- Tombol Detail -->
                <a href="#" class="btn btn-sm btn-outline-success mr-1"
                  data-toggle="tooltip" data-placement="top" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>

                <!-- Tombol Edit -->
                <a href="dashboard.php?module=video&page=edit-video&type=kelola-video&id=<?= $row['id_video'] ?>"
                  class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>

                <!-- Tombol Hapus -->
                <a href="/PWD-Project-Mandiri/admin/page/video/hapus-video.php?type=kelola-video&id=<?= $row['id_video'] ?>"
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