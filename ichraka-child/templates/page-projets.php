<?php
/**
 * Template Name: Ichraka — Projets
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
ichraka_breadcrumbs();

$projets = new WP_Query( array(
    'post_type'      => 'projet',
    'posts_per_page' => -1,
    'no_found_rows'  => true,
) );
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

            <?php if ( $projets->have_posts() ) : ?>
                <div class="ichraka-projects-grid">
                    <?php while ( $projets->have_posts() ) : $projets->the_post();
                        $status   = get_post_meta( get_the_ID(), '_ichraka_projet_status', true );
                        $obj      = (int) get_post_meta( get_the_ID(), '_ichraka_projet_objectif', true );
                        $coll     = (int) get_post_meta( get_the_ID(), '_ichraka_projet_collecte', true );
                        $progress = $obj > 0 ? min( 100, round( $coll / $obj * 100 ) ) : 0;
                    ?>
                        <article class="ichraka-card ichraka-project">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" class="ichraka-card__media"><?php the_post_thumbnail( 'ichraka-card' ); ?></a>
                            <?php endif; ?>
                            <div class="ichraka-card__body">
                                <h3 class="ichraka-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                                <?php if ( $status ) : ?>
                                    <p style="margin:0 0 0.5rem;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--ichraka-secondary);">
                                        <?php echo esc_html( $status ); ?>
                                    </p>
                                <?php endif; ?>

                                <p class="ichraka-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>

                                <?php if ( $obj > 0 ) : ?>
                                    <div class="ichraka-progress" style="background:#E5E7EB;height:8px;border-radius:4px;overflow:hidden;margin:0.75rem 0 0.5rem;">
                                        <div style="background:var(--ichraka-accent);width:<?php echo esc_attr( $progress ); ?>%;height:100%;transition:width 0.5s ease;"></div>
                                    </div>
                                    <p style="font-size:0.85rem;color:var(--ichraka-text-light);margin:0 0 0.75rem;">
                                        <?php printf(
                                            /* translators: 1: collected amount, 2: target amount */
                                            esc_html__( '%1$s MAD collectés sur %2$s MAD', 'ichraka' ),
                                            number_format_i18n( $coll ),
                                            number_format_i18n( $obj )
                                        ); ?>
                                    </p>
                                <?php endif; ?>

                                <a href="<?php the_permalink(); ?>" class="ichraka-card__link">
                                    <?php esc_html_e( 'Soutenir ce projet →', 'ichraka' ); ?>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            <?php else : ?>
                <p style="text-align:center;color:var(--ichraka-text-light);">
                    <?php esc_html_e( 'Aucun projet à afficher pour le moment.', 'ichraka' ); ?>
                </p>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>
