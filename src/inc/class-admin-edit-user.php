<?php

class Donkey_Admin_Edit_User {

	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'display_licenses' ) );
		add_action( 'edit_user_profile', array( $this, 'display_licenses' ) );

		add_action( 'profile_update', array( $this, 'update_profile' ) );
		add_action( 'edit_user_profile_update', array( $this, 'update_profile' ) );
	}

	public function display_licenses( $user ) {
		$user = donkey_get_user( $user );
		$licenses = $user->get_licenses();

		donkey()->template->get( 'admin-edit-user-licenses.php', array(
			'user' => $user,
			'licenses' => $licenses
		) );
	}

	public function update_profile() {
		$donkey = isset( $_POST[ 'donkey' ] ) ? $_POST[ 'donkey' ] : false;

		if ( ! $donkey ) {
			return;
		}

		$licenses = isset( $donkey[ 'licenses' ] ) ? $donkey[ 'licenses' ] : false;

		if ( ! $licenses ) {
			return;
		}

		foreach ( $licenses as $license ) {
			$l = donkey_get_license();

			if ( '' == $license[ 'code' ] && isset( $license[ 'id' ] ) ) {
				$l->delete( $license[ 'id' ] );
			} else {
				$license[ 'user_id' ] = donkey_get_user()->ID;

				if ( ! isset( $license[ 'id' ] ) && '' != $license[ 'code' ] ) {
					$l->insert( $license );
				} else {
					$l->update( $license );
				}
			}
		}
	}

}
