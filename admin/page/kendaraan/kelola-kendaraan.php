<h2 class="mt-4 mb-4"><i class="fas fa-car"></i> Daftar Kendaraan</h2>

<div class="row">
  <div class="col">
    <div class="mb-2">
      <a href="#" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kendaraan</a>
    </div>
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col" style="width: 40px;">NO</th>
          <th scope="col" style="width: 120px;">TANGGAL</th>
          <th scope="col" style="width: 300px;">Nama Kendaraan</th>
          <th scope="col" style="width: 100px;">Jumlah Unit</th>
          <th scope="col" style="width: 500px;">Deskripsi</th>
          <th scope="col" style="width: 100px;">Status Posting</th>
          <th scope="col" style="width: 120px;">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once('../model/Kendaraan.php');
        $kendaraan = new Kendaraan();
        $kendaraann = $kendaraan->getAll();

        $nomor = 1;
        foreach ($kendaraann as $row) {
        ?>
          <tr>
            <td><?= $nomor++; ?></td>
            <td><?= htmlspecialchars($row['tanggal']); ?></td>
            <td><?= htmlspecialchars($row['nama_kendaraan']); ?></td>
            <td><?= htmlspecialchars($row['jumlah_unit']); ?></td>
            <td><?= htmlspecialchars($row['deskripsi']); ?></td>
            <td><?= htmlspecialchars($row['status_post']); ?></td>
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