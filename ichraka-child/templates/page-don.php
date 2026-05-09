<?php
/**
 * Template Name: Ichraka — Faire un don
 *
 * Cette page intègre le formulaire de don GiveWP via le shortcode
 * [give_form id="X"] (à configurer par l'administrateur via une option) ou
 * affiche un texte de fallback si aucun ID n'est renseigné.
 *
 * Configuration : Réglages → Général → "ID formulaire GiveWP".
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
ichraka_breadcrumbs();

$give_form_id = (int) get_option( 'ichraka_give_form_id', 0 );
?>

<main id="primary" class="site-main">

    <section class="ichraka-section">
        <div class="ichraka-container ichraka-container--narrow">

            <?php while ( have_posts() ) : the_post(); ?>
                <header class="entry-header" style="text-align:center;margin-bottom:2rem;">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; ?>

            <div class="ichraka-donation-block">
                <?php if ( $give_form_id && shortcode_exists( 'give_form' ) ) : ?>
                    <?php echo do_shortcode( '[give_form id="' . absint( $give_form_id ) . '"]' ); ?>
                <?php elseif ( shortcode_exists( 'wpforms' ) ) : ?>
                    <?php
                    $wpf = (int) get_option( 'ichraka_wpforms_donate_id', 0 );
                    if ( $wpf ) {
                        echo do_shortcode( '[wpforms id="' . $wpf . '"]' );
                    } else {
                        ichraka_render_donate_fallback();
                    }
                    ?>
                <?php else : ?>
                    <?php ichraka_render_donate_fallback(); ?>
                <?php endif; ?>
            </div>

            <div style="margin-top:3rem;text-align:center;">
                <h3><?php esc_html_e( 'Pourquoi nous faire confiance ?', 'ichraka' ); ?></h3>
                <ul style="list-style:none;padding:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-top:1.5rem;">
                    <li>
                        <strong style="display:block;font-size:2rem;color:var(--ichraka-primary);"><?php echo esc_html( max( 1, (int) date( 'Y' ) - 2008 ) ); ?>+</strong>
                        <?php esc_html_e( 'années d\'engagement', 'ichraka' ); ?>
                    </li>
                    <li>
                        <strong style="display:block;font-size:2rem;color:var(--ichraka-primary);">300+</strong>
                        <?php esc_html_e( 'enfants aidés chaque année', 'ichraka' ); ?>
                    </li>
                    <li>
                        <strong style="display:block;font-size:2rem;color:var(--ichraka-primary);">100%</strong>
                        <?php esc_html_e( 'des fonds vont aux actions', 'ichraka' ); ?>
                    </li>
                </ul>
            </div>

        </div>
    </section>

</main>

<?php
get_footer();

/**
 * Fallback : affiche un message + coordonnées RIB / virement direct quand
 * aucun plugin de paiement n'est encore configuré.
 */
function ichraka_render_donate_fallback() {
    ?>
    <h2 style="text-align:center;"><?php esc_html_e( 'Faire un don', 'ichraka' ); ?></h2>
    <p>
        <?php esc_html_e( 'Le module de don en ligne sera prochainement activé. En attendant, vous pouvez nous soutenir par virement bancaire :', 'ichraka' ); ?>
    </p>
    <table class="ichraka-rib-table" style="width:100%;border-collapse:collapse;margin:1.5rem 0;">
        <tr><th style="text-align:left;padding:0.5rem;border-bottom:1px solid #eee;"><?php esc_html_e( 'Bénéficiaire', 'ichraka' ); ?></th><td style="padding:0.5rem;border-bottom:1px solid #eee;">Association Ichraka</td></tr>
        <tr><th style="text-align:left;padding:0.5rem;border-bottom:1px solid #eee;"><?php esc_html_e( 'Banque', 'ichraka' ); ?></th><td style="padding:0.5rem;border-bottom:1px solid #eee;"><?php echo esc_html( get_option( 'ichraka_bank_name', '—' ) ); ?></td></tr>
        <tr><th style="text-align:left;padding:0.5rem;border-bottom:1px solid #eee;">RIB</th><td style="padding:0.5rem;border-bottom:1px solid #eee;"><?php echo esc_html( get_option( 'ichraka_bank_rib', '—' ) ); ?></td></tr>
        <tr><th style="text-align:left;padding:0.5rem;"><?php esc_html_e( 'Contact', 'ichraka' ); ?></th><td style="padding:0.5rem;"><a href="<?php echo esc_url( home_url( '/contactez-nous/' ) ); ?>"><?php esc_html_e( 'Nous écrire', 'ichraka' ); ?></a></td></tr>
    </table>
    <?php
}
?>
