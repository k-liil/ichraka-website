<?php
/**
 * Template Name: Ichraka — Faire un don (Joyeux)
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$give_form_id = (int) get_option( 'ichraka_give_form_id', 0 );
?>

<section class="ichraka-page-section">
    <div class="shell">
        <?php while ( have_posts() ) : the_post(); ?>
            <header class="ichraka-page-header">
                <span class="eyebrow coral">★ <?php esc_html_e( 'Faire un don', 'ichraka' ); ?></span>
                <h1 class="display" style="margin-top: 1.5rem;"><?php the_title(); ?></h1>
            </header>
            <div class="entry-content"><?php the_content(); ?></div>
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
    </div>
</section>

<section>
    <?php echo do_shortcode( '[ichraka_donate_block]' ); ?>
</section>

<?php
get_footer();

/**
 * Fallback : affiche un message + RIB si aucun plugin de don configuré.
 */
function ichraka_render_donate_fallback() {
    ?>
    <h2 style="text-align:center;color:var(--ink);"><?php esc_html_e( 'Faire un don', 'ichraka' ); ?></h2>
    <p style="color:var(--ink-soft);">
        <?php esc_html_e( "Le module de don en ligne sera prochainement activé. En attendant, vous pouvez nous soutenir par virement bancaire :", 'ichraka' ); ?>
    </p>
    <table class="ichraka-rib-table">
        <tr><th><?php esc_html_e( 'Bénéficiaire', 'ichraka' ); ?></th><td>Association Ichraka</td></tr>
        <tr><th><?php esc_html_e( 'Banque', 'ichraka' ); ?></th><td><?php echo esc_html( get_option( 'ichraka_bank_name', '—' ) ); ?></td></tr>
        <tr><th>RIB</th><td><?php echo esc_html( get_option( 'ichraka_bank_rib', '—' ) ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Contact', 'ichraka' ); ?></th><td><a href="<?php echo esc_url( home_url( '/contactez-nous/' ) ); ?>"><?php esc_html_e( 'Nous écrire', 'ichraka' ); ?></a></td></tr>
    </table>
    <?php
}
?>
