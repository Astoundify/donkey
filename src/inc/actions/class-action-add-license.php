<?php

class Donkey_Add_License {

    public function __construct() {
        add_action( 'donkey_dashboard_content_add-license', array( $this, 'dashboard_content' ) );
        add_action( 'donkey_action_add-license', array( $this, 'add_license' ) );
    }

    public function dashboard_content() {
        return donkey()->template->get( 'license-add.php' );;
    }

    public function add_license() {
        $code = isset( $_REQUEST[ 'purchase-key' ] ) ? esc_attr( $_REQUEST[ 'purchase-key' ] ) : false;

        if ( ! $code ) {
            return donkey()->flash->set( __( 'Please add a license code.', 'donkey' ) );
        }

        $response = donkey()->api->authenticated_request( 'market/buyer/purchase', array(
            'code' => $code
        ) );

        $error = false;

        if ( isset( $response->error ) ) {
            $error = donkey()->flash->set( $response->description );
        }

        if ( ! $error ) {
            $license = donkey_get_license( $code, 'code' );

            $whitelist = donkey_get_allowed_products();

            if ( ! empty( $whitelist ) && ! in_array( $response->item->id, $whitelist ) ) {
                return donkey()->flash->set( 'This is not an Astoundify product', 'donkey' );
            }

            $licenses = donkey_get_user()->get_licenses();

            if ( $license->id && ! empty( $licenses ) ) {
                // ghetto redirect
                unset( $_REQUEST[ 'donkey-page' ] );

                return donkey()->flash->set( __( 'License already exists.', 'donkey' ) );
            }

            $data = array(
                'item_id' => $response->item->id,
                'item_name' => $response->item->name,
                'item_url' => $response->item->url,
                'code' => $code,
                'expiration' => $response->supported_until,
                'support_amount' => $response->support_amount
            );

            if ( $license->insert( $data ) ) {
                donkey()->flash->set( __( 'License added', 'donkey' ) );

                // ghetto redirect
                unset( $_REQUEST[ 'donkey-page' ] );
            } else {
                donkey()->flash->set( __( 'Unable to add license. Is it valid?', 'donkey' ) );
            }
        }
    }

}
