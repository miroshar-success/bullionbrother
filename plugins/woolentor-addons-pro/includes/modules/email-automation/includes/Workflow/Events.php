<?php
/**
 * Events.
 */

namespace WLEA\Workflow;

/**
 * Class.
 */
class Events {

	/**
     * Constructor.
     */
    public function __construct() {
        add_action( 'wloptf_meta_box__wlea_workflow_trigger_save_after', array( $this, 'store_event' ), 10, 4 );
        add_action( 'transition_post_status', array( $this, 'active_event' ), 10, 3 );
        add_action( 'delete_post', array( $this, 'delete_event' ), 10, 2 );
    }

    /**
     * Store event.
     */
    public function store_event( $meta_data = array(), $post_id = 0, $post = null, $update = false ) {
        if ( 'wlea-workflow' !== get_post_type( $post_id ) ) {
            return;
        }

        global $wpdb;

        $workflow_events_table = $wpdb->prefix . 'wlea_workflow_events';

        $ex_events = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(ID) FROM ' . $workflow_events_table . ' WHERE workflow_id=%d', $post_id ) );
        $ex_events = absint( $ex_events );

        $event = ( ( is_array( $meta_data ) && isset( $meta_data['event'] ) ) ? $meta_data['event'] : '' );
        $event = sanitize_key( $event );

        $active = ( empty( $event ) ? false : ( 'publish' !== get_post_status( $post_id ) ? false : true ) );

        if ( empty( $ex_events ) ) {
            $this->insert_event( $event, $active, $post_id, $workflow_events_table, $wpdb );
        } else {
            $this->update_event( $event, $active, $post_id, $workflow_events_table, $wpdb );
        }
    }

    /**
     * Insert event.
     */
    public function insert_event( $event = '', $active = false, $post_id = 0, $workflow_events_table = '', $wpdb = null ) {
        $current_time = current_time( 'mysql' );
        $current_time_gmt = current_time( 'mysql', true );

        $data = array(
            'event'             => $event,
            'workflow_id'       => $post_id,
            'active'            => $active,
            'modified_date'     => $current_time,
            'modified_date_gmt' => $current_time_gmt,
            'added_date'        => $current_time,
            'added_date_gmt'    => $current_time_gmt,
        );

        $format = array( '%s', '%d', '%d', '%s', '%s', '%s', '%s' );

        $wpdb->insert( $workflow_events_table, $data, $format );
    }

    /**
     * Update event.
     */
    public function update_event( $event = '', $active = false, $post_id = 0, $workflow_events_table = '', $wpdb = null ) {
        $current_time = current_time( 'mysql' );
        $current_time_gmt = current_time( 'mysql', true );

        $data = array(
            'event'             => $event,
            'active'            => $active,
            'modified_date'     => $current_time,
            'modified_date_gmt' => $current_time_gmt,
        );

        $where = array(
            'workflow_id' => $post_id,
        );

        $format = array( '%s', '%d', '%s', '%s' );

        $where_format = array( '%d' );

        $wpdb->update( $workflow_events_table, $data, $where, $format, $where_format );
    }

    /**
     * Active event.
     */
    public function active_event( $new_status = '', $old_status = '', $post = null ) {
        $post_id = ( ( is_object( $post ) && isset( $post->ID ) ) ? absint( $post->ID ) : 0 );

        if ( empty( $post_id ) || ( $old_status === $new_status ) || ( 'wlea-workflow' !== get_post_type( $post_id ) ) ) {
            return;
        }

        $meta_data = get_post_meta( $post_id, '_wlea_workflow_trigger', true );
        $meta_data = ( is_array( $meta_data ) ? $meta_data : array() );

        if ( empty( $meta_data ) ) {
            return;
        }

        $this->store_event( $meta_data, $post_id, $post );
    }

    /**
     * Delete event.
     */
    public function delete_event( $post_id = 0, $post = null ) {
        if ( 'wlea-workflow' !== get_post_type( $post_id ) ) {
            return;
        }

        global $wpdb;

        $workflow_events_table = $wpdb->prefix . 'wlea_workflow_events';

        $where = array(
            'workflow_id' => $post_id,
        );

        $where_format = array( '%d' );

        $wpdb->delete( $workflow_events_table, $where, $where_format );
    }

}