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

    /**
     * Returns the requested HTTP header.
     *
     * @param string $header
     * @return bool|string
     */
    private function getHeader( $header ) {
        if ( isset( $_SERVER[$header] ) ) {
            return $_SERVER[$header];
        }
        return false;
    }

    /**
     * Retrieve the JSON input
     *
     * @return bool|string
     */
    private function getJsonString() {
        if ( $this->input === false ) {
            $this->input = @file_get_contents( 'php://input' );
        }
        return $this->input;
    }

    /**
     * Generate the signature based on the secret key, to compare in isSignatureValid
     *
     * @return bool|string
     */
    private function generateSignature() {
        $str = $this->getJsonString();
        $sc  = donkey_get_setting( 'helpscout_secret' );
        
        if ( $str ) {
            return base64_encode( hash_hmac( 'sha1', $str, $sc, true ) );
        }

        return false;
    }

    /**
     * Returns true if the current request is a valid webhook issued from Help Scout, false otherwise.
     *
     * @return boolean
     */
    private function isSignatureValid() {
        $signature = $this->generateSignature();

        if ( !$signature || !$this->getHeader( 'HTTP_X_HELPSCOUT_SIGNATURE' ) )
            return false;

        return $signature == $this->getHeader( 'HTTP_X_HELPSCOUT_SIGNATURE' );
    }

    /**
     * Create a response.
     *
     * @return array
     */
    public function getResponse() {
        $ret = array( 'html' => '' );

        if ( !$this->isSignatureValid() ) {
            return array( 'html' => 'Invalid signature' );
        }

        $data = json_decode( $this->input, true );

        // do some stuff
        $ret['html'] = $this->fetchHtml( $data );

        return $ret;
    }

    /**
     * Generate output for the response.
     *
     * @param $data
     * @return string
     */
    private function fetchHtml( $data ) {
        global $wpdb;

        $hs_email = donkey_get_setting( 'helpscout_email' );

        if ( isset( $data['customer']['emails'] ) && is_array( $data['customer']['emails'] ) ) {
            if(($key = array_search( $hs_email, $messages)) !== false) {
                unset($data['customer']['emails'][$key]);
            }
        } else {
            if ( $data['customer']['email'] == $hs_email ) {
                return 'Cannot query customer licenses.  E-mail from ' . $hs_email;
            }
        }

        $email = $data[ 'customer' ][ 'email' ];
        $user = donkey_get_user( get_user_by( 'email', $email ) );
        $licenses = $user->get_licenses();

        $html = array();

        $html[] = '<h4 class="toggleBtn"><i class="icon-gear"></i> Licenses</h4>';
        $html[] = '<ul>';

        if ( empty( $licenses ) ) {
            $html[] = '<li>No valid licenses</li>';
        } else {
            foreach ( $license as $license ) {
                $license = donkey_get_license( $license );

                // clean name - astoundify format
                $name = $license->get_item_name();
                $name = array_map( 'trim', explode( '-', $name ) );

                $html[] = '<li><strong>' . $name[1] . '</strong>: ' . ( $license->is_active() ? 'Active' : '<span style="color: red;">Expired</span>' ) . '</li>';
            }
        }

        $html[] = '</ul>';

        return $html;
    }

}
