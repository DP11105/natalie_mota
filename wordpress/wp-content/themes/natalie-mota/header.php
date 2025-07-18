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
    
    <nav class="main-nav">
      <div class="logo">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Logo.png" alt="Logo">
      </div>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class' => 'main-menu'
        ));
        ?>
    </nav>
</header>