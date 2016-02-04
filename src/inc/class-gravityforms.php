<?php

class Donkey_GravityForms {

    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'filter_fields' ) );
    }

    public function filter_fields() {
        $form = donkey_get_setting( 'gravityform' );

        add_filter( 'gform_pre_render_' . $form, array( $this, 'validate_licenses' ) );

        add_filter( 'gform_pre_render_' . $form, array( $this, 'populate_licenses' ) );
        add_filter( 'gform_pre_validation_' . $form, array( $this, 'populate_licenses' ) );
        add_filter( 'gform_pre_submission_filter_' . $form, array( $this, 'populate_licenses' ) );
        add_filter( 'gform_admin_pre_render_' . $form, array( $this, 'populate_licenses' ) );

        add_filter( 'gform_pre_send_email', array( $this, 'notification' ) );
    }

    public function notification( $email ) {
        $bad = array( 'WordPress Directory Theme', 'Marketplace WordPress Theme', 'WordPress Job Board Theme', 'WP Job Manager', '-', '--', '—'  );
        $email[ 'subject' ] = trim( str_replace( $bad, '', $email[ 'subject'] ) );

        return $email;
    }

    public function validate_licenses( $form ) {
        // try and get a simple item
        $attempt = donkey()->api->authenticated_request( 'market/catalog/item', array( 'id' => '6570786' ), 'full' );

        // if the API is down but they have a refresh token let them through
        if ( ( ! $attempt || 200 != wp_remote_retrieve_response_code( $attempt ) ) && donkey_get_user()->get_refresh_token() ) {
            $form[ 'description' ] = false;

            return $form;
        }

        if ( ! is_user_logged_in() ) {
            $form[ 'fields' ] = array();
            $form[ 'description' ] = donkey()->template->find( 'login.php' );

            return $form;
        } else {
            if ( donkey()->api->can_make_authenticated_request() ) {
                donkey_get_user()->validate_licenses();

                $form[ 'description' ] = false;
            } else {
                $form[ 'description' ] = donkey()->template->find( 'oauth.php' );
                $form[ 'fields' ] = array();
            }
        }

        return $form;
    }

    function populate_licenses( $form ) {
        foreach ( $form[ 'fields' ] as $field ) {
            if ( $field->type != 'select' || strpos( $field->cssClass, 'donkey-licenses' ) === false ) {
                continue;
            }

            $licenses = apply_filters( 'donkey_gravityforms_populate_licenses', donkey_get_user()->get_licenses() );

            $choices  = array();

            if ( ! empty( $licenses ) ) {
                $choices[] = array( 'value' => 'no-licenses', 'text' => __( 'Choose a license', 'donkey' ) );
                foreach ( $licenses as $license ) {
                    $license = donkey_get_license( $license );

                    if ( ! $license->is_active() ) {
                        continue;
                    }

                    $choices[] = array( 'value' => 'valid-' . sanitize_title( $license->get_item_name() ), 'text' => $license->get_item_name() );
                }
            } else {
                $choices[] = array( 'value' => 'no-licenses', 'text' => __( 'Please add a valid Envato license code.', 'donkey' ) );
            }

            $field->choices = apply_filters( 'donkey_gravityforms_populate_license_choices', $choices );
        }

        return $form;
    }

}
