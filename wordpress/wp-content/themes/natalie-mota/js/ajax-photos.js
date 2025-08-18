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
                     console.log("Excluded envoyé :", excluded);
        console.log("Excluded reçu :", response.data.excluded);
                    $('#zone-photos').append(response.data.html);
                    // mettre à jour le data-excluded avec tous les IDs affichés
                    $btn.attr('data-excluded', response.data.excluded);
                    $btn.prop('disabled', false).text('Charger plus');
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
        this.classList.add("btn-pas-charge");
    }
})

jQuery(function($) {

    // === FILTRE ANNEE EN PUR JS ===
    $('#filter-year').on('change', function() {
        var selectedYear = $(this).val();
        $('.image-galerie').each(function() {
            if (!selectedYear || $(this).data('year') == selectedYear) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

// === FILTRE AJAX POUR TAXONOMIES ===
    $('#filter-categorie, #filter-format').on('change', function() {
        var categorie = $('#filter-categorie').val();
        var format    = $('#filter-format').val();

        $.ajax({
            url: mon_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'filtrer_photos',
                nonce: mon_ajax.nonce,
                categorie: categorie,
                format: format
            },
            beforeSend: function() {
                $('#zone-photos').html('<p>Chargement...</p>');
            },
            success: function(response) {
                if (response.success) {
                    $('#zone-photos').html(response.data.html);
                } else {
                    $('#zone-photos').html('<p>Aucune image trouvée.</p>');
                }
            }
        });
    });

});

