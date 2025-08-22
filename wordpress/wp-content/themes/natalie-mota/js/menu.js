document.addEventListener("DOMContentLoaded", () => {
  const openBtn = document.getElementById("burger-open");
  const closeBtn = document.getElementById("burger-close");
  const menu = document.getElementById("mobile-menu");
  const nav = document.querySelector(".main-nav")

  openBtn.addEventListener("click", () => {
    menu.classList.remove("hidden");
    openBtn.classList.add("hidden");
    closeBtn.classList.remove("hidden");
    nav.classList.add("fixed")
  });

  closeBtn.addEventListener("click", () => {
    menu.classList.add("hidden");
    closeBtn.classList.add("hidden");
    openBtn.classList.remove("hidden");
  });
});