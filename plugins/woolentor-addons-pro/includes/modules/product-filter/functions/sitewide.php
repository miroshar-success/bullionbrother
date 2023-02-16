<?php
/**
 * Sitewide.
 */

// Clean variables recursively.
if ( ! function_exists( 'wlpf_clean' ) ) {
    /**
     * Clean variables recursively.
     */
    function wlpf_clean( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'wlpf_clean', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }
}

// Clean array of ID.
if ( ! function_exists( 'wlpf_clean_array_of_id' ) ) {
    /**
     * Clean array of ID.
     */
    function wlpf_clean_array_of_id( $ids = array() ) {
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
if ( ! function_exists( 'wlpf_clean_array_of_key' ) ) {
    /**
     * Clean array of key.
     */
    function wlpf_clean_array_of_key( $keys = array() ) {
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

// String to array.
if ( ! function_exists( 'wlpf_string_to_array' ) ) {
    /**
     * String to array.
     */
    function wlpf_string_to_array( $string = '', $separator = ',' ) {
        $string = wlpf_cast( $string, 'text', false );

        $separator = wlpf_cast( $separator, 'text', false );
        $separator = ( ( 0 < strlen( $separator ) ) ? $separator : ',' );

        $array_of_string = explode( $separator, $string );

        return $array_of_string;
    }
}

// String to array of ID.
if ( ! function_exists( 'wlpf_string_to_array_of_id' ) ) {
    /**
     * String to array of ID.
     */
    function wlpf_string_to_array_of_id( $string = '', $separator = ',' ) {
        $array_of_id = wlpf_string_to_array( $string, $separator );
        $array_of_id = wlpf_clean_array_of_id( $array_of_id );

        return $array_of_id;
    }
}

// String to array of key.
if ( ! function_exists( 'wlpf_string_to_array_of_key' ) ) {
    /**
     * String to array of key.
     */
    function wlpf_string_to_array_of_key( $string = '', $separator = ',' ) {
        $array_of_key = wlpf_string_to_array( $string, $separator );
        $array_of_key = wlpf_clean_array_of_key( $array_of_key );

        return $array_of_key;
    }
}

// Array column to key.
if ( ! function_exists( 'wlpf_array_column_to_key' ) ) {
    /**
     * Array column to key.
     */
    function wlpf_array_column_to_key( $data_array = array(), $column = '' ) {
        $output = array();

        $data_array = wlpf_cast( $data_array, 'array', false );
        $column = wlpf_cast( $column, 'text', false );

        if ( ! empty( $data_array ) && ! empty( $column ) ) {
            $terms_keys = array_column( $data_array, $column );
            $output = array_combine( $terms_keys, $data_array );
        }

        return $output;
    }
}

// Key to absolute key.
if ( ! function_exists( 'wlpf_key_to_abskey' ) ) {
    /**
     * Key to absolute key.
     */
    function wlpf_key_to_abskey( $key = '' ) {
        $key = is_scalar( $key ) ? sanitize_key( $key ) : '';
        $key = str_replace( '-', '_', $key );

        return $key;
    }
}

// Type cast.
if ( ! function_exists( 'wlpf_cast' ) ) {
    /**
     * Type cast.
     */
    function wlpf_cast( $var = '', $type = 'text', $clean = true ) {
        $clean = rest_sanitize_boolean( $clean );

        switch ( $type ) {
            case 'key':
                $var = ( is_string( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_key( $var ) : $var );
                break;

            case 'textarea':
                $var = ( is_string( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_textarea_field( $var ) : $var );
                break;

            case 'array':
                $var = ( is_array( $var ) ? $var : array() );
                $var = ( true === $clean ? wlpf_clean( $var ) : $var );
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

            case 'url':
                $var = ( is_string( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_url( $var ) : $var );
                break;

            case 'selectbool':
            case 'selectboolean':
                $var = ( is_string( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_key( $var ) : $var );
                $var = ( ( 'off' !== $var ) ? true : false );
                break;

            case 'jsonarray':
                $var = ( is_string( $var ) ? stripslashes( $var ) : '' );
                $var = ( true === $clean ? sanitize_text_field( $var ) : $var );
                $var = json_decode( $var, true );
                break;

            default:
                $var = ( is_string( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_text_field( $var ) : $var );
                break;
        }

        return $var;
    }
}

// Get product taxonomies.
if ( ! function_exists( 'wlpf_get_product_taxonomies' ) ) {
    /**
     * Get product taxonomies.
     */
    function wlpf_get_product_taxonomies( $type = 'both' ) {
        $output = array(
            '' => esc_html__( 'Select Taxonomy', 'woolentor-pro' ),
        );

        $taxonomies = get_object_taxonomies( 'product', 'objects' );

        if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
            foreach ( $taxonomies as $key => $data ) {
                if ( is_object( $data ) ) {
                    if ( ( 'pa_' !== substr( $key, 0, 3 ) ) && ( $data->show_ui ) ) {
                        $output[ $key ] = $data->label;
                    }
                }
            }
        }

        if ( 'key' === $type ) {
            $output = array_keys( $output );
        } elseif ( 'label' === $type ) {
            $output = array_values( $output );
        }

        return $output;
    }
}

// Get product taxonomies with terms.
if ( ! function_exists( 'wlpf_get_product_taxonomies_with_terms' ) ) {
    /**
     * Get product taxonomies with terms.
     */
    function wlpf_get_product_taxonomies_with_terms() {
        $output = array();

        $taxonomies = get_object_taxonomies( 'product', 'objects' );

        if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
            foreach ( $taxonomies as $key => $data ) {
                if ( is_object( $data ) ) {
                    if ( ( 'pa_' !== substr( $key, 0, 3 ) ) && ( $data->show_ui ) ) {
                        $terms = get_terms( array( 'taxonomy' => $key ) );

                        if ( is_array( $terms ) && ! empty( $terms ) ) {
                            foreach ( $terms as $term ) {
                                if ( is_object( $term ) ) {
                                    $id = ( isset( $term->term_id ) ? absint( $term->term_id ) : 0 );
                                    $name = ( isset( $term->name ) ? sanitize_text_field( $term->name ) : '' );

                                    if ( ! empty( $id ) && ! empty( $name ) ) {
                                        $output[ $key ][ $id ] = $name;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }
}

// Get product attributes.
if ( ! function_exists( 'wlpf_get_product_attributes' ) ) {
    /**
     * Get product attributes.
     */
    function wlpf_get_product_attributes( $type = 'both' ) {
        $output = array(
            '' => esc_html__( 'Select Attribute', 'woolentor-pro' ),
        );

        $attributes = get_object_taxonomies( 'product', 'objects' );

        if ( is_array( $attributes ) && ! empty( $attributes ) ) {
            foreach ( $attributes as $key => $data ) {
                if ( is_object( $data ) ) {
                    if ( ( 'pa_' === substr( $key, 0, 3 ) ) && ( $data->show_ui ) ) {
                        $output[ $key ] = $data->label;
                    }
                }
            }
        }

        if ( 'key' === $type ) {
            $output = array_keys( $output );
        } elseif ( 'label' === $type ) {
            $output = array_values( $output );
        }

        return $output;
    }
}

// Get product attributes with terms.
if ( ! function_exists( 'wlpf_get_product_attributes_with_terms' ) ) {
    /**
     * Get product attributes with terms.
     */
    function wlpf_get_product_attributes_with_terms() {
        $output = array();

        $attributes = get_object_taxonomies( 'product', 'objects' );

        if ( is_array( $attributes ) && ! empty( $attributes ) ) {
            foreach ( $attributes as $key => $data ) {
                if ( is_object( $data ) ) {
                    if ( ( 'pa_' === substr( $key, 0, 3 ) ) && ( $data->show_ui ) ) {
                        $terms = get_terms( array( 'taxonomy' => $key ) );

                        if ( is_array( $terms ) && ! empty( $terms ) ) {
                            foreach ( $terms as $term ) {
                                if ( is_object( $term ) ) {
                                    $id = ( isset( $term->term_id ) ? absint( $term->term_id ) : 0 );
                                    $name = ( isset( $term->name ) ? sanitize_text_field( $term->name ) : '' );

                                    if ( ! empty( $id ) && ! empty( $name ) ) {
                                        $output[ $key ][ $id ] = $name;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }
}

// Get product global taxonomies.
if ( ! function_exists( 'wlpf_get_product_global_taxonomies' ) ) {
    /**
     * Get product global taxonomies.
     */
    function wlpf_get_product_global_taxonomies( $type = 'both' ) {
        $output = array(
            '' => esc_html__( 'Select Taxonomy', 'woolentor-pro' ),
        );

        $taxonomies = get_object_taxonomies( 'product', 'objects' );

        if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
            foreach ( $taxonomies as $key => $data ) {
                if ( is_object( $data ) ) {
                    if ( $data->show_ui ) {
                        $output[ $key ] = $data->label;
                    }
                }
            }
        }

        if ( 'key' === $type ) {
            $output = array_keys( $output );
        } elseif ( 'label' === $type ) {
            $output = array_values( $output );
        }

        return $output;
    }
}

// Get product global taxonomies with terms.
if ( ! function_exists( 'wlpf_get_product_global_taxonomies_with_terms' ) ) {
    /**
     * Get product global taxonomies with terms.
     */
    function wlpf_get_product_global_taxonomies_with_terms() {
        $output = array();

        $taxonomies = get_object_taxonomies( 'product', 'objects' );

        if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
            foreach ( $taxonomies as $key => $data ) {
                if ( is_object( $data ) ) {
                    if ( $data->show_ui ) {
                        $terms = get_terms( array( 'taxonomy' => $key ) );

                        if ( is_array( $terms ) && ! empty( $terms ) ) {
                            foreach ( $terms as $term ) {
                                if ( is_object( $term ) ) {
                                    $id = ( isset( $term->term_id ) ? absint( $term->term_id ) : 0 );
                                    $name = ( isset( $term->name ) ? sanitize_text_field( $term->name ) : '' );

                                    if ( ! empty( $id ) && ! empty( $name ) ) {
                                        $output[ $key ][ $id ] = $name;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }
}

// Get parent terms IDs.
if ( ! function_exists( 'wlpf_get_parent_terms_ids' ) ) {
    /**
     * Get parent terms IDs.
     */
    function wlpf_get_parent_terms_ids( $term_id, $taxonomy ) {
        $list = array();
        $term = get_term( $term_id, $taxonomy );

        if ( is_wp_error( $term ) ) {
            return $term;
        }

        if ( ! $term ) {
            return $list;
        }

        $term_id = $term->term_id;
        $parents = get_ancestors( $term_id, $taxonomy, 'taxonomy' );

        //foreach ( array_reverse( $parents ) as $term_id ) {}
        foreach ( $parents as $term_id ) {
            $parent = get_term( $term_id, $taxonomy );
            $parent_id = $parent->term_id;

            $list[] = $parent_id;
        }

        return $list;
    }
}

// Get sorting options.
if ( ! function_exists( 'wlpf_get_sorting_options' ) ) {
    /**
     * Get sorting options.
     */
    function wlpf_get_sorting_options( $type = 'both' ) {
        $output = array(
            'menu_order' => esc_html__( 'Default sorting', 'upfilter-pro' ),
            'popularity' => esc_html__( 'Sort by popularity', 'upfilter-pro' ),
            'rating'     => esc_html__( 'Sort by average rating', 'upfilter-pro' ),
            'date'       => esc_html__( 'Sort by latest', 'upfilter-pro' ),
            'price'      => esc_html__( 'Sort by price: low to high', 'upfilter-pro' ),
            'price-desc' => esc_html__( 'Sort by price: high to low', 'upfilter-pro' ),
        );

        if ( 'key' === $type ) {
            $output = array_keys( $output );
        } elseif ( 'label' === $type ) {
            $output = array_values( $output );
        }

        return $output;
    }
}

// Get terms orderby options.
if ( ! function_exists( 'wlpf_get_terms_orderby_options' ) ) {
    /**
     * Get terms orderby options.
     */
    function wlpf_get_terms_orderby_options( $type = 'both' ) {
        $output = array(
            'menu_order' => esc_html__( 'Default', 'upfilter-pro' ),
            'name'       => esc_html__( 'Term name', 'upfilter-pro' ),
            'term_id'    => esc_html__( 'Term ID', 'upfilter-pro' ),
            'slug'       => esc_html__( 'Term slug', 'upfilter-pro' ),
            'term_order' => esc_html__( 'Term order', 'upfilter-pro' ),
            'count'      => esc_html__( 'Term item count', 'upfilter-pro' ),
            'include'    => esc_html__( 'Include', 'upfilter-pro' ),
        );

        if ( 'key' === $type ) {
            $output = array_keys( $output );
        } elseif ( 'label' === $type ) {
            $output = array_values( $output );
        }

        return $output;
    }
}

// Get author orderby options.
if ( ! function_exists( 'wlpf_get_author_orderby_options' ) ) {
    /**
     * Get author orderby options.
     */
    function wlpf_get_author_orderby_options( $type = 'both' ) {
        $output = array(
            'login'        => esc_html__( 'Login', 'upfilter-pro' ),
            'id'           => esc_html__( 'User ID', 'upfilter-pro' ),
            'display_name' => esc_html__( 'Display name', 'upfilter-pro' ),
            'nicename'     => esc_html__( 'Nickname', 'upfilter-pro' ),
            'user_name'    => esc_html__( 'Username', 'upfilter-pro' ),
            'email'        => esc_html__( 'User email', 'upfilter-pro' ),
            'include'      => esc_html__( 'Include', 'upfilter-pro' ),
        );

        if ( 'key' === $type ) {
            $output = array_keys( $output );
        } elseif ( 'label' === $type ) {
            $output = array_values( $output );
        }

        return $output;
    }
}

// Get item title structure.
if ( ! function_exists( 'wlpf_get_item_title_structure' ) ) {
    /**
     * Get item title structure.
     */
    function wlpf_get_item_title_structure() {
        return sprintf( esc_html__( 'ID# %1$s', 'woolentor-pro' ), '_WLPF_ID_' );
    }
}

// Get item with label title structure.
if ( ! function_exists( 'wlpf_get_item_title_with_label_structure' ) ) {
    /**
     * Get item with label title structure.
     */
    function wlpf_get_item_title_with_label_structure() {
        return sprintf( esc_html__( 'ID# %1$s %2$s %3$s', 'woolentor-pro' ), '_WLPF_ID_', '&mdash;', '_WLPF_LABEL_' );
    }
}

// Get elementor editor mode.
if ( ! function_exists( 'wlpf_get_elementor_editor_mode' ) ) {
    /**
     * Get elementor editor mode.
     */
    function wlpf_get_elementor_editor_mode() {
        $editor_mode = false;

        if ( class_exists( '\Elementor\Plugin' ) ) {
            $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
            $preview_mode = \Elementor\Plugin::$instance->preview->is_preview_mode();

            if ( $edit_mode || $preview_mode ) {
                $editor_mode = true;
            }
        }

        return $editor_mode;
    }
}