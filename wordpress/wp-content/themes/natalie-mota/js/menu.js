document.addEventListener("DOMContentLoaded", () => {
  const openBtn = document.getElementById("burger-open");
  const closeBtn = document.getElementById("burger-close");
  const menu = document.getElementById("mobile-menu");
  const body = document.body
  const con = document.querySelector(".contact");

  openBtn.addEventListener("click", () => {
    menu.classList.remove("hidden");
    openBtn.classList.add("hidden");
    closeBtn.classList.remove("hidden");
    body.classList.add("no-scroll");
  });

  closeBtn.addEventListener("click", () => {
    menu.classList.add("hidden");
    closeBtn.classList.add("hidden");
    openBtn.classList.remove("hidden");
    body.classList.remove("no-scroll");
  });

  con.addEventListener("click", () => {
    menu.classList.add("hidden");
    closeBtn.classList.add("hidden");
    openBtn.classList.remove("hidden");
    body.classList.remove("no-scroll");
  });
});


 