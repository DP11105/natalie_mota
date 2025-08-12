<?php get_header(); ?>

<main class="photo-archive">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article class="photo-item">
            <div class="coté-gauche">
                        <!-- Titre du post -->
                        <h2><?php the_title(); ?></h2>

                        <!-- Champs SCF -->
                            <p class="ref-p" data-ref="<?php echo esc_attr(get_post_meta(get_the_ID(), 'reference', true)); ?>">Référence : <?php echo get_post_meta(get_the_ID(), 'reference', true); ?></p>
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
            <div class="coté-droit">
                <!-- Image mise en avant -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="photo-thumb">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>

    <?php endwhile; else : ?>
        <p>Aucune photo trouvée.</p>
    <?php endif; ?>
    <div class="contact-btn">
        <p> Cette photo vous intéresse ? </p>
        <a href= "#modale-contact" class= "contact-single">Contact</a>
    </div>
 
    <div class="autres-photos">
        <h3> VOUS AIMEREZ AUSSI</h3>
        <div class ="photos">
           <?php include 'template-parts/photo_block.php' ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>