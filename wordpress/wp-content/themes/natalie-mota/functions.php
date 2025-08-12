<?php
function mon_theme_enfant_enqueue_styles() {
    $parent_style = 'generate-style'; // Nom enregistré dans le thème parent

    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');

    wp_enqueue_style('child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'mon_theme_enfant_enqueue_styles');



function mon_theme_enqueue_scripts() {
    wp_enqueue_script(
        'script.js', // nom interne
        get_stylesheet_directory_uri(). '/js/script.js', // chemin vers le fichier
        array(), // dépendances éventuelles (ex: array('jquery'))
        false, // version (false pour ne pas mettre de version)
        true // true pour le charger dans le footer
    );
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_scripts');

function mon_theme_scripts() {
    // Charger jQuery (si ce n’est pas déjà fait)
    wp_enqueue_script('mon-script', get_template_directory_uri() . '/js/main.js', ['jquery'], null, true);

    wp_localize_script('mon-script', 'mon_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('mon_nonce')
    ]);

}
add_action('wp_enqueue_scripts', 'mon_theme_scripts');

// Ajouter une page d'administration au menu
function nataliemota_add_admin_pages() {
    add_menu_page(
        __('Paramètres du thème NatalieMota', 'nataliemota'), // Titre de la page
        __('NatalieMota', 'nataliemota'),                     // Nom du menu
        'manage_options',                                     // Capacité requise
        'nataliemota-settings',                               // Slug de la page
        'nataliemota_theme_settings',                         // Fonction de callback
        'dashicons-admin-customizer',                         // Icône du menu
        60                                                    // Position dans le menu
    );
}
add_action('admin_menu', 'nataliemota_add_admin_pages');

// Affichage du contenu de la page d'administration
function nataliemota_theme_settings() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p>Bienvenue dans les paramètres du thème <strong>NatalieMota</strong>.</p>

        <form method="post" action="options.php">
            <?php
                settings_fields('nataliemota_settings_group'); // Nom du groupe de réglages
                do_settings_sections('nataliemota-settings');  // ID de la page
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Initialiser les paramètres
function nataliemota_custom_settings() {
    // Enregistrement de l'option
    register_setting('nataliemota_settings_group', 'nataliemota_example_option');

    // Section
    add_settings_section('nataliemota_main_section', 'Paramètres principaux', null, 'nataliemota-settings');

    // Champ
    add_settings_field(
        'nataliemota_example_field',
        'Texte d’exemple',
        'nataliemota_example_field_callback',
        'nataliemota-settings',
        'nataliemota_main_section'
    );
}
add_action('admin_init', 'nataliemota_custom_settings');

// Callback pour afficher le champ
function nataliemota_example_field_callback() {
    $value = esc_attr(get_option('nataliemota_example_option'));
    echo '<input type="text" name="nataliemota_example_option" value="' . $value . '" />';

}

function my_theme_register_menus() {
    register_nav_menus(
        array(
            'primary' => 'Menu principal',
            'footer'  => 'Menu pied de page',
        )
    );
}
add_action( 'after_setup_theme', 'my_theme_register_menus' );

function nataliemota_customize_register($wp_customize) {
    $wp_customize->add_section('nataliemota_header_section', array(
        'title' => 'Options d’en-tête',
        'priority' => 30,
    ));

    $wp_customize->add_setting('nataliemota_header_text', array(
        'default' => 'Bienvenue sur mon site',
    ));

    $wp_customize->add_control('nataliemota_header_text', array(
        'label' => 'Texte d’en-tête',
        'section' => 'nataliemota_header_section',
        'type' => 'text',
    ));
}
add_action('customize_register', 'nataliemota_customize_register');