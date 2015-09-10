<p><a href="<?php echo donkey_get_page_url( 'licenses' ); ?>"><?php _e( '&larr; Manage Licenses', 'donkey' ); ?></a></p>
<p><a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Can-I-Find-my-Purchase-Code-"><?php _e( 'Where can I find my purchase code?', 'donkey' ); ?></a></p>

<form action="" method="POST">
    <p>
        <label for="code"><?php _e( 'Purchase Code', 'donkey' ); ?></label>
        <input type="text" name="purchase-key" class="regular-text" placeholder="" />
    </p>
    <p>
        <input type="submit" name="submit" value="<?php _e( 'Add License', 'donkey' ); ?>" />
        <input type="hidden" name="donkey-action" value="add-license" />
        <?php wp_nonce_field( 'add-license' ); ?>
    </p>
</form>
