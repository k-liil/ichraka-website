<?php
/**
 * Template Name: Ichraka — Bureau / Équipe
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

            <h2 class="ichraka-section-title"><?php esc_html_e( 'Le bureau', 'ichraka' ); ?></h2>
            <?php echo do_shortcode( '[ichraka_team]' ); ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>
