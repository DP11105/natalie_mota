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
                console.log("Réponse AJAX :", response);
                if (response.success) {
                    
                    $('#zone-photos').html(response.data.html);
                    rebuildPhotosArray();
                    
                } else {
                    $('#zone-photos').html('<p>Aucune image trouvée.</p>');
                }
            }
        });
    });

});

