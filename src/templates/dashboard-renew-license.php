<p>Still need support? <a href="<?php echo $license->get_item_url(); ?>">Visit the item page</a> to renew your support. Once completed, enter your license key below.</p>

<form action="" method="POST">
	<p>
		<label for="code"><?php _e( 'License Code', 'donkey' ); ?></label>
		<input type="text" name="purchase-key" class="regular-text" placeholder="" />
	</p>
	<p>
		<input type="submit" name="submit" value="<?php _e( 'Renew Support', 'donkey' ); ?>" />
		<input type="hidden" name="donkey-action" value="renew-license" />
		<?php wp_nonce_field( 'renew-license' ); ?>
	</p>
</form>
