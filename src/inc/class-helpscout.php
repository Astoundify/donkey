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

        $hs_email = donkey_get_setting( 'helpscout_email' );

        $html = array();

        $email = $data[ 'customer' ][ 'email' ];
        $user = get_user_by( 'email', $email );
        $user = donkey_get_user( $user->ID );
        $licenses = $user->get_licenses();

        $html[] = '<p><strong>' . $user->get_envato_username() . '</strong></p>';
        $html[] = '<p>' . $user->get_envato_display_name() . ', ' . $user->get_envato_country() . '</p>';

        if ( ! empty( $licenses ) ) {

            $html[] = '<table>';

            foreach ( $licenses as $license ) {
                $license = donkey_get_license( $license );

                // clean name - astoundify format
                $name = $license->get_item_name();
                $name = array_map( 'trim', explode( '-', $name ) );

                $html[] = '<tr>';
                $html[] = '<td style="border: 1px solid #ccc;">' . $name[1] . '</td>';
                $html[] = '<td style="border: 1px solid #ccc;">' . $license->get_code() . '</td>';
                $html[] = '<td style="border: 1px solid #ccc;">' . ( $license->is_active() ? 'Active' : '<span style="color: red;">Expired</span>' ) . '</td>';
                $html[] = '</tr>';
            }
            
            $html[] = '<table>';

        } else {
            $html[] = '<p>' . __( 'No licenses', 'donkey' ) . '</p>';
        }

        return implode( '', $html );

        return $html;
    }

}
