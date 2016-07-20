<?php

class Donkey_Settings {

    private $settings;

    public function __construct() {
        if ( ! is_admin() ) {
            return;
        }

        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    public function set_settings() {
        $this->settings = apply_filters( 'donkey_settings', array(
            'helpscout_secret' => array(
                'label' => __( 'Helpscout Secret Key', 'donkey' ),
                'section' => 'general',
                'default' => '',
                'type' => 'text'
            ),
            'product_whitelist' => array(
                'label' => __( 'Product Whitelist', 'donkey' ),
                'section' => 'general',
                'default' => '',
                'type' => 'text'
            ),
            'gravityform' => array(
                'label' => __( 'Gravity Form ID', 'donkey' ),
                'section' => 'general',
                'default' => '',
                'type' => 'text'
            )
        ) );

        return $this->settings;
    }

    public function get( $key, $default = false ) {
        if ( ! isset( $this->options ) ) {
            $this->options = get_option( 'donkey' );
        }

        if ( isset( $this->options[ $key ] ) ) {
            return $this->options[ $key ];
        } else {
            return $default;
        }
    }

    public function add_plugin_page() {
        add_options_page(
            'Donkey',
            'Donkey',
            'manage_options',
            'donkey',
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page() {
    ?>
        <div class="wrap">
            <h2><?php _e( 'Donkey', 'donkey' ); ?></h2>

            <form method="post" action="options.php">
                <?php
                    settings_fields( 'donkey' );
                    do_settings_sections( 'donkey' );
                    submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    public function page_init() {
        if ( ! $this->settings ) {
            $this->settings = $this->set_settings();
        }

        register_setting(
            'donkey',
            'donkey',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'donkey_general',
            '',
            '',
            'donkey'
        );

        foreach ( $this->settings as $key => $setting ) {
            add_settings_field(
                'donkey_' . $key,
                esc_attr( $setting[ 'label' ] ),
                array( $this, 'field_' . $setting[ 'type' ] ),
                'donkey',
                'donkey_' . $setting[ 'section' ],
                array(
                    'id' => $key,
                    'default' => $setting[ 'default' ]
                )
            );
        }
    }

    public function sanitize( $input ) {
        $new_input = array();

        foreach ( $this->settings as $setting => $label ) {
            if( isset( $input[ $setting ] ) ) {
                $new_input[ $setting ] = esc_attr( $input[ $setting ] );
            }
        }

        return $new_input;
    }

    public function field_text( $args ) {
        printf(
            '<input type="text" id="%1$s" name="donkey[%1$s]" value="%2$s" class="regular-text" />', 
            esc_attr( $args[ 'id' ] ),
            $this->get( $args[ 'id' ], $args[ 'default' ] )
        );
    }

}
