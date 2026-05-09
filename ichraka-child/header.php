<?php
/**
 * Header — direction Joyeux.
 * Remplace complètement le header Astra.
 *
 * Inclut : announce bar + nav sticky.
 *
 * @package Ichraka
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php wp_head(); ?>
</head>
<body <?php body_class( 'ichraka-theme' ); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#site-main">
    <?php esc_html_e( 'Aller au contenu principal', 'ichraka' ); ?>
</a>

<?php // Sprite SVG — chargé une seule fois. ?>
<?php get_template_part( 'template-parts/svg-sprite' ); ?>

<?php // ============ ANNOUNCE BAR ============ ?>
<?php
$announce_message = get_option(
    'ichraka_announce_message',
    __( 'Opération Cartables est lancée — un cartable complet dès <strong>200 DH</strong>.', 'ichraka' )
);
$announce_pill = get_option( 'ichraka_announce_pill', __( 'RENTRÉE 2026', 'ichraka' ) );
$announce_link = get_option( 'ichraka_announce_link', '#donate' );
$show_announce = (bool) get_option( 'ichraka_announce_show', true );
?>
<?php if ( $show_announce && $announce_message ) : ?>
<div class="announce" role="region" aria-label="<?php esc_attr_e( 'Annonce', 'ichraka' ); ?>">
    <?php if ( $announce_pill ) : ?>
        <span class="pill"><?php echo esc_html( $announce_pill ); ?></span>
    <?php endif; ?>
    <span><?php echo wp_kses_post( $announce_message ); ?></span>
    <?php if ( $announce_link ) : ?>
        <a href="<?php echo esc_url( $announce_link ); ?>"><?php esc_html_e( 'Je participe', 'ichraka' ); ?> →</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php // ============ NAV ============ ?>
<nav class="ichraka-nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'ichraka' ); ?>">
    <div class="ichraka-nav-row">

        <?php get_template_part( 'template-parts/logo' ); ?>

        <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_class'     => 'ichraka-nav-links',
                'container'      => false,
                'depth'          => 1,
                'fallback_cb'    => false,
            ) );
        } else {
            // Fallback statique avec ancres locales si aucun menu n'est encore configuré.
            ?>
            <ul class="ichraka-nav-links">
                <li><a href="<?php echo esc_url( home_url( '/qui-sommes-nous/' ) ); ?>"><?php esc_html_e( 'Qui sommes-nous', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/operations/' ) ); ?>"><?php esc_html_e( 'Nos actions', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/parrainage/' ) ); ?>"><?php esc_html_e( 'Parrainage', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/nos-projets/' ) ); ?>"><?php esc_html_e( 'Projets', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/contactez-nous/' ) ); ?>"><?php esc_html_e( 'Contact', 'ichraka' ); ?></a></li>
            </ul>
            <?php
        }
        ?>

        <div class="ichraka-nav-cta">
            <a href="<?php echo esc_url( ichraka_get_donate_url() ); ?>" class="btn btn-primary">
                <?php esc_html_e( 'Faire un don', 'ichraka' ); ?>
                <svg class="arrow" viewBox="0 0 14 14" fill="none" aria-hidden="true"><use href="#ic-arrow"/></svg>
            </a>
            <button type="button" class="ichraka-nav-toggle" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'ichraka' ); ?>" aria-expanded="false">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="4" y1="7" x2="20" y2="7"/>
                    <line x1="4" y1="12" x2="20" y2="12"/>
                    <line x1="4" y1="17" x2="20" y2="17"/>
                </svg>
            </button>
        </div>
    </div>
</nav>

<main id="site-main">
