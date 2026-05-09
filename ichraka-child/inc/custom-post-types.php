<?php
/**
 * Ichraka — Custom Post Types & taxonomies (direction Joyeux).
 *
 * CPT publics :
 *  - operation  : Opérations solidaires (Cartables, Vêtements, Lunettes…)
 *                 + meta : accent_color, icon, season, stats (3×)
 *  - projet     : Projets en cours
 *  - partenaire : Partenaires & donateurs
 *  - temoignage : Témoignages
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'ichraka_register_post_types' );
function ichraka_register_post_types() {

    /* Opérations */
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

    /* Projets */
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

    /* Partenaires */
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

    /* Témoignages */
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

    /* Taxonomy année */
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
 * Enregistre les meta pour la REST API (Gutenberg).
 */
add_action( 'init', 'ichraka_register_post_meta' );
function ichraka_register_post_meta() {

    register_post_meta( 'temoignage', '_ichraka_author_role', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );

    register_post_meta( 'partenaire', '_ichraka_partenaire_url', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'esc_url_raw',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );

    register_post_meta( 'projet', '_ichraka_projet_status', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
    register_post_meta( 'projet', '_ichraka_projet_objectif', array(
        'type'              => 'integer',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'absint',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
    register_post_meta( 'projet', '_ichraka_projet_collecte', array(
        'type'              => 'integer',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'absint',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );

    /* Opération — direction Joyeux */
    register_post_meta( 'operation', '_ichraka_op_accent', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
    register_post_meta( 'operation', '_ichraka_op_icon', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
    register_post_meta( 'operation', '_ichraka_op_season', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
    // Stats stockées comme array sérialisé : [ ['value' => '300', 'label' => 'cartables / an'], ... ]
}

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
    add_meta_box(
        'ichraka_op_meta',
        __( 'Détails de l\'opération (Joyeux)', 'ichraka' ),
        'ichraka_render_op_metabox',
        'operation',
        'normal',
        'high'
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

function ichraka_render_op_metabox( $post ) {
    wp_nonce_field( 'ichraka_save_meta', 'ichraka_meta_nonce' );

    $accent = get_post_meta( $post->ID, '_ichraka_op_accent', true );
    $icon   = get_post_meta( $post->ID, '_ichraka_op_icon', true );
    $season = get_post_meta( $post->ID, '_ichraka_op_season', true );
    $stats  = get_post_meta( $post->ID, '_ichraka_op_stats', true );
    if ( ! is_array( $stats ) ) {
        $stats = array(
            array( 'value' => '', 'label' => '' ),
            array( 'value' => '', 'label' => '' ),
            array( 'value' => '', 'label' => '' ),
        );
    }
    $stats = array_pad( $stats, 3, array( 'value' => '', 'label' => '' ) );

    $accents = array(
        ''             => __( 'Auto (suit l\'ordre)', 'ichraka' ),
        'accent-yellow'=> __( 'Jaune', 'ichraka' ),
        'accent-coral' => __( 'Corail', 'ichraka' ),
        'accent-mint'  => __( 'Menthe', 'ichraka' ),
        'accent-sky'   => __( 'Ciel', 'ichraka' ),
    );
    $icons = array(
        ''           => __( 'Auto', 'ichraka' ),
        'ic-bag'     => __( 'Cartable', 'ichraka' ),
        'ic-jacket'  => __( 'Vêtement', 'ichraka' ),
        'ic-glasses' => __( 'Lunettes', 'ichraka' ),
        'ic-school'  => __( 'École', 'ichraka' ),
        'ic-action'  => __( 'Action', 'ichraka' ),
        'ic-people'  => __( 'Personnes', 'ichraka' ),
        'ic-coin'    => __( 'Don', 'ichraka' ),
    );
    $seasons = array(
        ''            => __( 'Auto', 'ichraka' ),
        'Rentrée'     => __( 'Rentrée', 'ichraka' ),
        'Hiver'       => __( 'Hiver', 'ichraka' ),
        'Printemps'   => __( 'Printemps', 'ichraka' ),
        'Été'         => __( 'Été', 'ichraka' ),
        'Annuel'      => __( 'Annuel', 'ichraka' ),
    );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="ichraka_op_accent"><?php esc_html_e( 'Couleur d\'accent', 'ichraka' ); ?></label></th>
            <td>
                <select id="ichraka_op_accent" name="_ichraka_op_accent">
                    <?php foreach ( $accents as $k => $v ) : ?>
                        <option value="<?php echo esc_attr( $k ); ?>" <?php selected( $accent, $k ); ?>><?php echo esc_html( $v ); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="ichraka_op_icon"><?php esc_html_e( 'Icône', 'ichraka' ); ?></label></th>
            <td>
                <select id="ichraka_op_icon" name="_ichraka_op_icon">
                    <?php foreach ( $icons as $k => $v ) : ?>
                        <option value="<?php echo esc_attr( $k ); ?>" <?php selected( $icon, $k ); ?>><?php echo esc_html( $v ); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="ichraka_op_season"><?php esc_html_e( 'Saison', 'ichraka' ); ?></label></th>
            <td>
                <select id="ichraka_op_season" name="_ichraka_op_season">
                    <?php foreach ( $seasons as $k => $v ) : ?>
                        <option value="<?php echo esc_attr( $k ); ?>" <?php selected( $season, $k ); ?>><?php echo esc_html( $v ); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Statistiques (3 max)', 'ichraka' ); ?></th>
            <td>
                <?php for ( $i = 0; $i < 3; $i++ ) : ?>
                    <p>
                        <input type="text" name="_ichraka_op_stats[<?php echo $i; ?>][value]"
                               value="<?php echo esc_attr( $stats[ $i ]['value'] ?? '' ); ?>"
                               placeholder="<?php esc_attr_e( 'Valeur (ex: 300)', 'ichraka' ); ?>"
                               style="width: 30%;">
                        <input type="text" name="_ichraka_op_stats[<?php echo $i; ?>][label]"
                               value="<?php echo esc_attr( $stats[ $i ]['label'] ?? '' ); ?>"
                               placeholder="<?php esc_attr_e( 'Libellé (ex: cartables / an)', 'ichraka' ); ?>"
                               style="width: 60%;">
                    </p>
                <?php endfor; ?>
            </td>
        </tr>
    </table>
    <?php
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

    $simple_fields = array(
        '_ichraka_author_role'       => 'sanitize_text_field',
        '_ichraka_partenaire_url'    => 'esc_url_raw',
        '_ichraka_projet_status'     => 'sanitize_text_field',
        '_ichraka_projet_objectif'   => 'absint',
        '_ichraka_projet_collecte'   => 'absint',
        '_ichraka_op_accent'         => 'sanitize_text_field',
        '_ichraka_op_icon'           => 'sanitize_text_field',
        '_ichraka_op_season'         => 'sanitize_text_field',
    );

    foreach ( $simple_fields as $key => $sanitizer ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, call_user_func( $sanitizer, wp_unslash( $_POST[ $key ] ) ) );
        }
    }

    // Stats — array structuré
    if ( isset( $_POST['_ichraka_op_stats'] ) && is_array( $_POST['_ichraka_op_stats'] ) ) {
        $clean = array();
        foreach ( wp_unslash( $_POST['_ichraka_op_stats'] ) as $stat ) {
            $value = sanitize_text_field( $stat['value'] ?? '' );
            $label = sanitize_text_field( $stat['label'] ?? '' );
            if ( $value !== '' || $label !== '' ) {
                $clean[] = array( 'value' => $value, 'label' => $label );
            }
        }
        update_post_meta( $post_id, '_ichraka_op_stats', $clean );
    }
}

/* Active les permaliens propres à l'activation. */
add_action( 'after_switch_theme', 'ichraka_flush_rewrite_rules' );
function ichraka_flush_rewrite_rules() {
    ichraka_register_post_types();
    flush_rewrite_rules();
}
