<?php
/**
 * Ichraka — widgets personnalisés.
 *
 * Trois widgets exposés au customizer :
 *  - Ichraka_Donate_Widget    : carte appel aux dons
 *  - Ichraka_Operations_Widget: dernières opérations
 *  - Ichraka_Counter_Widget   : compteur animé
 *
 * @package Ichraka
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'widgets_init', 'ichraka_register_widgets' );
function ichraka_register_widgets() {
    register_widget( 'Ichraka_Donate_Widget' );
    register_widget( 'Ichraka_Operations_Widget' );
    register_widget( 'Ichraka_Counter_Widget' );
}

/**
 * Widget — Carte d'appel aux dons.
 */
class Ichraka_Donate_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'ichraka_donate',
            __( 'Ichraka — Appel aux dons', 'ichraka' ),
            array( 'description' => __( 'Carte avec bouton "Faire un don".', 'ichraka' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title    = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Soutenez nos actions', 'ichraka' );
        $message  = ! empty( $instance['message'] ) ? $instance['message'] : __( 'Votre don change la vie d\'un enfant.', 'ichraka' );
        $btn_text = ! empty( $instance['btn_text'] ) ? $instance['btn_text'] : __( 'Faire un don', 'ichraka' );
        $url      = ichraka_get_donate_url();

        echo $args['before_widget']; // phpcs:ignore
        echo '<div class="ichraka-donate-widget">';
        echo '<h4>' . esc_html( $title ) . '</h4>';
        echo '<p>' . esc_html( $message ) . '</p>';
        printf(
            '<a class="ichraka-btn ichraka-btn--donate" href="%s">%s</a>',
            esc_url( $url ),
            esc_html( $btn_text )
        );
        echo '</div>';
        echo $args['after_widget']; // phpcs:ignore
    }

    public function form( $instance ) {
        $title    = $instance['title']    ?? '';
        $message  = $instance['message']  ?? '';
        $btn_text = $instance['btn_text'] ?? '';
        ?>
        <p>
            <label><?php esc_html_e( 'Titre', 'ichraka' ); ?></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label><?php esc_html_e( 'Message', 'ichraka' ); ?></label>
            <textarea class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'message' ) ); ?>"><?php echo esc_textarea( $message ); ?></textarea>
        </p>
        <p>
            <label><?php esc_html_e( 'Texte bouton', 'ichraka' ); ?></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'btn_text' ) ); ?>" value="<?php echo esc_attr( $btn_text ); ?>">
        </p>
        <?php
    }

    public function update( $new, $old ) {
        return array(
            'title'    => sanitize_text_field( $new['title'] ?? '' ),
            'message'  => sanitize_textarea_field( $new['message'] ?? '' ),
            'btn_text' => sanitize_text_field( $new['btn_text'] ?? '' ),
        );
    }
}

/**
 * Widget — Liste des dernières opérations.
 */
class Ichraka_Operations_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'ichraka_operations',
            __( 'Ichraka — Dernières opérations', 'ichraka' ),
            array( 'description' => __( 'Liste des N dernières opérations publiées.', 'ichraka' ) )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Nos opérations', 'ichraka' );
        $count = absint( $instance['count'] ?? 3 );

        $query = new WP_Query( array(
            'post_type'      => 'operation',
            'posts_per_page' => $count,
            'no_found_rows'  => true,
        ) );

        echo $args['before_widget']; // phpcs:ignore
        echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore

        if ( $query->have_posts() ) {
            echo '<ul class="ichraka-widget-operations">';
            while ( $query->have_posts() ) {
                $query->the_post();
                printf(
                    '<li><a href="%s">%s</a></li>',
                    esc_url( get_permalink() ),
                    esc_html( get_the_title() )
                );
            }
            echo '</ul>';
            wp_reset_postdata();
        }

        echo $args['after_widget']; // phpcs:ignore
    }

    public function form( $instance ) {
        $title = $instance['title'] ?? '';
        $count = $instance['count'] ?? 3;
        ?>
        <p>
            <label><?php esc_html_e( 'Titre', 'ichraka' ); ?></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label><?php esc_html_e( 'Nombre', 'ichraka' ); ?></label>
            <input class="widefat" type="number" min="1" max="10" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr( $count ); ?>">
        </p>
        <?php
    }

    public function update( $new, $old ) {
        return array(
            'title' => sanitize_text_field( $new['title'] ?? '' ),
            'count' => absint( $new['count'] ?? 3 ),
        );
    }
}

/**
 * Widget — Compteur animé.
 */
class Ichraka_Counter_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'ichraka_counter',
            __( 'Ichraka — Compteur', 'ichraka' ),
            array( 'description' => __( 'Compteur animé (chiffre + libellé).', 'ichraka' ) )
        );
    }

    public function widget( $args, $instance ) {
        $number = absint( $instance['number'] ?? 0 );
        $label  = $instance['label'] ?? '';

        echo $args['before_widget']; // phpcs:ignore
        printf(
            '<div class="ichraka-counter"><span class="ichraka-counter__number" data-target="%d">0</span><span class="ichraka-counter__label">%s</span></div>',
            $number,
            esc_html( $label )
        );
        echo $args['after_widget']; // phpcs:ignore
    }

    public function form( $instance ) {
        $number = $instance['number'] ?? 0;
        $label  = $instance['label'] ?? '';
        ?>
        <p>
            <label><?php esc_html_e( 'Nombre cible', 'ichraka' ); ?></label>
            <input class="widefat" type="number" min="0" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" value="<?php echo esc_attr( $number ); ?>">
        </p>
        <p>
            <label><?php esc_html_e( 'Libellé', 'ichraka' ); ?></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'label' ) ); ?>" value="<?php echo esc_attr( $label ); ?>">
        </p>
        <?php
    }

    public function update( $new, $old ) {
        return array(
            'number' => absint( $new['number'] ?? 0 ),
            'label'  => sanitize_text_field( $new['label'] ?? '' ),
        );
    }
}
