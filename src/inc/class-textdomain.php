<?php

class Donkey_Textdomain {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'load' ) );
	}

	public function load() {
		load_textdomain( 'donkey', WP_LANG_DIR . "/donkey/donkey-" . apply_filters( 'plugin_locale', get_locale(), 'donkey' ) . '.mo' );
		load_plugin_textdomain( 'donkey', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

}
