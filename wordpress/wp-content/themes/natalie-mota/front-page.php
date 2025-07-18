<?php get_header(); ?>

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

    <img src ="wp-content/themes/natalie-mota/images/Header.png" class="hero-header" alt=" image d'en tete">
</main>

<?php get_footer(); ?>