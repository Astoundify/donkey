<?php

class Donkey_Disconnect_oAuth {

    public function __construct() {
        add_action( 'donkey_action_disconnect-oauth', array( $this, 'disconnect_oauth' ) );
    }

    public function disconnect_oauth() {
        return donkey_get_user()->clear_oauth();
    }

}
