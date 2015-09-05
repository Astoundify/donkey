<?php if ( ! $user->get_refresh_token() ) : ?>

	<a href="<?php echo donkey()->oauth->auth_url(); ?>"><?php _e( 'Connect with Envato' ); ?></a>

<?php else : ?> 

	<ul>
		<li><a href="<?php echo esc_url( add_query_arg( 'donkey-page', 'add-license', donkey_get_page_url( 'licenses' )  ) ); ?>"><?php _e( 'Add License', 'donkey' ); ?></a></li>
	</ul>

	<?php if ( ! empty( $licenses ) ) : ?>

	<table>
		<thead>
			<tr>
				<td width="60%"><?php _e( 'Item', 'donkey' ); ?></td>
				<td><?php _e( 'Support Status', 'donkey' ); ?></td>
				<td width="10%"></td>
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
			<td style="vertical-align: middle; text-align: right;"><a href="<?php echo $license->get_delete_url(); ?>">&times;</a></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php endif; ?>

<?php endif; ?>
