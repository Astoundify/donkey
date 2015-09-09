<?php

class Donkey_Renew_License {

    public function __construct() {
        add_action( 'donkey_dashboard_content_renew-license', array( $this, 'dashboard_content' ) );
        add_action( 'donkey_action_renew-license', array( $this, 'update_license' ) );
    }

    public function dashboard_content() {
        $id = isset( $_REQUEST[ 'id' ] ) ? absint( $_REQUEST[ 'id' ] ) : false;
        $license = donkey_get_license( $id );

        return donkey()->template->get( 'license-renew.php', array( 'license' => $license ) );
    }

    public function update_license() {
        $code = isset( $_REQUEST[ 'purchase-key' ] ) ? esc_attr( $_REQUEST[ 'purchase-key' ] ) : false;

        if ( ! $code ) {
            return donkey()->flash->set( __( 'Please enter a license', 'donkey' ) );
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

            if ( ! $license ) {
                return donkey()->flash->set( __( 'Unable to locate previous license code.', 'donkey' ) );
            }

            $data = array(
                'id' => $license->get_id(),
                'expiration' => $response->supported_until
            );

            if ( $license->update( $data ) ) {
                donkey()->flash->set( __( 'License renewed.', 'astoundify-rcp-envato' ) );

                // ghetto redirect
                unset( $_REQUEST[ 'donkey-page' ] );
            } else {
                donkey()->flash->set( __( 'Error renewing license. Have you renewed your support on ThemeForest.net?', 'donkey' ) );
            }
        }
    }

}
