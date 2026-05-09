<?php
/**
 * Ichraka — chargement des templates de page personnalisés.
 *
 * Permet aux administrateurs de sélectionner un template depuis l'éditeur
 * (Attributs de la page → Modèle).
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Liste les templates disponibles.
 *
 * @return array<string,string>
 */
function ichraka_get_page_templates() {
    return array(
        'templates/page-accueil.php'    => __( 'Ichraka — Accueil', 'ichraka' ),
        'templates/page-operations.php' => __( 'Ichraka — Liste des opérations', 'ichraka' ),
        'templates/page-don.php'        => __( 'Ichraka — Faire un don', 'ichraka' ),
        'templates/page-contact.php'    => __( 'Ichraka — Contact', 'ichraka' ),
        'templates/page-equipe.php'     => __( 'Ichraka — Bureau / Équipe', 'ichraka' ),
        'templates/page-projets.php'    => __( 'Ichraka — Projets', 'ichraka' ),
    );
}

add_filter( 'theme_page_templates', 'ichraka_register_page_templates' );
function ichraka_register_page_templates( $templates ) {
    return array_merge( $templates, ichraka_get_page_templates() );
}

add_filter( 'template_include', 'ichraka_load_page_template', 99 );
function ichraka_load_page_template( $template ) {
    if ( ! is_page() ) {
        return $template;
    }

    $custom = get_page_template_slug( get_queried_object_id() );
    if ( $custom && array_key_exists( $custom, ichraka_get_page_templates() ) ) {
        $candidate = ICHRAKA_DIR . $custom;
        if ( file_exists( $candidate ) ) {
            return $candidate;
        }
    }

    return $template;
}
