<?php

class Donkey_GravityForms {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'filter_fields' ) );
	}

	public function filter_fields() {
		$form = donkey_get_setting( 'gravityform' );

		add_filter( 'gform_pre_render_' . $form, array( $this, 'populate_licenses' ) );
		add_filter( 'gform_pre_validation_' . $form, array( $this, 'populate_licenses' ) );
		add_filter( 'gform_pre_submission_filter_' . $form, array( $this, 'populate_licenses' ) );
		add_filter( 'gform_admin_pre_render_' . $form, array( $this, 'populate_licenses' ) );
	}

	function populate_licenses( $form ) {
		foreach ( $form[ 'fields' ] as $field ) {
			if ( $field->type != 'select' || strpos( $field->cssClass, 'donkey-licenses' ) === false ) {
				continue;
			}

			$licenses = donkey_get_user()->get_licenses();
			$choices  = array();

			if ( ! empty( $licenses ) ) {
				foreach ( $licenses as $license ) {
					$license = donkey_get_license( $license );
					$value = $license->is_active() ? 'valid' : $license->get_id();

					$choices[] = array( 'value' => $value, 'text' => $license->get_item_name() );
				}
			} else {
				$choices[] = array( 'value' => 'no-licenses', 'text' => __( 'Please add a valid Envato license code.', 'donkey' ) );
			}

			$field->choices = $choices;
		}

		return $form;
	}
}
