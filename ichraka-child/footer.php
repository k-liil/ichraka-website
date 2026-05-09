<?php
/**
 * Footer — direction Joyeux.
 *
 * @package Ichraka
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$contact_address = get_option( 'ichraka_contact_address', __( 'Témara, Maroc', 'ichraka' ) );
$contact_email   = get_option( 'ichraka_contact_email', 'contact@ichraka.ma' );
$contact_phone   = get_option( 'ichraka_contact_phone', '' );
$tagline         = get_option( 'ichraka_footer_tagline', __( 'Allumons des sourires, un cartable à la fois.', 'ichraka' ) );
?>

</main><?php // /#site-main ?>

<footer class="ichraka-footer">
    <div class="ichraka-footer-grid">

        <div class="ichraka-footer-brand">
            <?php get_template_part( 'template-parts/logo' ); ?>
            <p class="ichraka-footer-tagline">
                <?php
                // Met en avant le mot "sourires" en jaune si présent.
                $tagline_html = preg_replace(
                    '/(sourires)/iu',
                    '<span class="hl">$1</span>',
                    esc_html( $tagline ),
                    1
                );
                echo wp_kses_post( $tagline_html );
                ?>
            </p>
        </div>

        <div class="ichraka-footer-col">
            <h4><?php esc_html_e( "L'association", 'ichraka' ); ?></h4>
            <ul>
                <li><a href="<?php echo esc_url( home_url( '/qui-sommes-nous/' ) ); ?>"><?php esc_html_e( 'Qui sommes-nous', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/qui-sommes-nous/notre-mission/' ) ); ?>"><?php esc_html_e( 'Notre mission', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/qui-sommes-nous/notre-charte/' ) ); ?>"><?php esc_html_e( 'Charte éthique', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/qui-sommes-nous/responsabilites/' ) ); ?>"><?php esc_html_e( 'Équipe', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Bilans annuels', 'ichraka' ); ?></a></li>
            </ul>
        </div>

        <div class="ichraka-footer-col">
            <h4><?php esc_html_e( "S'engager", 'ichraka' ); ?></h4>
            <ul>
                <li><a href="<?php echo esc_url( ichraka_get_donate_url() ); ?>"><?php esc_html_e( 'Faire un don', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/parrainage/' ) ); ?>"><?php esc_html_e( 'Parrainer un enfant', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/contactez-nous/' ) ); ?>"><?php esc_html_e( 'Devenir bénévole', 'ichraka' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/contactez-nous/' ) ); ?>"><?php esc_html_e( "Don d'entreprise", 'ichraka' ); ?></a></li>
            </ul>
        </div>

        <div class="ichraka-footer-col">
            <h4><?php esc_html_e( 'Contact', 'ichraka' ); ?></h4>
            <p>
                <?php echo nl2br( esc_html( $contact_address ) ); ?><br><br>
                <a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
                <?php if ( $contact_phone ) : ?>
                    <br><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $contact_phone ) ); ?>"><?php echo esc_html( $contact_phone ); ?></a>
                <?php endif; ?>
            </p>
        </div>

    </div>

    <div class="ichraka-footer-bottom">
        <span><?php
            printf(
                /* translators: %s: current year */
                esc_html__( '© %s Association Ichraka — Reconnue d\'utilité publique. Fondée en 2008 à Témara.', 'ichraka' ),
                esc_html( date_i18n( 'Y' ) )
            );
        ?></span>
        <div class="links">
            <a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>"><?php esc_html_e( 'Mentions légales', 'ichraka' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/confidentialite/' ) ); ?>"><?php esc_html_e( 'Confidentialité', 'ichraka' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>"><?php esc_html_e( 'Cookies', 'ichraka' ); ?></a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
