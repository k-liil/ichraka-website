<?php
/**
 * Template Name: Ichraka — Contact (Joyeux)
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$cf7_id   = (int) get_option( 'ichraka_cf7_id', 0 );
$gmap_q   = get_option( 'ichraka_gmap_query', 'Temara, Maroc' );
$email    = get_option( 'ichraka_contact_email', 'contact@ichraka.ma' );
$phone    = get_option( 'ichraka_contact_phone', '' );
$address  = get_option( 'ichraka_contact_address', __( 'Témara, Maroc', 'ichraka' ) );
?>

<section class="ichraka-page-section">
    <div class="shell">
        <?php while ( have_posts() ) : the_post(); ?>
            <header class="ichraka-page-header">
                <span class="eyebrow sky">★ <?php esc_html_e( 'Contact', 'ichraka' ); ?></span>
                <h1 class="display" style="margin-top: 1.5rem;"><?php the_title(); ?></h1>
            </header>
        <?php endwhile; ?>

        <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:4rem;align-items:start;">
            <div>
                <h2 class="display" style="font-size: 1.8rem;"><?php esc_html_e( 'Nos coordonnées', 'ichraka' ); ?></h2>

                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <div class="entry-content" style="margin: 1.5rem 0;"><?php the_content(); ?></div>
                <?php endwhile; rewind_posts(); endif; ?>

                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:1.2rem;">
                    <li>
                        <strong style="display:block;color:var(--ink);font-family:var(--font-display);font-size:1.1rem;"><?php esc_html_e( 'Adresse', 'ichraka' ); ?></strong>
                        <?php echo esc_html( $address ); ?>
                    </li>
                    <li>
                        <strong style="display:block;color:var(--ink);font-family:var(--font-display);font-size:1.1rem;">Email</strong>
                        <a href="mailto:<?php echo esc_attr( $email ); ?>" style="color:var(--coral);"><?php echo esc_html( $email ); ?></a>
                    </li>
                    <?php if ( $phone ) : ?>
                    <li>
                        <strong style="display:block;color:var(--ink);font-family:var(--font-display);font-size:1.1rem;"><?php esc_html_e( 'Téléphone', 'ichraka' ); ?></strong>
                        <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" style="color:var(--coral);"><?php echo esc_html( $phone ); ?></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div>
                <h2 class="display" style="font-size: 1.8rem;"><?php esc_html_e( 'Écrivez-nous', 'ichraka' ); ?></h2>

                <?php if ( $cf7_id && shortcode_exists( 'contact-form-7' ) ) : ?>
                    <?php echo do_shortcode( '[contact-form-7 id="' . absint( $cf7_id ) . '"]' ); ?>
                <?php else : ?>
                    <?php ichraka_render_basic_contact_form(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section style="padding: 0; margin-top: var(--section-gap);">
    <iframe
        src="https://www.google.com/maps?q=<?php echo urlencode( $gmap_q ); ?>&output=embed"
        width="100%"
        height="450"
        style="border:0;display:block;"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="<?php esc_attr_e( 'Carte Google Maps — Témara', 'ichraka' ); ?>">
    </iframe>
</section>

<style>
@media (max-width: 880px) {
    .ichraka-page-section .shell > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 2.5rem !important;
    }
}
</style>

<?php
get_footer();

function ichraka_render_basic_contact_form() {
    $sent  = false;
    $error = '';

    if ( isset( $_POST['ichraka_contact_nonce'] ) && wp_verify_nonce( $_POST['ichraka_contact_nonce'], 'ichraka_contact' ) ) {
        $name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
        $email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
        $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

        if ( $name && is_email( $email ) && $message ) {
            $to      = get_option( 'ichraka_contact_email', get_option( 'admin_email' ) );
            $subject = sprintf( __( '[Ichraka] Message de %s', 'ichraka' ), $name );
            $body    = $message . "\n\n--\n" . $name . ' <' . $email . '>';
            $headers = array( 'Reply-To: ' . $email );
            $sent = wp_mail( $to, $subject, $body, $headers );
            if ( ! $sent ) {
                $error = __( 'Une erreur est survenue, merci de réessayer.', 'ichraka' );
            }
        } else {
            $error = __( 'Merci de remplir tous les champs correctement.', 'ichraka' );
        }
    }

    if ( $sent ) {
        echo '<div class="ichraka-alert ichraka-alert--success">' . esc_html__( 'Merci, votre message a bien été envoyé.', 'ichraka' ) . '</div>';
        return;
    }
    if ( $error ) {
        echo '<div class="ichraka-alert ichraka-alert--error" style="margin-bottom:1rem;">' . esc_html( $error ) . '</div>';
    }
    ?>
    <form method="post" class="ichraka-basic-form">
        <?php wp_nonce_field( 'ichraka_contact', 'ichraka_contact_nonce' ); ?>
        <p style="margin-bottom:1rem;">
            <label for="if-name" style="display:block;margin-bottom:0.4rem;font-weight:600;color:var(--ink);"><?php esc_html_e( 'Votre nom', 'ichraka' ); ?> *</label>
            <input id="if-name" name="name" type="text" required>
        </p>
        <p style="margin-bottom:1rem;">
            <label for="if-email" style="display:block;margin-bottom:0.4rem;font-weight:600;color:var(--ink);"><?php esc_html_e( 'Votre email', 'ichraka' ); ?> *</label>
            <input id="if-email" name="email" type="email" required>
        </p>
        <p style="margin-bottom:1.5rem;">
            <label for="if-msg" style="display:block;margin-bottom:0.4rem;font-weight:600;color:var(--ink);"><?php esc_html_e( 'Votre message', 'ichraka' ); ?> *</label>
            <textarea id="if-msg" name="message" rows="6" required></textarea>
        </p>
        <p>
            <button type="submit"><?php esc_html_e( 'Envoyer', 'ichraka' ); ?></button>
        </p>
    </form>
    <?php
}
?>
