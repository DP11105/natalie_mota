
var photos = [];
var currentIndex = -1;

function rebuildPhotosArray() {
    photos = [];
    jQuery('#zone-photos .image-galerie').each(function(i) {
        var $link = jQuery(this);
        photos.push({
            src: $link.find('img').attr('src'),
            category: $link.find('.categorie-photo').text(),
            reference: $link.find('.reference-photo').text() || ''
        });
        $link.find('.icone-grand-ecran').data('index', i);
    });
}

jQuery(document).ready(function($) {
    rebuildPhotosArray();
console.log('Photos reconstruits :', photos);
    // Ouverture lightbox
    jQuery(document).on('click', '.icone-grand-ecran', function() {
        var index = jQuery(this).data('index');
        if (index !== undefined) {
            currentIndex = index;
            openLightbox(currentIndex);
            
        }
    });

    function openLightbox(index) {
        var photo = photos[index];
        if (!photo) return;
        $('#lightbox-img').attr('src', photo.src);
        $('#lightbox-category').text(photo.category);
        $('#lightbox-reference').text(photo.reference);
        $('#lightbox-overlay').removeClass('hidden');
        $('#reff-photos').removeClass('reference-photo');
    }

    // Fermeture
    jQuery('#lightbox-close, #lightbox-overlay').on('click', function(e) {
        if(e.target.id === 'lightbox-overlay' || e.target.id === 'lightbox-close'){
            $('#lightbox-overlay').addClass('hidden');
            $('#reff-photos').addClass('reference-photo');
        }
    });

    // Navigation
    jQuery('#lightbox-prev').on('click', function() {
        currentIndex = (currentIndex - 1 + photos.length) % photos.length;
        openLightbox(currentIndex);
    });

    jQuery('#lightbox-next').on('click', function() {
        currentIndex = (currentIndex + 1) % photos.length;
        openLightbox(currentIndex);
    });

    // Navigation clavier
    jQuery(document).on('keydown', function(e) {
        if($('#lightbox-overlay').is(':visible')) {
            if(e.key === "ArrowLeft") $('#lightbox-prev').click();
            if(e.key === "ArrowRight") $('#lightbox-next').click();
            if(e.key === "Escape") $('#lightbox-close').click();
        }
    });
});
