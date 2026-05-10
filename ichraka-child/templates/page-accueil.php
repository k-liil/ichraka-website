<?php
/**
 * Template Name: Ichraka — Accueil (Joyeux)
 *
 * Page d'accueil reproduisant fidèlement la direction "Joyeux" :
 * Hero split → Mission → Opérations → Impact/Counters → Story/Timeline →
 * Témoignages → Donate tiers → News → Newsletter.
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<?php // ============ HERO ============ ?>
<header class="ichraka-hero">
    <div class="ichraka-hero-grid">
        <div>
            <span class="eyebrow yellow">★ <?php esc_html_e( 'Asso · 2008 · Maroc', 'ichraka' ); ?></span>
            <h1 class="display ichraka-hero-title">
                <?php
                /* Titre par défaut. Modifiable via Réglages → Général. */
                $hero_title = get_option(
                    'ichraka_hero_title',
                    __( 'Allumons<br>des <span style="white-space:nowrap;"><em>sourires</em>,</span><br>un cartable<br><span class="coral">à la fois.</span>', 'ichraka' )
                );
                echo wp_kses_post( $hero_title );
                ?>
            </h1>
            <p class="ichraka-hero-lead">
                <?php
                $hero_lead = get_option(
                    'ichraka_hero_lead',
                    __( "Depuis dix-huit ans, Ichraka habille, soigne et scolarise les enfants des régions rurales du Maroc — une rentrée joyeuse, une paire de lunettes, un manteau d'hiver à chaque fois.", 'ichraka' )
                );
                echo esc_html( $hero_lead );
                ?>
            </p>
            <div class="ichraka-hero-cta">
                <a href="<?php echo esc_url( ichraka_get_donate_url() ); ?>" class="btn btn-primary">
                    <?php esc_html_e( 'Soutenir un enfant', 'ichraka' ); ?>
                    <svg class="arrow" viewBox="0 0 14 14" aria-hidden="true"><use href="#ic-arrow"/></svg>
                </a>
                <a href="#operations" class="btn btn-ghost"><?php esc_html_e( 'Découvrir nos actions', 'ichraka' ); ?></a>
                <span class="ichraka-hero-cta-note">
                    <svg width="14" height="14" viewBox="0 0 14 14" aria-hidden="true"><use href="#ic-check"/></svg>
                    <?php esc_html_e( '100% des dons au terrain', 'ichraka' ); ?>
                </span>
            </div>
        </div>

        <div class="ichraka-hero-image-wrap">
            <svg class="deco deco-sun" style="color: var(--yellow);" aria-hidden="true"><use href="#ic-sun"/></svg>
            <svg class="deco deco-heart" style="color: var(--coral);" aria-hidden="true"><use href="#ic-heart"/></svg>
            <svg class="deco deco-sparkle" style="color: var(--mint-deep);" aria-hidden="true"><use href="#ic-sparkle"/></svg>
            <svg class="deco deco-squiggle" style="color: var(--sky);" aria-hidden="true"><use href="#ic-squiggle"/></svg>

            <?php
            $hero_image_id = (int) get_option( 'ichraka_hero_image_id', 0 );
            if ( $hero_image_id ) {
                echo wp_get_attachment_image( $hero_image_id, 'ichraka-hero', false, array(
                    'class' => 'ichraka-hero-image',
                    'alt'   => esc_attr__( 'Enfant souriant — Association Ichraka', 'ichraka' ),
                ) );
            } else {
                echo '<div class="ichraka-hero-image" role="img" aria-label="' . esc_attr__( 'Visuel hero', 'ichraka' ) . '"></div>';
            }
            ?>
        </div>
    </div>

    <?php echo do_shortcode( '[ichraka_hero_pillars]' ); ?>
</header>

<?php // ============ MISSION (Notre conviction — portrait + texte) ============ ?>
<?php echo do_shortcode( '[ichraka_mission]' ); ?>

<?php // ============ OPÉRATIONS ============ ?>
<section class="shell" id="operations">
    <div class="section-head">
        <div>
            <span class="eyebrow mint">★ <?php esc_html_e( 'Nos opérations', 'ichraka' ); ?></span>
            <h2 class="display" style="margin-top: 1.4rem;">
                <?php esc_html_e( 'Trois', 'ichraka' ); ?><br>
                <?php esc_html_e( 'rendez-vous,', 'ichraka' ); ?><br>
                <?php esc_html_e( 'chaque année.', 'ichraka' ); ?>
            </h2>
        </div>
        <div class="right">
            <p>
                <?php esc_html_e( "Notre calendrier est rythmé : la rentrée scolaire en septembre, la collecte d'hiver en décembre, la santé visuelle au printemps. Chaque opération mobilise bénévoles, parrains et familles autour d'un geste précis, mesurable, joyeux.", 'ichraka' ); ?>
            </p>
        </div>
    </div>

    <?php echo do_shortcode( '[ichraka_operations limit="3"]' ); ?>
</section>

<?php // ============ IMPACT / COUNTERS ============ ?>
<section id="impact">
    <?php echo do_shortcode( '[ichraka_impact_counters]' ); ?>
</section>

<?php // ============ STORY / TIMELINE ============ ?>
<section class="shell" id="story">
    <div class="section-head">
        <div>
            <span class="eyebrow sky">★ <?php esc_html_e( 'Notre histoire', 'ichraka' ); ?></span>
            <h2 class="display" style="margin-top: 1.4rem;">
                <?php esc_html_e( 'Depuis 2008,', 'ichraka' ); ?><br>
                <?php esc_html_e( 'une lumière', 'ichraka' ); ?><br>
                <?php esc_html_e( 'à chaque rentrée.', 'ichraka' ); ?>
            </h2>
        </div>
        <div class="right">
            <p><?php esc_html_e( "Quatre moments-clés résument dix-huit ans d'engagement : des rencontres, des erreurs corrigées, des opérations qui se reconduisent année après année avec la même joie.", 'ichraka' ); ?></p>
        </div>
    </div>

    <?php echo do_shortcode( '[ichraka_timeline]' ); ?>
</section>

<?php // ============ TÉMOIGNAGES ============ ?>
<?php
$temoignages_count = wp_count_posts( 'temoignage' );
if ( $temoignages_count && $temoignages_count->publish > 0 ) : ?>
<section>
    <div class="testimonials">
        <div class="section-head">
            <div>
                <span class="eyebrow yellow">★ <?php esc_html_e( 'Ils nous font confiance', 'ichraka' ); ?></span>
                <h2 class="display" style="margin-top: 1.4rem;">
                    <?php esc_html_e( 'Voix du', 'ichraka' ); ?><br>
                    <?php esc_html_e( 'terrain.', 'ichraka' ); ?>
                </h2>
            </div>
            <div class="right">
                <p><?php esc_html_e( "Parrains, bénévoles, directrices d'école — celles et ceux qui font Ichraka chaque jour, dans leurs propres mots.", 'ichraka' ); ?></p>
            </div>
        </div>
        <?php echo do_shortcode( '[ichraka_testimonials limit="2"]' ); ?>
    </div>
</section>
<?php endif; ?>

<?php // ============ DONATE TIERS ============ ?>
<section id="donate">
    <?php echo do_shortcode( '[ichraka_donate_block]' ); ?>
</section>

<?php // ============ NEWS ============ ?>
<?php
$news = new WP_Query( array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'no_found_rows'  => true,
) );
if ( $news->have_posts() ) : ?>
<section class="shell" id="actualites">
    <div class="section-head">
        <div>
            <span class="eyebrow coral">★ <?php esc_html_e( 'Actualités', 'ichraka' ); ?></span>
            <h2 class="display" style="margin-top: 1.4rem;">
                <?php esc_html_e( 'Sur le', 'ichraka' ); ?><br>
                <?php esc_html_e( 'terrain.', 'ichraka' ); ?>
            </h2>
        </div>
        <div class="right">
            <p><?php esc_html_e( "Carnets de mission, bilans d'opérations, portraits — la vie de l'association racontée régulièrement.", 'ichraka' ); ?></p>
        </div>
    </div>

    <div class="news-grid">
        <?php while ( $news->have_posts() ) : $news->the_post();
            $cats     = get_the_category();
            $cat_name = ! empty( $cats ) ? $cats[0]->name : __( 'Actualité', 'ichraka' );
        ?>
        <a href="<?php the_permalink(); ?>" class="news-card">
            <div class="news-image">
                <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ichraka-card' ); endif; ?>
            </div>
            <div class="news-body">
                <div class="news-meta">
                    <span class="news-tag"><?php echo esc_html( $cat_name ); ?></span>
                    <span><?php echo esc_html( get_the_date() ); ?></span>
                </div>
                <h3 class="news-title"><?php the_title(); ?></h3>
                <p class="news-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
            </div>
        </a>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>

<?php // ============ NEWSLETTER ============ ?>
<section>
    <?php echo do_shortcode( '[ichraka_newsletter]' ); ?>
</section>

<?php
// Le contenu libre de la page (Gutenberg) est rendu en bonus en fin si non vide.
while ( have_posts() ) : the_post();
    $content = get_the_content();
    if ( trim( $content ) !== '' ) :
?>
<section class="shell">
    <div class="entry-content">
        <?php the_content(); ?>
    </div>
</section>
<?php
    endif;
endwhile;
?>

<?php get_footer(); ?>
