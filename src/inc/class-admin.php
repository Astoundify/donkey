<?php

class Donkey_Admin {

	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_init() {
		$this->includes();
		$this->setup();
	}

	public function includes() {
		$files = array(
			'class-admin-edit-user.php',
		);

		foreach ( $files as $file ) {
			require( trailingslashit( donkey()->plugin_dir ) . trailingslashit( 'inc' ) . $file );
		}
	}

	public function setup() {
		$this->edit_user = new Donkey_Admin_Edit_User();
	}

}
