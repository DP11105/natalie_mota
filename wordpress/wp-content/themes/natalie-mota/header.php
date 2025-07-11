<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php bloginfo('name'); ?> <?php wp_title('|'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header>
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Logo.png" class="logo" alt="Logo">
</header>

<?php
wp_nav_menu(array(
  'theme_location' => 'primary',
  'container' => 'nav',
  'menu_class' => 'main-menu'
));
?>

<?php
echo '<!-- Header ENFANT chargé -->';
?>