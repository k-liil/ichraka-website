<?php
/**
 * Template Name: Ichraka — Bureau / Équipe (Joyeux)
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
                <span class="eyebrow yellow"><?php esc_html_e( "L'équipe", 'ichraka' ); ?></span>
                <h1 class="display" style="margin-top: 1.5rem;"><?php the_title(); ?></h1>
            </header>
            <?php $content = get_the_content();
            if ( trim( $content ) !== '' ) : ?>
                <div class="entry-content"><?php the_content(); ?></div>
            <?php endif; ?>
        <?php endwhile; ?>

        <h2 class="display" style="text-align:center;margin-top:4rem;"><?php esc_html_e( 'Le bureau', 'ichraka' ); ?></h2>
        <?php echo do_shortcode( '[ichraka_team]' ); ?>
    </div>
</section>

<?php get_footer(); ?>
