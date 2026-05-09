<?php
/**
 * Ichraka — script d'import de contenu (WP-CLI).
 *
 * Crée toutes les pages, opérations, projets et témoignages initiaux.
 * Idempotent : peut être relancé sans dupliquer (recherche par slug).
 *
 * USAGE :
 *   wp eval-file wp-content/themes/ichraka-child/tools/import_content.php
 *   wp eval-file wp-content/themes/ichraka-child/tools/import_content.php --reset    # force la re-création
 *
 * Prérequis : WP-CLI 2.x, le thème enfant Ichraka activé (CPT enregistrés).
 *
 * @package Ichraka
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
    fwrite( STDERR, "Ce script doit être exécuté via WP-CLI.\n" );
    exit( 1 );
}

$reset = in_array( '--reset', (array) ( $args ?? [] ), true ); // phpcs:ignore

WP_CLI::log( '════════════════════════════════════════════════════════' );
WP_CLI::log( '  Ichraka — Import du contenu initial' );
WP_CLI::log( '════════════════════════════════════════════════════════' );

/* ---------------------------------------------------------------------------
 * Helpers
 * -------------------------------------------------------------------------*/
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

/* ---------------------------------------------------------------------------
 * 1) Pages principales
 * -------------------------------------------------------------------------*/
WP_CLI::log( "\n[1/4] Création des pages principales…" );

$pages = array(

    array(
        'post_title'    => 'Accueil',
        'post_name'     => 'accueil',
        'post_type'     => 'page',
        'post_status'   => 'publish',
        'post_content'  => '<p>L\'Association Ichraka œuvre depuis 2008 pour l\'éducation et la santé des enfants défavorisés au Maroc. Découvrez nos actions, nos projets et nos opérations annuelles.</p>',
        'meta_input'    => array( '_wp_page_template' => 'templates/page-accueil.php' ),
    ),

    array(
        'post_title'   => 'Qui sommes-nous',
        'post_name'    => 'qui-sommes-nous',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Ichraka est une association de développement social et culturel apolitique et indépendante à but non lucratif qui œuvre, principalement, dans les domaines du soutien scolaire et de la santé au profit des enfants dans le besoin.</p>\n<p>Suite à une visite organisée à l'orphelinat Dar Al Atfal de Témara en octobre 2007, un groupe de bénévoles a décidé de se réunir pour aider ces enfants. C'est ainsi que le <strong>27 mars 2008 l'Association Ichraka a vu le jour.</strong></p>",
    ),

    array(
        'post_title'   => 'Historique',
        'post_name'    => 'historique',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Ichraka est une association de développement social et culturel apolitique et indépendante à but non lucratif qui œuvre, principalement, dans les domaines du soutien scolaire et de la santé au profit des enfants dans le besoin.</p>\n\n<p>Suite à une visite organisée, au cours d'une soirée de Ramadan, à l'orphelinat Dar Al Atfal de Témara en octobre 2007, un groupe de bénévoles a eu l'occasion de prendre un ftour avec les enfants, de discuter avec eux et de connaître leurs besoins et leurs problèmes.</p>\n\n<p>Au départ des cours de soutien ont été dispensés, suivis par l'organisation de journées culturelles et éducatives et par des activités sportives. Après la réussite de ces premières actions, nous nous sommes donnés comme objectif de nous organiser et de nous engager à œuvrer pour aider ces enfants à prendre leur avenir en main.</p>\n\n<p><strong>C'est ainsi que le 27 mars 2008 l'Association Ichraka a vu le jour.</strong></p>",
    ),

    array(
        'post_title'   => 'Notre mission',
        'post_name'    => 'notre-mission',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>L'association SUNRISE (Ichraka) se fixe pour mission d'entreprendre ou de contribuer à la réalisation de projets couvrant les domaines de l'éducation et de la santé de l'enfance défavorisée.</p>\n\n<h3>Nos principaux objectifs</h3>\n<ul>\n<li>Apporter un soutien moral et financier aux enfants qui sont dans le besoin et plus particulièrement aux orphelins</li>\n<li>Participer à l'éducation et à la scolarisation des enfants</li>\n<li>Participer à la lutte contre la déperdition scolaire</li>\n<li>Mener des actions de soins sanitaires au profit de l'enfance défavorisée</li>\n<li>Contribuer à l'amélioration des conditions de vie de familles démunies</li>\n<li>Développer le sens de la solidarité au sein de la société</li>\n</ul>\n\n<h3>Nos cibles</h3>\n<ul>\n<li>Orphelinat Dar Al Atfal — Témara</li>\n<li>Orphelinat Dar Al Fatat — Témara</li>\n<li>Dar Attaliba — Sidi Yahya Zaïr</li>\n<li>Écoles rurales Al Hawamid</li>\n<li>Et toute personne dans le besoin</li>\n</ul>",
    ),

    array(
        'post_title'   => 'Nos objectifs',
        'post_name'    => 'nos-objectifs',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>L'association se fixe pour objectifs d'entreprendre ou de contribuer à la réalisation de projets couvrant les domaines de l'éducation et de la santé de l'enfance défavorisée.</p>\n\n<ul>\n<li>Apporter un soutien moral et financier aux enfants qui sont dans le besoin et en particulier aux orphelins</li>\n<li>Participer à l'éducation et à la scolarisation des enfants</li>\n<li>Travailler dans le domaine de l'orientation scolaire</li>\n<li>Participer à la lutte contre la déperdition scolaire</li>\n<li>Mener des actions de soins sanitaires</li>\n<li>Contribuer à l'amélioration des conditions de vie de familles démunies</li>\n<li>Développer le sens de la solidarité</li>\n</ul>\n\n<p>Nos ambitions ne se limitent pas uniquement à la réalisation de ces objectifs mais aussi à assurer leur continuité à travers un processus visant l'autonomie des entités bénéficiaires.</p>",
    ),

    array(
        'post_title'   => 'Notre charte',
        'post_name'    => 'notre-charte',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>L'Association Ichraka s'engage sur les valeurs suivantes :</p>\n<ul>\n<li><strong>Indépendance</strong> — apolitique et libre de toute allégeance partisane</li>\n<li><strong>Transparence</strong> — comptes rendus publics annuels des opérations</li>\n<li><strong>Bénévolat</strong> — l'engagement des membres est volontaire et désintéressé</li>\n<li><strong>Respect</strong> — dignité des bénéficiaires, confidentialité de leurs données</li>\n<li><strong>Continuité</strong> — viser l'autonomie des entités aidées</li>\n</ul>",
    ),

    array(
        'post_title'   => 'Communication interne',
        'post_name'    => 'communication-interne',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<h3>Entre les membres de la même équipe</h3>\n<ul>\n<li>Toute information concernant l'équipe doit transiter par l'ensemble de ses membres</li>\n<li>Une réunion ne doit être tenue que si au moins la majorité de l'équipe est disponible</li>\n<li>Favoriser la communication verbale pour les discussions plutôt que les emails</li>\n<li>Les CR des réunions doivent être validés par les membres avant diffusion</li>\n<li>Utilisation de Google Calendar pour les dates de réunions et événements</li>\n<li>Toutes les dates devront être partagées avec tous les membres de l'association</li>\n</ul>",
    ),

    array(
        'post_title'   => 'Responsabilités des membres',
        'post_name'    => 'responsabilites',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<h3>Tout membre doit :</h3>\n<ul>\n<li>Organiser ses propres priorités (Famille, travail, études, association…) et ne s'engager que dans la mesure du possible</li>\n<li>Une fois engagé dans une tâche, l'assumer de manière professionnelle</li>\n<li>Aider à la recherche des moyens de financement</li>\n<li>Respecter tous les membres et se comporter avec professionnalisme</li>\n<li>Respecter la confidentialité des données personnelles</li>\n<li>Respecter les objectifs de l'association</li>\n<li>Donner une meilleure image de l'association</li>\n<li>Envoyer ses remarques et propositions pour l'amélioration</li>\n<li>Exprimer ses objections en temps réel</li>\n</ul>\n\n<h3>Toute équipe doit :</h3>\n<ul>\n<li>Préparer un planning annuel d'actions à mener</li>\n<li>Arrêter le budget prévisionnel annuel</li>\n<li>Créer et maintenir une base de données de documentation</li>\n<li>Produire en fin d'année un récapitulatif documentaire</li>\n</ul>",
    ),

    array(
        'post_title'   => 'Nos actions',
        'post_name'    => 'nos-actions',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Ichraka concentre son activité sur deux types d'actions :</p>\n<ul>\n<li><strong>Actions annuelles</strong> : étalées sur toute l'année scolaire (cours de soutien, suivi médical)</li>\n<li><strong>Actions ponctuelles</strong> : Opération Cartables, Vêtements, Lunettes</li>\n</ul>\n\n<p>Chaque action est gérée par une équipe composée de membres du bureau et d'adhérents. Les tâches sont réparties sous le contrôle d'un responsable. Tout projet doit être validé par le comité de direction.</p>\n\n[ichraka_operations limit=\"-1\"]",
    ),

    array(
        'post_title'   => 'Opérations',
        'post_name'    => 'operations',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Découvrez nos trois grandes opérations annuelles solidaires.</p>",
        'meta_input'   => array( '_wp_page_template' => 'templates/page-operations.php' ),
    ),

    array(
        'post_title'   => 'Parrainage',
        'post_name'    => 'parrainage',
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_content' => "<p>Le parrainage est un engagement durable au profit d'un enfant : prise en charge de ses frais de scolarité, fournitures, suivi médical, accompagnement éducatif.</p>\n\n<p>Pour devenir parrain ou marraine, contactez-nous via le formulaire de contact. Nous vous mettrons en relation avec un enfant et vous tiendrons informé(e) de son évolution chaque trimestre.</p>",
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

// Hiérarchie : sous-pages de "Qui sommes-nous"
$qui_children = array( 'historique', 'notre-mission', 'nos-objectifs', 'notre-charte', 'communication-interne', 'responsabilites' );
foreach ( $qui_children as $slug ) {
    ichraka_set_parent( $slug, 'qui-sommes-nous' );
}

// Page d'accueil officielle.
$accueil = get_page_by_path( 'accueil' );
if ( $accueil ) {
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $accueil->ID );
}

/* ---------------------------------------------------------------------------
 * 2) Opérations (CPT)
 * -------------------------------------------------------------------------*/
WP_CLI::log( "\n[2/4] Création des opérations…" );

$operations = array(
    array(
        'post_title'   => 'Opération Cartables',
        'post_name'    => 'operation-cartables',
        'post_type'    => 'operation',
        'post_status'  => 'publish',
        'post_excerpt' => 'Offrir un cartable complet à près de 300 enfants des régions rurales démunies pour chaque rentrée scolaire depuis 2008.',
        'menu_order'   => 1,
        'post_content' => "<p>La pauvreté constitue sans doute le principal obstacle à la scolarisation et la principale cause de déperdition scolaire, notamment en milieu rural.</p>\n\n<p>Le but de cette action est d'<strong>offrir un cartable complet</strong> (trousse, cahiers, manuels, ardoise) à près de <strong>300 enfants des régions rurales démunies</strong> pour chaque rentrée scolaire depuis 2008.</p>\n\n<p>Les écoliers sont issus de familles très pauvres (agriculteurs, ouvriers…) et souffrent de conditions difficiles qui peuvent nuire à leur scolarité : absence d'électricité, conditions de vie très dures, longue distance entre la résidence et l'école…</p>\n\n<p><em>Toute contribution est la bienvenue pour aider ces enfants à poursuivre leurs études.</em></p>",
    ),
    array(
        'post_title'   => 'Opération Vêtements',
        'post_name'    => 'operation-vetements',
        'post_type'    => 'operation',
        'post_status'  => 'publish',
        'post_excerpt' => 'Depuis 2007, collecte de fonds pour l\'achat de vêtements d\'hiver au profit des orphelins et des élèves de l\'école rurale Al Hawamid.',
        'menu_order'   => 2,
        'post_content' => "<p>Ramadan, mois sacré de solidarité et de compassion, est une occasion pour soutenir les personnes défavorisées, spécialement les orphelins et les enfants du monde rural.</p>\n\n<p>Depuis <strong>2007</strong>, l'association Ichraka organise une collecte de fonds pour l'achat de <strong>vêtements d'hiver</strong> au profit des orphelins de Dar Al Atfal et des élèves de l'école rurale Al Hawamid, à Témara.</p>\n\n<p>Une tenue neuve et de bonne qualité (pantalon, pull/imperméable, chaussures/bottes…) est offerte à chaque enfant.</p>\n\n<p><em>200 ou 300 dh suffisent pour faire plaisir à un enfant qui parfois ne porte que des sandales en plastique pendant toute l'année.</em></p>",
    ),
    array(
        'post_title'   => 'Opération Lunettes',
        'post_name'    => 'operation-lunettes',
        'post_type'    => 'operation',
        'post_status'  => 'publish',
        'post_excerpt' => 'Visites d\'ophtalmologues et distribution de lunettes optiques aux enfants de Dar Al Atfal et des écoles rurales de Témara.',
        'menu_order'   => 3,
        'post_content' => "<p>Dans le domaine médical, Ichraka assure des consultations et prises en charge de certains enfants de « Dar Al Atfal » et de quelques écoles rurales à Témara.</p>\n\n<p>Notre action dans ce domaine couvre les <strong>visites d'ophtalmologues</strong>, ainsi que la <strong>distribution de lunettes optiques</strong>.</p>\n\n<p>Pour mener à bien ce projet, Ichraka est soutenue par plusieurs médecins spécialistes bénévoles. Cette action permet aux enfants nécessiteux de disposer de lunettes optiques et d'avoir une scolarité facilitée en évitant les retards et lacunes qui peuvent s'accumuler à cause des problèmes de vision.</p>",
    ),
);

foreach ( $operations as $op ) {
    ichraka_upsert_post( $op, $reset );
}

/* ---------------------------------------------------------------------------
 * 3) Témoignages d'exemple (à remplacer par de vrais en prod)
 * -------------------------------------------------------------------------*/
WP_CLI::log( "\n[3/4] Création de témoignages d'exemple…" );

$temoignages = array(
    array(
        'post_title'   => 'Safae BOUJENDAR',
        'post_name'    => 'temoignage-safae-boujendar',
        'post_type'    => 'temoignage',
        'post_status'  => 'publish',
        'post_content' => "<p>Voir le sourire d'un enfant recevoir son cartable, c'est la plus belle des récompenses pour notre engagement.</p>",
        'meta_input'   => array( '_ichraka_author_role' => 'Présidente, Association Ichraka' ),
    ),
    array(
        'post_title'   => 'Un parrain',
        'post_name'    => 'temoignage-parrain',
        'post_type'    => 'temoignage',
        'post_status'  => 'publish',
        'post_content' => "<p>Parrainer un enfant via Ichraka, c'est suivre concrètement son parcours scolaire et savoir où va chaque dirham donné.</p>",
        'meta_input'   => array( '_ichraka_author_role' => 'Parrain depuis 2018' ),
    ),
);

foreach ( $temoignages as $t ) {
    ichraka_upsert_post( $t, $reset );
}

/* ---------------------------------------------------------------------------
 * 4) Menu principal
 * -------------------------------------------------------------------------*/
WP_CLI::log( "\n[4/4] Création du menu principal…" );

$menu_name = 'Menu principal';
$menu      = wp_get_nav_menu_object( $menu_name );

if ( ! $menu ) {
    $menu_id = wp_create_nav_menu( $menu_name );
    WP_CLI::log( "  ✓ menu créé : {$menu_name} (#{$menu_id})" );
} else {
    $menu_id = $menu->term_id;
    if ( $reset ) {
        // vider les items pour reconstruire
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
        array( 'slug' => 'accueil',           'title' => 'Accueil' ),
        array( 'slug' => 'qui-sommes-nous',   'title' => 'Qui sommes-nous' ),
        array( 'slug' => 'nos-actions',       'title' => 'Nos actions' ),
        array( 'slug' => 'operations',        'title' => 'Opérations' ),
        array( 'slug' => 'parrainage',        'title' => 'Parrainage' ),
        array( 'slug' => 'nos-projets',       'title' => 'Nos projets' ),
        array( 'slug' => 'contactez-nous',    'title' => 'Contactez-nous' ),
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

    // Assigner au menu primary
    $locations = get_theme_mod( 'nav_menu_locations' );
    $locations['primary'] = $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
    WP_CLI::log( "  ✓ menu assigné à l'emplacement « primary »" );
}

/* ---------------------------------------------------------------------------
 * Permaliens jolis + flush rewrite rules
 * -------------------------------------------------------------------------*/
update_option( 'permalink_structure', '/%postname%/' );
flush_rewrite_rules();

WP_CLI::log( "\n════════════════════════════════════════════════════════" );
WP_CLI::success( 'Import terminé avec succès.' );
WP_CLI::log( '════════════════════════════════════════════════════════' );
WP_CLI::log( '' );
WP_CLI::log( 'Étapes suivantes :' );
WP_CLI::log( '  1. Réglages → Lecture : vérifier que "Accueil" est bien la page d\'accueil' );
WP_CLI::log( '  2. Apparence → Menus : vérifier le menu principal' );
WP_CLI::log( '  3. Apparence → Personnaliser : régler le logo et l\'identité' );
WP_CLI::log( '  4. Réglages → Permaliens : enregistrer pour activer les jolis URL' );
WP_CLI::log( '  5. Installer le plugin de don (GiveWP ou WPForms)' );
WP_CLI::log( '  6. Installer Polylang/WPML pour le bilinguisme FR/AR' );
