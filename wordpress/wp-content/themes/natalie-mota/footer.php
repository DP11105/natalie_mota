<?php wp_footer(); ?>

<?php
wp_nav_menu( array(
    'theme_location' => 'footer',
    'container' => 'nav',
    'menu_class' => 'footer-menu',
) );
?>

<?php get_template_part('template-parts/modale'); ?>

</body>
</html>

