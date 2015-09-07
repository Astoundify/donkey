<?php if ( ! $user->get_refresh_token() ) : ?>

	<?php donkey()->template->get( 'dashboard-oauth.php' ); ?>

<?php else : ?> 

	<p><?php printf( 
		__( 'Welcome <img src="%1$s" alt="%2$s" style="width: 25px; height: 25px;vertical-align: middle;margin: -3px 5px 0;padding: 0;box-shadow: none;" />%2$s <a href="%3$s">(disconnect)</a>. Manage your licenses below.', 'donkey' ),
		$user->get_envato_image(),
		$user->get_envato_username(), 
		donkey()->oauth->unauth_url() 
	); ?></p>

	<ul>
		<li><a href="<?php echo esc_url( add_query_arg( 'donkey-page', 'add-license', donkey_get_page_url( 'licenses' )  ) ); ?>"><?php _e( 'Add License', 'donkey' ); ?></a></li>
		<li><a href="http://themeforest.net/user/astoundify/portfolio"><?php _e( 'Purchase Licenses', 'donkey' ); ?></a></li>
		<li><a href="<?php echo donkey_get_page_url( 'submit' ); ?>"><?php _e( 'Submit Ticket', 'donkey' ); ?></a></li>
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
