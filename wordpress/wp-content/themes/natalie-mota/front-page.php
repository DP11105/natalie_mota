<?php get_header(); ?>
<img src ="wp-content/themes/natalie-mota/images/Header.png" id="h-header" class="hero-header" alt=" image d'en tete">
<main>


    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
    else :
        echo '<p>Aucun contenu à afficher.</p>';
    endif;
    ?>

    <?php
    // Récupérer tous les termes de la taxonomy "catégorie"
    $categories = get_terms(array(
        'taxonomy'   => 'categorie', // slug de ta taxonomy
        'hide_empty' => false        // false = même si aucun post associé
    ));

    // Récupérer tous les termes de la taxonomy "format"
    $formats = get_terms(array(
        'taxonomy'   => 'format', // slug de ta taxonomy
        'hide_empty' => false
    ));
    ?>
    
    <?php
        $years = array();

        $query = new WP_Query(array(
            'post_type'      => 'photo',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids', // plus léger, on ne récupère que les IDs
        ));

        if ($query->have_posts()) {
            foreach ($query->posts as $post_id) {
                $year = get_the_date('Y', $post_id); // récupère l'année du post
                if ($year) {
                    $years[] = $year;
                }
            }
        }

        wp_reset_postdata();

        $years = array_unique($years); // supprime doublons
        rsort($years); // tri décroissant
    ?>
    <div class="select0">
        <div class="select1">
            <select id="filter-categorie">
                <option value="">CATÉGORIES</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo esc_attr($cat->slug); ?>">
                        <?php echo esc_html($cat->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select id="filter-format">  
            <option value="">FORMATS</option>
            <?php foreach ($formats as $fmt): ?>
                    <option value="<?php echo esc_attr($fmt->slug); ?>">
                        <?php echo esc_html($fmt->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="select2">
            <select id="filter-year">
                <option value="">TRIER PAR</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?php echo esc_attr($year); ?>"><?php echo esc_html($year); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php
// 1) Zone d'affichage initiale
echo '<div id="zone-photos">';

$images = new WP_Query([
    'post_type'      => 'photo',
    'post_status'    => 'publish',
    'posts_per_page' => 8,
    
]);

$excluded_ids = [];

if ($images->have_posts()) :
    while ($images->have_posts()) : $images->the_post();
        if (has_post_thumbnail()) {
            $excluded_ids[] = get_the_ID();
            $year = get_the_date('Y');

            // Récupération des catégories (taxonomy "categorie")
            $categories = get_the_terms(get_the_ID(), 'categorie');
            $categories_list = '';
            if ($categories && !is_wp_error($categories)) {
                $cats = wp_list_pluck($categories, 'name');
                $categories_list = implode(', ', $cats);
            }

            echo '<div
                    class="image-galerie" 
                    data-year="' . esc_attr($year) . '">';

            // Image
            the_post_thumbnail('large', array('class' => 'images-galerie'));

            // Overlay au survol
            echo '<div class="overlay">
                    <h3 class="titre-photo">' . esc_html(get_the_title()) . '</h3>
                    <p class="categorie-photo">' . esc_html($categories_list) . '</p>
                    <p id="reff-photos" class="reference-photo">' . get_post_meta(get_the_ID(), 'reference', true) . '</p>
                    <div class="icones">
                        <a href="' . esc_url(get_permalink()) . '"  class="icone-oeil"><i class="fa-regular fa-eye"></i></a>
                        <span class="icone-grand-ecran"><i class="fa fa-expand"></i></span>
                    </div>
                  </div>';

            echo '</div>';
        }
    endwhile;
endif;
wp_reset_postdata();

echo '</div>'; // #zone-photos

// 2) Bouton "Charger plus" avec la liste d'IDs déjà affichés
$excluded_str = implode(',', $excluded_ids);
echo '<div class ="btn-content">';
echo '<button id="btn-charger" data-excluded="' . esc_attr($excluded_str) . '">Charger plus</button>';
echo '</div>';

?>

</main>

<?php get_footer(); ?>