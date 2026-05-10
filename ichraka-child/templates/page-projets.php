<?php
/**
 * Template Name: Ichraka — Projets (Joyeux)
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$projets = new WP_Query( array(
    'post_type'      => 'projet',
    'posts_per_page' => -1,
    'no_found_rows'  => true,
) );

$status_labels = array(
    'planifie' => __( 'Planifié', 'ichraka' ),
    'en_cours' => __( 'En cours', 'ichraka' ),
    'termine'  => __( 'Terminé', 'ichraka' ),
);
?>

<section class="ichraka-page-section">
    <div class="shell">
        <?php while ( have_posts() ) : the_post(); ?>
            <header class="ichraka-page-header">
                <span class="eyebrow coral"><?php esc_html_e( 'Nos projets', 'ichraka' ); ?></span>
                <h1 class="display" style="margin-top: 1.5rem;"><?php the_title(); ?></h1>
            </header>
            <?php $content = get_the_content();
            if ( trim( $content ) !== '' ) : ?>
                <div class="entry-content"><?php the_content(); ?></div>
            <?php endif; ?>
        <?php endwhile; ?>

        <?php if ( $projets->have_posts() ) : ?>
            <div class="news-grid" style="margin-top: 3rem;">
                <?php while ( $projets->have_posts() ) : $projets->the_post();
                    $status   = get_post_meta( get_the_ID(), '_ichraka_projet_status', true );
                    $obj      = (int) get_post_meta( get_the_ID(), '_ichraka_projet_objectif', true );
                    $coll     = (int) get_post_meta( get_the_ID(), '_ichraka_projet_collecte', true );
                    $progress = $obj > 0 ? min( 100, round( $coll / $obj * 100 ) ) : 0;
                    $status_label = $status_labels[ $status ] ?? '';
                ?>
                    <a href="<?php the_permalink(); ?>" class="news-card">
                        <div class="news-image">
                            <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ichraka-card' ); endif; ?>
                        </div>
                        <div class="news-body">
                            <?php if ( $status_label ) : ?>
                                <div class="news-meta">
                                    <span class="news-tag"><?php echo esc_html( $status_label ); ?></span>
                                </div>
                            <?php endif; ?>
                            <h3 class="news-title"><?php the_title(); ?></h3>
                            <p class="news-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>

                            <?php if ( $obj > 0 ) : ?>
                            <div style="background:var(--line);height:8px;border-radius:999px;overflow:hidden;margin-top:0.5rem;">
                                <div style="background:var(--coral);width:<?php echo esc_attr( $progress ); ?>%;height:100%;border-radius:999px;"></div>
                            </div>
                            <p style="font-size:0.85rem;color:var(--ink-mute);margin-top:0.5rem;">
                                <?php printf(
                                    /* translators: 1: collected, 2: target */
                                    esc_html__( '%1$s / %2$s MAD', 'ichraka' ),
                                    number_format_i18n( $coll ),
                                    number_format_i18n( $obj )
                                ); ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p style="text-align:center;color:var(--ink-mute);font-size:1.1rem;margin-top:3rem;">
                <?php esc_html_e( 'Aucun projet à afficher pour le moment.', 'ichraka' ); ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<section>
    <?php echo do_shortcode( '[ichraka_donate_block]' ); ?>
</section>

<?php get_footer(); ?>
