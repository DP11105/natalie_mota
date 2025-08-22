<?php

function nataliemota_enqueue_styles() {
    wp_enqueue_style(
        'nataliemota-style',
        get_stylesheet_uri(), // récupère le style.css du thème actif
        array(),              // dépendances éventuelles
        filemtime(get_stylesheet_directory() . '/style.css') // version dynamique pour éviter le cache
    );
}
add_action('wp_enqueue_scripts', 'nataliemota_enqueue_styles');




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



// Dans functions.php ou via wp_enqueue_script()
function enqueue_custom_pagination_script() {
    wp_enqueue_script(
        'pagination-js',
        get_stylesheet_directory_uri() . '/js/pagination.js',
        ['jquery'],
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_custom_pagination_script');


function mon_theme_enqueue_script() {
    wp_enqueue_script(
        'lightbox', // identifiant unique (pas d'extension .js)
        get_stylesheet_directory_uri() . '/js/lightbox.js', 
        array('jquery'), // dépendances (si ton script utilise jQuery)
        false, // version (tu peux mettre filemtime() pour éviter le cache)
        true   // charger dans le footer
    );
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_script');

function mon_theme_enqueue_scrip() {
    wp_enqueue_script(
        'menu.js', // nom interne
        get_stylesheet_directory_uri(). '/js/menu.js', // chemin vers le fichier
        array(), // dépendances éventuelles (ex: array('jquery'))
        false, // version (false pour ne pas mettre de version)
        true // true pour le charger dans le footer
    );
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_scrip');



// Enqueue + localisation (assure-toi que le chemin du JS est correct)
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'filtres-photos',
        get_stylesheet_directory_uri() . '/js/filtres.js',
        array('jquery'),
        null,
        true
    );

    wp_localize_script('filtres-photos', 'mon_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('filtrer_photos_nonce'),
    ));
});

// Hooks AJAX
add_action('wp_ajax_filtrer_photos', 'filtrer_photos_callback');
add_action('wp_ajax_nopriv_filtrer_photos', 'filtrer_photos_callback');

function filtrer_photos_callback() {
    //check_ajax_referer('filtrer_photos_nonce', 'nonce');
    $categorie = isset($_POST['categorie']) ? sanitize_text_field($_POST['categorie']) : '';
    $format    = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $year      = isset($_POST['year']) ? intval($_POST['year']) : '';
    $paged     = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $args = [
        'post_type'      => 'photo',
        'post_status'    => 'publish',
        'posts_per_page' => 8,
        'paged'          => $paged,
    ];

    if ($categorie) {
        $args['tax_query'][] = [
            'taxonomy' => 'categorie',
            'field'    => 'slug',
            'terms'    => $categorie,
        ];
    }

    if ($format) {
        $args['tax_query'][] = [
            'taxonomy' => 'format',
            'field'    => 'slug',
            'terms'    => $format,
        ];
    }

    if ($year) {
        $args['date_query'][] = [
            'year' => $year,
        ];
    }

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            if (has_post_thumbnail()) {
                $year_post = get_the_date('Y');
                $categories = get_the_terms(get_the_ID(), 'categorie');
                $categories_list = '';

                if ($categories && !is_wp_error($categories)) {
                    $cats = wp_list_pluck($categories, 'name');
                    $categories_list = implode(', ', $cats);
                }

                $html .= '  <div
                            class="image-galerie" 
                            data-year="' . esc_attr($year_post) . '">';

                $html .= get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'images-galerie']);

                $html .= '<div class="overlay">
                            <h3 class="titre-photo">' . esc_html(get_the_title()) . '</h3>
                            <p class="categorie-photo">' . esc_html($categories_list) . '</p>
                            <p id="reff-photos" class="reference-photo">' . get_post_meta(get_the_ID(), 'reference', true) . '</p>
                            <div class="icones">
                                <a href="' . esc_url(get_permalink()) . '" class="icone-oeil"><i class="fa-regular fa-eye"></i></a>
                                  <span class="icone-grand-ecran"><i class="fa fa-expand"></i></span>
                            </div>
                          </div>';

                $html .= '</div>';
            }
        }
    } else {
        $html = '<p>Aucune photo trouvée.</p>';
    }
    wp_reset_postdata();

    wp_send_json_success([
        'html'  => $html,
        'debug' => [
            'categorie' => $categorie,
            'format'    => $format,
            'year'      => $year,
            'paged'     => $paged,
            'args'      => $args,
        ]
    ]);
}


// Enqueue JS + localisation
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'charger-photos',
        get_stylesheet_directory_uri() . '/js/ajax-photos.js',
        array('jquery'),
        null,
        true
    );

    wp_localize_script('charger-photos', 'mon_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('charger_photos_nonce'),
    ));
});

// Actions AJAX
add_action('wp_ajax_charger_photos', 'charger_photos');
add_action('wp_ajax_nopriv_charger_photos', 'charger_photos');

function charger_photos() {
    //check_ajax_referer('charger_photos_nonce', 'nonce');

    // IDs déjà affichés
    $excluded_ids = !empty($_POST['excluded']) ? array_map('intval', explode(',', $_POST['excluded'])) : [];

    // Récupère 8 photos suivantes
    $args = array(
        'post_type'      => 'photo',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post__not_in'   => $excluded_ids,
        'orderby'        => 'rand'
        
    );

    
    $query = new WP_Query($args);
    ob_start();
    $new_ids = [];

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            if (has_post_thumbnail()) {
                $new_ids[] = get_the_ID();
                $year = get_the_date('Y');
                $categories = get_the_terms(get_the_ID(), 'categorie');
                $categories_list = '';
                if ($categories && !is_wp_error($categories)) {
                    $cats = wp_list_pluck($categories, 'name');
                    $categories_list = implode(', ', $cats);
                }
               
                echo '<div class="image-galerie" data-year="' . esc_attr($year) . '">';
                echo get_the_post_thumbnail(get_the_ID(), 'large', array('class' => 'images-galerie'));
                echo '<div class="overlay">
                        <h3 class="titre-photo">' . esc_html(get_the_title()) . '</h3>
                        <p class="categorie-photo">' . esc_html($categories_list) . '</p> 
                        <p id="reff-photos" class="reference-photo">' . get_post_meta(get_the_ID(), 'reference', true) . '</p>
                        <div class="icones">
                            <a href="' . esc_url(get_permalink()) . '" class="icone-oeil"><i class="fa-regular fa-eye"></i></a>
                            <span class="icone-grand-ecran"><i class="fa fa-expand"></i></span>
                        </div>
                      </div>';
                echo '</div>';
            }
            
        endwhile;
    endif;

    // On fusionne l'ancien excluded avec les new_ids
    $excluded_final = array_merge($excluded_ids, $new_ids);

    wp_send_json_success(array(
        'html'     => ob_get_clean(),
        'excluded' => implode(',', $excluded_final),
    ));
}


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

function mytheme_enqueue_font_awesome() {
        wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', array(), '6.5.2', 'all' );
    }

add_action('wp_enqueue_scripts', 'mytheme_enqueue_font_awesome');