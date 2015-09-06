
	<?php if ( ! empty( $licenses ) ) : ?>

	<h3><?php _e( 'Licenses', 'donkey' ); ?></h3>

	<p><?php _e( 'Remove a code from a row to delete it.', 'donkey' ); ?></p>

	<table>
		<thead>
			<tr>
				<td><?php _e( 'Item ID', 'donkey' ); ?></td>
				<td><?php _e( 'Item Name', 'donkey' ); ?></td>
				<td><?php _e( 'Item URL', 'donkey' ); ?></td>
				<td><?php _e( 'License Code', 'donkey' ); ?></td>
				<td><?php _e( 'Expiration', 'donkey' ); ?></td>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $licenses as $key => $license ) : $license = donkey_get_license( $license ); ?>
		<tr>
			<td>
				<input type="text" name="donkey[licenses][<?php echo $key; ?>][item_id]" value="<?php echo $license->get_item_id(); ?>" />
				<input type="hidden" name="donkey[licenses][<?php echo $key; ?>][id]" value="<?php echo $license->get_id(); ?>" />
			</td>
			<td>
				<input type="text" name="donkey[licenses][<?php echo $key; ?>][item_name]" value="<?php echo $license->get_item_name(); ?>" />
			</td>
			<td>
				<input type="text" name="donkey[licenses][<?php echo $key; ?>][item_url]" value="<?php echo $license->get_item_url(); ?>" />
			</td>
			<td>
				<input type="text" name="donkey[licenses][<?php echo $key; ?>][code]" value="<?php echo $license->get_code(); ?>" />
			</td>
			<td>
				<input type="text" name="donkey[licenses][<?php echo $key; ?>][expiration]" value="<?php echo $license->get_expiration(); ?>" />
			</td>
		</tr>
		<?php endforeach; ?>
		<tr>
			<td>
				<input type="text" name="donkey[licenses][999][item_id]" value="" />
			</td>
			<td>
				<input type="text" name="donkey[licenses][999][item_name]" value="" />
			</td>
			<td>
				<input type="text" name="donkey[licenses][999][item_url]" value="" />
			</td>
			<td>
				<input type="text" name="donkey[licenses][999][code]" value="" />
			</td>
			<td>
				<input type="text" name="donkey[licenses][999][expiration]" value="" />
			</td>
		</tr>
		</tbody>
	</table>

	<?php endif; ?>
