<?php
/**
 * Logo Ichraka — affiche l'image uploadée si dispo, sinon fallback CSS
 * (soleil souriant + wordmark).
 *
 * Pour utiliser un logo image :
 *   - Apparence → Personnaliser → Identité du site → Logo (Astra)
 *   - Ou : wp option update ichraka_logo_id <attachment-id>
 *
 * @package Ichraka
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$logo_id = (int) get_option( 'ichraka_logo_id', 0 );
if ( ! $logo_id ) {
    $logo_id = (int) get_theme_mod( 'custom_logo', 0 );
}
?>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ichraka-logo" aria-label="<?php esc_attr_e( 'Ichraka — accueil', 'ichraka' ); ?>">
    <?php if ( $logo_id ) :
        echo wp_get_attachment_image( $logo_id, 'medium', false, array(
            'class' => 'ichraka-logo-img',
            'alt'   => esc_attr( get_bloginfo( 'name' ) ),
        ) );
    else : ?>
        <span class="ichraka-logo-mark" aria-hidden="true">
            <span class="ray r1"></span>
            <span class="ray r2"></span>
            <span class="ray r3"></span>
            <span class="smile"></span>
        </span>
        <span class="ichraka-logo-word">Ichraka</span>
    <?php endif; ?>
</a>
