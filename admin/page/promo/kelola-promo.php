<h2 class="mt-4 mb-4"><i class="fas fa-percentage"></i> Daftar Promo</h2>

<div class="row">
  <div class="col">
    <div class="mb-2">
      <a href="#" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Promo</a>
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
        <?php
        require_once('../model/Promo.php');
        $promo = new Promo();
        $promos = $promo->getAll();

        $nomor = 1;
        foreach ($promos as $row) {
        ?>
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
              <a href="#" class="btn btn-sm btn-outline-warning mr-1" data-toggle="tooltip" data-placement="top" title="Edit">
                <i class="fa fa-edit"></i>
              </a>
              <a href="#" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Hapus">
                <i class="fa fa-trash"></i>
              </a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</div>