<?php

class Donkey_License {

    public $id;
    public $item_id;
    public $item_name;
    public $item_url;
    public $code;
    public $expiration;
    public $support_amount;

    public function __construct( $search = false, $field = 'id' ) {
        if ( $search ) {
            return $this->get( $search, $field );
        }
    }

    public function get( $value, $field = 'id' ) {
        global $wpdb;

        if ( is_int( $value ) ) {
            $value = absint( $value );
        } else {
            $value = esc_attr( $value );
        }

        $license = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}donkey_licenses WHERE $field = '%s'", $value ) );

        if ( $license ) {
            $this->id = $license->id;
            $this->item_id = $license->item_id;
            $this->item_name = $license->item_name;
            $this->item_url = $license->item_url;
            $this->code = $license->code;
            $this->expiration = $license->expiration;
            $this->support_amount = $license->support_amount;

            return $this;
        }

        return false;
    }

    public function insert( $data ) {
        global $wpdb;

        $user = donkey_get_user();

        $defaults = array(
            'user_id' => $user->ID
        );

        $data = wp_parse_args( $data, $defaults );

        $license = $wpdb->insert( $wpdb->prefix . 'donkey_licenses', $data );

        return $wpdb->insert_id;
    }

    public function update( $data ) {
        global $wpdb;

        $license = false;

        if ( isset( $data[ 'id' ] ) ) {
            $license = $this->get( $data[ 'id' ] );
        }

        if ( ! $license ) {
            return false;
        }

        $data = wp_parse_args( $data, (array) $license );
        $where = array( 'id' => $data[ 'id' ] );

        unset( $data[ 'id' ] );

        $license = $wpdb->update( $wpdb->prefix . 'donkey_licenses', $data, $where );

        return $license;
    }

    public function delete() {
        global $wpdb;

        return $wpdb->delete( $wpdb->prefix . 'donkey_licenses', array( 'id' => $this->get_id() ), array( '%d' ) );
    }

    public function get_id() {
        return absint( $this->id );
    }

    public function get_item_id() {
        return absint( $this->item_id );
    }

    public function get_item_name() {
        return esc_attr( $this->item_name );
    }

    public function get_item_url() {
        return esc_url( $this->item_url );
    }

    public function get_code() {
        return esc_attr( $this->code );
    }

    public function get_expiration( $format = false ) {
        if ( 'timestamp'  == $format ) {
            $expiration = strtotime( $this->expiration );
        } elseif ( $format ) {
            $expiration = date( $format, $this->expiration );
        } else {
            $expiration = $this->expiration;
        }

        return $expiration;
    }

    public function is_active() {
        // this isnt totally true since we offered 6 months from the start
        return strtotime( $this->get_expiration() ) > current_time( 'timestamp' );
    }


    public function get_renew_url() {
        $base = donkey_get_page_url( 'licenses' );
        $args = array(
            'donkey-page' => 'renew-license',
            'id' => $this->get_id()
        );

        $url = add_query_arg( $args, $base );

        return esc_url( $url );
    }

    public function get_delete_url() {
        $base = donkey_get_page_url( 'licenses' );
        $args = array(
            'donkey-action' => 'delete-license',
            'license' => $this->get_id()
        );

        $url = add_query_arg( $args, $base );

        return esc_url( wp_nonce_url( $url, 'delete-license' ) );
    }

}
