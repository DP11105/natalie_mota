<?php wp_footer(); ?>

<?php
wp_nav_menu( array(
    'theme_location' => 'footer',
    'container' => 'nav',
    'menu_class' => 'footer-menu',
) );
?>

<?php get_template_part('template-parts/modale'); ?>
<script>
  if (typeof jQuery !== 'undefined') {
    console.log('jQuery est bien chargé');
  } else {
    console.error('jQuery est ABSENT');
  }
</script>

</body>
</html>

