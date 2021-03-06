<?php

class Donkey_EDD_SL {

    public function __construct() {
        if ( ! defined( 'EDD_SL_VERSION' ) ) {
            return;
        }

        $this->init();
    }

    public function init() {
        add_filter( 'donkey_gravityforms_populate_license_choices', array( $this, 'populate_choices' ) );
    }

    public function populate_choices( $choices ) {
        $licenses = edd_software_licensing()->get_license_keys_of_user( get_current_user_id() );

        if ( empty( $licenses ) ) {
            return $choices;
        }

        $to_add = array();

        foreach ( $licenses as $license ) {
            $key = edd_software_licensing()->get_license_key( $license->ID );
            $download = new EDD_Download( edd_software_licensing()->get_download_by_license( $key ) );
            $status = edd_software_licensing()->get_license_status( $license->ID );

            if ( 0 === $download->ID || $download->is_free() ) {
                continue;
            }

			if ( 'expired' != $status ) {
				$choices[] = array( 'value' => 'valid-' . sanitize_title( get_the_title( $download->ID ) ), 'text' => get_the_title( $download->ID ) );
			} else {
				$choices[] = array( 'value' => 'expired-' . sanitize_title( get_the_title( $download->ID ) ), 'text' => get_the_title( $download->ID ) . '  &mdash; Expired: ' . date_i18n( get_option( 'date_format' ), edd_software_licensing()->get_license_expiration( $license->ID ) ) );
			}
        }

        return $choices;
    }
  }
