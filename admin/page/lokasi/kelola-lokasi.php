<h2 class="mt-4 mb-4"><i class="fas fa-map-marker-alt"></i> Daftar Lokasi</h2>

<div class="row">
  <div class="col">
    <div class="mb-2">
      <a href="#" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Lokasi</a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 200px;">Nama Lokasi</th>
          <th scope="col" style="width: 80px;">Status</th>
          <th scope="col" style="width: 80px;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once('../model/Lokasi.php');
        $lokasi = new Lokasi();
        $location = $lokasi->getAll();

        $nomor = 1;
        foreach ($location as $row) {
        ?>
          <tr>
            <td><?= $nomor++; ?></td>
            <td><?= htmlspecialchars($row['nama_lokasi']); ?></td>
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