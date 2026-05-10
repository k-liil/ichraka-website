<?php
/**
 * Ichraka — shortcodes (direction Joyeux).
 *
 * Disponibles :
 *  [ichraka_hero_pillars]            ruban 4 piliers chiffrés sur fond ink
 *  [ichraka_mission]                 bloc cream-2 avec citation + bio
 *  [ichraka_operations limit="3"]    grille 3 cartes opérations colorées
 *  [ichraka_impact_counters]         section ink + 4 compteurs animés
 *  [ichraka_timeline]                story-grid avec timeline 4 jalons
 *  [ichraka_testimonials limit="2"]  cartes témoignages
 *  [ichraka_donate_block]            section coral avec 3 paliers
 *  [ichraka_newsletter]              bloc mint avec form
 *  [ichraka_donate_button]           bouton pill
 *  [ichraka_team]                    grille bureau
 *  [ichraka_partners]                logos partenaires
 *  [ichraka_counters]                alias legacy → impact_counters
 *  [ichraka_hero]                    alias legacy
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ==========================================================================
 * HERO PILLARS — ruban 4 piliers chiffrés
 * ========================================================================== */
add_shortcode( 'ichraka_hero_pillars', 'ichraka_sc_hero_pillars' );
function ichraka_sc_hero_pillars() {
    $current_year = (int) date( 'Y' );
    $pillars = apply_filters( 'ichraka_hero_pillars', array(
        array( 'value' => max( 1, $current_year - 2008 ), 'sup' => '', 'label' => __( "années d'engagement<br>continu depuis 2008", 'ichraka' ) ),
        array( 'value' => 4200, 'sup' => '+', 'label' => __( "enfants accompagnés<br>au fil des opérations", 'ichraka' ) ),
        array( 'value' => 12,   'sup' => '', 'label' => __( "écoles partenaires<br>en milieu rural", 'ichraka' ) ),
        array( 'value' => 100,  'sup' => '%', 'label' => __( "des dons<br>vont au terrain", 'ichraka' ) ),
    ) );

    ob_start();
    echo '<div class="ichraka-hero-pillars">';
    foreach ( $pillars as $i => $p ) {
        $num_class = 'num-' . ( ( $i % 4 ) + 1 );
        echo '<div class="pillar">';
        echo '<div class="pillar-num">';
        printf(
            '<span class="%s" data-count="%d">0</span>',
            esc_attr( $num_class ),
            (int) $p['value']
        );
        if ( ! empty( $p['sup'] ) ) {
            echo '<sup>' . esc_html( $p['sup'] ) . '</sup>';
        }
        echo '</div>';
        echo '<div class="pillar-label">' . wp_kses_post( $p['label'] ) . '</div>';
        echo '</div>';
    }
    echo '</div>';
    return ob_get_clean();
}

/* ==========================================================================
 * MISSION — bloc cream-2 + citation + bio
 * ========================================================================== */
add_shortcode( 'ichraka_mission', 'ichraka_sc_mission' );
function ichraka_sc_mission() {
    $quote_html = get_option(
        'ichraka_mission_quote',
        __( 'Aucune <span class="hl-yellow">rentrée</span> ne devrait dépendre d\'un cartable absent. Aucun hiver d\'un <span class="hl-coral">manteau</span> manquant.', 'ichraka' )
    );
    $author      = get_option( 'ichraka_mission_author', 'Safae Boujendar' );
    $author_role = get_option( 'ichraka_mission_role', __( "Présidente de l'association", 'ichraka' ) );
    $body        = get_option(
        'ichraka_mission_body',
        __( "<p>Ichraka — <em>« la lumière qui se lève »</em> en arabe — est née en 2008 d'un constat simple : à quelques kilomètres des grandes villes, des enfants quittent l'école faute d'un cartable, d'une vue corrigée, d'un vêtement chaud pour tenir l'hiver.</p><p>Trois opérations annuelles, un calendrier joyeux, un seul objectif : que chaque enfant que nous croisons ait sa <em>chance</em> de s'asseoir en classe avec ce qu'il faut pour apprendre.</p><p>Et chaque dirham est tracé. Chaque opération est documentée. Chaque bénéficiaire est suivi. La transparence n'est pas une option, c'est notre pacte.</p>", 'ichraka' )
    );

    $portrait_id = (int) get_option( 'ichraka_mission_portrait_id', 0 );

    ob_start();
    ?>
    <section class="ichraka-mission" id="notre-conviction">
        <div class="mission-grid">

            <div class="mission-portrait">
                <span class="portrait-quote-mark" aria-hidden="true">&ldquo;</span>
                <div class="portrait-frame">
                    <?php if ( $portrait_id ) :
                        echo wp_get_attachment_image( $portrait_id, 'ichraka-hero', false, array(
                            'alt'     => esc_attr( $author . ', ' . $author_role ),
                            'loading' => 'lazy',
                        ) );
                    else : ?>
                        <span style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:var(--cream);font-family:var(--font-display);font-size:3rem;">
                            <?php echo esc_html( ichraka_get_initials( $author ) ); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="portrait-tag">
                    <?php echo esc_html( $author ); ?>
                    <small><?php echo esc_html( $author_role ); ?></small>
                </div>
            </div>

            <div class="mission-body">
                <span class="eyebrow eyebrow--coral">★ <?php esc_html_e( 'Notre conviction', 'ichraka' ); ?></span>
                <p class="mission-quote">
                    <?php echo wp_kses_post( $quote_html ); ?>
                </p>
                <?php echo wp_kses_post( $body ); ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
}

/* ==========================================================================
 * OPÉRATIONS — 3 cartes colorées avec stats
 * ========================================================================== */
add_shortcode( 'ichraka_operations', 'ichraka_sc_operations' );
function ichraka_sc_operations( $atts ) {
    $atts = shortcode_atts( array(
        'limit'   => 3,
        'orderby' => 'menu_order',
    ), $atts, 'ichraka_operations' );

    $query = new WP_Query( array(
        'post_type'      => 'operation',
        'posts_per_page' => intval( $atts['limit'] ),
        'orderby'        => sanitize_key( $atts['orderby'] ),
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ) );

    if ( ! $query->have_posts() ) {
        return '';
    }

    $accent_map = array(
        1 => 'accent-yellow',
        2 => 'accent-coral',
        3 => 'accent-mint',
        0 => 'accent-sky',
    );
    $icon_map = array( 'ic-bag', 'ic-jacket', 'ic-glasses', 'ic-action' );

    $season_labels = array(
        __( 'Rentrée', 'ichraka' ),
        __( 'Hiver', 'ichraka' ),
        __( 'Printemps', 'ichraka' ),
        __( 'Été', 'ichraka' ),
    );

    ob_start();
    echo '<div class="ops-grid">';

    $i = 0;
    while ( $query->have_posts() ) {
        $query->the_post();
        $i++;

        $accent = get_post_meta( get_the_ID(), '_ichraka_op_accent', true );
        if ( ! $accent ) {
            $accent = $accent_map[ $i % 4 ];
        }

        $icon = get_post_meta( get_the_ID(), '_ichraka_op_icon', true );
        if ( ! $icon ) {
            $icon = $icon_map[ ( $i - 1 ) % count( $icon_map ) ];
        }

        $season = get_post_meta( get_the_ID(), '_ichraka_op_season', true );
        if ( ! $season ) {
            $season = $season_labels[ ( $i - 1 ) % count( $season_labels ) ];
        }

        $stats = get_post_meta( get_the_ID(), '_ichraka_op_stats', true );
        if ( ! is_array( $stats ) ) {
            $stats = array();
        }
        ?>
        <article class="op-card <?php echo esc_attr( $accent ); ?>">
            <span class="op-num">— <?php
                /* translators: %1$d: numéro, %2$s: saison */
                printf( esc_html__( 'No. %1$02d · %2$s', 'ichraka' ), $i, esc_html( $season ) );
            ?></span>
            <div class="op-icon">
                <svg width="46" height="46" viewBox="0 0 64 64" aria-hidden="true"><use href="#<?php echo esc_attr( $icon ); ?>"/></svg>
            </div>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="op-desc"><?php echo esc_html( get_the_excerpt() ); ?></p>

            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="op-image"><?php the_post_thumbnail( 'ichraka-card' ); ?></a>
            <?php else : ?>
                <span class="op-image" aria-hidden="true"></span>
            <?php endif; ?>

            <?php if ( ! empty( $stats ) ) : ?>
            <div class="op-stats">
                <?php foreach ( $stats as $stat ) :
                    if ( empty( $stat['value'] ) ) continue; ?>
                    <div class="op-stat">
                        <strong><?php echo esc_html( $stat['value'] ); ?></strong>
                        <span><?php echo esc_html( $stat['label'] ?? '' ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <a href="<?php the_permalink(); ?>" class="op-link">
                <?php esc_html_e( 'En savoir plus', 'ichraka' ); ?>
                <svg class="arrow" viewBox="0 0 14 14" aria-hidden="true"><use href="#ic-arrow"/></svg>
            </a>
        </article>
        <?php
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}

/* ==========================================================================
 * IMPACT / COUNTERS — section ink + 4 compteurs
 * ========================================================================== */
add_shortcode( 'ichraka_impact_counters', 'ichraka_sc_impact_counters' );
add_shortcode( 'ichraka_counters', 'ichraka_sc_impact_counters' );
function ichraka_sc_impact_counters() {
    $items = apply_filters( 'ichraka_counters', array(
        array( 'icon' => 'ic-people', 'value' => 4200,    'sup' => '+',  'label' => __( 'enfants accompagnés depuis la création', 'ichraka' ) ),
        array( 'icon' => 'ic-coin',   'value' => 2400000, 'sup' => 'DH', 'label' => __( 'collectés et tracés sur 18 années', 'ichraka' ) ),
        array( 'icon' => 'ic-action', 'value' => 62,      'sup' => '',   'label' => __( 'opérations menées sur le terrain', 'ichraka' ) ),
        array( 'icon' => 'ic-school', 'value' => 240,     'sup' => '+',  'label' => __( 'bénévoles mobilisés au fil des années', 'ichraka' ) ),
    ) );

    ob_start();
    ?>
    <div class="impact">
        <svg class="impact-deco-1" style="color: var(--coral); opacity: 0.18;" aria-hidden="true"><use href="#ic-blob"/></svg>
        <svg class="impact-deco-2" style="color: var(--yellow); opacity: 0.15;" aria-hidden="true"><use href="#ic-blob"/></svg>

        <div class="section-head">
            <div>
                <span class="eyebrow yellow">★ <?php esc_html_e( 'Notre impact', 'ichraka' ); ?></span>
                <h2 class="display" style="margin-top: 1.4rem;">
                    <?php esc_html_e( 'Mesuré.', 'ichraka' ); ?><br>
                    <span class="hl"><?php esc_html_e( 'Publié.', 'ichraka' ); ?></span><br>
                    <?php esc_html_e( 'Vérifiable.', 'ichraka' ); ?>
                </h2>
            </div>
            <div class="right">
                <p><?php esc_html_e( 'Chaque opération produit un bilan publié et une comptabilité détaillée. Notre rapport moral est ouvert à tout donateur souhaitant comprendre où va son don.', 'ichraka' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="btn btn-yellow" style="margin-top: 1.5rem;">
                    <?php esc_html_e( 'Lire le bilan 2025', 'ichraka' ); ?>
                    <svg class="arrow" viewBox="0 0 14 14" aria-hidden="true"><use href="#ic-arrow"/></svg>
                </a>
            </div>
        </div>

        <div class="counters">
            <?php foreach ( $items as $i ) : ?>
            <div class="counter">
                <div class="counter-emoji">
                    <svg width="28" height="28" viewBox="0 0 64 64" aria-hidden="true"><use href="#<?php echo esc_attr( $i['icon'] ); ?>"/></svg>
                </div>
                <div class="counter-num">
                    <span data-count="<?php echo (int) $i['value']; ?>">0</span>
                    <?php if ( ! empty( $i['sup'] ) ) : ?><sup><?php echo esc_html( $i['sup'] ); ?></sup><?php endif; ?>
                </div>
                <div class="counter-label"><?php echo esc_html( $i['label'] ); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/* ==========================================================================
 * TIMELINE — story-grid + 4 jalons colorés
 * ========================================================================== */
add_shortcode( 'ichraka_timeline', 'ichraka_sc_timeline' );
function ichraka_sc_timeline() {
    $items = apply_filters( 'ichraka_timeline_items', array(
        array(
            'year'  => '2008',
            'title' => __( 'Première Opération Cartables', 'ichraka' ),
            'desc'  => __( "80 cartables à l'école rurale Al Hawamid, Témara. Le geste fondateur de l'association.", 'ichraka' ),
        ),
        array(
            'year'  => '2012',
            'title' => __( "Reconnaissance d'utilité publique", 'ichraka' ),
            'desc'  => __( 'Agrément officiel et structuration du réseau de parrains réguliers. Premiers bilans audités.', 'ichraka' ),
        ),
        array(
            'year'  => '2018',
            'title' => __( "Lancement de l'Opération Lunettes", 'ichraka' ),
            'desc'  => __( "Première campagne santé visuelle, en partenariat avec un réseau d'ophtalmologues bénévoles.", 'ichraka' ),
        ),
        array(
            'year'  => '2026',
            'title' => __( '18 ans, 4 200 enfants', 'ichraka' ),
            'desc'  => __( 'Nouvelle plateforme numérique, programme de parrainage individuel, cap des 4 200 bénéficiaires.', 'ichraka' ),
        ),
    ) );

    ob_start();
    ?>
    <div class="story-grid">
        <div class="story-image">
            <?php
            $story_image_id = (int) get_option( 'ichraka_story_image_id', 0 );
            if ( $story_image_id ) {
                echo wp_get_attachment_image( $story_image_id, 'ichraka-card', false, array(
                    'class' => 'story-image-frame',
                    'alt'   => esc_attr__( 'Histoire de l\'association', 'ichraka' ),
                ) );
            } else {
                echo '<span class="story-image-frame" aria-hidden="true"></span>';
            }
            ?>
        </div>

        <div class="timeline">
            <?php foreach ( $items as $item ) : ?>
            <div class="tl-item">
                <div class="tl-year"><?php echo esc_html( $item['year'] ); ?></div>
                <div>
                    <div class="tl-title"><?php echo esc_html( $item['title'] ); ?></div>
                    <div class="tl-desc"><?php echo esc_html( $item['desc'] ); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/* ==========================================================================
 * TÉMOIGNAGES — cartes blanches avec quote display
 * ========================================================================== */
add_shortcode( 'ichraka_testimonials', 'ichraka_sc_testimonials' );
function ichraka_sc_testimonials( $atts ) {
    $atts = shortcode_atts( array( 'limit' => 2 ), $atts, 'ichraka_testimonials' );

    $query = new WP_Query( array(
        'post_type'      => 'temoignage',
        'posts_per_page' => intval( $atts['limit'] ),
        'no_found_rows'  => true,
    ) );

    if ( ! $query->have_posts() ) {
        return '';
    }

    ob_start();
    echo '<div class="t-grid">';
    while ( $query->have_posts() ) {
        $query->the_post();
        $role     = get_post_meta( get_the_ID(), '_ichraka_author_role', true );
        $initials = ichraka_get_initials( get_the_title() );
        ?>
        <article class="t-card">
            <p class="t-quote"><?php echo wp_kses_post( wp_strip_all_tags( get_the_content() ) ); ?></p>
            <div class="t-author">
                <div class="t-author-avatar">
                    <?php
                    if ( has_post_thumbnail() ) {
                        the_post_thumbnail( array( 96, 96 ) );
                    } else {
                        echo esc_html( $initials );
                    }
                    ?>
                </div>
                <div>
                    <strong><?php the_title(); ?></strong>
                    <?php if ( $role ) : ?>
                        <?php echo esc_html( $role ); ?>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}

/* ==========================================================================
 * DONATE BLOCK — section coral + 3 paliers
 * ========================================================================== */
add_shortcode( 'ichraka_donate_block', 'ichraka_sc_donate_block' );
function ichraka_sc_donate_block() {
    $tiers = apply_filters( 'ichraka_donate_tiers', array(
        array( 'amount' => 200,  'label' => __( 'Un cartable<br>complet', 'ichraka' ), 'active' => false ),
        array( 'amount' => 500,  'label' => __( 'Lunettes +<br>consultation', 'ichraka' ), 'active' => true ),
        array( 'amount' => 1200, 'label' => __( 'Parrainage<br>annuel', 'ichraka' ), 'active' => false ),
    ) );

    $donate_url = ichraka_get_donate_url();

    ob_start();
    ?>
    <div class="donate">
        <svg class="donate-deco-1" style="color: var(--yellow);" aria-hidden="true"><use href="#ic-sun"/></svg>
        <svg class="donate-deco-2" style="color: var(--coral-deep);" aria-hidden="true"><use href="#ic-blob"/></svg>

        <div class="donate-grid">
            <div>
                <span class="eyebrow yellow">★ <?php esc_html_e( 'Faire un don', 'ichraka' ); ?></span>
                <h2 class="display" style="margin-top: 1.4rem;">
                    <?php esc_html_e( 'Un cartable.', 'ichraka' ); ?><br>
                    <?php esc_html_e( 'Un enfant.', 'ichraka' ); ?><br>
                    <span class="hl-yellow"><?php esc_html_e( 'Une rentrée.', 'ichraka' ); ?></span>
                </h2>
                <p class="donate-lead">
                    <?php esc_html_e( 'Chaque don, quel que soit son montant, finance directement nos opérations terrain. 100% des contributions vont aux bénéficiaires — le fonctionnement est couvert par les bénévoles.', 'ichraka' ); ?>
                </p>

                <div class="donate-tiers" role="radiogroup" aria-label="<?php esc_attr_e( 'Montant', 'ichraka' ); ?>">
                    <?php foreach ( $tiers as $tier ) : ?>
                        <button type="button"
                                class="tier <?php echo $tier['active'] ? 'active' : ''; ?>"
                                data-amount="<?php echo (int) $tier['amount']; ?>"
                                role="radio"
                                aria-checked="<?php echo $tier['active'] ? 'true' : 'false'; ?>">
                            <div class="tier-amount">
                                <?php echo esc_html( number_format_i18n( $tier['amount'] ) ); ?><small> DH</small>
                            </div>
                            <div class="tier-impact"><?php echo wp_kses_post( $tier['label'] ); ?></div>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="donate-cta-row">
                    <a href="<?php echo esc_url( $donate_url ); ?>" class="btn btn-yellow">
                        <?php esc_html_e( 'Donner', 'ichraka' ); ?> <span id="donate-amount">500 DH</span>
                        <svg class="arrow" viewBox="0 0 14 14" aria-hidden="true"><use href="#ic-arrow"/></svg>
                    </a>
                    <a href="<?php echo esc_url( $donate_url ); ?>" style="color: rgba(255,255,255,0.95); border-bottom: 2px solid rgba(255,255,255,0.5); padding-bottom: 2px; font-size: 0.95rem; font-weight: 600;">
                        <?php esc_html_e( 'Choisir un autre montant', 'ichraka' ); ?>
                    </a>
                </div>

                <div class="donate-trust">
                    <span><svg width="12" height="12" viewBox="0 0 14 14" aria-hidden="true"><use href="#ic-check"/></svg> <?php esc_html_e( 'Reçu fiscal', 'ichraka' ); ?></span>
                    <span><svg width="12" height="12" viewBox="0 0 14 14" aria-hidden="true"><use href="#ic-check"/></svg> <?php esc_html_e( 'Bilans publiés', 'ichraka' ); ?></span>
                    <span><svg width="12" height="12" viewBox="0 0 14 14" aria-hidden="true"><use href="#ic-check"/></svg> <?php esc_html_e( 'Paiement sécurisé', 'ichraka' ); ?></span>
                </div>
            </div>

            <div class="donate-image">
                <?php
                $img_id = (int) get_option( 'ichraka_donate_image_id', 0 );
                if ( $img_id ) {
                    echo wp_get_attachment_image( $img_id, 'ichraka-card', false, array(
                        'alt' => esc_attr__( 'Faire un don à Ichraka', 'ichraka' ),
                    ) );
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/* ==========================================================================
 * NEWSLETTER — bloc mint
 * ========================================================================== */
add_shortcode( 'ichraka_newsletter', 'ichraka_sc_newsletter' );
function ichraka_sc_newsletter() {
    $action = get_option( 'ichraka_newsletter_action', '#' );

    ob_start();
    ?>
    <div class="newsletter">
        <svg class="nl-deco" style="color: var(--ink);" aria-hidden="true"><use href="#ic-sparkle"/></svg>
        <div class="nl-grid">
            <div>
                <span class="eyebrow" style="background: var(--ink); color: var(--mint);">★ <?php esc_html_e( 'Restez avec nous', 'ichraka' ); ?></span>
                <h3 style="margin-top: 1.2rem;">
                    <?php esc_html_e( 'Recevez nos carnets', 'ichraka' ); ?><br>
                    <?php esc_html_e( 'de mission,', 'ichraka' ); ?>
                    <span class="hl-coral"><?php esc_html_e( '4 fois par an', 'ichraka' ); ?></span>.
                </h3>
            </div>
            <div>
                <form class="nl-form" action="<?php echo esc_url( $action ); ?>" method="post"
                      onsubmit="event.preventDefault(); this.querySelector('input').value=''; this.querySelector('button').textContent='<?php echo esc_js( __( 'Merci ✓', 'ichraka' ) ); ?>';">
                    <input type="email" name="email" placeholder="<?php esc_attr_e( 'votre@email.com', 'ichraka' ); ?>" required />
                    <button type="submit" class="btn btn-primary"><?php esc_html_e( "S'abonner", 'ichraka' ); ?></button>
                </form>
                <p style="margin-top: 0.8rem; font-size: 0.82rem; color: rgba(20, 33, 61, 0.7);">
                    <?php esc_html_e( 'Pas de spam — uniquement les bilans et grandes annonces. Désinscription en un clic.', 'ichraka' ); ?>
                </p>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/* ==========================================================================
 * DONATE BUTTON
 * ========================================================================== */
add_shortcode( 'ichraka_donate_button', 'ichraka_sc_donate_button' );
function ichraka_sc_donate_button( $atts ) {
    $atts = shortcode_atts( array(
        'text'  => __( 'Faire un don', 'ichraka' ),
        'style' => 'primary',
    ), $atts, 'ichraka_donate_button' );

    $class = 'btn btn-' . sanitize_html_class( $atts['style'], 'primary' );

    ob_start();
    ?>
    <a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( ichraka_get_donate_url() ); ?>">
        <?php echo esc_html( $atts['text'] ); ?>
        <svg class="arrow" viewBox="0 0 14 14" aria-hidden="true"><use href="#ic-arrow"/></svg>
    </a>
    <?php
    return ob_get_clean();
}

/* ==========================================================================
 * HERO (alias legacy)
 * ========================================================================== */
add_shortcode( 'ichraka_hero', 'ichraka_sc_hero' );
function ichraka_sc_hero( $atts ) {
    $atts = shortcode_atts( array(
        'title'   => '',
        'tagline' => '',
    ), $atts, 'ichraka_hero' );

    // Sur la nouvelle direction, le hero est dans le template page-accueil.
    // Ce shortcode reste pour rétro-compat et ré-utilisations dans d'autres pages.
    ob_start();
    ?>
    <header class="ichraka-hero">
        <div class="ichraka-hero-grid">
            <div>
                <h1 class="display ichraka-hero-title"><?php echo esc_html( $atts['title'] ?: get_bloginfo( 'name' ) ); ?></h1>
                <p class="ichraka-hero-lead"><?php echo esc_html( $atts['tagline'] ?: __( 'Ensemble, nous pouvons créer le changement.', 'ichraka' ) ); ?></p>
                <div class="ichraka-hero-cta">
                    <?php echo ichraka_sc_donate_button( array( 'text' => __( 'Faire un don', 'ichraka' ) ) ); ?>
                </div>
            </div>
            <div class="ichraka-hero-image-wrap">
                <div class="ichraka-hero-image" aria-hidden="true"></div>
            </div>
        </div>
    </header>
    <?php
    return ob_get_clean();
}

/* ==========================================================================
 * BUREAU / ÉQUIPE
 * ========================================================================== */
add_shortcode( 'ichraka_team', 'ichraka_sc_team' );
function ichraka_sc_team() {
    $members = apply_filters( 'ichraka_team_members', array(
        array( 'name' => 'Safae BOUJENDAR',         'role' => __( 'Présidente', 'ichraka' ),         'accent' => 'yellow' ),
        array( 'name' => 'Asmae OUAZZANI CHAHDI',   'role' => __( 'Vice-présidente', 'ichraka' ),    'accent' => 'coral' ),
        array( 'name' => 'Brahim FERHAT',           'role' => __( 'Secrétaire Général', 'ichraka' ), 'accent' => 'mint' ),
        array( 'name' => 'Khalil FERHAT',           'role' => __( 'Trésorier', 'ichraka' ),          'accent' => 'sky' ),
        array( 'name' => 'Ikrame OUAHBI',           'role' => __( 'Trésorier Adjoint', 'ichraka' ),  'accent' => 'yellow' ),
        array( 'name' => 'Fouzia KHALDI',           'role' => __( 'Conseillère', 'ichraka' ),        'accent' => 'coral' ),
        array( 'name' => 'Imane CHERRAT',           'role' => __( 'Conseillère', 'ichraka' ),        'accent' => 'mint' ),
    ) );

    ob_start();
    echo '<div class="ichraka-team-grid">';
    foreach ( $members as $m ) {
        $initials = ichraka_get_initials( $m['name'] );
        $accent   = isset( $m['accent'] ) ? sanitize_html_class( $m['accent'] ) : 'yellow';
        ?>
        <div class="ichraka-team-member accent-<?php echo esc_attr( $accent ); ?>">
            <div class="ichraka-team-avatar"><?php echo esc_html( $initials ); ?></div>
            <p class="ichraka-team-name"><?php echo esc_html( $m['name'] ); ?></p>
            <p class="ichraka-team-role"><?php echo esc_html( $m['role'] ); ?></p>
        </div>
        <?php
    }
    echo '</div>';
    return ob_get_clean();
}

function ichraka_get_initials( $name ) {
    $parts = preg_split( '/\s+/', trim( $name ) );
    $out   = '';
    foreach ( $parts as $p ) {
        $out .= mb_substr( $p, 0, 1 );
        if ( mb_strlen( $out ) >= 2 ) break;
    }
    return mb_strtoupper( $out );
}

/* ==========================================================================
 * PARTENAIRES
 * ========================================================================== */
add_shortcode( 'ichraka_partners', 'ichraka_sc_partners' );
function ichraka_sc_partners() {
    $query = new WP_Query( array(
        'post_type'      => 'partenaire',
        'posts_per_page' => -1,
        'no_found_rows'  => true,
    ) );
    if ( ! $query->have_posts() ) {
        return '';
    }

    ob_start();
    echo '<div class="ichraka-partners-grid">';
    while ( $query->have_posts() ) {
        $query->the_post();
        $url = get_post_meta( get_the_ID(), '_ichraka_partenaire_url', true );
        $img = has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'medium' ) : '<span>' . esc_html( get_the_title() ) . '</span>';
        if ( $url ) {
            printf( '<a href="%s" target="_blank" rel="noopener" class="ichraka-partner">%s</a>', esc_url( $url ), $img ); // phpcs:ignore
        } else {
            printf( '<div class="ichraka-partner">%s</div>', $img ); // phpcs:ignore
        }
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
