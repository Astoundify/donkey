<?php

class Donkey_Envato_API {

    public function __construct() {
        $this->api_base = 'https://api.envato.com/v2/';
    }

    public function can_make_authenticated_request() {
        $user = donkey_get_user();

        return $user->get_access_token();
    }

    public function authenticated_request( $action, $args = array() ) {
        if ( ! $this->can_make_authenticated_request() ) {
            donkey()->oauth->refresh_access_token( $action, $args );

            return $this->authenticated_request( $action, $args );
        } else {
            return $this->make_authenticated_request( $action, $args );
        }
    }

    public function make_authenticated_request( $action, $args = array() ) {
        $user = donkey_get_user();

        $default_request_args = array(
            'method' => 'GET',
            'sslverify' => false,
            'httpversion' => '1.1',
            'blocking' => true,
            'headers' => array(
                'Authorization' => 'Bearer ' . $user->get_access_token(),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
        );

        $request_args = donkey()->helpers->parse_args_r( $args, $default_request_args );

        $request = wp_remote_post( $this->get_url( $action, $args ), $request_args );
        $response = json_decode( wp_remote_retrieve_body( $request ) );

        if ( isset( $response->error ) && 'forbidden' == $response->error ) {
            donkey()->oauth->refresh_access_token( $action, $args );
        }

        return $response;
    }

    private function get_url( $action, $args ) {
		if ( filter_var( $action, FILTER_VALIDATE_URL ) ) {
			$base = $action;
		}else {
			$base = trailingslashit( $this->api_base . $action );
		}

        return esc_url( add_query_arg( $args, $base ) );
    }

}
