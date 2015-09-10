<p><?php _e( 'Hey there! Before you can submit a ticket you must first connect your Envato account and add a valid license code.', 'donkey' ); ?></p>
<p><?php _e( 'Connected before? No worries! Just click below again and your existing licenses will still be available.', 'donkey' ); ?></p>
<p><strong><a href="<?php echo donkey()->oauth->auth_url(); ?>" class="button"><?php _e( 'Connect to Envato', 'donkey' ); ?></a></strong></p>
