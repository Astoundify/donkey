<?php

class Donkey_Helpers {

    public static function parse_args_r( &$a, $b ) {
        $a = (array) $a;
        $b = (array) $b;
        $r = $b;
        foreach ( $a as $k => &$v ) {
            if ( is_array( $v ) && isset( $r[ $k ] ) ) {
                $r[ $k ] = self::parse_args_r( $v, $r[ $k ] );
            } else {
                $r[ $k ] = $v;
            }
        }
        return $r;
    }

}
