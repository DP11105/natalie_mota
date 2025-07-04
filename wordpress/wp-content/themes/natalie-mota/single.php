<?php get_header(); ?>

<main id="primary" class="site-main">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-meta">
                        <span>Publié le <?php the_date(); ?> par <?php the_author(); ?></span>
                    </div>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    <?php the_category(', '); ?>
                    <?php the_tags('<span class="tag-links">', ', ', '</span>'); ?>
                </footer>
            </article>

            <?php
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }

        endwhile;
    else :
        echo '<p>Aucun article trouvé.</p>';
    endif;
    ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
