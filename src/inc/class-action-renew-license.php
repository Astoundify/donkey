<?php

class Donkey_Renew_License {

    public function __construct() {
        add_action( 'donkey_dashboard_content_renew-license', array( $this, 'dashboard_content' ) );
        add_action( 'donkey_action_renew-license', array( $this, 'update_license' ) );
    }

    public function dashboard_content() {
		$id = isset( $_REQUEST[ 'id' ] ) ? absint( $_REQUEST[ 'id' ] ) : false;
		$license = donkey_get_license( $id );
?>
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
<?php
    }

    public function update_license() {
        $code = isset( $_REQUEST[ 'purchase-key' ] ) ? esc_attr( $_REQUEST[ 'purchase-key' ] ) : false;

        if ( ! $code ) {
            return; // do soemthing
        }

        $response = donkey()->api->authenticated_request( 'market/buyer/purchase', array(
            'code' => $code
        ) );

		$error = false;

		if ( isset( $response->error ) ) {
			$error = donkey()->message = $response->description;
		}

		if ( ! $error ) {
			$license = donkey_get_license( $code, 'code' );

			if ( ! $license ) {
				return donkey()->message = __( 'Unable to locate previous license code.', 'donkey' );
			}

			$data = array(
				'id' => $license->get_id(),
				'expiration' => $response->supported_until
			);

			if ( $license->update( $data ) ) {
				donkey()->message = __( 'License renewed.', 'astoundify-rcp-envato' );

				// ghetto redirect
				unset( $_REQUEST[ 'donkey-page' ] );
			} else {
				donkey()->message = __( 'Error renewing license. Have you renewed your support on ThemeForest.net?', 'donkey' );
			}
		}
    }

}
