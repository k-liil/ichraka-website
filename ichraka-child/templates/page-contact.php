<?php
/**
 * Template Name: Ichraka — Contact
 *
 * Combine le contenu de la page (description, adresse), un formulaire CF7
 * et la carte Google Maps de Témara.
 *
 * Configuration :
 *  - Réglages → Général → "ID Contact Form 7"
 *  - Réglages → Général → "Lat/Long Google Maps"
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
ichraka_breadcrumbs();

$cf7_id     = (int) get_option( 'ichraka_cf7_id', 0 );
$gmap_q     = get_option( 'ichraka_gmap_query', 'Temara, Maroc' );
$email      = get_option( 'ichraka_contact_email', 'contact@ichraka.ma' );
$phone      = get_option( 'ichraka_contact_phone', '' );
$address    = get_option( 'ichraka_contact_address', __( 'Témara, Maroc', 'ichraka' ) );
?>

<main id="primary" class="site-main">

    <section class="ichraka-section">
        <div class="ichraka-container">

            <?php while ( have_posts() ) : the_post(); ?>
                <header class="entry-header" style="text-align:center;margin-bottom:2.5rem;">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
            <?php endwhile; ?>

            <div class="ichraka-contact-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;">

                <div class="ichraka-contact-info">
                    <h2><?php esc_html_e( 'Nos coordonnées', 'ichraka' ); ?></h2>

                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <div class="entry-content"><?php the_content(); ?></div>
                    <?php endwhile; rewind_posts(); endif; ?>

                    <ul style="list-style:none;padding:0;margin:1.5rem 0;">
                        <li style="margin-bottom:1rem;">
                            <strong><?php esc_html_e( 'Adresse', 'ichraka' ); ?></strong><br>
                            <?php echo esc_html( $address ); ?>
                        </li>
                        <li style="margin-bottom:1rem;">
                            <strong>Email</strong><br>
                            <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                        </li>
                        <?php if ( $phone ) : ?>
                        <li style="margin-bottom:1rem;">
                            <strong><?php esc_html_e( 'Téléphone', 'ichraka' ); ?></strong><br>
                            <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="ichraka-contact-form">
                    <h2><?php esc_html_e( 'Écrivez-nous', 'ichraka' ); ?></h2>

                    <?php if ( $cf7_id && shortcode_exists( 'contact-form-7' ) ) : ?>
                        <?php echo do_shortcode( '[contact-form-7 id="' . absint( $cf7_id ) . '"]' ); ?>
                    <?php else : ?>
                        <?php ichraka_render_basic_contact_form(); ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <?php /* Carte Google Maps — embed sans clé API (mode q=) */ ?>
    <section class="ichraka-section--map" aria-label="<?php esc_attr_e( 'Localisation sur la carte', 'ichraka' ); ?>">
        <iframe
            src="https://www.google.com/maps?q=<?php echo urlencode( $gmap_q ); ?>&output=embed"
            width="100%"
            height="400"
            style="border:0;display:block;"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="<?php esc_attr_e( 'Carte Google Maps — Témara', 'ichraka' ); ?>">
        </iframe>
    </section>

</main>

<style>
@media (max-width: 768px) {
    .ichraka-contact-grid { grid-template-columns: 1fr !important; gap: 2rem !important; }
}
</style>

<?php
get_footer();

/**
 * Formulaire de contact minimal sans dépendance — fallback si CF7 absent.
 *
 * Utilise wp_mail() avec une nonce pour limiter les abus.
 */
function ichraka_render_basic_contact_form() {
    $sent = false;
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
        echo '<div class="ichraka-alert ichraka-alert--success" style="background:#D4EDDA;color:#155724;padding:1rem;border-radius:8px;">' . esc_html__( 'Merci, votre message a bien été envoyé.', 'ichraka' ) . '</div>';
        return;
    }
    if ( $error ) {
        echo '<div class="ichraka-alert ichraka-alert--error" style="background:#F8D7DA;color:#721C24;padding:1rem;border-radius:8px;margin-bottom:1rem;">' . esc_html( $error ) . '</div>';
    }
    ?>
    <form method="post" class="ichraka-basic-form">
        <?php wp_nonce_field( 'ichraka_contact', 'ichraka_contact_nonce' ); ?>
        <p>
            <label for="if-name"><?php esc_html_e( 'Votre nom', 'ichraka' ); ?> *</label>
            <input class="wpcf7-form-control" id="if-name" name="name" type="text" required>
        </p>
        <p>
            <label for="if-email"><?php esc_html_e( 'Votre email', 'ichraka' ); ?> *</label>
            <input class="wpcf7-form-control" id="if-email" name="email" type="email" required>
        </p>
        <p>
            <label for="if-msg"><?php esc_html_e( 'Votre message', 'ichraka' ); ?> *</label>
            <textarea class="wpcf7-form-control" id="if-msg" name="message" rows="6" required></textarea>
        </p>
        <p>
            <button type="submit" class="ichraka-btn ichraka-btn--primary"><?php esc_html_e( 'Envoyer', 'ichraka' ); ?></button>
        </p>
    </form>
    <?php
}
?>
