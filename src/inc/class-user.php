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

    public function get_envato_username() {
        return $this->user->envato_username;
    }

    public function get_envato_image() {
        return $this->user->envato_image;
    }

    public function get_envato_display_name() {
        return $this->user->envato_firstname . ' ' . $this->user->envato_surname;
    }

    public function get_envato_country() {
        return $this->user->envato_country;
    }

    public function get_refresh_token() {
        return $this->user->envato_refresh_token;
    }

    public function get_licenses() {
        global $wpdb;

        // $licenses = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}donkey_licenses WHERE user_id = '%s'", $this->ID ) );

		if ( isset( $license->supported_until ) && ( strtotime( $license->supported_until ) < current_time( 'timestamp'  ) ) ) {
		}

        return $licenses;
    }

}
