<?php
/**
 * Theme Licenses
 *
 * @package Donkey
 * @category License
 * @since 1.1.0
 */
class Donkey_Theme_Licenses {

	public function __construct() {
		add_action( 'template_redirect', array( $this, 'validate_licenses' ) );
	}

	/**
	 * When the submit ticket page or theme licenses page loads on the
	 * frontend validate licenses.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function validate_licenses() {
		$license_page = donkey_get_setting( 'page_licenses' );
		$submit_page = donkey_get_setting( 'page_submit' );

		if ( ! is_page( array( $license_page, $submit_page ) ) ) {
			return;
		}

		$user = donkey_get_user();
		$user->validate_licenses();
	}

}
