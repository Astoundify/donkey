<?php

class Donkey {

    private static $instance;

    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct() {
        $this->plugin_dir = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );

        $this->setup_actions();
    }

    private function setup_actions() {
        $this->includes();
        $this->setup();
    }

    public function includes() {
        $files = array(
            'class-helpers.php',
            'class-flash.php',
            'class-template.php',
            'class-textdomain.php',
            'class-api.php',
            'class-user.php',
            'class-oauth.php',
            'class-actions.php',
            'class-shortcodes.php',
            'class-license.php',
            'class-settings.php',
            'class-gravityforms.php',
            'class-helpscout.php',
            'class-admin.php',
            'functions.php',
        );

        foreach ( $files as $file ) {
            require( trailingslashit( 'inc' ) . $file );
        }
    }

    public function setup() {
        $this->helpers      = new Donkey_Helpers();
        $this->flash        = new Donkey_Flash();
        $this->template     = new Donkey_Template();
        $this->textdomain   = new Donkey_Textdomain();
        $this->api          = new Donkey_Envato_API();
        $this->oauth        = new Donkey_Envato_oAuth();
        $this->shortcodes   = new Donkey_Shortcodes();
        $this->actions      = new Donkey_Actions();
        $this->settings     = new Donkey_Settings();
        $this->gravityforms = new Donkey_GravityForms();
        $this->helpscout    = new Donkey_Helpscout();
        $this->admin        = new Donkey_Admin();
    }

}
add_action( 'plugins_loaded', array( 'Donkey', 'instance' ) );

function donkey() {
    return Donkey::instance();
}
