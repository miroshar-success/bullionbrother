<?php
/**
 * Functions.
 */

// Clean variables recursively.
if ( ! function_exists( 'wloptf_clean' ) ) {
    /**
     * Clean variables recursively.
     */
    function wloptf_clean( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'wloptf_clean', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }
}

// Clean array of ID.
if ( ! function_exists( 'wloptf_clean_array_of_id' ) ) {
    /**
     * Clean array of ID.
     */
    function wloptf_clean_array_of_id( $ids = array() ) {
        $array_of_id = array();

        if ( is_array( $ids ) && ! empty( $ids ) ) {
            foreach ( $ids as $id ) {
                $id = is_scalar( $id ) ? absint( $id ) : 0;

                if ( 0 !== $id ) {
                    $array_of_id[] = $id;
                }
            }
        }

        return $array_of_id;
    }
}

// Clean array of key.
if ( ! function_exists( 'wloptf_clean_array_of_key' ) ) {
    /**
     * Clean array of key.
     */
    function wloptf_clean_array_of_key( $keys = array() ) {
        $array_of_key = array();

        if ( is_array( $keys ) && ! empty( $keys ) ) {
            foreach ( $keys as $key ) {
                $key = is_scalar( $key ) ? sanitize_key( $key ) : '';

                if ( '' !== $key ) {
                    $array_of_key[] = $key;
                }
            }
        }

        return $array_of_key;
    }
}

// Key to absolute key.
if ( ! function_exists( 'wloptf_key_to_abskey' ) ) {
    /**
     * Key to absolute key.
     */
    function wloptf_key_to_abskey( $key = '' ) {
        $key = is_scalar( $key ) ? sanitize_key( $key ) : '';
        $key = str_replace( '-', '_', $key );

        return $key;
    }
}

// Clean HTML.
if ( ! function_exists( 'wloptf_clean_html' ) ) {
    /**
     * Clean HTML.
     */
    function wloptf_clean_html( $html = '', $search = array(), $replace = array() ) {
        $html = ( ( is_string( $html ) && ! empty( $html ) ) ? trim( $html ) : '' );
        $html = preg_replace( array( '/\s{2,}/', '/[\t\n]/'), ' ', $html );
        $html = str_replace( '> <', '><', $html );

        if ( is_array( $search ) && ! empty( $search ) && is_array( $replace ) && ! empty( $replace ) ) {
            $html = str_replace( $search, $replace, $html );
        }

        return $html;
    }
}

// Type cast.
if ( ! function_exists( 'wloptf_cast' ) ) {
    /**
     * Type cast.
     */
    function wloptf_cast( $var = '', $type = 'text', $clean = true ) {
        $clean = rest_sanitize_boolean( $clean );

        switch ( $type ) {
            case 'key':
                $var = ( is_scalar( $var ) ? strval( $var ) : '' );
                $var = ( true === $clean ? sanitize_key( $var ) : $var );
                break;

            case 'textarea':
                $var = ( is_scalar( $var ) ? strval( $var ) : '' );
                $var = ( true === $clean ? sanitize_textarea_field( $var ) : $var );
                break;

            case 'array':
                $var = ( is_array( $var ) ? $var : array() );
                $var = ( true === $clean ? wloptf_clean( $var ) : $var );
                break;

            case 'bool':
            case 'boolean':
                $var = rest_sanitize_boolean( $var );
                break;

            case 'int':
            case 'integer':
                $var = intval( $var );
                break;

            case 'absint':
            case 'absinteger':
                $var = absint( $var );
                break;

            case 'float':
                $var = floatval( $var );
                break;

            case 'absfloat':
                $var = floatval( $var );
                $var = ( ( 0 > $var ) ? ( $var * ( -1 ) ) : $var );
                $var = floatval( $var );
                break;

            case 'email':
                $var = ( is_email( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_email( $var ) : $var );
                break;

            default:
                $var = ( is_scalar( $var ) ? strval( $var ) : '' );
                $var = ( true === $clean ? sanitize_text_field( $var ) : $var );
                break;
        }

        return $var;
    }
}