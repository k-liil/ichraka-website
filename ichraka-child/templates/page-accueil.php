<?php
/**
 * Template Name: Ichraka — Accueil
 *
 * Page d'accueil avec hero, actions phares, compteurs, témoignages,
 * appel aux dons. Le contenu de la page (the_content) est inséré entre
 * les sections de présentation et les actualités, ce qui permet à un
 * éditeur non-développeur d'ajouter du contenu intermédiaire dans
 * Gutenberg sans toucher au PHP.
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main ichraka-home-main">

    <?php /* Hero */ ?>
    <?php echo do_shortcode( '[ichraka_hero title="' . esc_attr( get_bloginfo( 'name' ) ) . '" tagline="' . esc_attr__( 'Ensemble, nous pouvons créer le changement', 'ichraka' ) . '"]' ); ?>

    <?php /* Actions phares */ ?>
    <section class="ichraka-section ichraka-section--soft">
        <div class="ichraka-container">
            <h2 class="ichraka-section-title"><?php esc_html_e( 'Nos actions phares', 'ichraka' ); ?></h2>
            <p class="ichraka-section-lead" style="text-align:center;max-width:720px;margin:0 auto 2rem;">
                <?php esc_html_e( 'Depuis 2008, l\'Association Ichraka œuvre pour l\'éducation et la santé des enfants défavorisés au Maroc, à travers trois opérations annuelles emblématiques.', 'ichraka' ); ?>
            </p>
            <?php echo do_shortcode( '[ichraka_operations limit="3"]' ); ?>
            <p style="text-align:center;margin-top:2rem;">
                <a class="ichraka-btn ichraka-btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'operation' ) ?: home_url( '/operations/' ) ); ?>">
                    <?php esc_html_e( 'Voir toutes nos opérations', 'ichraka' ); ?>
                </a>
            </p>
        </div>
    </section>

    <?php /* Compteurs */ ?>
    <?php echo do_shortcode( '[ichraka_counters]' ); ?>

    <?php /* Contenu libre depuis l'éditeur Gutenberg */ ?>
    <?php while ( have_posts() ) : the_post(); ?>
        <?php $content = get_the_content(); if ( trim( $content ) !== '' ) : ?>
        <section class="ichraka-section">
            <div class="ichraka-container ichraka-container--narrow">
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
    <?php endwhile; ?>

    <?php /* Appel aux dons */ ?>
    <section class="ichraka-section ichraka-section--soft" id="don">
        <div class="ichraka-container">
            <div class="ichraka-donation-block">
                <h2><?php esc_html_e( 'Soutenez nos actions', 'ichraka' ); ?></h2>
                <p>
                    <?php esc_html_e( 'Chaque don, quel que soit son montant, contribue directement à offrir un cartable, des vêtements chauds ou des soins à un enfant. Aidez-nous à poursuivre notre mission.', 'ichraka' ); ?>
                </p>
                <p>
                    <?php echo do_shortcode( '[ichraka_donate_button text="' . esc_attr__( 'Faire un don maintenant', 'ichraka' ) . '"]' ); ?>
                </p>
                <p style="font-size:0.9rem;color:#666;margin-top:1rem;">
                    <?php esc_html_e( '100% transparent — Bilans annuels publiés', 'ichraka' ); ?>
                </p>
            </div>
        </div>
    </section>

    <?php /* Témoignages */ ?>
    <?php
    $temoignages_count = wp_count_posts( 'temoignage' );
    if ( $temoignages_count && $temoignages_count->publish > 0 ) : ?>
    <section class="ichraka-section">
        <div class="ichraka-container">
            <h2 class="ichraka-section-title"><?php esc_html_e( 'Ils nous font confiance', 'ichraka' ); ?></h2>
            <?php echo do_shortcode( '[ichraka_testimonials limit="3"]' ); ?>
        </div>
    </section>
    <?php endif; ?>

    <?php /* Dernières actualités */ ?>
    <?php
    $news = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'no_found_rows'  => true,
    ) );
    if ( $news->have_posts() ) : ?>
    <section class="ichraka-section ichraka-section--gray">
        <div class="ichraka-container">
            <h2 class="ichraka-section-title"><?php esc_html_e( 'Actualités', 'ichraka' ); ?></h2>
            <div class="ichraka-actions-grid">
                <?php while ( $news->have_posts() ) : $news->the_post(); ?>
                    <article class="ichraka-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="ichraka-card__media">
                                <?php the_post_thumbnail( 'ichraka-card' ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="ichraka-card__body">
                            <h3 class="ichraka-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="ichraka-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="ichraka-card__link">
                                <?php esc_html_e( 'Lire la suite →', 'ichraka' ); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php wp_reset_postdata(); endif; ?>

</main>

<?php get_footer(); ?>
