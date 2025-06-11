<h2 class="mt-4 mb-4">
  <i class="fas fa-file-invoice-dollar mr-2"></i> Daftar Pesanan
</h2>

<?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'berhasil'): ?>
  <div class="alert alert-success" role="alert">
    Status pesanan berhasil diperbarui.
  </div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <!-- Tidak ada tombol tambah karena data otomatis masuk -->
    <table class="table table-hover table-bordered">
      <thead class="thead-light">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Invoice</th>
          <th scope="col">Email Pembeli</th>
          <th scope="col">Kategori</th>
          <th scope="col">Nama Produk</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Total Harga</th>
          <th scope="col">Metode Bayar</th>
          <th scope="col">Tanggal Pesan</th>
          <th scope="col">Status</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once('../model/Transaksi.php');
        $tran_beli = new Transaksi();
        $trans_belii = $tran_beli->getAll(); // Ambil semua transaksi dari DB

        $nomor = 1;
        foreach ($trans_belii as $row) {
          $id_transaksi = $row['id_transaksi'];
          $status = strtolower($row['status']);
        ?>
          <tr>
            <td><?= $nomor++; ?></td>
            <td><?= htmlspecialchars($row['no_invoice']); ?></td>
            <td><?= htmlspecialchars($row['email_pembeli']); ?></td>
            <td><?= htmlspecialchars(ucfirst($row['type'])); ?></td>
            <td><?= htmlspecialchars($row['nama_produk']); ?></td>
            <td><?= htmlspecialchars($row['jumlah_unit']); ?></td>
            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars(str_replace('_', ' ', $row['metode_bayar'])); ?></td>
            <td><?= date('d M Y', strtotime($row['tanggal_dipesan'])); ?></td>
            <td>
              <?php
              switch ($status) {
                case 'pending':
                  $badgeClass = 'badge badge-warning';
                  break;
                case 'dikonfirmasi':
                  $badgeClass = 'badge badge-success';
                  break;
                case 'dibatalkan':
                  $badgeClass = 'badge badge-danger';
                  break;
                case 'selesai':
                  $badgeClass = 'badge badge-primary';
                  break;
                default:
                  $badgeClass = 'badge badge-secondary';
              }
              ?>
              <span class="<?= $badgeClass ?>"><?= ucfirst($status); ?></span>
            </td>
            <td>
              <div class="btn-group">
                <!-- Tombol Konfirmasi (hanya muncul saat pending) -->
                <?php if ($status === 'pending'): ?>
                  <a href="/PWD-Project-Mandiri/admin/page/transaksi/proses-update.php?id=<?= $id_transaksi ?>&action=confirm"
                    class="btn btn-sm btn-success mr-1"
                    onclick="return confirm('Yakin ingin konfirmasi pesanan ini?')">
                    <i class="fa fa-check"></i> Konfirmasi
                  </a>
                <?php endif; ?>

                <!-- Tombol Batalkan (bisa digunakan untuk tolak / membatalkan setelah dikonfirmasi) -->
                <?php if ($status !== 'dibatalkan' && $status !== 'selesai'): ?>
                  <a href="/PWD-Project-Mandiri/admin/page/transaksi/proses-update.php?id=<?= $id_transaksi ?>&action=reject"
                    class="btn btn-sm btn-danger mr-1"
                    onclick="return confirm('Apakah Anda yakin ingin batalkan pesanan ini?')">
                    <i class="fa fa-times"></i> Batalkan
                  </a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>