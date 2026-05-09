<?php
/**
 * Template Name: Ichraka — Liste des opérations
 *
 * Affiche le contenu de la page suivi de la grille de toutes les opérations.
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
ichraka_breadcrumbs();
?>

<main id="primary" class="site-main">

    <section class="ichraka-section">
        <div class="ichraka-container">

            <?php while ( have_posts() ) : the_post(); ?>
                <header class="entry-header" style="text-align:center;margin-bottom:2.5rem;">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                <div class="entry-content ichraka-container--narrow" style="margin:0 auto 3rem;">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; ?>

            <?php echo do_shortcode( '[ichraka_operations limit="-1"]' ); ?>

        </div>
    </section>

    <section class="ichraka-section ichraka-section--soft">
        <div class="ichraka-container" style="text-align:center;">
            <h2><?php esc_html_e( 'Aidez-nous à poursuivre nos actions', 'ichraka' ); ?></h2>
            <p style="max-width:600px;margin:0 auto 1.5rem;">
                <?php esc_html_e( 'Chaque don nous permet d\'aider un enfant supplémentaire. Rejoignez-nous dans cette belle aventure solidaire.', 'ichraka' ); ?>
            </p>
            <?php echo do_shortcode( '[ichraka_donate_button]' ); ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>
