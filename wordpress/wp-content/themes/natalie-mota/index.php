<?php
// Appelle l’en-tête
get_header(); 
?>

<main>
    <?php
    // Boucle WordPress
    if ( have_posts() ) :
        while ( have_posts() ) : the_post(); ?>
            <article>
                <h2><?php the_title(); ?></h2>
                <div>
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile;
    else : ?>
        <p>Aucun contenu trouvé.</p>
    <?php endif; ?>
</main>

<?php
// Appelle le pied de page
get_footer(); 
?>