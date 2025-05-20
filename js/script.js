document.addEventListener("DOMContentLoaded", function () {
  initBannerSlider();
  initCardSlider();
  initLocationPlaceholder();
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

// =======================
// Placeholder Lokasi
// =======================
function initLocationPlaceholder() {
  const locationDropdown = document.getElementById("location-dropdown");
  const searchInput = document.getElementById("search-input");

  if (!locationDropdown || !searchInput) return;

  function updatePlaceholder() {
    const selectedLocation =
      locationDropdown.options[locationDropdown.selectedIndex].text;
    searchInput.placeholder = `Temukan Mobil di ${selectedLocation}...`;
  }

  updatePlaceholder();
  locationDropdown.addEventListener("change", updatePlaceholder);
}
