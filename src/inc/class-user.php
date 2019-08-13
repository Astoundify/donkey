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

    public function get_token_timestamp() {
        return $this->user->envato_token_timestamp;
    }

    public function get_token_expire_time() {
        return $this->user->envato_token_expire_time;
    }

    public function get_licenses() {
		$licenses = array();
		$access_token = \edd_envato_login\envato_api\Functions::get_user_token( $this->ID );

		// couldn't reach Envato so they have no licenses
		if ( ! $access_token ) {
			return $licenses;
		}

		$args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . esc_attr( $access_token ),
			),
		);

		$url = 'https://api.envato.com/v3/market/buyer/list-purchases';

		$raw_response = wp_remote_get( esc_url_raw( $url ), $args );

		if ( ! is_wp_error( $raw_response ) || 200 == wp_remote_retrieve_response_code( $raw_response ) ) {
			$response = json_decode( wp_remote_retrieve_body( $raw_response ), true );
			$whitelist = donkey_get_allowed_products();

			$transient_key = base64_encode( get_current_user_id() ) . '_envato_items';
			// save a transient with all the items bought by this user.
			if ( ! empty( $response[ 'results' ] ) ) {
				set_transient( $transient_key, $response, 3600 );

			} else {
				set_transient( $transient_key, 'no-themeforest-items', 3600 );

			}

			foreach ( $response[ 'results' ] as $purchase => $purchase_data ) {
				if ( ! in_array( $purchase_data[ 'item' ][ 'id' ], $whitelist ) ) {
					continue;
				}

				$licenses[ $purchase_data[ 'code' ] ] = array(
					'id' => $purchase_data[ 'code' ],
					'item_id' => $purchase_data[ 'item' ][ 'id' ],
					'item_name' => $purchase_data[ 'item' ][ 'name' ],
					'item_url' => $purchase_data[ 'item' ][ 'url' ],
					'code' => $purchase_data[ 'code' ],
					'expiration' => $purchase_data[ 'supported_until' ],
					'support_amount' => $purchase_data[ 'support_amount' ]
				);
			}

			update_user_meta( $this->ID, 'envato_licenses', $licenses, $this->user->envato_licenses );
		}

		return $licenses;
    }

}
