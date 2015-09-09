<?php

class Donkey_Flash {

    public $flash = false;

    public function set( $message = '' ) {
        return $this->flash = new WP_Error( 'donkey', $message, array() );
    }

    public function has() {
        return is_wp_error( $this->flash );
    }

    public function get() {
        $flashes = $this->flash->get_error_messages();

        if( empty( $flashes ) ) {
            return '';
        }

        return $flashes[0];
    }

}
