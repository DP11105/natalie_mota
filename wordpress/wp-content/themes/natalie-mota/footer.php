<?php wp_footer(); ?>

<?php
wp_nav_menu( array(
    'theme_location' => 'footer',
    'container' => 'nav',
    'menu_class' => 'footer-menu',
) );
?>

<?php get_template_part('template-parts/modale'); ?>

<!-- LIGHTBOX -->
<div id="lightbox-overlay" class="hidden">
  <div class="lightbox-content">
    <span id="lightbox-close">&times;</span>
    <div class="lightbox-img-wrapper">
      <img id="lightbox-img" src="" alt="">
    </div>
    <div class="lightbox-meta">
      <p id="lightbox-category"></p>
      <p id="lightbox-reference"></p>
    </div>
    <div class="lightbox-nav">
      <button id="lightbox-prev"><span class="fleche fleche-gauche">&larr;</span> Précédente</button>
      <button id="lightbox-next">Suivante <span class="fleche fleche-droite">&rarr;</span></button>
    </div>
  </div>
</div>

</body>
</html>

