document.addEventListener("DOMContentLoaded", function () {
  initBannerSlider();
  initCardSlider();
  initLocationPlaceholder();

  const currentPage = getCurrentPage();

  // Jalankan hanya jika halaman adalah mobil/motor/sepeda/dll
  if (
    [
      "mobil",
      "motor",
      "truk",
      "alat_berat",
      "kend_khusus",
      "sepeda",
      "kebutuhan",
    ].includes(currentPage)
  ) {
    initDynamicFilter(currentPage);
  }

  // Tambahkan event listener untuk input pencarian
  const searchInput = document.getElementById("search-produk");
  if (searchInput) {
    searchInput.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        handleSearch();
      }
    });
  }
});

// === Ambil ?page dari URL ===
function getCurrentPage() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get("page") || "home";
}

// === Inisiasi filter dinamis ===
function initDynamicFilter(type) {
  const checkboxes = document.querySelectorAll(
    ".filter-box input[type='checkbox']"
  );
  const productGrid = document.querySelector(".product-grid");
  const loadingScreen = document.querySelector(".loading-screen");

  if (!checkboxes.length || !productGrid || !loadingScreen) return;

  // Restore checkbox dari URL saat pertama kali halaman dimuat
  restoreFiltersFromUrl(checkboxes);

  checkboxes.forEach((cb) => {
    cb.addEventListener("change", () => {
      const filters = {};

      // Ambil semua checkbox aktif
      document
        .querySelectorAll(".filter-box input[type='checkbox']:checked")
        .forEach((checkbox) => {
          const name = checkbox.name.replace("[]", ""); // hilangkan [] jika ada
          if (!filters[name]) filters[name] = [];
          filters[name].push(checkbox.value);
        });

      // Bangun URL
      let url = `/PWD-Project-Mandiri/api/get_produk.php?type=${type}`;
      for (let key in filters) {
        filters[key].forEach((value) => {
          url += `&${key}[]=${encodeURIComponent(value)}`;
        });
      }

      // Bangun URL untuk redirect browser
      let pageUrl = `/PWD-Project-Mandiri/index.php?page=${type}`;
      for (let key in filters) {
        filters[key].forEach((value) => {
          pageUrl += `&${key}[]=${encodeURIComponent(value)}`;
        });
      }

      // Simpan ke history tanpa reload
      history.pushState(null, "", pageUrl);

      console.log("Fetching URL:", url);

      // Tampilkan loading screen
      loadingScreen.style.display = "flex";

      // Fetch HTML hasil filter
      fetch(url)
        .then((response) => response.text())
        .then((html) => {
          // Simulasikan delay minimal agar loading screen tetap terlihat
          setTimeout(() => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");
            const newGrid = doc.querySelector(".product-grid");

            if (newGrid) {
              productGrid.innerHTML = newGrid.innerHTML;
            } else {
              productGrid.innerHTML = "<p>Tidak ada data ditemukan</p>";
            }

            // Sembunyikan loading screen setelah data dimuat
            loadingScreen.style.display = "none";
          }, 300); // Minimal 500ms loading screen
        })
        .catch((err) => {
          console.error("Error fetching filtered products:", err);
          productGrid.innerHTML = "<p>Terjadi kesalahan saat memuat data.</p>";

          // Sembunyikan loading screen jika terjadi error
          setTimeout(() => {
            loadingScreen.style.display = "none";
          }, 300);
        });
    });
  });
}

// === Fungsi restore checkbox berdasarkan URL ===
function restoreFiltersFromUrl(checkboxes) {
  const params = new URLSearchParams(window.location.search);

  // Baca semua parameter dari URL
  const activeFilters = {};
  for (const [key, value] of params.entries()) {
    const cleanKey = key.replace("[]", "");
    if (!activeFilters[cleanKey]) activeFilters[cleanKey] = [];
    activeFilters[cleanKey].push(value);
  }

  // Centang otomatis
  checkboxes.forEach((cb) => {
    const name = cb.name.replace("[]", "");
    if (activeFilters[name] && activeFilters[name].includes(cb.value)) {
      cb.checked = true;
    }
  });
}

// =======================
// Toogle dropdown
// =======================
const toggleBtn = document.querySelector(".dropdown-toggle");
const dropdownMenu = document.getElementById("kategori-dropdown");

// Toggle dropdown saat klik
toggleBtn.addEventListener("click", function () {
  dropdownMenu.classList.toggle("active");
  this.setAttribute(
    "aria-expanded",
    this.getAttribute("aria-expanded") === "true" ? "false" : "true"
  );
});

// Tutup dropdown jika klik di luar
document.addEventListener("click", function (e) {
  if (!toggleBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
    dropdownMenu.classList.remove("active");
    toggleBtn.setAttribute("aria-expanded", "false");
  }
});

// =======================
// Banner Slider
// =======================
function initBannerSlider() {
  const slides = document.querySelectorAll(".slides img");
  const slideContainer = document.querySelector(".slides");
  const nextBtn = document.querySelector(".next");
  const prevBtn = document.querySelector(".prev");
  const sliderWrapper = document.querySelector(".slider-container");

  if (!slides.length || !slideContainer) return;

  let currentIndex = 0;
  let isTransitioning = false;
  const totalSlides = slides.length;
  const slideDuration = 500;

  function updateSlider() {
    if (isTransitioning) return;

    isTransitioning = true;
    const offset = -currentIndex * 100;
    slideContainer.style.transform = `translateX(${offset}%)`;

    setTimeout(() => {
      isTransitioning = false;
    }, slideDuration);
  }

  if (nextBtn) {
    nextBtn.addEventListener("click", () => {
      if (isTransitioning) return;
      currentIndex = (currentIndex + 1) % totalSlides;
      updateSlider();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", () => {
      if (isTransitioning) return;
      currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
      updateSlider();
    });
  }

  let slideInterval = setInterval(() => {
    if (!isTransitioning) {
      currentIndex = (currentIndex + 1) % totalSlides;
      updateSlider();
    }
  }, 5000);

  if (sliderWrapper) {
    sliderWrapper.addEventListener("mouseenter", () =>
      clearInterval(slideInterval)
    );
    sliderWrapper.addEventListener("mouseleave", () => {
      slideInterval = setInterval(() => {
        if (!isTransitioning) {
          currentIndex = (currentIndex + 1) % totalSlides;
          updateSlider();
        }
      }, 5000);
    });
  }
}

// =======================
// Card Slider
// =======================
function initCardSlider() {
  const cardGroup = document.getElementById("cardGroup");
  const cardPrevBtn = document.getElementById("cardPrevBtn");
  const cardNextBtn = document.getElementById("cardNextBtn");

  if (!cardGroup || !cardPrevBtn || !cardNextBtn) return;

  const cardWidth = 200;
  const cardsPerPage = 3;
  let currentPosition = 0;

  function slideCards(direction) {
    const totalCards = cardGroup.children.length;
    const maxPosition = Math.ceil(totalCards / cardsPerPage) - 1;

    currentPosition += direction;

    if (currentPosition > maxPosition) currentPosition = 0;
    if (currentPosition < 0) currentPosition = maxPosition;

    cardGroup.style.transform = `translateX(-${
      currentPosition * cardsPerPage * cardWidth
    }px)`;
  }

  cardPrevBtn.addEventListener("click", () => slideCards(-1));
  cardNextBtn.addEventListener("click", () => slideCards(1));
}

function initLocationPlaceholder() {
  const searchInput = document.getElementById("search-input");
  const chevronIcon = document.querySelector(".chevron-icon");

  if (!searchInput) {
    console.warn("Elemen searchInput tidak ditemukan!");
    return;
  }

  const inputElement = searchInput.querySelector("input");
  const searchIcon = searchInput.querySelector(".search-icon");
  const dropdown = searchInput.querySelector(".location-dropdown");
  const historyList = searchInput.querySelector(".history-list");
  const locationOptionsContainer =
    searchInput.querySelector(".location-options");

  // Fungsi bantu simpan/ambil dari localStorage
  function getSearchHistory() {
    const stored = localStorage.getItem("locationHistory");
    return stored ? JSON.parse(stored) : [];
  }

  function saveSearchHistory(history) {
    localStorage.setItem("locationHistory", JSON.stringify(history));
  }

  function updateHistoryDropdown() {
    const history = getSearchHistory();
    historyList.innerHTML = "";

    if (history.length === 0) {
      const noItem = document.createElement("div");
      noItem.className = "history-item text-muted";
      noItem.textContent = "Belum ada riwayat";
      historyList.appendChild(noItem);
    } else {
      history.slice(0, 3).forEach((loc) => {
        const item = document.createElement("div");
        item.className = "history-item";
        item.textContent = loc;

        item.addEventListener("click", () => {
          inputElement.value = loc;
          updatePlaceholder();
          dropdown.style.display = "none";
          chevronIcon.classList.remove("rotate");
        });

        historyList.appendChild(item);
      });
    }
  }

  function populateLocationOptions() {
    locationOptionsContainer.innerHTML = "";
    availableLocations.slice(0, 5).forEach((loc) => {
      const item = document.createElement("div");
      item.className = "location-option";
      item.textContent = loc;

      item.addEventListener("click", () => {
        inputElement.value = loc;
        inputElement.placeholder = loc;
        dropdown.style.display = "none";
        chevronIcon.classList.remove("rotate");

        // Simpan ke riwayat
        let history = getSearchHistory();
        history = [loc, ...history.filter((h) => h !== loc)].slice(0, 3);
        saveSearchHistory(history);
      });

      locationOptionsContainer.appendChild(item);
    });
  }

  // ================
  // Ambil data lokasi dari PHP (via JS)
  // ================

  // Fungsi update placeholder
  function updatePlaceholder() {
    if (!inputElement) return;

    const selectedLocation = inputElement.value.trim();

    if (selectedLocation) {
      inputElement.placeholder = selectedLocation;
    } else {
      const maxToShow = 3;
      const topLocations = availableLocations.slice(0, maxToShow);
      const hasMore = availableLocations.length > maxToShow;
      const displayText = topLocations.join(", ") + (hasMore ? "..." : "");
      inputElement.placeholder = displayText;
    }
  }

  // ================
  // 🔍 Event: Redirect ke GMaps
  // ================

  function handleRedirectToGMaps() {
    const locationName = inputElement.value.trim();

    if (!locationName) {
      alert("Silakan masukkan nama lokasi.");
      return;
    }

    // Cari apakah cocok dengan database lokasi (case-insensitive partial match)
    const matchedLocation = availableLocations.find((loc) =>
      loc.toLowerCase().includes(locationName.toLowerCase())
    );

    if (!matchedLocation) {
      alert("Tidak ada dealer tersedia di lokasi tersebut.");
      return;
    }

    // Redirect ke Google Maps
    const query = `Dealer terdekat di ${matchedLocation}`;
    const encodedQuery = encodeURIComponent(query);
    window.open(
      `https://www.google.com/maps/search/?api=1&query=${encodedQuery}`,
      "_blank"
    );

    // Reset input setelah redirect
    inputElement.value = "";
    updatePlaceholder();
  }

  // ================
  // 🔄 Fungsi Reset Input
  // ================

  function resetInput() {
    if (!inputElement) return;

    inputElement.value = "";
    updatePlaceholder();
  }

  // ================
  // 🖱️ Event Listener: Toggle Dropdown
  // ================

  function toggleDropdown() {
    const isVisible = dropdown.style.display === "block";
    dropdown.style.display = isVisible ? "none" : "block";
    chevronIcon.classList.toggle("rotate", !isVisible);
    updateHistoryDropdown();
  }

  searchInput.addEventListener("click", (e) => {
    e.stopPropagation();
    toggleDropdown();
  });

  chevronIcon.addEventListener("click", (e) => {
    e.stopPropagation();
    toggleDropdown();
  });

  document.addEventListener("click", (e) => {
    if (!e.target.closest("#search-input")) {
      dropdown.style.display = "none";
      chevronIcon.classList.remove("rotate");
    }
  });

  // ================
  // 📦 Inisialisasi dropdown options
  // ================

  updateHistoryDropdown();
  populateLocationOptions();

  // ================
  // 🖱️ Event Listener: Enter & Klik Ikon Pencarian
  // ================

  if (searchIcon) {
    searchIcon.addEventListener("click", handleRedirectToGMaps);
  }

  if (inputElement) {
    inputElement.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        handleRedirectToGMaps();
      }
    });
  }

  // Optional: sembunyikan ikon chevron jika tidak perlu
  if (chevronIcon) {
    chevronIcon.style.display = "inline-block";
  }

  // ================
  // 🚀 Inisialisasi Awal
  // ================
  updatePlaceholder();
}

// URL VIA SEARCH PRODUK
function handleSearch() {
  const input = document.getElementById("search-produk");

  if (!input) {
    console.error("Input pencarian tidak ditemukan!");
    return;
  }

  const query = input.value.trim().toLowerCase();

  if (!query) {
    alert("Silakan masukkan kata kunci pencarian.");
    return;
  }

  // Mapping input ke modul
  const keywordMap = {
    mobil: "mobil",
    suv: "mobil",
    mpv: "mobil",
    motor: "motor",
    skuter: "motor",
    trail: "motor",
    truk: "truk",
    alat: "alat_berat",
    berat: "alat_berat",
    toyota: "mobil",
    honda: "mobil",
    suzuki: "mobil",
    bmw: "mobil",
    mercedes: "mobil",
    yamaha: "motor",
    kawasaki: "motor",
    daihatsu: "mobil",
    mitsubishi: "mobil",
    ford: "mobil",
    hyundai: "mobil",
    audi: "mobil",
    volvo: "mobil",
    nissan: "mobil",
    chevrolet: "mobil",
    scania: "truk",
    hino: "truk",
    forklift: "alat_berat",
    excavator: "alat_berat",
    khusus: "kend_khusus",
    ambulans: "kend_khusus",
    damkar: "kend_khusus",
    derek: "kend_khusus",
    sepeda: "sepeda",
    "sepeda listrik": "sepeda",
    kebutuhan: "kebutuhan",
    sparepart: "kebutuhan",
    oli: "kebutuhan",
    pelumas: "kebutuhan",
    aksesoris: "kebutuhan",
    berita: "berita",
    news: "berita",
    artikel: "berita",
    promo: "promo",
    diskon: "promo",
    video: "video",
    galeri: "video",
  };

  let targetPage = null;
  let filters = {};

  if (query.includes("promo")) {
    window.location.href = `/PWD-Project-Mandiri/index.php?page=promo`;
    return;
  }
  if (
    query.includes("berita") ||
    query.includes("artikel") ||
    query.includes("news")
  ) {
    window.location.href = `/PWD-Project-Mandiri/index.php?page=berita`;
    return;
  }
  if (query.includes("video") || query.includes("galeri")) {
    window.location.href = `/PWD-Project-Mandiri/index.php?page=video`;
    return;
  }

  // Cek apakah input mengandung kata kunci untuk modul kebutuhan
  if (
    query.includes("sparepart") ||
    query.includes("aksesoris") ||
    query.includes("oli") ||
    query.includes("pelumas") ||
    query.includes("kebutuhan")
  ) {
    targetPage = "kebutuhan";

    // Deteksi jenis kebutuhan
    if (query.includes("sparepart")) {
      filters["jenis[]"] = "Sparepart";
    } else if (query.includes("aksesoris")) {
      filters["jenis[]"] = "Aksesoris";
    } else if (query.includes("oli") || query.includes("pelumas")) {
      filters["jenis[]"] = "Oli_Pelumas";
    }

    // Deteksi kategori kendaraan
    if (query.includes("mobil")) {
      filters["kategori[]"] = "Mobil";
    } else if (query.includes("motor")) {
      filters["kategori[]"] = "Motor";
    } else if (query.includes("truk")) {
      filters["kategori[]"] = "Truk";
    } else if (query.includes("sepeda")) {
      filters["kategori[]"] = "Sepeda";
    } else if (query.includes("alat berat")) {
      filters["kategori[]"] = "Heavy";
    } else if (query.includes("kendaraan khusus")) {
      filters["kategori[]"] = "Khusus";
    }
  } else {
    // Jika bukan bagian dari kebutuhan, cari modul lainnya
    for (let keyword in keywordMap) {
      if (query.includes(keyword)) {
        targetPage = keywordMap[keyword];
        break;
      }
    }

    // Jika tidak ketemu, arahkan ke mobil
    if (!targetPage) {
      window.location.href = `/PWD-Project-Mandiri/index.php?page=mobil`;
      return;
    }

    // Buat filter otomatis dari input
    if (query.includes("bekas")) {
      filters["kondisi[]"] = "Bekas";
    } else if (query.includes("baru")) {
      filters["kondisi[]"] = "Baru";
    }

    if (query.includes("listrik")) {
      if (["mobil", "motor"].includes(targetPage)) {
        filters["bahan_bakar[]"] = "Listrik";
        filters["jenis[]"] = "Listrik";
      } else if (targetPage === "sepeda") {
        filters["jenis[]"] = "Listrik";
      } else if (targetPage === "truk") {
        filters["bahan_bakar[]"] = "Listrik";
      }
    }

    if (query.includes("suv")) {
      filters["jenis[]"] = "SUV";
    }

    if (query.includes("mpv")) {
      filters["jenis[]"] = "MPV";
    }

    if (query.includes("hatchback")) {
      filters["jenis[]"] = "Hatchback";
    }

    if (query.includes("sport")) {
      filters["jenis[]"] = "Sport";
    }

    if (query.includes("skuter")) {
      filters["jenis[]"] = "Skuter";
    }

    if (query.includes("trail")) {
      filters["jenis[]"] = "Trail";
    }

    if (query.includes("pickup")) {
      filters["jenis[]"] = "Pickup";
    }

    if (query.includes("tipper")) {
      filters["jenis[]"] = "Tipper";
    }

    if (query.includes("forklift")) {
      filters["jenis[]"] = "Forklift";
    }

    if (query.includes("excavator")) {
      filters["jenis[]"] = "Excavator";
    }

    if (query.includes("ambulans")) {
      filters["jenis[]"] = "Ambulans";
    }

    if (query.includes("derek")) {
      filters["jenis[]"] = "Derek";
    }

    if (query.includes("gunung")) {
      filters["jenis[]"] = "Gunung";
    }

    if (query.includes("balap")) {
      filters["jenis[]"] = "Balap";
    }

    if (query.includes("lipat")) {
      filters["jenis[]"] = "Lipat";
    }
  }

  // Bangun URL hasil
  let url = `/PWD-Project-Mandiri/index.php?page=${targetPage}`;

  for (let key in filters) {
    let values = filters[key];
    if (!Array.isArray(values)) values = [values];

    values.forEach((value) => {
      url += `&${key}=${encodeURIComponent(value)}`;
    });
  }

  // Redirect ke URL hasil
  window.location.href = url;
}

function showDetailModal(data) {
  const modalGambar = document.getElementById("modalGambar");
  const modalJudul = document.getElementById("modalJudul");
  const modalHarga = document.getElementById("modalHarga");
  const specTable = document.getElementById("modalSpecTable");
  const deskripsiEl = document.getElementById("modalDeskripsi");
  const beliLink = document.getElementById("modalBeliLink");
  const chatLink = document.getElementById("modalChatLink");
  const modalBody = document.querySelector(".modal-body");

  // Isi gambar & judul
  if ((data.gambar && data.gambar.includes("no-image")) || !data.gambar) {
    modalGambar.style.display = "none";
    modalBody.classList.add("full-width");
  } else {
    modalGambar.style.display = "block";
    modalBody.classList.remove("full-width");
    modalGambar.src = data.gambar || "/PWD-Project-Mandiri/asset/no-image.png";
  }

  modalJudul.innerText = `${data.nama} ${data.tahun || ""}`;
  modalHarga.innerText = data.harga;

  // Kosongkan spesifikasi sebelumnya
  specTable.innerHTML = "";

  let specs = [];

  // Tentukan field berdasarkan type
  if (data.type === "kebutuhan") {
    specs = [
      { label: "Jenis", value: data.jenis },
      { label: "Kategori", value: data.kategori },
      { label: "Jumlah", value: data.jumlah },
    ];
  } else {
    specs = [
      { label: "Jenis", value: data.jenis },
      { label: "Merk", value: data.merk },
      { label: "Transmisi", value: data.transmisi },
      { label: "Bahan Bakar", value: data.bahan_bakar },
      { label: "Kondisi", value: data.kondisi },
      { label: "Jumlah Kursi", value: data.jumlah_kursi },
    ];
  }

  // Tambahkan hanya field yang tersedia
  specs.forEach((spec) => {
    if (spec.value && spec.value.toString().trim()) {
      const row = document.createElement("div");
      row.className = "spec-row";
      row.innerHTML = `
        <div class="spec-label">${spec.label}</div>
        <div class="spec-value">${spec.value}</div>
      `;
      specTable.appendChild(row);
    }
  });

  // Deskripsi
  deskripsiEl.innerHTML = `<h3>Deskripsi</h3><p>${
    data.deskripsi || "Tidak tersedia."
  }</p>`;

  // Tombol Aksi
  if (data.type === "kebutuhan") {
    beliLink.href = `/PWD-Project-Mandiri/detail-kebutuhan.php?type=kebutuhan&id=${data.id}`;
    chatLink.href = `https://wa.me/6281808313324?text=Saya%20tertarik%20dengan%20${encodeURIComponent(
      data.nama
    )}.%20Apakah%20masih%20tersedia?`;
  } else {
    beliLink.href = `/PWD-Project-Mandiri/detail-kendaraan.php?type=${data.type}&id=${data.id}`;
    chatLink.href = `https://wa.me/6281808313324?text=Saya%20tertarik%20dengan%20${encodeURIComponent(
      data.nama
    )}.%20Apakah%20masih%20tersedia?`;
  }

  // Tampilkan modal
  const modal = document.getElementById("detailModal");
  modal.classList.add("show");
}

function hideDetailModal() {
  const modal = document.getElementById("detailModal");
  modal.classList.remove("show");

  // Reset isi tabel spesifikasi
  setTimeout(() => {
    document.getElementById("modalSpecTable").innerHTML = "";
  }, 300);
}

// Event listener untuk semua tombol Lihat Detail
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".product-varian-content").forEach((link) => {
    link.addEventListener("click", function () {
      const jsonData = this.getAttribute("data-detail");

      try {
        const data = JSON.parse(jsonData);
        data.type = this.getAttribute("data-type"); // tambahkan type ke data
        showDetailModal(data);
      } catch (e) {
        console.error("Gagal parsing JSON:", e);
        alert("Terjadi kesalahan saat memuat detail.");
      }
    });
  });

  // Tutup modal saat klik di luar konten
  const modal = document.getElementById("detailModal");
  if (modal) {
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        hideDetailModal();
      }
    });
  }
});

function batalkanPesanan(idTransaksi) {
  const konfirmasi = confirm(
    "Apakah Anda yakin ingin membatalkan pesanan ini?"
  );
  if (konfirmasi) {
    window.location.href =
      "/PWD-Project-Mandiri/pesanan/batalkan-pesanan-user.php?id=" +
      idTransaksi;
  }
}

function selesaikanPesanan(idTransaksi) {
  const konfirmasi = confirm(
    "Apakah Anda yakin ingin menyelesaikan pesanan ini?"
  );
  if (konfirmasi) {
    window.location.href =
      "/PWD-Project-Mandiri/pesanan/selesaikan-pesanan.php?id=" + idTransaksi;
  }
}

function hubungiAdmin(noInvoice, namaProduk) {
  // Ambil nama user dari DOM (via hidden input)
  const usernameInput = document.getElementById("username");
  const namaUser = usernameInput ? usernameInput.value : "Pengguna";

  // Pesan panduan untuk user (ditampilkan sebagai info di prompt)
  const contohPesan = "Contoh: Saya ingin membatalkan pesanan ini.";

  // Prompt hanya untuk isi pesan utama
  let isiPesan = prompt(
    "Silakan tulis pesan Anda untuk admin:\n\n" +
      "Nomor Invoice: " +
      noInvoice +
      "\n" +
      "Nama Produk: " +
      namaProduk +
      "\n\n" +
      contohPesan,
    "" // Kosongkan bagian default
  );

  if (isiPesan === null) {
    // User klik Cancel → stop
    return;
  }

  // Validasi apakah isi pesan kosong
  if (!isiPesan || isiPesan.trim() === "") {
    alert("Pesan tidak boleh kosong.");
    return;
  }

  // Format lengkap pesan WhatsApp
  const pesanLengkap = `Hai Admin,

Saya ${namaUser} ingin menyampaikan pesan terkait pesanan saya.

Nomor Invoice: ${noInvoice}
Nama Produk: ${namaProduk}

${isiPesan.trim()}
  
Terima kasih.`;

  // Encode agar bisa dikirim via URL
  const nomorAdmin = "6281808313324"; // Ganti dengan nomor admin
  const urlPesan = encodeURIComponent(pesanLengkap);

  // Buka WhatsApp
  window.open(`https://wa.me/${nomorAdmin}?text=${urlPesan}`, "_blank");
}

function toggleContent(header) {
  const content = header.nextElementSibling;
  const isOpen = content.classList.contains("active");

  // Tutup semua dulu
  document
    .querySelectorAll(".service-content")
    .forEach((el) => el.classList.remove("active"));
  document
    .querySelectorAll(".service-header")
    .forEach((el) => el.classList.remove("open"));

  // Buka yang dipilih
  if (!isOpen) {
    content.classList.add("active");
    header.classList.add("open");
  }
}

function openAccordionFromParam() {
  const urlParams = new URLSearchParams(window.location.search);
  const section = urlParams.get("section");

  if (section) {
    const targetSection = document.getElementById(section);
    if (targetSection) {
      const header = targetSection.querySelector(".service-header");
      if (header) {
        toggleContent(header);
      }
    }
  }
}

// Jalankan saat halaman dimuat
window.addEventListener("DOMContentLoaded", () => {
  openAccordionFromParam();
  window.scrollTo(0, 0); // pastikan tetap di atas
});
