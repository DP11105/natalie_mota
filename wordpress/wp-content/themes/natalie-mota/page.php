<?php
/**
 * Template Name: Default Page Template
 * Description: A standard page template
 */

get_header(); ?>

<main id="primary" class="site-main">

    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    // Pagination si le contenu est divisé en plusieurs pages
                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'your-theme' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>
            </article>

            <?php
        endwhile;
    else :
        get_template_part( 'template-parts/content', 'none' );
    endif;
    ?>

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>