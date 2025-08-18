document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById("contactModal");
    var btn = document.getElementById("menu-item-61");
    var span = modal.querySelector(".close");

    btn.onclick = function () {
        modal.style.display = "block";
    }

    span.onclick = function () {
        modal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var modals = document.getElementById("contactModal");
    var btnn = document.querySelector(".contact-single");
    var spans = modals.querySelector(".close");
    var refs = jQuery(".ref-p").data("ref");

    btnn.onclick = function () {
        modals.style.display = "block";
        jQuery(".ref-contact").val(refs);
    }

    spans.onclick = function () {
        modals.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target === modals) {
            modals.style.display = "none";
        }
    }

  
});



    