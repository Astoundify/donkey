<?php

class Donkey_Helpscout {

    private $input = false;

    public function __construct() {
        add_action( 'init', array( $this, 'add_rewrite_endpoints' ) );
        add_action( 'template_redirect', array( $this, 'template_redirect' ) );
    }

    public function add_rewrite_endpoints() {
        add_rewrite_endpoint( 'helpscout', EP_PAGES );
    }

    public function template_redirect() {
        global $wp_query;

        if ( isset( $wp_query->query_vars[ 'helpscout' ] ) ) {
            echo json_encode( $this->getResponse() );

            exit();
        }
    }

    public function getResponse() {
        $sc  = get_option( 'donkey', array() );
        $sc  = $sc[ 'helpscout_secret' ];

        $data = file_get_contents('php://input');

        $signature = $_SERVER['HTTP_X_HELPSCOUT_SIGNATURE'];

        $calculated = base64_encode( hash_hmac( 'sha1', $data, $sc, true ) );

        $ret = array( 'html' => '' );

        if ( ! $signature == $calculated ) {
            return array( 'html' => 'Invalid signature' );
        }

        $data = json_decode( $data, true );

        $ret['html'] = $this->fetchHtml( $data );
        // $ret['html'] = '<pre>'.print_r($data,1).'</pre>' . $ret['html'];
        return $ret;
    }

    private function fetchHtml( $data ) {
        global $wpdb;

        $html = array();

        $email = $data[ 'customer' ][ 'email' ];
        $user = get_user_by( 'email', $email );
        $user = donkey_get_user( $user->ID );
        $licenses = $user->get_licenses();

		if ( '' != $user->get_envato_username() ) {
			$html[] = '<p>Envato Username: <strong>' . $user->get_envato_username() . '</strong></p>';

			if ( '' != $user->get_envato_display_name() ) {
				$html[] = '<p>' . $user->get_envato_display_name() . ', ' . $user->get_envato_country() . '</p>';
			}
		}

        if ( ! empty( $licenses ) ) {

            foreach ( $licenses as $license ) {
                $license = donkey_get_license( $license );

                // clean name - astoundify format
                $name = $license->get_item_name();
                $name = str_replace( array( 'WordPress Directory Theme', 'Digital Marketplace WordPress Theme', 'WordPress Job Board Theme', '-' ), '', $name );
				$name = trim( $name );

				$html[] = '<table>';
                $html[] = '<tr>';
                $html[] = '<td style="border: 1px solid #ccc;"><strong>' . $name . '</strong></td>';
                $html[] = '<td style="border: 1px solid #ccc;"><span style="badge ' . ( $license->is_active() ? 'green' : 'red' ) . ' ">' . $license->is_active() ? 'Valid' : 'Expired' . '</span></td>';
				$html[] = '</tr>';
                $html[] = '<tr>';
                $html[] = '<td colspan="2" style="border: 1px solid #ccc;">' . $license->get_code() . '</td>';
                $html[] = '</tr>';
                $html[] = '<tr>';
                $html[] = '<td colspan="2" style="border: 1px solid #ccc;">Expires: ' . $license->get_expiration( 'Y-m-d' ) . '</td>';
                $html[] = '</tr>';
				$html[] = '<table>';
            }

        } else {
            $html[] = '<p>' . __( 'No licenses', 'donkey' ) . '</p>';
        }

        return implode( '', $html );

        return $html;
    }

}
