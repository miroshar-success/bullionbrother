<?php
/**
 * Create log.
 */

namespace WLEA\Workflow\Action;

/**
 * Class.
 */
class Create_Log {

    /**
     * Task.
     */
    protected $task;

    /**
     * Status.
     */
    protected $status;

    /**
     * Response.
     */
    protected $response;

	/**
     * Constructor.
     */
    public function __construct( $task = array(), $status = false ) {
        $this->task = wlea_cast( $task, 'array', false );
        $this->status = wlea_cast( $status, 'bool' );

        $this->trigger();
    }

    /**
     * Trigger.
     */
    protected function trigger() {
        global $wpdb;

        $performed_tasks_table = $wpdb->prefix . 'wlea_performed_tasks';

        $task = $this->task;
        $status = $this->status;

        $task_id = ( isset( $task['ID'] ) ? wlea_cast( $task['ID'], 'absint' ) : 0 );
        $action = ( isset( $task['action'] ) ? wlea_cast( $task['action'], 'key' ) : '' );
        $event = ( isset( $task['event'] ) ? wlea_cast( $task['event'], 'key' ) : '' );
        $recipient = ( isset( $task['recipient'] ) ? wlea_cast( $task['recipient'], 'email' ) : '' );
        $template = ( isset( $task['template'] ) ? wlea_cast( $task['template'], 'absint' ) : 0 );
        $wait_for = ( isset( $task['wait_for'] ) ? wlea_cast( $task['wait_for'], 'absint' ) : 0 );
        $elements = ( isset( $task['elements'] ) ? wlea_cast( $task['elements'], 'text' ) : '' );
        $schedule_date = ( isset( $task['schedule_date'] ) ? wlea_cast( $task['schedule_date'], 'text' ) : '' );
        $schedule_date_gmt = ( isset( $task['schedule_date_gmt'] ) ? wlea_cast( $task['schedule_date_gmt'], 'text' ) : '' );
        $success = $status;
        $tried = ( ( isset( $task['tried'] ) ) ? wlea_cast( $task['tried'], 'absint' ) : 0 );
        $modified_date = ( isset( $task['modified_date'] ) ? wlea_cast( $task['modified_date'], 'text' ) : '' );
        $modified_date_gmt = ( isset( $task['modified_date_gmt'] ) ? wlea_cast( $task['modified_date_gmt'], 'text' ) : '' );
        $added_date = ( isset( $task['added_date'] ) ? wlea_cast( $task['added_date'], 'text' ) : '' );
        $added_date_gmt = ( isset( $task['added_date_gmt'] ) ? wlea_cast( $task['added_date_gmt'], 'text' ) : '' );

        $current_time = current_time( 'mysql' );
        $current_time_gmt = current_time( 'mysql', true );

        $data = array(
            'task_id' => $task_id,
            'action' => $action,
            'event' => $event,
            'recipient' => $recipient,
            'template' => $template,
            'elements' => $elements,
            'schedule_date' => $schedule_date,
            'schedule_date_gmt' => $schedule_date_gmt,
            'perform_date' => $current_time,
            'perform_date_gmt' => $current_time_gmt,
            'success' => $success,
            'tried' => $tried,
            'modified_date' => $modified_date,
            'modified_date_gmt' => $modified_date_gmt,
            'added_date' => $added_date,
            'added_date_gmt' => $added_date_gmt,
        );

        $format = array( '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s' );

        $insert = $wpdb->insert( $performed_tasks_table, $data, $format );

        $this->response = ( ! empty( $insert ) ? true : false );
    }

    /**
     * Get response.
     */
    public function get_response() {
        return rest_sanitize_boolean( $this->response );
    }

}