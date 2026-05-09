<?php
/**
 * Ichraka — réglages additionnels Astra & ajustements thème.
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Astra — bouton "Faire un don" injecté à droite du menu principal.
 *
 * Compatible avec le hook Astra `astra_masthead_content`. Si Astra n'est pas
 * actif, le hook est simplement ignoré.
 */
add_action( 'astra_masthead_content', 'ichraka_inject_donate_button', 20 );
function ichraka_inject_donate_button() {
    $don_url = ichraka_get_donate_url();
    printf(
        '<a class="ichraka-btn ichraka-btn--donate ichraka-header-donate" href="%1$s">%2$s</a>',
        esc_url( $don_url ),
        esc_html__( 'Faire un don', 'ichraka' )
    );
}

/**
 * Renvoie l'URL de la page "Faire un don".
 *
 * Cherche par slug `faire-un-don`, sinon fallback sur l'option `ichraka_donate_url`.
 *
 * @return string
 */
function ichraka_get_donate_url() {
    $page = get_page_by_path( 'faire-un-don' );
    if ( $page ) {
        return get_permalink( $page );
    }
    return get_option( 'ichraka_donate_url', home_url( '/faire-un-don/' ) );
}

/**
 * Ajoute la balise lang RTL appropriée si Polylang est actif et que la
 * langue courante est l'arabe.
 */
add_filter( 'language_attributes', 'ichraka_language_attributes' );
function ichraka_language_attributes( $output ) {
    if ( function_exists( 'pll_current_language' ) && 'ar' === pll_current_language() ) {
        if ( false === strpos( $output, 'dir=' ) ) {
            $output .= ' dir="rtl"';
        }
    }
    return $output;
}

/**
 * Body classes — ajoute des classes utiles pour le ciblage CSS.
 */
add_filter( 'body_class', 'ichraka_body_classes' );
function ichraka_body_classes( $classes ) {
    $classes[] = 'ichraka-theme';
    if ( is_rtl() ) {
        $classes[] = 'ichraka-rtl';
    }
    if ( is_front_page() ) {
        $classes[] = 'ichraka-home';
    }
    return $classes;
}

/**
 * Désactive les emojis WP pour gagner en performance.
 */
add_action( 'init', 'ichraka_disable_emojis' );
function ichraka_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

/**
 * Enregistre une zone de widget pour le footer.
 */
add_action( 'widgets_init', 'ichraka_register_sidebars' );
function ichraka_register_sidebars() {
    register_sidebar( array(
        'name'          => __( 'Pied de page — colonne 1', 'ichraka' ),
        'id'            => 'ichraka-footer-1',
        'before_widget' => '<div class="ichraka-footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Pied de page — colonne 2', 'ichraka' ),
        'id'            => 'ichraka-footer-2',
        'before_widget' => '<div class="ichraka-footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Pied de page — colonne 3', 'ichraka' ),
        'id'            => 'ichraka-footer-3',
        'before_widget' => '<div class="ichraka-footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
}
