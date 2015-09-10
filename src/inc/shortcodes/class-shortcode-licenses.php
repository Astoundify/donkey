<?php

class Donkey_Shortcode_Licenses {

    public function __construct() {
        add_shortcode( 'donkey_licenses', array( $this, 'output_shortcode' ) );
    }

    public function output_shortcode( $atts ) {
        extract( shortcode_atts( array(
        ), $atts, 'donkey_licenses' ) );

        $user = donkey_get_user();

        ob_start();

        if ( donkey()->flash->has() ) {
            donkey()->template->get( 'notice.php', array( 'message' => donkey()->flash->get() ) );
        }

        if ( ! is_user_logged_in() ) {
            return donkey()->template->find( 'login.php' );
        } elseif ( ! donkey()->api->can_make_authenticated_request() ) {
            return donkey()->template->find( 'oauth.php' );
        }

        if ( ! empty( $_REQUEST[ 'donkey-page' ] ) ) {
            $page = sanitize_title( $_REQUEST[ 'donkey-page' ] );

            if ( has_action( 'donkey_dashboard_content_' . $page ) ) {
                do_action( 'donkey_dashboard_content_' . $page, $atts );

                return ob_get_clean();
            }
        }

        donkey()->template->get( 'licenses.php', array(
            'user' => $user,
            'licenses' => $user->get_licenses()
        ) );
    }

}
