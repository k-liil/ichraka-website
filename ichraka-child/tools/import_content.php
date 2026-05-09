<?php
/**
 * Ichraka — script d'import de contenu (WP-CLI), direction Joyeux.
 *
 * Idempotent — peut être relancé sans dupliquer.
 *
 * USAGE :
 *   wp eval-file wp-content/themes/ichraka-child/tools/import_content.php
 *   wp eval-file wp-content/themes/ichraka-child/tools/import_content.php --reset
 *
 * @package Ichraka
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
    fwrite( STDERR, "Ce script doit être exécuté via WP-CLI.\n" );
    exit( 1 );
}

$reset = in_array( '--reset', (array) ( $args ?? [] ), true ); // phpcs:ignore

WP_CLI::log( '════════════════════════════════════════════════════════' );
WP_CLI::log( '  Ichraka — Import du contenu (direction Joyeux)' );
WP_CLI::log( '════════════════════════════════════════════════════════' );

/* -------------------------------------------------------------------------
 * Helpers
 * -----------------------------------------------------------------------*/
function ichraka_upsert_post( array $data, $reset = false ) {
    $existing = get_page_by_path( $data['post_name'], OBJECT, $data['post_type'] );

    if ( $existing && $reset ) {
        wp_delete_post( $existing->ID, true );
        $existing = null;
        WP_CLI::log( "  → supprimé l'ancien : {$data['post_name']}" );
    }

    if ( $existing ) {
        $data['ID'] = $existing->ID;
        wp_update_post( $data );
        WP_CLI::log( "  ↻ mis à jour : {$data['post_title']} (#{$existing->ID})" );
        return $existing->ID;
    }

    $id = wp_insert_post( $data, true );
    if ( is_wp_error( $id ) ) {
        WP_CLI::warning( "Échec : {$data['post_title']} — " . $id->get_error_message() );
        return 0;
    }
    WP_CLI::log( "  ✓ créé : {$data['post_title']} (#{$id})" );
    return $id;
}

function ichraka_set_parent( $child_slug, $parent_slug, $post_type = 'page' ) {
    $child  = get_page_by_path( $child_slug, OBJECT, $post_type );
    $parent = get_page_by_path( $parent_slug, OBJECT, $post_type );
    if ( $child && $parent ) {
        wp_update_post( array( 'ID' => $child->ID, 'post_parent' => $parent->ID ) );
    }
}

/* -------------------------------------------------------------------------
 * 1) Pages principales
 * -----------------------------------------------------------------------*/
WP_CLI::log( "\n[1/4] Création des pages principales…" );

$pages = array(
    array(
        'post_title'   => 'Accueil',
        'post_name'    => 'accueil',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => '<p>L\'Association Ichraka œuvre depuis 2008 pour l\'éducation et la santé des enfants défavorisés au Maroc.</p>',
        'meta_input'   => array( '_wp_page_template' => 'templates/page-accueil.php' ),
    ),
    array(
        'post_title'   => 'Qui sommes-nous',
        'post_name'    => 'qui-sommes-nous',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Ichraka — <em>« la lumière qui se lève »</em> en arabe — est née en 2008 d'un constat simple : à quelques kilomètres des grandes villes, des enfants quittent l'école faute d'un cartable, d'une vue corrigée, d'un vêtement chaud pour tenir l'hiver.</p><p>Trois opérations annuelles, un calendrier joyeux, un seul objectif : que chaque enfant que nous croisons ait sa chance de s'asseoir en classe avec ce qu'il faut pour apprendre.</p>",
    ),
    array(
        'post_title'   => 'Historique',
        'post_name'    => 'historique',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Suite à une visite organisée à l'orphelinat Dar Al Atfal de Témara en octobre 2007, un groupe de bénévoles a décidé de se réunir pour aider ces enfants. Le <strong>27 mars 2008, l'Association Ichraka voit le jour.</strong></p><p>Au départ : cours de soutien, journées culturelles et éducatives, activités sportives. Depuis, trois opérations annuelles structurent notre action : Cartables, Vêtements, Lunettes.</p>",
    ),
    array(
        'post_title'   => 'Notre mission',
        'post_name'    => 'notre-mission',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Entreprendre ou contribuer à la réalisation de projets couvrant les domaines de l'éducation et de la santé de l'enfance défavorisée.</p><h3>Nos principaux objectifs</h3><ul><li>Soutien moral et financier aux orphelins</li><li>Scolarisation et lutte contre la déperdition scolaire</li><li>Soins sanitaires et accès à la santé</li><li>Amélioration des conditions de vie des familles démunies</li><li>Développement du sens de la solidarité</li></ul>",
    ),
    array(
        'post_title'   => 'Nos objectifs',
        'post_name'    => 'nos-objectifs',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<ul><li>Apporter un soutien moral et financier aux enfants en besoin</li><li>Participer à l'éducation et à la scolarisation</li><li>Lutter contre la déperdition scolaire</li><li>Mener des actions de soins sanitaires</li><li>Améliorer les conditions de vie de familles démunies</li><li>Développer le sens de la solidarité</li></ul><p>Nos ambitions ne se limitent pas à la réalisation de ces objectifs mais visent leur continuité par l'autonomie des entités bénéficiaires.</p>",
    ),
    array(
        'post_title'   => 'Notre charte',
        'post_name'    => 'notre-charte',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>L'Association Ichraka s'engage sur ces valeurs :</p><ul><li><strong>Indépendance</strong> — apolitique et sans allégeance partisane</li><li><strong>Transparence</strong> — comptes rendus publics annuels des opérations</li><li><strong>Bénévolat</strong> — engagement volontaire et désintéressé</li><li><strong>Respect</strong> — dignité des bénéficiaires, confidentialité de leurs données</li><li><strong>Continuité</strong> — viser l'autonomie des entités aidées</li></ul>",
    ),
    array(
        'post_title'   => 'Communication interne',
        'post_name'    => 'communication-interne',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<h3>Entre les membres de la même équipe</h3><ul><li>Toute information doit transiter par l'ensemble de l'équipe</li><li>Réunion uniquement si la majorité de l'équipe est disponible</li><li>Favoriser la communication verbale aux échanges email</li><li>CR des réunions validés par les membres avant diffusion</li><li>Google Calendar partagé pour les dates</li></ul>",
    ),
    array(
        'post_title'   => 'Responsabilités des membres',
        'post_name'    => 'responsabilites',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<h3>Tout membre doit :</h3><ul><li>Organiser ses propres priorités et s'engager dans la mesure du possible</li><li>Assumer ses tâches de manière professionnelle</li><li>Aider à la recherche de financements</li><li>Respecter la confidentialité des données</li><li>Respecter les objectifs de l'association</li><li>Donner une meilleure image de l'association</li></ul><h3>Toute équipe doit :</h3><ul><li>Préparer un planning annuel d'actions</li><li>Arrêter le budget prévisionnel annuel</li><li>Maintenir une base documentaire</li></ul>",
    ),
    array(
        'post_title'   => 'Nos actions',
        'post_name'    => 'nos-actions',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Ichraka concentre son activité sur deux types d'actions :</p><ul><li><strong>Actions annuelles</strong> — sur toute l'année scolaire (cours de soutien, suivi médical)</li><li><strong>Actions ponctuelles</strong> — Cartables, Vêtements, Lunettes</li></ul>[ichraka_operations limit=\"-1\"]",
    ),
    array(
        'post_title'   => 'Opérations',
        'post_name'    => 'operations',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Trois rendez-vous, chaque année — un calendrier joyeux et mesurable.</p>",
        'meta_input'   => array( '_wp_page_template' => 'templates/page-operations.php' ),
    ),
    array(
        'post_title'   => 'Parrainage',
        'post_name'    => 'parrainage',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Le parrainage est un engagement durable au profit d'un enfant : prise en charge de ses frais de scolarité, fournitures, suivi médical, accompagnement éducatif.</p><p>Pour devenir parrain ou marraine, contactez-nous via le formulaire de contact. Nous vous mettrons en relation avec un enfant et vous tiendrons informé(e) chaque trimestre.</p>",
    ),
    array(
        'post_title'   => 'Nos projets',
        'post_name'    => 'nos-projets',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Découvrez l'ensemble des projets en cours et passés portés par l'Association Ichraka.</p>",
        'meta_input'   => array( '_wp_page_template' => 'templates/page-projets.php' ),
    ),
    array(
        'post_title'   => 'Contactez-nous',
        'post_name'    => 'contactez-nous',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Une question ? Une proposition de partenariat ? Un don à organiser ? Écrivez-nous via le formulaire ci-dessous.</p>",
        'meta_input'   => array( '_wp_page_template' => 'templates/page-contact.php' ),
    ),
    array(
        'post_title'   => 'Faire un don',
        'post_name'    => 'faire-un-don',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Votre soutien permet à Ichraka de poursuivre ses actions auprès des enfants défavorisés. Chaque contribution, quel que soit son montant, fait la différence.</p>",
        'meta_input'   => array( '_wp_page_template' => 'templates/page-don.php' ),
    ),
);

foreach ( $pages as $p ) {
    ichraka_upsert_post( $p, $reset );
}

$qui_children = array( 'historique', 'notre-mission', 'nos-objectifs', 'notre-charte', 'communication-interne', 'responsabilites' );
foreach ( $qui_children as $slug ) {
    ichraka_set_parent( $slug, 'qui-sommes-nous' );
}

$accueil = get_page_by_path( 'accueil' );
if ( $accueil ) {
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $accueil->ID );
}

/* -------------------------------------------------------------------------
 * 2) Opérations (CPT) avec stats Joyeux
 * -----------------------------------------------------------------------*/
WP_CLI::log( "\n[2/4] Création des opérations (avec stats)…" );

$operations = array(
    array(
        'data' => array(
            'post_title'   => 'Opération Cartables',
            'post_name'    => 'operation-cartables',
            'post_type'    => 'operation',
            'post_status'  => 'publish',
            'post_excerpt' => "Un cartable complet — fournitures, manuels, blouse — offert à près de 300 enfants des régions rurales pour chaque rentrée scolaire depuis 2008.",
            'menu_order'   => 1,
            'post_content' => "<p>La pauvreté constitue le principal obstacle à la scolarisation et la principale cause de déperdition scolaire en milieu rural.</p><p>Le but de cette action est d'<strong>offrir un cartable complet</strong> (trousse, cahiers, manuels, ardoise) à près de <strong>300 enfants des régions rurales démunies</strong> pour chaque rentrée scolaire depuis 2008.</p><p><em>Toute contribution est la bienvenue pour aider ces enfants à poursuivre leurs études.</em></p>",
        ),
        'meta' => array(
            '_ichraka_op_accent' => 'accent-yellow',
            '_ichraka_op_icon'   => 'ic-bag',
            '_ichraka_op_season' => 'Rentrée',
            '_ichraka_op_stats'  => array(
                array( 'value' => '300', 'label' => 'cartables / an' ),
                array( 'value' => '5',   'label' => 'écoles' ),
                array( 'value' => '18',  'label' => 'éditions' ),
            ),
        ),
    ),
    array(
        'data' => array(
            'post_title'   => 'Opération Vêtements',
            'post_name'    => 'operation-vetements',
            'post_type'    => 'operation',
            'post_status'  => 'publish',
            'post_excerpt' => "Manteaux, bonnets, chaussures fermées au profit des orphelins de Dar Al Atfal et des élèves d'Al Hawamid — neufs, à la bonne taille, livrés avant les premières neiges.",
            'menu_order'   => 2,
            'post_content' => "<p>Depuis <strong>2007</strong>, l'association Ichraka organise une collecte de fonds pour l'achat de <strong>vêtements d'hiver</strong> au profit des orphelins de Dar Al Atfal et des élèves d'Al Hawamid, à Témara.</p><p>Une tenue neuve et de bonne qualité (pantalon, pull, chaussures) est offerte à chaque enfant.</p><p><em>200 ou 300 dh suffisent pour faire plaisir à un enfant qui parfois ne porte que des sandales en plastique pendant tout l'hiver.</em></p>",
        ),
        'meta' => array(
            '_ichraka_op_accent' => 'accent-coral',
            '_ichraka_op_icon'   => 'ic-jacket',
            '_ichraka_op_season' => 'Hiver',
            '_ichraka_op_stats'  => array(
                array( 'value' => '450', 'label' => 'tenues / hiver' ),
                array( 'value' => '2',   'label' => 'villages' ),
                array( 'value' => '19',  'label' => 'éditions' ),
            ),
        ),
    ),
    array(
        'data' => array(
            'post_title'   => 'Opération Lunettes',
            'post_name'    => 'operation-lunettes',
            'post_type'    => 'operation',
            'post_status'  => 'publish',
            'post_excerpt' => "Une mauvaise vue exclut silencieusement de l'école. Consultations d'ophtalmologues bénévoles puis livraison de lunettes adaptées — montures et verres compris.",
            'menu_order'   => 3,
            'post_content' => "<p>Dans le domaine médical, Ichraka assure des consultations et prises en charge d'enfants de Dar Al Atfal et d'écoles rurales à Témara.</p><p>Notre action couvre les <strong>visites d'ophtalmologues</strong> ainsi que la <strong>distribution de lunettes optiques</strong>. Plusieurs médecins spécialistes bénévoles soutiennent ce projet, qui permet aux enfants nécessiteux de disposer de lunettes optiques et d'avoir une scolarité facilitée.</p>",
        ),
        'meta' => array(
            '_ichraka_op_accent' => 'accent-mint',
            '_ichraka_op_icon'   => 'ic-glasses',
            '_ichraka_op_season' => 'Printemps',
            '_ichraka_op_stats'  => array(
                array( 'value' => '180', 'label' => 'consultations' ),
                array( 'value' => '120', 'label' => 'paires' ),
                array( 'value' => '8',   'label' => 'éditions' ),
            ),
        ),
    ),
);

foreach ( $operations as $op ) {
    $id = ichraka_upsert_post( $op['data'], $reset );
    if ( $id ) {
        foreach ( $op['meta'] as $k => $v ) {
            update_post_meta( $id, $k, $v );
        }
        WP_CLI::log( "    + meta (accent, icon, season, stats) appliquée" );
    }
}

/* -------------------------------------------------------------------------
 * 3) Témoignages
 * -----------------------------------------------------------------------*/
WP_CLI::log( "\n[3/4] Création de témoignages d'exemple…" );

$temoignages = array(
    array(
        'data' => array(
            'post_title'   => 'Karim B.',
            'post_name'    => 'temoignage-karim-b',
            'post_type'    => 'temoignage',
            'post_status'  => 'publish',
            'post_content' => "Parrainer un enfant via Ichraka, c'est suivre concrètement son parcours scolaire et savoir où va chaque dirham donné.",
        ),
        'meta' => array( '_ichraka_author_role' => 'Parrain depuis 2018' ),
    ),
    array(
        'data' => array(
            'post_title'   => 'Safae Boujendar',
            'post_name'    => 'temoignage-safae-boujendar',
            'post_type'    => 'temoignage',
            'post_status'  => 'publish',
            'post_content' => "Voir le sourire d'un enfant qui reçoit son cartable, c'est la plus belle des récompenses pour notre engagement.",
        ),
        'meta' => array( '_ichraka_author_role' => 'Présidente, Association Ichraka' ),
    ),
);

foreach ( $temoignages as $t ) {
    $id = ichraka_upsert_post( $t['data'], $reset );
    if ( $id ) {
        foreach ( $t['meta'] as $k => $v ) {
            update_post_meta( $id, $k, $v );
        }
    }
}

/* -------------------------------------------------------------------------
 * 4) Menu principal
 * -----------------------------------------------------------------------*/
WP_CLI::log( "\n[4/4] Création/mise à jour du menu principal…" );

$menu_name = 'Menu principal';
$menu      = wp_get_nav_menu_object( $menu_name );

if ( ! $menu ) {
    $menu_id = wp_create_nav_menu( $menu_name );
    WP_CLI::log( "  ✓ menu créé : {$menu_name} (#{$menu_id})" );
} else {
    $menu_id = $menu->term_id;
    if ( $reset ) {
        $items = wp_get_nav_menu_items( $menu_id );
        foreach ( (array) $items as $i ) {
            wp_delete_post( $i->ID, true );
        }
        WP_CLI::log( "  ↻ menu vidé pour reconstruction" );
    } else {
        WP_CLI::log( "  ↻ menu existant, items conservés" );
    }
}

if ( $menu_id && ( ! $menu || $reset ) ) {
    $items = array(
        array( 'slug' => 'qui-sommes-nous',   'title' => 'Qui sommes-nous' ),
        array( 'slug' => 'operations',        'title' => 'Nos actions' ),
        array( 'slug' => 'parrainage',        'title' => 'Parrainage' ),
        array( 'slug' => 'nos-projets',       'title' => 'Projets' ),
        array( 'slug' => 'contactez-nous',    'title' => 'Contact' ),
    );
    foreach ( $items as $position => $i ) {
        $page = get_page_by_path( $i['slug'] );
        if ( $page ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-title'     => $i['title'],
                'menu-item-position'  => $position + 1,
            ) );
        }
    }
    $locations = get_theme_mod( 'nav_menu_locations' );
    $locations['primary'] = $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
    WP_CLI::log( "  ✓ menu assigné à l'emplacement « primary »" );
}

/* Permaliens propres */
update_option( 'permalink_structure', '/%postname%/' );
flush_rewrite_rules();

WP_CLI::log( "\n════════════════════════════════════════════════════════" );
WP_CLI::success( 'Import terminé — direction Joyeux active.' );
WP_CLI::log( '════════════════════════════════════════════════════════' );
WP_CLI::log( '' );
WP_CLI::log( 'Étapes suivantes :' );
WP_CLI::log( '  1. Vider le cache navigateur (Ctrl+Shift+R)' );
WP_CLI::log( '  2. Visiter http://asunrise0001-s2.fhmutu.net/' );
WP_CLI::log( '  3. Ajouter des images mises en avant aux 3 opérations' );
WP_CLI::log( '  4. Configurer le formulaire CF7 sur la page Contact' );
