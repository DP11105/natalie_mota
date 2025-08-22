<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php bloginfo('name'); ?> <?php wp_title('|'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
     

  <!-- Icône fermeture -->
 
    <nav class="main-nav">
      
      <div class="logo">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Logo.png" alt="Logo">
      </div>
      <button id="burger-open" class="burger-btn">
        <i class="fa-solid fa-bars"></i>
      </button>
      <button id="burger-close" class="burger-btn hidden">
        <i class="fa-solid fa-xmark"></i>
      </button>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class' => 'main-menu'
        ));
        ?>
      <div id="mobile-menu" class="hidden"> 
         
         <ul>
          <li><a href="">Accueil</a></li>
          <li><a href="a-propos">A propos</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
     
    </nav>
</header>