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