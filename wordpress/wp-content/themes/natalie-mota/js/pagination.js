jQuery(document).ready(function($) {
  const container = $(".custom-pagination");

  // Récupérer et parser le JSON
  let slidesRaw = container.attr("data-slides");
  let slides = [];
  try {
    slides = JSON.parse(slidesRaw);
  } catch(e) {
    console.error("Erreur JSON :", e, slidesRaw);
  }

  let currentIndex = parseInt(container.attr("data-index"), 10) || 0;

  const $img  = $("#slider-img");
  const $link = $("#slider-link");

  console.log("Slides disponibles :", slides);
  console.log("Index de départ :", currentIndex);

  function updateSlide() {
    if (!slides.length) return;
    const slide = slides[currentIndex];
    console.log("Affichage du slide :", slide);

    $img.attr("src", slide.src).attr("alt", slide.title);
    $link.attr("href", slide.permalink);
  }

  // Flèche gauche
  $(".pagin-fleche-gau").on("click", function() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateSlide();
  });

  // Flèche droite
  $(".pagin-fleche-droir").on("click", function() {
    currentIndex = (currentIndex + 1) % slides.length;
    updateSlide();
  });

  // Initialisation (au cas où l'image affichée doit être recalée sur slides[index])
  updateSlide();
});