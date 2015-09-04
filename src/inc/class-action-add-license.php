<?php

class Donkey_Add_License {

    public function __construct() {
        add_action( 'donkey_dashboard_content_add-license', array( $this, 'dashboard_content' ) );
        add_action( 'donkey_action_add-license', array( $this, 'add_license' ) );
    }

    public function dashboard_content() {
?>
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
<?php
    }

    public function add_license() {
        $code = isset( $_REQUEST[ 'purchase-key' ] ) ? esc_attr( $_REQUEST[ 'purchase-key' ] ) : false;

        if ( ! $code ) {
            return donkey()->message = __( 'Please add a license code.', 'donkey' );
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

			if ( $license->id ) {
				// ghetto redirect
				unset( $_REQUEST[ 'donkey-page' ] );

				return donkey()->message = __( 'License already exists.', 'donkey' );
			}

			$data = array(
				'item_id' => $response->item->id,
				'item_name' => $response->item->name,
				'item_url' => $response->item->url,
				'code' => $code,
				'expiration' => $response->supported_until,
				'support_amount' => $response->support_amount
			);

			if ( $license->insert( $data ) ) {
				donkey()->message = __( 'License added', 'donkey' );

				// ghetto redirect
				unset( $_REQUEST[ 'donkey-page' ] );
			} else {
				donkey()->message = __( 'Unable to add license. Is it valid?', 'donkey' );
			}
		}
    }

}
