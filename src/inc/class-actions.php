<?php

class Donkey_Actions {

    public function __construct() {
        add_action( 'wp', array( $this, 'include_actions' ) );
        add_action( 'wp', array( $this, 'register_actions' ) );
        add_action( 'wp', array( $this, 'action_handler' ) );
    }

    public function include_actions() {
        $files = array(
            'class-action-add-license.php',
            'class-action-delete-license.php',
            'class-action-renew-license.php'
        );

        foreach ( $files as $file ) {
            require( trailingslashit( donkey()->plugin_dir ) . trailingslashit( 'inc' ) . $file );
        }
    }

    public function register_actions() {
        $this->add_license = new Donkey_Add_License();
        $this->delete_license = new Donkey_Delete_License();
        $this->renew_license = new Donkey_Renew_License();
    }

    public function action_handler() {
        if ( ! isset( $_REQUEST[ 'donkey-action' ] ) ) {
            return;
        }

        if ( empty( $_REQUEST[ '_wpnonce' ] ) ) {
            return;
        }

        $action = sanitize_title( $_REQUEST[ 'donkey-action' ] );

        if ( !  wp_verify_nonce( $_REQUEST[ '_wpnonce' ], $action ) ) {
            return;
        }

        do_action( 'donkey_action_' . $action );
    }

}
