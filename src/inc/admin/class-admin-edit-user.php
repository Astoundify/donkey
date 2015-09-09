<?php

class Donkey_Admin_Edit_User {

    public function __construct() {
        if ( ! current_user_can( 'edit_users' ) ) {
            return;
        }

        add_action( 'show_user_profile', array( $this, 'display_licenses' ) );
        add_action( 'edit_user_profile', array( $this, 'display_licenses' ) );

        add_action( 'personal_options_update', array( $this, 'update_profile' ) );
        add_action( 'edit_user_profile_update', array( $this, 'update_profile' ) );
    }

    public function display_licenses( $user ) {
        $user = donkey_get_user( $user );
        $licenses = $user->get_licenses();

        donkey()->template->get( 'admin-edit-user-licenses.php', array(
            'user' => $user,
            'licenses' => $licenses
        ) );
    }

    public function update_profile( $user_id ) {
        $donkey = isset( $_POST[ 'donkey' ] ) ? $_POST[ 'donkey' ] : false;

        if ( ! $donkey ) {
            return;
        }

        $licenses = isset( $donkey[ 'licenses' ] ) ? $donkey[ 'licenses' ] : false;

        if ( ! $licenses ) {
            return;
        }

        foreach ( $licenses as $license ) {
            $id = isset( $license[ 'id' ] ) ? $license[ 'id' ] : null;
            $l  = donkey_get_license( $id );

            if ( '' == $license[ 'code' ] && isset( $license[ 'id' ] ) ) {
                $l->delete();
            } else {
                $license[ 'user_id' ] = $user_id;

                if ( ! isset( $license[ 'id' ] ) && '' != $license[ 'code' ] ) {
                    $l->insert( $license );
                } else {
                    $l->update( $license );
                }
            }
        }
    }

}
