<ul>
	<li><a href="http://themeforest.net/user/astoundify/portfolio?ref=Astoundify"><?php _e( 'Purchase Licenses or <strong>Extend Support</strong>', 'donkey' ); ?></a></li>
	<li><a href="<?php echo donkey_get_page_url( 'submit' ); ?>"><?php _e( 'Submit Ticket', 'donkey' ); ?></a></li>
</ul>

<?php if ( ! empty( $licenses ) ) : ?>

<table>
	<thead>
		<tr>
			<th width="70%"><?php _e( 'Item', 'donkey' ); ?></th>
			<th><?php _e( 'Support Status', 'donkey' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ( $licenses as $license ) : $license = donkey_get_license( $license ); ?>
	<tr>
		<td>
			<?php echo $license->get_item_name(); ?><br />
			<small><?php echo $license->get_code(); ?></small>
		</td>
		<td>
			<span class="license-status" title="<?php printf( __( 'Expires %s', 'donkey' ), $license->get_expiration() ); ?>">
				<?php if ( $license->is_active() ) : ?>
					<?php printf( __( '&#10004; Active for %s', 'donkey' ), human_time_diff( current_time( 'timestamp' ), $license->get_expiration( 'timestamp' ) ) ); ?>
					<?php else : ?>
						<strong><?php _e( 'Expired', 'donkey' ); ?></strong>
				<?php endif; ?>
			</span><br />
			<a href="<?php echo $license->get_renew_url(); ?>"><?php _e( 'Extend Support', 'donkey' ); ?></a>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php endif; ?>
