<?php
require_once '../model/Kendaraan.php';
require_once '../model/Kebutuhan.php';

$type = $_GET['type'] ?? 'mobil';
$filters = [];

// Daftar filter berdasarkan type
if ($type === 'kebutuhan') {
  $supportedFilters = ['jenis', 'kategori', 'status'];
} else {
  $supportedFilters = ['jenis', 'merk', 'kondisi', 'bahan_bakar', 'transmisi', 'tahun'];
}

foreach ($_GET as $key => $value) {
  if (!in_array($key, $supportedFilters)) continue;

  // Jika bukan array, jadikan array
  $values = is_array($value) ? $value : [$value];

  foreach ($values as $v) {
    $filters[$key][] = $v;
  }
}

error_log("API Filters for $type: " . json_encode($filters));

// Load model sesuai type
if ($type === 'kebutuhan') {
  require_once '../model/Kebutuhan.php';
  $model = new Kebutuhan();
} else {
  $model = new Kendaraan();
}

$data = $model->getPostedAvailable($type, $filters);

$viewFile = "../beranda/{$type}/kelola-{$type}.php";

if (file_exists($viewFile)) {
  include $viewFile;
} else {
  echo "<p class='text-muted'>Tidak ada tampilan untuk '$type'</p>";
}
