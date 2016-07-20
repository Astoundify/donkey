<?php

class Donkey_License {

	public $licenses = array();

    public $id;
    public $item_id;
    public $item_name;
    public $item_url;
    public $code;
    public $expiration;
    public $support_amount;

    public function __construct( $license_data ) {
        return $this->get( $license_data );
    }

    public function get( $license ) {
		$this->id = $license[ 'id' ];
		$this->item_id = $license[ 'item_id' ];
		$this->item_name = $license[ 'item_name' ];
		$this->item_url = $license[ 'item_url' ];
		$this->code = $license[ 'code' ];
		$this->expiration = $license[ 'expiration' ];
		$this->support_amount = $license[ 'support_amount' ];
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
            $expiration = date( $format, strtotime( $this->expiration ) );
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
		return esc_url( $this->get_item_url() );
	}

}
