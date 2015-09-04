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
    global $wpdb;

    $wpdb->hide_errors();
    $collate = '';

    if ( $wpdb->has_cap( 'collation' ) ) {
        if ( ! empty($wpdb->charset ) ) {
            $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
        }
        if ( ! empty($wpdb->collate ) ) {
            $collate .= " COLLATE $wpdb->collate";
        }
    }

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $sql = "
    CREATE TABLE {$wpdb->prefix}donkey_licenses (
    id bigint(20) NOT NULL auto_increment,
    user_id bigint(20) NOT NULL,
    item_id bigint(20) NOT NULL,
    item_name varchar(100) NOT NULL,
    item_url varchar(512) NOT NULL,
    code varchar(100) NOT NULL,
    expiration datetime NOT NULL default '0000-00-00 00:00:00',
    support_amount bigint(20) NULL,
    PRIMARY KEY  (id)
    ) $collate;
    ";
    dbDelta( $sql );

    update_option( 'donkey_db_version', DONKEY_VERSION );
}
register_activation_hook( __FILE__, 'donkey_activate' );

require( 'src/class-donkey.php' );
