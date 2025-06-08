<!-- Main Content -->
<section class="main-content">
  <h2>Cari Yang Terbaik</h2>
  <div class="card-slider-container">
    <button class="card-slide-btn prev" id="cardPrevBtn">&lt;</button>
    <div class="card-slider">
      <div class="card-group" id="cardGroup">
        <?php
        $cards = [
          ["asset/mobil-baru-icon.png", "Mobil Baru", "Penawaran Menarik"],
          ["asset/motor-baru-icon.png", "Motor Baru", "Pilihan Terlengkap"],
          ["asset/truk-baru-icon.png", "Truk Baru", "Harga Kompetitif"],
          ["asset/berita-mobil-icon.png", "Berita Mobil", "Update Terbaru"],
          ["asset/berita-motor-icon.png", "Berita Motor", "Update Terbaru"],
          ["asset/bandingkan-mobil-icon.png", "Kebutuhan Mobil", "Pilih yang Tepat"],
          ["asset/bandingkan-motor-icon.png", "Kebutuhan Motor", "Pilih yang Tepat"],
          ["asset/video-mobil-icon.png", "Video Mobil", "Lihat & yang Tepat"],
          ["asset/video-motor-icon.png", "Video Motor", "Lihat & yang Tepat"],
          ["asset/video-truk-icon.png", "Video Truk", "Lihat & yang Tepat"],
          ["asset/promo-icon.png", "Promo", "Pilih yang Tepat"],
        ];
        foreach ($cards as [$img, $title, $desc]): ?>
          <?php
          $url = '';
          if (strpos($title, 'Baru') !== false) {
            $jenis = strtolower(str_replace(' Baru', '', $title));
            $url = "/PWD-Project-Mandiri/index.php?page=$jenis&kondisi[]=Baru";
          } elseif (strpos($title, 'Berita') !== false) {
            $jenis = strtolower(str_replace(' Berita', '', $title));
            $url = "/PWD-Project-Mandiri/index.php?page=berita&type=$jenis";
          } elseif ($title === 'Promo') {
            $url = "/PWD-Project-Mandiri/index.php?page=promo";
          } elseif (strpos($title, 'Kebutuhan') !== false) {
            $jenis = trim(str_replace('Kebutuhan ', ' ', $title));
            $url = "/PWD-Project-Mandiri/index.php?page=kebutuhan&kategori[]=$jenis";
          } else {
            $url = "/PWD-Project-Mandiri/index.php?page=video";
          }
          ?>
          <a href="<?= htmlspecialchars($url) ?>" class="card-link">
            <div class="card">
              <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($title) ?>" />
              <h3><?= htmlspecialchars($title) ?></h3>
              <p><?= htmlspecialchars($desc) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <button class="card-slide-btn next" id="cardNextBtn">&gt;</button>
  </div>
</section>