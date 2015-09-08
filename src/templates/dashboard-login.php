<?php printf( 
	__( '<a href="%s" class="button">Log in to Continue</a> <a href="%s" class="button">Register a New Account</a>', 'donkey' ),
	wp_login_url( donkey_get_page_url( 'licenses' ) ),
	wp_registration_url()
); ?>
