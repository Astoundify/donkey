<?php

class Donkey_Envato_oAuth {

    public function __construct() {
        add_action( 'wp', array( $this, 'auth_handler' ) );
    }

    public function auth_handler() {
        $code = isset( $_GET[ 'code' ] ) ? esc_attr( $_GET[ 'code' ] ) : false;

        if ( ! $code ) {
            return;
        }

        $this->generate_access_token( $code );
    }

    public function request( $url, $args ) {
        $default_request_args = array(
            'method' => 'POST',
            'sslverify' => false,
            'httpversion' => '1.1',
            'blocking' => true,
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'body' => array(
                'client_id' => donkey_get_setting( 'client_id' ),
                'client_secret' => donkey_get_setting( 'client_secret' )
            )
        );

        $request_args = donkey()->helpers->parse_args_r( $args, $default_request_args );

        $request = wp_remote_post( $url, $request_args );
        $response = json_decode( wp_remote_retrieve_body( $request ) );

        return $response;
    }

    public function refresh_access_token() {
        $user = donkey_get_user();
        $user->clear_access_token();

        $url = 'https://api.envato.com/token';

        $request_args = array(
            'body' => array(
                'grant_type' => 'refresh_token',
                'refresh_token' => $user->get_refresh_token(),
            )
        );

        $response = $this->request( $url, $request_args );

        if ( empty( $response->access_token ) ) {
            return donkey()->flash->set( __( 'Unable to retrieve access token.'. 'donkey' ) );
        }

        $user->save_access_token( $response->access_token, 3600 );
    }

    public function generate_access_token( $code ) {
        $url = 'https://api.envato.com/token';

        $request_args = array(
            'body' => array(
                'grant_type' => 'authorization_code',
                'code' => $code,
            )
        );

        $response = $this->request( $url, $request_args );

        if ( empty( $response->access_token ) ) {
            return donkey()->flash->set( 'Unable to connect to Envato', 'donkey' );
        }

        $user = donkey_get_user();
        $user->save_access_token( $response->access_token, 3600 );
        $user->save_refresh_token( $response->refresh_token );
    }

    public function auth_url() {
        $base = 'https://api.envato.com/authorization';

        $args = array(
            'response_type' => 'code',
            'client_id' => donkey_get_setting( 'client_id' ),
            'redirect_uri' => donkey_get_page_url( 'licenses' )
        );

        return esc_url( add_query_arg( $args, $base ) );
    }

    public function unauth_url() {
        $base = donkey_get_page_url( 'licenses' );
        $args = array(
            'donkey-action' => 'disconnect-oauth',
        );

        return esc_url( wp_nonce_url( add_query_arg( $args, $base ), 'disconnect-oauth' ) );
    }

}
