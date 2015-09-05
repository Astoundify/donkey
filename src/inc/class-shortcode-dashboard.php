<?php

class Donkey_Shortcode_Dashboard {

    public function __construct() {
        add_shortcode( 'donkey_dashboard', array( $this, 'output_shortcode' ) );
    }

    public function output_shortcode( $atts ) {
        extract( shortcode_atts( array(
        ), $atts, 'donkey_dashboard' ) );

        $user = donkey_get_user();

        ob_start();

        if ( ! is_user_logged_in() ) {
            return __( 'Please log in.', 'astoundify-rcp-envato' );
        }

		if ( donkey()->flash->has_flash() ) {
			donkey()->template->get( 'notice.php', array( 'message' => donkey()->flash->get_flash() ) );
		}

        if ( ! empty( $_REQUEST[ 'donkey-page' ] ) ) {
            $page = sanitize_title( $_REQUEST[ 'donkey-page' ] );

            if ( has_action( 'donkey_dashboard_content_' . $page ) ) {
                do_action( 'donkey_dashboard_content_' . $page, $atts );

                return ob_get_clean();
            }
        }

		donkey()->template->get( 'dashboard.php', array(
			'user' => $user,
			'licenses' => $user->get_licenses()
		) );
    }

}
