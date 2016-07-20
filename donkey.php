<?php
/**
 * Plugin Name: Donkey - Astoundify Support System
 * Description: Provide support to Envato purchases.
 * Author: Astoundify
 * Author URI: http://astoundify.com
 * Version: 1.0.0
 */

define( 'DONKEY_VERSION', '1.0.0' );

function donkey_activate() {
    update_option( 'donkey_db_version', DONKEY_VERSION );
}
register_activation_hook( __FILE__, 'donkey_activate' );

require( 'src/class-donkey.php' );
