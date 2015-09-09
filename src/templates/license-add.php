<form action="" method="POST">
	<p>
		<label for="code"><?php _e( 'License Code', 'donkey' ); ?></label>
		<input type="text" name="purchase-key" class="regular-text" placeholder="" />
	</p>
	<p>
		<input type="submit" name="submit" value="<?php _e( 'Add License', 'donkey' ); ?>" />
		<input type="hidden" name="donkey-action" value="add-license" />
		<?php wp_nonce_field( 'add-license' ); ?>
	</p>
</form>
