<?php get_header(); ?>

<main>
    <h1>Bienvenue sur la page d’accueil</h1>

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
</main>

<?php get_footer(); ?>