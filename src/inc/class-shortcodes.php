<?php

class Donkey_Shortcodes {

    public function __construct() {
        $this->setup_actions();
    }

    private function setup_actions() {
        add_action( 'after_setup_theme', array( $this, 'includes' ) );
        add_action( 'after_setup_theme', array( $this, 'register_shortcodes' ) );
    }

    public function includes() {
        $files = array(
            'class-shortcode-licenses.php'
        );

        foreach ( $files as $file ) {
            require( trailingslashit( donkey()->plugin_dir ) . trailingslashit( 'inc/shortcodes' ) . $file );
        }
    }

    public function register_shortcodes() {
        $this->licenses = new Donkey_Shortcode_Licenses();
    }

}
