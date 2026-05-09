<?php
/**
 * Ichraka — chargement des scripts et styles (direction Joyeux).
 *
 * Polices : Bricolage Grotesque (display) + Manrope (corps) + Cairo (RTL).
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_enqueue_scripts', 'ichraka_enqueue_assets', 20 );
function ichraka_enqueue_assets() {

    // Astra (parent) — on conserve la base, mais notre style.css masque son header/footer.
    if ( wp_get_theme()->parent() ) {
        wp_enqueue_style(
            'astra-parent',
            get_template_directory_uri() . '/style.css',
            array(),
            wp_get_theme()->parent()->get( 'Version' )
        );
    }

    // Google Fonts — Bricolage Grotesque + Manrope + Cairo (pour RTL).
    wp_enqueue_style(
        'ichraka-fonts',
        'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=Manrope:wght@400;500;600;700;800&family=Cairo:wght@400;600;700&display=swap',
        array(),
        ICHRAKA_VERSION
    );

    // Style enfant principal.
    wp_enqueue_style(
        'ichraka-child',
        get_stylesheet_uri(),
        array( 'astra-parent', 'ichraka-fonts' ),
        ICHRAKA_VERSION
    );

    // Composants additionnels.
    wp_enqueue_style(
        'ichraka-components',
        ICHRAKA_URI . 'assets/css/components.css',
        array( 'ichraka-child' ),
        ICHRAKA_VERSION
    );

    // RTL — surcharges spécifiques.
    if ( is_rtl() ) {
        wp_enqueue_style(
            'ichraka-rtl',
            ICHRAKA_URI . 'assets/css/rtl.css',
            array( 'ichraka-child' ),
            ICHRAKA_VERSION
        );
    }

    // JS — compteurs animés, tier toggle, scroll fluide.
    wp_enqueue_script(
        'ichraka-main',
        ICHRAKA_URI . 'assets/js/ichraka.js',
        array(),
        ICHRAKA_VERSION,
        true
    );

    wp_localize_script( 'ichraka-main', 'IchrakaConfig', array(
        'restUrl'   => esc_url_raw( rest_url() ),
        'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
        'nonce'     => wp_create_nonce( 'ichraka-front' ),
        'isRtl'     => is_rtl(),
        'donateUrl' => ichraka_get_donate_url(),
    ) );
}

/**
 * Preconnect aux origines de polices.
 */
add_action( 'wp_head', 'ichraka_preconnect_fonts', 1 );
function ichraka_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

/**
 * Styles dans l'éditeur Gutenberg.
 */
add_action( 'after_setup_theme', 'ichraka_editor_styles' );
function ichraka_editor_styles() {
    add_editor_style( array(
        'style.css',
        'assets/css/components.css',
        'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=Manrope:wght@400;500;600;700;800&display=swap',
    ) );
}
