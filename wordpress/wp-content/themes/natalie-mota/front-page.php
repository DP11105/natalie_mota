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

    <div class="select1">
        <select>
            <option value="">CATÉGORIES</option>
             <?php foreach ($categories as $cat): ?>
                <option value="<?php echo esc_attr($cat->slug); ?>">
                    <?php echo esc_html($cat->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select> 
           <option value="">FORMATS</option>
           <?php foreach ($formats as $fmt): ?>
                <option value="<?php echo esc_attr($fmt->slug); ?>">
                    <?php echo esc_html($fmt->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="select2">
        <select>
            <option value="">TRIER PAR</option>
            <?php foreach ($years as $year): ?>
                <option value="<?php echo esc_attr($year); ?>"><?php echo esc_html($year); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="liste-photos">
        <?php
        // 1. Première requête → 8 photos aléatoires
        $args_first = array(
            'post_type'      => 'photo',
            'orderby'        => 'rand',
            'posts_per_page' => 8,
        );

        $my_query_first = new WP_Query($args_first);

        $excluded_ids = array(); // tableau pour stocker les IDs

        echo '<div id="zone-photos" data-excluded="">';

        if ($my_query_first->have_posts()) :
            while ($my_query_first->have_posts()) :
                $my_query_first->the_post();

                // On garde l'ID en mémoire
                $excluded_ids[] = get_the_ID();

                if (has_post_thumbnail()) {
                    echo '<a href="' . get_permalink() . '">';
                    the_post_thumbnail('medium');
                    echo '</a>';
                }

            endwhile;
        endif;

        wp_reset_postdata();

        echo '</div>';

        // On passe les IDs exclus dans un attribut HTML pour le JS
        $excluded_str = implode(',', $excluded_ids);

        echo '<button id="btn-charger" data-excluded="' . esc_attr($excluded_str) . '">Charger plus</button>';
        ?>
        
    </div>
</main>

<?php get_footer(); ?>