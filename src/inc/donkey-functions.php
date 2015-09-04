<?php

function donkey_get_user( $user = false ) {
	return new Donkey_User( $user );
}

function donkey_get_license( $search = false, $by = 'id' ) {
	return new Donkey_License( $search, $by );
}

function donkey_get_setting( $key, $default = false ) {
	return donkey()->settings->get( $key, $default );
}

function donkey_get_page_url( $page ) {
	return esc_url( get_permalink( donkey_get_setting( $page . '_page' ) ) );
}
