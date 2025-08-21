jQuery(document).ready(function ($) {
    $('#btn-charger').on('click', function () {
        var $btn = $(this);
        var excluded = $btn.attr('data-excluded') || ''; // ATTENTION : utiliser attr, pas data

        $.ajax({
            url: mon_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'charger_photos',
                nonce: mon_ajax.nonce,
                excluded: excluded
            },
            beforeSend: function() {
                $btn.prop('disabled', true).text('Chargement…');
            },
            success: function (response) {
                if (response.success) {
    
                    $('#zone-photos').append(response.data.html);
                    // mettre à jour le data-excluded avec tous les IDs affichés
                    $btn.attr('data-excluded', response.data.excluded);
                    $btn.prop('disabled', false).text('Charger plus');
                    rebuildPhotosArray();
                } else {
                    $btn.prop('disabled', true).text('Aucune autre photo');
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Erreur, réessayer');
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

