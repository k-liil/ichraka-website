<?php
/**
 * Ichraka — Custom Post Types & taxonomies.
 *
 * Trois CPT publics :
 *  - operation  : Opérations solidaires (Cartables, Vêtements, Lunettes…)
 *  - projet     : Projets en cours
 *  - partenaire : Partenaires & donateurs
 *  - temoignage : Témoignages
 *
 * Tous sont gutenberg-ready (show_in_rest), avec icônes Dashicons,
 * labels en français et permaliens propres.
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'ichraka_register_post_types' );
function ichraka_register_post_types() {

    /* ---------- Opérations ---------- */
    register_post_type( 'operation', array(
        'labels' => array(
            'name'               => __( 'Opérations', 'ichraka' ),
            'singular_name'      => __( 'Opération', 'ichraka' ),
            'add_new'            => __( 'Ajouter', 'ichraka' ),
            'add_new_item'       => __( 'Ajouter une opération', 'ichraka' ),
            'edit_item'          => __( 'Modifier l\'opération', 'ichraka' ),
            'all_items'          => __( 'Toutes les opérations', 'ichraka' ),
            'menu_name'          => __( 'Opérations', 'ichraka' ),
        ),
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => 'operations',
        'menu_icon'          => 'dashicons-heart',
        'menu_position'      => 20,
        'rewrite'            => array( 'slug' => 'operations', 'with_front' => false ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes' ),
        'taxonomies'         => array( 'operation_annee' ),
    ) );

    /* ---------- Projets ---------- */
    register_post_type( 'projet', array(
        'labels' => array(
            'name'               => __( 'Projets', 'ichraka' ),
            'singular_name'      => __( 'Projet', 'ichraka' ),
            'add_new_item'       => __( 'Ajouter un projet', 'ichraka' ),
            'edit_item'          => __( 'Modifier le projet', 'ichraka' ),
            'all_items'          => __( 'Tous les projets', 'ichraka' ),
            'menu_name'          => __( 'Projets', 'ichraka' ),
        ),
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => 'projets',
        'menu_icon'          => 'dashicons-portfolio',
        'menu_position'      => 21,
        'rewrite'            => array( 'slug' => 'projets', 'with_front' => false ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
    ) );

    /* ---------- Partenaires ---------- */
    register_post_type( 'partenaire', array(
        'labels' => array(
            'name'               => __( 'Partenaires', 'ichraka' ),
            'singular_name'      => __( 'Partenaire', 'ichraka' ),
            'add_new_item'       => __( 'Ajouter un partenaire', 'ichraka' ),
            'all_items'          => __( 'Tous les partenaires', 'ichraka' ),
            'menu_name'          => __( 'Partenaires', 'ichraka' ),
        ),
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 22,
        'rewrite'            => array( 'slug' => 'partenaires', 'with_front' => false ),
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
    ) );

    /* ---------- Témoignages ---------- */
    register_post_type( 'temoignage', array(
        'labels' => array(
            'name'               => __( 'Témoignages', 'ichraka' ),
            'singular_name'      => __( 'Témoignage', 'ichraka' ),
            'add_new_item'       => __( 'Ajouter un témoignage', 'ichraka' ),
            'all_items'          => __( 'Tous les témoignages', 'ichraka' ),
            'menu_name'          => __( 'Témoignages', 'ichraka' ),
        ),
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-format-quote',
        'menu_position'      => 23,
        'rewrite'            => array( 'slug' => 'temoignages', 'with_front' => false ),
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
    ) );

    /* ---------- Taxonomies ---------- */
    register_taxonomy( 'operation_annee', 'operation', array(
        'label'             => __( 'Année', 'ichraka' ),
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => array( 'slug' => 'annee' ),
    ) );
}

/**
 * Ajoute des champs meta simples (rôle, citation, lien partenaire) via la
 * REST API, exposés dans Gutenberg via meta block bindings.
 */
add_action( 'init', 'ichraka_register_post_meta' );
function ichraka_register_post_meta() {

    register_post_meta( 'temoignage', '_ichraka_author_role', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );

    register_post_meta( 'partenaire', '_ichraka_partenaire_url', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'esc_url_raw',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );

    register_post_meta( 'projet', '_ichraka_projet_status', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );

    register_post_meta( 'projet', '_ichraka_projet_objectif', array(
        'type'         => 'integer',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'absint',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );

    register_post_meta( 'projet', '_ichraka_projet_collecte', array(
        'type'         => 'integer',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'absint',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
}

/**
 * Métabox simple en éditeur classique pour saisir les meta sans plugin tiers.
 */
add_action( 'add_meta_boxes', 'ichraka_register_metaboxes' );
function ichraka_register_metaboxes() {
    add_meta_box(
        'ichraka_temoignage_meta',
        __( 'Détails du témoignage', 'ichraka' ),
        'ichraka_render_temoignage_metabox',
        'temoignage',
        'side'
    );
    add_meta_box(
        'ichraka_partenaire_meta',
        __( 'Site web partenaire', 'ichraka' ),
        'ichraka_render_partenaire_metabox',
        'partenaire',
        'side'
    );
    add_meta_box(
        'ichraka_projet_meta',
        __( 'Suivi du projet', 'ichraka' ),
        'ichraka_render_projet_metabox',
        'projet',
        'side'
    );
}

function ichraka_render_temoignage_metabox( $post ) {
    wp_nonce_field( 'ichraka_save_meta', 'ichraka_meta_nonce' );
    $role = get_post_meta( $post->ID, '_ichraka_author_role', true );
    printf(
        '<p><label for="ichraka_role">%s</label><br><input type="text" id="ichraka_role" name="_ichraka_author_role" value="%s" class="widefat"></p>',
        esc_html__( 'Rôle / fonction', 'ichraka' ),
        esc_attr( $role )
    );
}

function ichraka_render_partenaire_metabox( $post ) {
    wp_nonce_field( 'ichraka_save_meta', 'ichraka_meta_nonce' );
    $url = get_post_meta( $post->ID, '_ichraka_partenaire_url', true );
    printf(
        '<p><label for="ichraka_url">%s</label><br><input type="url" id="ichraka_url" name="_ichraka_partenaire_url" value="%s" class="widefat" placeholder="https://..."></p>',
        esc_html__( 'URL du site partenaire', 'ichraka' ),
        esc_attr( $url )
    );
}

function ichraka_render_projet_metabox( $post ) {
    wp_nonce_field( 'ichraka_save_meta', 'ichraka_meta_nonce' );
    $status   = get_post_meta( $post->ID, '_ichraka_projet_status', true );
    $obj      = get_post_meta( $post->ID, '_ichraka_projet_objectif', true );
    $collecte = get_post_meta( $post->ID, '_ichraka_projet_collecte', true );

    $statuses = array(
        'planifie'  => __( 'Planifié', 'ichraka' ),
        'en_cours'  => __( 'En cours', 'ichraka' ),
        'termine'   => __( 'Terminé', 'ichraka' ),
    );
    echo '<p><label>' . esc_html__( 'Statut', 'ichraka' ) . '</label><br><select name="_ichraka_projet_status" class="widefat">';
    foreach ( $statuses as $key => $label ) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr( $key ),
            selected( $status, $key, false ),
            esc_html( $label )
        );
    }
    echo '</select></p>';

    printf(
        '<p><label>%s (MAD)</label><br><input type="number" name="_ichraka_projet_objectif" value="%s" class="widefat" min="0" step="100"></p>',
        esc_html__( 'Objectif financier', 'ichraka' ),
        esc_attr( $obj )
    );
    printf(
        '<p><label>%s (MAD)</label><br><input type="number" name="_ichraka_projet_collecte" value="%s" class="widefat" min="0" step="100"></p>',
        esc_html__( 'Montant collecté', 'ichraka' ),
        esc_attr( $collecte )
    );
}

add_action( 'save_post', 'ichraka_save_post_meta' );
function ichraka_save_post_meta( $post_id ) {
    if ( ! isset( $_POST['ichraka_meta_nonce'] ) || ! wp_verify_nonce( $_POST['ichraka_meta_nonce'], 'ichraka_save_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array(
        '_ichraka_author_role'    => 'sanitize_text_field',
        '_ichraka_partenaire_url' => 'esc_url_raw',
        '_ichraka_projet_status'  => 'sanitize_text_field',
        '_ichraka_projet_objectif' => 'absint',
        '_ichraka_projet_collecte' => 'absint',
    );

    foreach ( $fields as $key => $sanitizer ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, call_user_func( $sanitizer, wp_unslash( $_POST[ $key ] ) ) );
        }
    }
}

/**
 * Active les permaliens propres après activation du thème (flush rules).
 */
add_action( 'after_switch_theme', 'ichraka_flush_rewrite_rules' );
function ichraka_flush_rewrite_rules() {
    ichraka_register_post_types();
    flush_rewrite_rules();
}
