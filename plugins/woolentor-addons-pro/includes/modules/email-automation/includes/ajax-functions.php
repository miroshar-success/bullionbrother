<?php
/**
 * Ajax functions.
 */

 // Hooks.
add_action( 'wp_ajax_wlea_admin_ajax_duplicate', 'wlea_admin_ajax_duplicate' );

// Admin ajax duplicate.
if ( ! function_exists( 'wlea_admin_ajax_duplicate' ) ) {
    /**
     * Admin ajax duplicate.
     */
    function wlea_admin_ajax_duplicate () {
        $response = array();

        if ( ! isset( $_POST['post_id'] ) || ! isset( $_POST['ajax_nonce'] ) && ! wp_verify_nonce( $_POST['ajax_nonce'], 'wlea-admin-ajax-nonce' ) ) {
            wp_send_json( $response );
        }

        $post_id = absint( $_POST['post_id'] );

        if ( empty( $post_id ) ) {
            wp_send_json( $response );
        }

        $post_type = get_post_type( $post_id );

        if ( ( 'wlea-email' !== $post_type ) && ( 'wlea-workflow' !== $post_type ) ) {
            wp_send_json( $response );
        }

        $duplicate_post = get_post( $post_id, 'ARRAY_A' );

        // Post title.
        $duplicate_post['post_title'] = sprintf( esc_html__( '%1$s Copy', 'woolentor-pro' ), $duplicate_post['post_title'] );

        // Post name.
        $duplicate_post['post_name'] = sanitize_title( $duplicate_post['post_name'] ) . '-copy';

        // Post status.
        $duplicate_post['post_status'] = 'draft';

        // Post date.
        $mysql_current_time = current_time( 'mysql' );
        $mysql_current_time_gmt = current_time( 'mysql', true );

        $duplicate_post['post_date'] = $mysql_current_time;
        $duplicate_post['post_date_gmt'] = $mysql_current_time_gmt;
        $duplicate_post['post_modified'] = $mysql_current_time;
        $duplicate_post['post_modified_gmt'] = $mysql_current_time_gmt;

        // Post author.
        $current_user_id = get_current_user_id();

        $duplicate_post['post_author'] = get_current_user_id();

        // Post content.
        $post_content = $duplicate_post['post_content'];

        $duplicate_post['post_content'] = str_replace( array( '\r\n', '\r', '\n' ), '<br />', $post_content );

        // Remove variable keys.
        unset( $duplicate_post['ID'] );
        unset( $duplicate_post['guid'] );
        unset( $duplicate_post['comment_count'] );

        // Insert the post.
        $duplicate_post_id = wp_insert_post( $duplicate_post );

        // Taxonomies & terms.
        $taxonomies = get_object_taxonomies( $duplicate_post['post_type'] );

        foreach ( $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'names' ) );

            wp_set_object_terms( $duplicate_post_id, $terms, $taxonomy );
        }

        // Custom fields.
        $custom_fields = get_post_custom( $post_id );

        if ( is_array( $custom_fields ) && ! empty( $custom_fields ) ) {
            global $wpdb;

            foreach ( $custom_fields as $key => $value ) {
                if ( is_array( $value ) && ! empty( $value ) ) {
                    foreach ( $value as $meta_value ) {
                        $wpdb->insert( $wpdb->prefix . 'postmeta', array(
                            'post_id'    => $duplicate_post_id,
                            'meta_key'   => $key,
                            'meta_value' => $meta_value,
                        ) );
                    }
                }
            }
        }

        // Trigger Codestar Framework metabox hooks.
        if ( 'wlea-workflow' === $post_type ) {
            $workflow_trigger_meta = get_post_meta( $duplicate_post_id, '_wlea_workflow_trigger', true );

            if ( empty( $workflow_trigger_meta ) ) {
                $workflow_trigger_meta = get_post_meta( $post_id, '_wlea_workflow_trigger', true );
            }

            do_action( 'wloptf_meta_box__wlea_workflow_trigger_save_after', $workflow_trigger_meta, $duplicate_post_id );
        }

        if ( ! empty( $duplicate_post_id ) ) {
            $response['duplicate_post_id'] = $duplicate_post_id;
        }

        wp_send_json( $response );
    }
}