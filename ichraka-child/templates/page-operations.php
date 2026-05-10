<?php
/**
 * Template Name: Ichraka — Liste des opérations (Joyeux)
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<section class="ichraka-page-section">
    <div class="shell">

        <?php while ( have_posts() ) : the_post(); ?>
            <header class="ichraka-page-header">
                <span class="eyebrow mint"><?php esc_html_e( 'Nos opérations', 'ichraka' ); ?></span>
                <h1 class="display" style="margin-top: 1.5rem;"><?php the_title(); ?></h1>
                <?php $excerpt = get_the_excerpt();
                if ( $excerpt ) : ?>
                    <p class="lead"><?php echo esc_html( $excerpt ); ?></p>
                <?php endif; ?>
            </header>
            <?php $content = get_the_content();
            if ( trim( $content ) !== '' ) : ?>
                <div class="entry-content"><?php the_content(); ?></div>
            <?php endif; ?>
        <?php endwhile; ?>

        <?php echo do_shortcode( '[ichraka_operations limit="-1"]' ); ?>

    </div>
</section>

<section>
    <?php echo do_shortcode( '[ichraka_donate_block]' ); ?>
</section>

<?php get_footer(); ?>
