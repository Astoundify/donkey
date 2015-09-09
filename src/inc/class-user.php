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

    public function get_envato_account() {
        $response = donkey()->api->authenticated_request( 'https://api.envato.com/v1/market/private/user/username.json', array() );

        if ( ! isset( $response->error ) ) {
            add_user_meta( $this->ID, 'envato_username', $response->username, true );
        }

        $save = array(
            'image', 'firstname', 'surname', 'country'
        );

        $response = donkey()->api->authenticated_request( 'https://api.envato.com/v1/market/private/user/account.json', array() );

        if ( ! isset( $response->error ) ) {
            update_user_meta( $this->ID, 'envato_image', $response->account->image );
            update_user_meta( $this->ID, 'envato_firstname', $response->account->firstname );
            update_user_meta( $this->ID, 'envato_surname', $response->account->surname );
            update_user_meta( $this->ID, 'envato_country', $response->account->country );
        }

        add_user_meta( $this->ID, 'envato_account', true, true );
    }

    public function get_envato_username() {
        if ( ! $this->user->envato_account ) {
            $this->get_envato_account();
        }

        return $this->user->envato_username;
    }

    public function get_envato_image() {
        if ( ! $this->user->envato_account ) {
            $this->get_envato_account();
        }

        return $this->user->envato_image;
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

    public function validate_licenses() {
        $licenses = $this->get_licenses();

        if ( empty( $licenses ) ) {
            return;
        }

        foreach ( $licenses as $license ) {
            $license  = donkey_get_license( $license );
            $response = donkey()->api->authenticated_request( 'market/buyer/purchase', array(
                'code' => $license->get_code()
            ) );

            // no longer valid
            if ( isset( $response->error ) ) {
                $license->delete();
            } else {
            // update expiration if changed
                $license->update( array(
                    'id' => $license->get_id(),
                    'expiration' => $response->supported_until
                ) );
            }
        }
    }

}
