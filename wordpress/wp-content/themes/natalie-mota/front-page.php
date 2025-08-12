<?php get_header(); ?>
<img src ="wp-content/themes/natalie-mota/images/Header.png" id="h-header" class="hero-header" alt=" image d'en tete">
<main>


    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_title('<h2>', '</h2>');
            the_content();
        endwhile;
    else :
        echo '<p>Aucun contenu à afficher.</p>';
    endif;
    ?>


    
    <div class="liste-photos">
        <?php
            // 1. On définit les arguments pour définir ce que l'on souhaite récupérer
            $args = array(
                'post_type' => 'photo',
                'orderby'        => 'rand',
                'posts_per_page' => 8,
            );

            // 2. On exécute la WP Query
            $my_query = new WP_Query($args);

            // 3. On lance la boucle !
            if ($my_query->have_posts()) : 
                while ($my_query->have_posts()) : 
                    $my_query->the_post();

                    if (has_post_thumbnail()) {
                        echo '<a href="' . get_permalink() . '">';
                        the_post_thumbnail('medium');
                        echo '</a>';
                    }

                endwhile;
            endif;

            // 4. On réinitialise à la requête principale (important)
            wp_reset_postdata();
        ?>
        <a href="#" class="btn-charger"> Charger plus </a>
    </div>
</main>

<?php get_footer(); ?>