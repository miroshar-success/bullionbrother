<?php
/**
 * Handler.
 */

namespace WLEA\Workflow;

/**
 * Class.
 */
class Handler {

	/**
     * Constructor.
     */
    public function __construct() {
        add_action( 'wlea_perform_minute_tasks', array( $this, 'perform_tasks' ) );
    }

    /**
     * Perform tasks.
     */
    public function perform_tasks() {
        global $wpdb;

        $scheduled_tasks_table = $wpdb->prefix . 'wlea_scheduled_tasks';

        $current_time_gmt = current_time( 'mysql', true );

        $tasks = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $scheduled_tasks_table . ' WHERE schedule_date_gmt < %s AND active=%d', $current_time_gmt, true ), ARRAY_A );
        $tasks = wlea_cast( $tasks, 'array' );

        if ( empty( $tasks ) ) {
            return;
        }

        foreach ( $tasks as $task ) {
            $task = wlea_cast( $task, 'array' );

            if ( empty( $task ) ) {
                continue;
            }

            $id = ( ( isset( $task['ID'] ) ) ? wlea_cast( $task['ID'], 'absint' ) : 0 );
            $action = ( ( isset( $task['action'] ) ) ? wlea_cast( $task['action'], 'key' ) : '' );
            $elements = ( ( isset( $task['elements'] ) && is_serialized( $task['elements'] ) ) ? wlea_cast( unserialize( $task['elements'] ), 'array', false ) : array() );

            $tried = ( ( isset( $task['tried'] ) ) ? wlea_cast( $task['tried'], 'absint' ) : 0 );
            $tried += 1;

            $task['tried'] = $tried;

            $task_action_status = false;

            if ( ! empty( $id ) && ! empty( $action ) && ! empty( $elements ) ) {
                if ( 'send_email' === $action ) {
                    $task_action_status = \WLEA\Workflow\Action::send_email( $elements );
                }

                \WLEA\Workflow\Action::create_log( $task, $task_action_status );
            }

            if ( true === $task_action_status ) {
                $this->remove_task( $id );
            } else {
                \WLEA\Workflow\Action::update_tried( $id, $tried );
            }
        }
    }

    /**
     * Remove task.
     */
    protected function remove_task( $id = 0 ) {
        global $wpdb;

        $table = $wpdb->prefix . 'wlea_scheduled_tasks';

        $wpdb->delete( $table, array( 'ID' => $id ), array( '%d' ) );
    }

}