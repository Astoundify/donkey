<?php

class Donkey_Template {

	function get( $template_name, $args = array(), $template_path = 'donkey', $default_path = '' ) {
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}

		include( $this->locate( $template_name, $template_path, $default_path ) );
	}

	public function find( $template_name, $args = array(), $template_path = 'donkey', $default_path = '' ) {
		ob_start();

		$this->get( $template_name, $args = array(), $template_path = 'donkey', $default_path = '' );

		return ob_get_clean();
	}

	function locate( $template_name, $template_path = 'donkey', $default_path = '' ) {
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name
			)
		);

		if ( ! $template && $default_path !== false ) {
			$default_path = $default_path ? $default_path : donkey()->plugin_dir . '/templates/';
			if ( file_exists( trailingslashit( $default_path ) . $template_name ) ) {
				$template = trailingslashit( $default_path ) . $template_name;
			}
		}

		return apply_filters( 'donkey_locate_template', $template, $template_name, $template_path );
	}

}
