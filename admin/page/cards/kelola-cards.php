<?php
require_once('../model/Cards.php');
$card = new Cards();
$cards = $card->getAllFromTable('cards');
?>

<h2 class="mt-4 mb-4"><i class="fas fa-window-maximize"></i> Daftar Card</h2>

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
      <a href="dashboard.php?module=cards&page=tambah-cards&type=kelola-cards" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Card
      </a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 150px;">Judul</th>
          <th scope="col" style="width: 150px;">Sub Judul</th>
          <th scope="col" style="width: 80px;">Urutan</th>
          <th scope="col" style="width: 80px;">Status Aktif</th>
          <th scope="col" style="width: 120px;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($cards) === 0): ?>
          <tr>
            <td colspan="8" class="text-center">Data belum tersedia.</td>
          </tr>
        <?php else: ?>
          <?php $nomor = 1;
          foreach ($cards as $row): ?>
            <tr>
              <td><?= $nomor++; ?></td>
              <td><?= htmlspecialchars($row['judul']); ?></td>
              <td><?= htmlspecialchars($row['sub_judul']); ?></td>
              <td><?= htmlspecialchars($row['urutan']); ?></td>
              <td><?= htmlspecialchars($row['status']); ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-outline-success mr-1" data-toggle="tooltip" data-placement="top" title="Detail">
                  <i class="fa fa-eye"></i>
                </a>
                <a href="dashboard.php?module=cards&page=edit-cards&type=kelola-cards&id=<?= $row['id_cards'] ?>" class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="/PWD-Project-Mandiri/admin/page/cards/hapus-cards.php?type=kelola-cards&id=<?= $row['id_cards'] ?>"
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