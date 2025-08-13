jQuery(document).ready(function ($) {
    jQuery('#btn-charger').on('click', function () {
        console.log("fichier chargé");
        jQuery.ajax({
            url: mon_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'charger_photos',
                nonce: mon_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    $('#zone-photos').html(response.data.html);
                } else {
                    alert('Erreur: ' + response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Erreur AJAX:', error);
            }
            
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const bouton = document.getElementById("btn-charger");

    bouton.onclick = function () {
        this.classList.add("btn-pas-charger");
    }
})