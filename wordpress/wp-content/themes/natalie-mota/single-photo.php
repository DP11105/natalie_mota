<?php get_header(); ?>

<main class="photo-archive">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article class="photo-item">
            <div class="coté-gauche">
                <!-- Titre du post -->
                <h2><?php the_title(); ?></h2>

                <!-- Champs SCF -->
                    <p>Référence : <?php echo get_post_meta(get_the_ID(), 'reference', true); ?></p>
                    <p>Type : <?php echo get_post_meta(get_the_ID(), 'type', true); ?></p>
        
                
                <!-- Taxonomies (ex: catégorie personnalisée ou "Catégorie de photo") -->
                <div class="taxonomies">
                   <?php
                        // Liste des taxonomies à afficher
                        $taxonomies = ['categorie', 'format']; // remplace par les slugs exacts de tes taxonomies

                        foreach ($taxonomies as $taxonomy) {
                            $terms = get_the_terms(get_the_ID(), $taxonomy);
                            if ($terms && !is_wp_error($terms)) {
                                echo '<div class="taxonomy-block">';
                                echo '<p>' . ucfirst($taxonomy) . ' : ';
                                $term_names = wp_list_pluck($terms, 'name'); // récupère juste les noms
                                echo esc_html(implode(', ', $term_names)); // affiche sur une ligne séparés par des virgules
                                echo '</p>';
                                echo '</div>';
                            }
                        }

                        // Récupération et affichage de l'année de publication
                        $year = get_the_date('Y');
                        echo '<div class="taxonomy-block">';
                        echo '<p>Année : ' . esc_html($year) . '</p>';
                        echo '</div>';
                        
                    ?>
                </div>
            </div>
            <div class="coté-droite">
                <!-- Image mise en avant -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="photo-thumb">
                        <?php the_post_thumbnail('medium'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>

    <?php endwhile; else : ?>
        <p>Aucune photo trouvée.</p>
    <?php endif; ?>
    <div class="concact-btn">
        <p> Cette photo vous intéresse ? </p>
        <a href= "#" class= "contact-single">Contact</a>
    </div>
 
    <div class="autres-photos">
        <p> VOUS AIMEREZ AUSSI</p>

        <?php 
            // 1. Récupère les termes (catégories) du post actuel
            $terms = wp_get_post_terms(get_the_ID(), 'categorie');

            if (!empty($terms) && !is_wp_error($terms)) {
                $term_ids = wp_list_pluck($terms, 'term_id');

                // 2. Arguments de la requête
                $args = array(
                    'post_type' => 'photo',
                    'posts_per_page' => 2,
                    'post__not_in' => array(get_the_ID()), // exclut l'article en cours
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'categorie',
                            'field'    => 'term_id',
                            'terms'    => $term_ids,
                        ),
                    ),
                );

                // 3. La requête personnalisée
                $my_query = new WP_Query($args);

                // 4. Affichage des thumbnails
                if ($my_query->have_posts()) :
                    while ($my_query->have_posts()) : $my_query->the_post();
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medium'); // ou 'thumbnail', 'large' selon ton besoin
                        }
                    endwhile;
                endif;

                // 5. Réinitialisation
                wp_reset_postdata();
            }
        ?>
</main>

<?php get_footer(); ?>