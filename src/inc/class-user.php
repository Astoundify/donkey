<?php

class Donkey_User {

    public $user;
    public $ID;

    public function __construct( $user = false ) {
        if ( ! $user ) {
            $user = get_current_user_id();
        }

        $this->user = new WP_User( $user );

        // easier
        $this->ID = $this->user->ID;
    }

	public function get_username() {
		if ( ! $this->user->envato_username ) {
	        $response = donkey()->api->authenticated_request( 'https://api.envato.com/v1/market/private/user/username.json', array() );

			if ( ! isset( $response->error ) ) {
				add_user_meta( $this->ID, 'envato_username', $response->username, true );

				return $response->username;
			} else {
				return $user->user_login;
			}
		}

		return $this->user->envato_username;
	}

    public function get_access_token() {
        return get_transient( 'donkey_user_' . $this->ID . '_access_token' );
    }

    public function get_refresh_token() {
        return $this->user->refresh_token;
    }

    public function save_access_token( $token, $expires ) {
        set_transient( 'donkey_user_' . $this->ID . '_access_token', $token, $expires );
    }

    public function save_refresh_token( $token ) {
        add_user_meta( $this->ID, 'refresh_token', $token, true );
    }

    public function clear_access_token() {
        delete_transient( 'donkey_user_' . $this->ID . '_access_token' );
    }

	public function clear_refresh_token() {
		delete_user_meta( $this->ID, 'refresh_token' );
	}

	public function clear_oauth() {
		$this->clear_access_token();
		$this->clear_refresh_token();
	}

	public function get_licenses() {
		global $wpdb;

		$licenses = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}donkey_licenses WHERE user_id = '%s'", $this->ID ) );

		return $licenses;
	}

}
