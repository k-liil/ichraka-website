<?php
/**
 * Ichraka — shortcodes utilisables dans Gutenberg/Elementor/CF7.
 *
 * Disponibles :
 *  [ichraka_counter number="300" label="Enfants aidés"]
 *  [ichraka_counters]            ... grille de compteurs préconfigurée
 *  [ichraka_operations limit="3"] grille d'opérations
 *  [ichraka_team]                  membres du bureau
 *  [ichraka_testimonials limit="3"]
 *  [ichraka_partners]
 *  [ichraka_donate_button text="Faire un don"]
 *  [ichraka_hero title="..." tagline="..."]
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ------------------------------------------------------------------
 * Compteurs
 * ------------------------------------------------------------------ */
add_shortcode( 'ichraka_counter', 'ichraka_sc_counter' );
function ichraka_sc_counter( $atts ) {
    $atts = shortcode_atts( array(
        'number' => '0',
        'label'  => '',
        'suffix' => '',
    ), $atts, 'ichraka_counter' );

    return sprintf(
        '<div class="ichraka-counter"><span class="ichraka-counter__number" data-target="%1$d" data-suffix="%3$s">0</span><span class="ichraka-counter__label">%2$s</span></div>',
        absint( $atts['number'] ),
        esc_html( $atts['label'] ),
        esc_attr( $atts['suffix'] )
    );
}

add_shortcode( 'ichraka_counters', 'ichraka_sc_counters' );
function ichraka_sc_counters( $atts ) {
    // Compteurs par défaut : peuvent être surchargés via le filtre `ichraka_counters`.
    $current_year = (int) date( 'Y' );
    $defaults     = array(
        array( 'number' => 300, 'label' => __( 'Enfants aidés chaque année', 'ichraka' ), 'suffix' => '+' ),
        array( 'number' => max( 1, $current_year - 2008 ), 'label' => __( 'Années d\'engagement', 'ichraka' ), 'suffix' => '' ),
        array( 'number' => 4,   'label' => __( 'Établissements partenaires', 'ichraka' ), 'suffix' => '' ),
        array( 'number' => 3,   'label' => __( 'Opérations annuelles', 'ichraka' ), 'suffix' => '' ),
    );
    $items = apply_filters( 'ichraka_counters', $defaults );

    ob_start();
    echo '<section class="ichraka-counters"><div class="ichraka-container"><div class="ichraka-counters-grid">';
    foreach ( $items as $i ) {
        echo ichraka_sc_counter( $i );
    }
    echo '</div></div></section>';
    return ob_get_clean();
}

/* ------------------------------------------------------------------
 * Grille des opérations
 * ------------------------------------------------------------------ */
add_shortcode( 'ichraka_operations', 'ichraka_sc_operations' );
function ichraka_sc_operations( $atts ) {
    $atts = shortcode_atts( array(
        'limit'  => 3,
        'orderby' => 'menu_order',
    ), $atts, 'ichraka_operations' );

    $query = new WP_Query( array(
        'post_type'      => 'operation',
        'posts_per_page' => absint( $atts['limit'] ),
        'orderby'        => sanitize_key( $atts['orderby'] ),
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ) );

    if ( ! $query->have_posts() ) {
        return '';
    }

    ob_start();
    echo '<div class="ichraka-operations-grid">';
    while ( $query->have_posts() ) {
        $query->the_post();
        ?>
        <article class="ichraka-card ichraka-operation">
            <a href="<?php the_permalink(); ?>" class="ichraka-card__media">
                <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ichraka-card' ); endif; ?>
            </a>
            <div class="ichraka-card__body">
                <h3 class="ichraka-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="ichraka-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <a class="ichraka-card__link" href="<?php the_permalink(); ?>">
                    <?php esc_html_e( 'En savoir plus →', 'ichraka' ); ?>
                </a>
            </div>
        </article>
        <?php
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}

/* ------------------------------------------------------------------
 * Bureau / équipe
 * ------------------------------------------------------------------ */
add_shortcode( 'ichraka_team', 'ichraka_sc_team' );
function ichraka_sc_team( $atts ) {
    $members = apply_filters( 'ichraka_team_members', array(
        array( 'name' => 'Safae BOUJENDAR',         'role' => __( 'Présidente', 'ichraka' ) ),
        array( 'name' => 'Asmae OUAZZANI CHAHDI',  'role' => __( 'Vice-présidente', 'ichraka' ) ),
        array( 'name' => 'Brahim FERHAT',          'role' => __( 'Secrétaire Général', 'ichraka' ) ),
        array( 'name' => 'Khalil FERHAT',          'role' => __( 'Trésorier', 'ichraka' ) ),
        array( 'name' => 'Ikrame OUAHBI',          'role' => __( 'Trésorier Adjoint', 'ichraka' ) ),
        array( 'name' => 'Fouzia KHALDI',          'role' => __( 'Conseillère', 'ichraka' ) ),
        array( 'name' => 'Imane CHERRAT',          'role' => __( 'Conseillère', 'ichraka' ) ),
    ) );

    ob_start();
    echo '<div class="ichraka-team-grid">';
    foreach ( $members as $m ) {
        $initials = ichraka_get_initials( $m['name'] );
        ?>
        <div class="ichraka-team-member">
            <div class="ichraka-team-member__avatar">
                <?php echo esc_html( $initials ); ?>
            </div>
            <p class="ichraka-team-member__name"><?php echo esc_html( $m['name'] ); ?></p>
            <p class="ichraka-team-member__role"><?php echo esc_html( $m['role'] ); ?></p>
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

/* ------------------------------------------------------------------
 * Témoignages
 * ------------------------------------------------------------------ */
add_shortcode( 'ichraka_testimonials', 'ichraka_sc_testimonials' );
function ichraka_sc_testimonials( $atts ) {
    $atts = shortcode_atts( array( 'limit' => 3 ), $atts, 'ichraka_testimonials' );

    $query = new WP_Query( array(
        'post_type'      => 'temoignage',
        'posts_per_page' => absint( $atts['limit'] ),
        'no_found_rows'  => true,
    ) );

    if ( ! $query->have_posts() ) {
        return '';
    }

    ob_start();
    echo '<div class="ichraka-testimonials">';
    while ( $query->have_posts() ) {
        $query->the_post();
        $role = get_post_meta( get_the_ID(), '_ichraka_author_role', true );
        ?>
        <article class="ichraka-testimonial">
            <div class="ichraka-testimonial__quote"><?php echo wp_kses_post( get_the_content() ); ?></div>
            <p class="ichraka-testimonial__author"><?php the_title(); ?></p>
            <?php if ( $role ) : ?>
                <p class="ichraka-testimonial__role"><?php echo esc_html( $role ); ?></p>
            <?php endif; ?>
        </article>
        <?php
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}

/* ------------------------------------------------------------------
 * Partenaires
 * ------------------------------------------------------------------ */
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

/* ------------------------------------------------------------------
 * Bouton de don
 * ------------------------------------------------------------------ */
add_shortcode( 'ichraka_donate_button', 'ichraka_sc_donate_button' );
function ichraka_sc_donate_button( $atts ) {
    $atts = shortcode_atts( array(
        'text'  => __( 'Faire un don', 'ichraka' ),
        'style' => 'donate',
    ), $atts, 'ichraka_donate_button' );

    $class = 'ichraka-btn ichraka-btn--' . sanitize_html_class( $atts['style'], 'donate' );

    return sprintf(
        '<a class="%1$s" href="%2$s">%3$s</a>',
        esc_attr( $class ),
        esc_url( ichraka_get_donate_url() ),
        esc_html( $atts['text'] )
    );
}

/* ------------------------------------------------------------------
 * Hero
 * ------------------------------------------------------------------ */
add_shortcode( 'ichraka_hero', 'ichraka_sc_hero' );
function ichraka_sc_hero( $atts ) {
    $atts = shortcode_atts( array(
        'title'   => __( 'Association Ichraka', 'ichraka' ),
        'tagline' => __( 'Ensemble, nous pouvons créer le changement', 'ichraka' ),
    ), $atts, 'ichraka_hero' );

    ob_start();
    ?>
    <section class="ichraka-hero">
        <div class="ichraka-hero__inner">
            <h1 class="ichraka-hero__title"><?php echo esc_html( $atts['title'] ); ?></h1>
            <p class="ichraka-hero__tagline"><?php echo esc_html( $atts['tagline'] ); ?></p>
            <div class="ichraka-hero__cta">
                <?php echo ichraka_sc_donate_button( array( 'text' => __( 'Faire un don', 'ichraka' ) ) ); ?>
                <a class="ichraka-btn ichraka-btn--outline" href="<?php echo esc_url( home_url( '/nos-actions/' ) ); ?>">
                    <?php esc_html_e( 'Découvrir nos actions', 'ichraka' ); ?>
                </a>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
