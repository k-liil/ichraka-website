<?php
/**
 * Logo Ichraka — soleil souriant + wordmark.
 * Reproduit fidèlement le logo CSS du design "Joyeux".
 *
 * @package Ichraka
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ichraka-logo" aria-label="<?php esc_attr_e( 'Ichraka — accueil', 'ichraka' ); ?>">
    <span class="ichraka-logo-mark" aria-hidden="true">
        <span class="ray r1"></span>
        <span class="ray r2"></span>
        <span class="ray r3"></span>
        <span class="smile"></span>
    </span>
    <span class="ichraka-logo-word">Ichraka</span>
</a>
