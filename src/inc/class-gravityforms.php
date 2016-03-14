<?php

class Donkey_GravityForms {

    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'filter_fields' ) );
    }

    public function filter_fields() {
        $form = donkey_get_setting( 'gravityform' );

		add_filter( 'the_content', array( $this, 'filter_shortcode_login' ) );

        add_filter( 'gform_pre_render_' . $form, array( $this, 'populate_licenses' ) );
        add_filter( 'gform_pre_validation_' . $form, array( $this, 'populate_licenses' ) );
        add_filter( 'gform_pre_submission_filter_' . $form, array( $this, 'populate_licenses' ) );
        add_filter( 'gform_admin_pre_render_' . $form, array( $this, 'populate_licenses' ) );

        add_filter( 'gform_pre_send_email', array( $this, 'notification' ) );
    }

	/**
	 * Display a login form on the support page if the user is not logged in.
	 *
	 * @since 1.1.0
	 *
	 * @param string $content
	 * @return mixed
	 */
	public function filter_shortcode_login( $content ) {
		if ( ! is_page( donkey_get_setting( 'page_submit' ) ) ) {
			return $content;
		}

		if ( is_user_logged_in() ) {
			return $content;
		}

		return donkey()->template->find( 'login.php' );
	}

    public function notification( $email ) {
        $bad = array( 'WordPress Directory Theme', 'Marketplace WordPress Theme', 'WordPress Job Board Theme', 'WP Job Manager', '-', '--', '—'  );
        $email[ 'subject' ] = trim( str_replace( $bad, '', $email[ 'subject'] ) );

        return $email;
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
