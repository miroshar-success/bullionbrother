<?php
/**
 * Functions.
 */

// Hooks.
add_action( 'wp_ajax_wloptf_ajax_select', 'wloptf_ajax_select' );
add_action( 'wp_ajax_wloptf_ajax_select2', 'wloptf_ajax_select2' );
add_action( 'wp_ajax_wloptf_ajax_update_rules', 'wloptf_ajax_update_rules' );

// Ajax select.
if ( ! function_exists( 'wloptf_ajax_select' ) ) {
    /**
     * Ajax select.
     */
    function wloptf_ajax_select () {
        $response = array();
        $select_options = '';

        $data = ( isset( $_POST ) ? wloptf_cast( $_POST, 'array' ) : array() );

        if ( ! isset( $data['nonce'] ) || ! wp_verify_nonce( $data['nonce'], 'wloptf-ajax-nonce' ) ) {
            wp_send_json( $response );
        }

        $ajax     = ( isset( $data['ajax'] ) ? wloptf_cast( $data['ajax'], 'bool' ) : false );
        $multiple = ( isset( $data['multiple'] ) ? wloptf_cast( $data['multiple'], 'bool' ) : false );

        $options = ( isset( $data['options'] ) ? wloptf_cast( $data['options'], 'array' ) : array() );

        $query_type = ( isset( $data['query_type'] ) ? wloptf_cast( $data['query_type'], 'key' ) : '' );
        $query_args = ( isset( $data['query_args'] ) ? wloptf_cast( $data['query_args'], 'array' ) : array() );

        $value = ( isset( $data['value'] ) ? $data['value'] : '' );
        $value = ( ( true === $multiple ) ? wloptf_cast( $value, 'array' ) : wloptf_cast( $value, 'text' ) );

        $opts = array();

        if ( ! empty( $query_args ) ) {
            if ( 'taxonomy_term' === $query_type ) {
                $query_args = wp_parse_args( $query_args, array(
                    'taxonomy' => 'category',
                    'hide_empty' => false,
                ) );

                if ( ( true === $ajax ) && ! empty( $value ) ) {
                    $query_args['include'] = ( is_array( $value ) ? $value : array( $value ) );
                }

                $terms = get_terms( $query_args );

                if ( is_array( $terms ) && ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        if ( ! is_object( $term ) || empty( $term ) ) {
                            continue;
                        }

                        $id = ( isset( $term->term_id ) ? absint( $term->term_id ) : 0 );

                        $title = ( isset( $term->name ) ? sanitize_text_field( $term->name ) : '' );
                        $title = ( ( 0 < strlen( $title ) ) ? $title : esc_html__( 'Unnamed', 'woolentor-pro' ) );

                        if ( ! empty( $id ) ) {
                            $opts[ $id ] = $title;
                        }
                    }
                }
            } else {
                $query_args = wp_parse_args( $query_args, array(
                    'post_type' => 'post',
                ) );

                if ( ( true === $ajax ) && ! empty( $value ) ) {
                    $query_args['include'] = ( is_array( $value ) ? $value : array( $value ) );
                }

                $query_args['posts_per_page'] = -1;

                $posts = get_posts( $query_args );

                if ( is_array( $posts ) && ! empty( $posts ) ) {
                    foreach ( $posts as $post ) {
                        if ( ! is_object( $post ) || empty( $post ) ) {
                            continue;
                        }

                        $id = ( isset( $post->ID ) ? absint( $post->ID ) : 0 );

                        $title = ( isset( $post->post_title ) ? sanitize_text_field( $post->post_title ) : '' );
                        $title = ( ( 0 < strlen( $title ) ) ? $title : esc_html__( 'Unnamed', 'woolentor-pro' ) );

                        if ( ! empty( $id ) ) {
                            $opts[ $id ] = $title;
                        }
                    }
                }
            }
        } else {
            $opts = $options;
        }

        foreach ( $opts as $opt_key => $opt_label ) {
            if ( in_array( $opt_key, $value ) ) {
                $select_options .= '<option value="' . esc_attr( $opt_key ) . '" selected>' . esc_html( $opt_label ) . '</option>';
            } else {
                $select_options .= '<option value="' . esc_attr( $opt_key ) . '">' . esc_html( $opt_label ) . '</option>';
            }
        }

        $response['select_options'] = $select_options;

        wp_send_json( $response );
    }
}

// Ajax Select2.
if ( ! function_exists( 'wloptf_ajax_select2' ) ) {
    /**
     * Ajax Select2.
     */
    function wloptf_ajax_select2 () {
        $response = array();

        $data = ( isset( $_POST ) ? wloptf_cast( $_POST, 'array' ) : array() );

        if ( ! isset( $data['nonce'] ) || ! wp_verify_nonce( $data['nonce'], 'wloptf-ajax-nonce' ) ) {
            wp_send_json( $response );
        }

        $options     = ( isset( $data['options'] ) ? wloptf_cast( $data['options'], 'array' ) : array() );
        $query_type  = ( isset( $data['query_type'] ) ? wloptf_cast( $data['query_type'], 'key' ) : '' );
        $query_args  = ( isset( $data['query_args'] ) ? wloptf_cast( $data['query_args'], 'array' ) : array() );
        $search_term = ( isset( $data['search_term'] ) ? wloptf_cast( $data['search_term'], 'text' ) : '' );

        if ( ! empty( $query_args ) ) {
            if ( 'taxonomy_term' === $query_type ) {
                $query_args = wp_parse_args( $query_args, array(
                    'taxonomy' => 'category',
                    'hide_empty' => false,
                ) );

                if ( 0 < strlen( $search_term ) ) {
                    $query_args['search'] = $search_term;
                }

                $terms = get_terms( $query_args );

                if ( is_array( $terms ) && ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        if ( ! is_object( $term ) || empty( $term ) ) {
                            continue;
                        }

                        $id = ( isset( $term->term_id ) ? absint( $term->term_id ) : 0 );

                        $title = ( isset( $term->name ) ? sanitize_text_field( $term->name ) : '' );
                        $title = ( ( 0 < strlen( $title ) ) ? $title : esc_html__( 'Unnamed', 'woolentor-pro' ) );

                        if ( ! empty( $id ) ) {
                            $response[ $id ] = $title;
                        }
                    }
                }
            } else {
                $query_args = wp_parse_args( $query_args, array(
                    'post_type' => 'post',
                ) );

                $query_args['posts_per_page'] = 25;

                if ( 0 < strlen( $search_term ) ) {
                    $query_args['s'] = $search_term;
                }

                $posts = get_posts( $query_args );

                if ( is_array( $posts ) && ! empty( $posts ) ) {
                    foreach ( $posts as $post ) {
                        if ( ! is_object( $post ) || empty( $post ) ) {
                            continue;
                        }

                        $id = ( isset( $post->ID ) ? absint( $post->ID ) : 0 );

                        $title = ( isset( $post->post_title ) ? sanitize_text_field( $post->post_title ) : '' );
                        $title = ( ( 0 < strlen( $title ) ) ? $title : esc_html__( 'Unnamed', 'woolentor-pro' ) );

                        if ( ! empty( $id ) ) {
                            $response[ $id ] = $title;
                        }
                    }
                }
            }
        } else {
            $response = array_merge( $response, $options );
        }

        wp_send_json( $response );
    }
}

// Ajax update rules.
if ( ! function_exists( 'wloptf_ajax_update_rules' ) ) {
    /**
     * Ajax update rules.
     */
    function wloptf_ajax_update_rules () {
        $response = array();

        if ( ! isset( $_POST ) || ! is_array( $_POST ) || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wloptf-ajax-nonce' ) ) {
            wp_send_json( $response );
        }

        $name     = ( isset( $_POST['name'] ) ? wloptf_cast( $_POST['name'], 'text' ) : array() );
        $groups   = ( isset( $_POST['groups'] ) ? wloptf_cast( $_POST['groups'], 'array', false ) : array() );
        $settings = ( isset( $_POST['settings'] ) ? wloptf_cast( $_POST['settings'], 'array', false ) : array() );

        if ( empty( $settings ) ) {
            wp_send_json( $response );
        }

        $items  = wloptf_ajax_create_rules_items( $name, $groups, $settings );
        $fields = wloptf_ajax_create_rules_fields( $settings );
        $sample = wloptf_ajax_create_rules_sample( $fields );

        $fields = json_encode( $fields );

        $response = array(
            'items'  => $items,
            'fields' => $fields,
            'sample' => $sample,
        );

        wp_send_json( $response );
    }
}

// Ajax create rules items.
if ( ! function_exists( 'wloptf_ajax_create_rules_items' ) ) {
    /**
     * Ajax create rules items.
     */
    function wloptf_ajax_create_rules_items ( $name = '', $groups = array(), $settings = array() ) {
        $items = '';

        $name     = wloptf_cast( $name, 'text' );
        $groups   = wloptf_cast( $groups, 'array', false );
        $settings = wloptf_cast( $settings, 'array', false );

        if ( empty( $name ) || empty( $groups ) || empty( $settings ) ) {
            return $items;
        }

        $base_args = ( isset( $settings['base'] ) ? wloptf_cast( $settings['base'], 'array', false ) : array() );
        $deps_args = ( isset( $settings['deps'] ) ? wloptf_cast( $settings['deps'], 'array', false ) : array() );

        $tbase_name = sprintf( '%1$s[%2$s][%3$s]', $name, 'WLOPTF8888', 'WLOPTF9999' );

        $g_count = 0;

        foreach ( $groups as $group ) {
            $g_items = wloptf_cast( $group, 'array', false );

            if ( ! empty( $g_items ) ) {
                ob_start();
                ?>
                <div class="wloptf-rules-group">
                    <div class="wloptf-rules-group-devider"><span><?php esc_html_e( 'OR', 'woolentor-pro' ); ?></span></div>
                    <div class="wloptf-rules-group-content">
                        <div class="wloptf-rules-items">
                            <?php
                            $i_count = 0;

                            foreach ( $g_items as $g_item ) {
                                $g_item = ( is_array( $g_item ) ? $g_item : array() );

                                if ( ! empty( $g_item ) ) {
                                    $base_name = sprintf( '%1$s[%2$s][%3$s]', $name, $g_count, $i_count );

                                    foreach ( $g_item as $item_base => $item_deps ) {
                                        $item_base = wloptf_cast( $item_base, 'text' );
                                        $item_deps = wloptf_cast( $item_deps, 'array', false );

                                        if ( empty( $item_base ) || empty( $item_deps ) ) {
                                            continue;
                                        }

                                        $operator = ( isset( $item_deps['operator'] ) ? wloptf_cast( $item_deps['operator'], 'text' ) : '' );

                                        $value = ( isset( $item_deps['value'] ) ? $item_deps['value'] : '' );
                                        $value = ( is_array( $value ) ? wloptf_cast( $value, 'array' ) : wloptf_cast( $value, 'text' ) );

                                        if ( 1 > strlen( $operator ) ) {
                                            continue;
                                        }

                                        $item_deps_args = ( isset( $deps_args[ $item_base ] ) ? wloptf_cast( $deps_args[ $item_base ], 'array', false ) : array() );

                                        $item_operator_args = ( isset( $item_deps_args['operator'] ) ? wloptf_cast( $item_deps_args['operator'], 'array', false ) : array() );
                                        $item_value_args    = ( isset( $item_deps_args['value'] ) ? wloptf_cast( $item_deps_args['value'], 'array', false ) : array() );

                                        $item_base_args = array_merge( $base_args, array(
                                            'default'    => $item_base,
                                            'base_name'  => $base_name,
                                            'tbase_name' => $tbase_name,
                                        ) );

                                        $item_operator_args = array_merge( $item_operator_args, array(
                                            'default'    => $operator,
                                            'base_name'  => $base_name,
                                            'tbase_name' => $tbase_name,
                                        ) );

                                        $item_value_args = array_merge( $item_value_args, array(
                                            'default'    => $value,
                                            'base_name'  => $base_name,
                                            'tbase_name' => $tbase_name,
                                        ) );
                                        ?>
                                        <div class="wloptf-rules-item">
                                            <div class="wloptf-rules-item-devider"><span><?php esc_html_e( 'AND', 'woolentor-pro' ); ?></span></div>
                                            <div class="wloptf-rules-item-content">
                                                <div class="wloptf-rules-item-fields">
                                                    <div class="wloptf-rules-item-base"><?php \WLOPTF\Field::instance( $item_base_args, true, false, false ); ?></div>
                                                    <div class="wloptf-rules-item-operator"><?php \WLOPTF\Field::instance( $item_operator_args, true, false, false ); ?></div>
                                                    <div class="wloptf-rules-item-value"><?php \WLOPTF\Field::instance( $item_value_args, true, false, false ); ?></div>
                                                </div>
                                                <div class="wloptf-rules-item-controls">
                                                    <button class="button wloptf-add"><span class="wloptf-icon wloptf-icon-insert"></span></button>
                                                    <button class="button wloptf-remove"><span class="wloptf-icon wloptf-icon-remove"></span></button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }

                                $i_count++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                $item = ob_get_clean();
                $item = wloptf_clean_html( $item );

                $items .= $item;
            }

            $g_count++;
        }

        return $items;
    }
}

// Ajax create rules fields.
if ( ! function_exists( 'wloptf_ajax_create_rules_fields' ) ) {
    /**
     * Ajax create rules fields.
     */
    function wloptf_ajax_create_rules_fields ( $settings = array() ) {
        $fields = array();

        $base = ( isset( $settings['base'] ) ? wloptf_cast( $settings['base'], 'array', false ) : array() );
        $deps = ( isset( $settings['deps'] ) ? wloptf_cast( $settings['deps'], 'array', false ) : array() );

        if ( empty( $base ) || empty( $deps ) ) {
            return $fields;
        }

        foreach ( $deps as $deps_key => $deps_fields ) {
            $operator = ( isset( $deps_fields['operator'] ) ? wloptf_cast( $deps_fields['operator'], 'array', false ) : array() );
            $value    = ( isset( $deps_fields['value'] ) ? wloptf_cast( $deps_fields['value'], 'array', false ) : array() );

            ob_start();
            \WLOPTF\Field::instance( $operator, false, false, false );
            $operator_field = ob_get_clean();

            ob_start();
            \WLOPTF\Field::instance( $value, false, false, false );
            $value_field = ob_get_clean();

            $operator_field = wloptf_clean_html( $operator_field );
            $value_field    = wloptf_clean_html( $value_field );

            $fields['deps'][ $deps_key ] = array(
                'operator' => $operator_field,
                'value'    => $value_field,
            );
        }

        ob_start();
        \WLOPTF\Field::instance( $base, false, false, false );
        $base_field = ob_get_clean();

        $base_field = wloptf_clean_html( $base_field );

        $fields['base'] = $base_field;

        return $fields;
    }
}

// Ajax create rules sample.
if ( ! function_exists( 'wloptf_ajax_create_rules_sample' ) ) {
    /**
     * Ajax create rules sample.
     */
    function wloptf_ajax_create_rules_sample ( $fields = array() ) {
        $sample = '';

        $base_field = ( isset( $fields['base'] ) ? $fields['base'] : '' );

        $deps_fields = ( isset( $fields['deps'] ) ? wloptf_cast( $fields['deps'], 'array', false ) : array() );
        $deps_fields = array_values( $deps_fields );
        $deps_fields = ( isset( $deps_fields[0] ) ? wloptf_cast( $deps_fields[0], 'array', false ) : array() );

        if ( ! empty( $base_field ) && ! empty( $deps_fields ) ) {
            $operator_field = ( isset( $deps_fields['operator'] ) ? $deps_fields['operator'] : '' );
            $value_field    = ( isset( $deps_fields['value'] ) ? $deps_fields['value'] : '' );

            if ( ! empty( $operator_field ) && ! empty( $value_field ) ) {
                ob_start();
                ?>
                <div class="wloptf-rules-group">
                    <div class="wloptf-rules-group-devider"><span><?php esc_html_e( 'OR', 'woolentor-pro' ); ?></span></div>
                    <div class="wloptf-rules-group-content">
                        <div class="wloptf-rules-items">
                            <div class="wloptf-rules-item">
                                <div class="wloptf-rules-item-devider"><span><?php esc_html_e( 'AND', 'woolentor-pro' ); ?></span></div>
                                <div class="wloptf-rules-item-content">
                                    <div class="wloptf-rules-item-fields">
                                        <div class="wloptf-rules-item-base"><?php echo $base_field; ?></div>
                                        <div class="wloptf-rules-item-operator"><?php echo $operator_field; ?></div>
                                        <div class="wloptf-rules-item-value"><?php echo $value_field; ?></div>
                                    </div>
                                    <div class="wloptf-rules-item-controls">
                                        <button class="button wloptf-add"><span class="wloptf-icon wloptf-icon-insert"></span></button>
                                        <button class="button wloptf-remove"><span class="wloptf-icon wloptf-icon-remove"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $sample = ob_get_clean();
                $sample = wloptf_clean_html( $sample );
            }
        }

        return $sample;
    }
}