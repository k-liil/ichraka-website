<?php
/**
 * Ichraka — SEO basique (en complément d'un plugin SEO).
 *
 * Si Yoast SEO ou Rank Math est actif, ces fonctions ne dupliquent pas leur
 * sortie. Sinon elles fournissent un minimum vital : meta description,
 * Open Graph, JSON-LD pour l'organisation et fil d'Ariane.
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Détecte si un plugin SEO connu est actif (pour ne pas dupliquer).
 */
function ichraka_seo_plugin_active() {
    return defined( 'WPSEO_VERSION' )
        || defined( 'RANK_MATH_VERSION' )
        || class_exists( 'AIOSEO\\Plugin\\AIOSEO' );
}

add_action( 'wp_head', 'ichraka_meta_tags', 5 );
function ichraka_meta_tags() {
    if ( ichraka_seo_plugin_active() ) {
        return;
    }

    $title       = wp_get_document_title();
    $description = ichraka_get_meta_description();
    $url         = is_singular() ? get_permalink() : home_url( add_query_arg( null, null ) );
    $image       = '';

    if ( is_singular() && has_post_thumbnail() ) {
        $image = get_the_post_thumbnail_url( get_queried_object_id(), 'large' );
    }

    echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
    echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}

function ichraka_get_meta_description() {
    if ( is_singular() && has_excerpt() ) {
        return wp_strip_all_tags( get_the_excerpt() );
    }
    if ( is_singular() ) {
        $post = get_post();
        if ( $post ) {
            return wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '…' );
        }
    }
    return get_bloginfo( 'description' );
}

/**
 * JSON-LD — schéma "NGO" pour la home et le footer.
 */
add_action( 'wp_head', 'ichraka_jsonld_organization', 6 );
function ichraka_jsonld_organization() {
    if ( ichraka_seo_plugin_active() ) {
        return;
    }

    $data = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'NGO',
        'name'        => 'Association Ichraka',
        'alternateName' => 'Sunrise',
        'url'         => home_url( '/' ),
        'logo'        => esc_url( get_site_icon_url() ?: ICHRAKA_URI . 'assets/images/logo.png' ),
        'description' => __( 'Association à but non lucratif œuvrant pour l\'éducation et la santé des enfants défavorisés au Maroc.', 'ichraka' ),
        'foundingDate'=> '2008-03-27',
        'address'     => array(
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Témara',
            'addressCountry'  => 'MA',
        ),
        'sameAs'      => array(),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}

/**
 * Fil d'Ariane simple, à appeler dans les templates via ichraka_breadcrumbs().
 */
function ichraka_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }

    echo '<nav class="ichraka-breadcrumbs" aria-label="' . esc_attr__( 'Fil d\'Ariane', 'ichraka' ) . '"><div class="ichraka-container">';
    printf(
        '<a href="%s">%s</a> <span aria-hidden="true">›</span> ',
        esc_url( home_url( '/' ) ),
        esc_html__( 'Accueil', 'ichraka' )
    );

    if ( is_singular() ) {
        $post = get_queried_object();

        if ( $post && $post->post_parent ) {
            $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
            foreach ( $ancestors as $anc ) {
                printf(
                    '<a href="%s">%s</a> <span aria-hidden="true">›</span> ',
                    esc_url( get_permalink( $anc ) ),
                    esc_html( get_the_title( $anc ) )
                );
            }
        }

        if ( $post ) {
            echo '<span aria-current="page">' . esc_html( $post->post_title ) . '</span>';
        }
    } elseif ( is_archive() ) {
        echo '<span aria-current="page">' . esc_html( get_the_archive_title() ) . '</span>';
    } elseif ( is_search() ) {
        printf(
            '<span aria-current="page">%s</span>',
            esc_html( sprintf( __( 'Recherche : %s', 'ichraka' ), get_search_query() ) )
        );
    }

    echo '</div></nav>';
}
