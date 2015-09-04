<?php

class Donkey_Delete_License {

    public function __construct() {
        add_action( 'donkey_action_delete-license', array( $this, 'delete_license' ) );
    }

    public function delete_license() {
		$id = isset( $_REQUEST[ 'license' ] ) ? absint( $_REQUEST[ 'license' ] ) : false;

        if ( ! $id ) {
			return donkey()->message = __( 'Unable to locate license', 'donkey' );
        }

		$license = donkey_get_license( $id );

		if ( ! $license ) {
			donkey()->message = __( 'Unable to locate license', 'donkey' );
		}

		$delete = $license->delete( $id );

		donkey()->message = __( 'License deleted.', 'donkey' );
    }

}
