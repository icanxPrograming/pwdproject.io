<?php
include($_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/data_dashboard.php'); ?>

<h2 class="mt-2" style="font-weight: 600; color: #333;">
  <i class="fas fa-tachometer-alt"></i> Dashboard Admin
</h2>

<div class="alert alert-success mt-3" style="font-size: 0.95rem;">
  <strong>Selamat datang!</strong> Anda masuk sebagai admin pengelola Alter-Ex.
</div>

<div class="row mt-4">
  <!-- Kendaraan -->
  <div class="col-md-3 mb-4">
    <div class="card border-0 shadow-sm" style="background-color: #e3f2fd; color: #0d47a1;">
      <div class="card-body">
        <div class="card-title" style="font-weight: 600;">Total Kendaraan</div>
        <div style="font-size: 1.8rem;">
          <?= isset($dataDashboard['kendaraan']) ? $dataDashboard['kendaraan'] : '0'; ?>
          <i class="fas fa-car float-right"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Barang kebutuhan -->
  <div class="col-md-3 mb-4">
    <div class="card border-0 shadow-sm" style="background-color:rgb(233, 227, 253); color:rgb(114, 13, 161);">
      <div class="card-body">
        <div class="card-title" style="font-weight: 600;">Total Kebutuhan Kendaraan</div>
        <div style="font-size: 1.8rem;">
          <?= isset($dataDashboard['kebutuhan']) ? $dataDashboard['kebutuhan'] : '0'; ?>
          <i class="fas fa-wrench float-right"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Penjual -->
  <div class="col-md-3 mb-4">
    <div class="card border-0 shadow-sm" style="background-color: #fff3cd; color: #856404;">
      <div class="card-body">
        <div class="card-title" style="font-weight: 600;">Penjual Terdaftar</div>
        <div style="font-size: 1.8rem;">
          <?= isset($dataDashboard['penjual']) ? $dataDashboard['penjual'] : '0'; ?>
          <i class="fas fa-user-tie float-right"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Transaksi -->
  <div class="col-md-3 mb-4">
    <div class="card border-0 shadow-sm" style="background-color: #c8e6c9; color: #1b5e20;">
      <div class="card-body">
        <div class="card-title" style="font-weight: 600;">Transaksi Sukses</div>
        <div style="font-size: 1.8rem;">
          <?= isset($dataDashboard['transaksi']) ? $dataDashboard['transaksi'] : '0'; ?>
          <i class="fas fa-file-invoice-dollar float-right"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Lokasi -->
  <div class="col-md-3 mb-4">
    <div class="card border-0 shadow-sm" style="background-color: #b3e5fc; color: #01579b;">
      <div class="card-body">
        <div class="card-title" style="font-weight: 600;">Lokasi Aktif</div>
        <div style="font-size: 1.8rem;">
          <?= isset($dataDashboard['lokasi']) ? $dataDashboard['lokasi'] : '0'; ?>
          <i class="fas fa-map-marker-alt float-right"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Promo -->
  <div class="col-md-3 mb-4">
    <div class="card border-0 shadow-sm" style="background-color: #f8bbd0; color: #880e4f;">
      <div class="card-body">
        <div class="card-title" style="font-weight: 600;">Promo Aktif</div>
        <div style="font-size: 1.8rem;">
          <?= isset($dataDashboard['promo']) ? $dataDashboard['promo'] : '0'; ?>
          <i class="fas fa-percentage float-right"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Berita -->
  <div class="col-md-3 mb-4">
    <div class="card border-0 shadow-sm" style="background-color:#e0e780; color:#4f522f;">
      <div class="card-body">
        <div class="card-title" style="font-weight: 600;">Berita</div>
        <div style="font-size: 1.8rem;">
          <?= isset($dataDashboard['berita']) ? $dataDashboard['berita'] : '0'; ?>
          <i class="fas fa-newspaper float-right"></i>
        </div>
      </div>
    </div>
  </div>
</div>