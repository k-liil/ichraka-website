<?php
/**
 * Ichraka — chargement des scripts et styles.
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue parent + child styles, Google Fonts et JS du thème.
 */
add_action( 'wp_enqueue_scripts', 'ichraka_enqueue_assets', 20 );
function ichraka_enqueue_assets() {
    // Parent (Astra) — on s'appuie sur le système Astra pour ne pas dupliquer.
    if ( wp_get_theme()->parent() ) {
        wp_enqueue_style(
            'astra-parent',
            get_template_directory_uri() . '/style.css',
            array(),
            wp_get_theme()->parent()->get( 'Version' )
        );
    }

    // Google Fonts — chargement async via preconnect.
    wp_enqueue_style(
        'ichraka-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:wght@400;600;700&family=Cairo:wght@400;600;700&display=swap',
        array(),
        ICHRAKA_VERSION
    );

    // Style enfant principal (style.css à la racine du thème enfant).
    wp_enqueue_style(
        'ichraka-child',
        get_stylesheet_uri(),
        array( 'astra-parent', 'ichraka-fonts' ),
        ICHRAKA_VERSION
    );

    // Style additionnel modulaire (composants).
    wp_enqueue_style(
        'ichraka-components',
        ICHRAKA_URI . 'assets/css/components.css',
        array( 'ichraka-child' ),
        ICHRAKA_VERSION
    );

    // RTL — chargé uniquement si nécessaire.
    if ( is_rtl() ) {
        wp_enqueue_style(
            'ichraka-rtl',
            ICHRAKA_URI . 'assets/css/rtl.css',
            array( 'ichraka-child' ),
            ICHRAKA_VERSION
        );
    }

    // JS — compteurs animés, scroll, accessibilité.
    wp_enqueue_script(
        'ichraka-main',
        ICHRAKA_URI . 'assets/js/ichraka.js',
        array(),
        ICHRAKA_VERSION,
        true
    );

    wp_localize_script( 'ichraka-main', 'IchrakaConfig', array(
        'restUrl'    => esc_url_raw( rest_url() ),
        'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
        'nonce'      => wp_create_nonce( 'ichraka-front' ),
        'isRtl'      => is_rtl(),
    ) );
}

/**
 * Preconnect aux origines de polices pour réduire le CLS.
 */
add_action( 'wp_head', 'ichraka_preconnect_fonts', 1 );
function ichraka_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

/**
 * Injecte les styles CSS dans l'éditeur Gutenberg pour cohérence visuelle.
 */
add_action( 'after_setup_theme', 'ichraka_editor_styles' );
function ichraka_editor_styles() {
    add_editor_style( array(
        'style.css',
        'assets/css/components.css',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:wght@400;600;700&display=swap',
    ) );
}
