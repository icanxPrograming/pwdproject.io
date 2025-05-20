<!-- Main Content -->
<section class="main-content">
  <h2>Cari Yang Terbaik</h2>
  <div class="card-slider-container">
    <button class="card-slide-btn prev" id="cardPrevBtn">&lt;</button>
    <div class="card-slider">
      <div class="card-group" id="cardGroup">
        <!-- Card Items -->
        <?php
        $cards = [
          ["mobil-baru-icon.png", "Mobil Baru", "Penawaran Menarik"],
          ["mobil-bekas-icon.png", "Mobil Bekas", "Berkualitas"],
          ["motor-baru-icon.png", "Motor Baru", "Pilihan Terlengkap"],
          ["truk-baru-icon.png", "Truk Baru", "Harga Kompetitif"],
          ["berita-mobil-icon.png", "Berita Mobil", "Update Terbaru"],
          ["bandingkan-mobil-icon.png", "Bandingkan Mobil", "Pilih yang Tepat"],
          ["bandingkan-motor-icon.png", "Bandingkan Motor", "Pilih yang Tepat"],
          ["bandingkan-truk-icon.png", "Bandingkan Truk", "Pilih yang Tepat"],
          ["tukartambah-mobil-icon.png", "Tukar Tambah Mobil", "Pilih yang Tepat"],
          ["tukartambah-motor-icon.png", "Tukar Tambah Motor", "Pilih yang Tepat"],
          ["promo-icon.png", "Promo", "Pilih yang Tepat"],
        ];
        foreach ($cards as [$img, $title, $desc]): ?>
          <div class="card">
            <img src="<?= $img ?>" alt="<?= $title ?>" />
            <h3><?= $title ?></h3>
            <p><?= $desc ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <button class="card-slide-btn next" id="cardNextBtn">&gt;</button>
  </div>
</section>