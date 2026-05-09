<?php
/**
 * Ichraka Child Theme — bootstrap functions.
 *
 * Charge les modules : enqueue, theme setup, custom post types, widgets,
 * shortcodes et hooks de personnalisation Astra.
 *
 * @package Ichraka
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'ICHRAKA_VERSION', '1.0.0' );
define( 'ICHRAKA_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'ICHRAKA_URI', trailingslashit( get_stylesheet_directory_uri() ) );

require_once ICHRAKA_DIR . 'inc/theme-setup.php';
require_once ICHRAKA_DIR . 'inc/enqueue.php';
require_once ICHRAKA_DIR . 'inc/custom-post-types.php';
require_once ICHRAKA_DIR . 'inc/widgets.php';
require_once ICHRAKA_DIR . 'inc/shortcodes.php';
require_once ICHRAKA_DIR . 'inc/template-loader.php';
require_once ICHRAKA_DIR . 'inc/seo.php';

/**
 * Add child theme custom logic that should run on `after_setup_theme`.
 */
add_action( 'after_setup_theme', 'ichraka_after_setup_theme' );
function ichraka_after_setup_theme() {
    load_child_theme_textdomain( 'ichraka', ICHRAKA_DIR . 'languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption' ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );

    // Tailles d'images dédiées au site Ichraka.
    add_image_size( 'ichraka-hero', 1920, 800, true );
    add_image_size( 'ichraka-card', 720, 405, true );
    add_image_size( 'ichraka-thumb', 360, 240, true );

    // Menus dédiés.
    register_nav_menus( array(
        'primary' => esc_html__( 'Menu principal', 'ichraka' ),
        'footer'  => esc_html__( 'Menu pied de page', 'ichraka' ),
        'social'  => esc_html__( 'Réseaux sociaux', 'ichraka' ),
    ) );
}
