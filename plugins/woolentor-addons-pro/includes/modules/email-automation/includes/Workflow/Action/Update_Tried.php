<?php
/**
 * Update tried.
 */

namespace WLEA\Workflow\Action;

/**
 * Class.
 */
class Update_Tried {

    /**
     * Task ID.
     */
    protected $task_id;

    /**
     * Tried.
     */
    protected $tried;

    /**
     * Response.
     */
    protected $response;

	/**
     * Constructor.
     */
    public function __construct( $task_id = 0, $tried = 0 ) {
        $this->task_id = wlea_cast( $task_id, 'absint', 0 );
        $this->tried = wlea_cast( $tried, 'absint', 0 );

        $this->trigger();
    }

    /**
     * Trigger.
     */
    protected function trigger() {
        global $wpdb;

        $scheduled_tasks_table = $wpdb->prefix . 'wlea_scheduled_tasks';

        $current_time = current_time( 'mysql' );
        $current_time_gmt = current_time( 'mysql', true );

        $data = array(
            'tried' => $this->tried,
            'modified_date' => $current_time,
            'modified_date_gmt' => $current_time_gmt,
        );

        $where = array(
            'ID' => $this->task_id,
        );

        $format = array( '%d', '%s', '%s' );

        $where_format = array( '%d' );

        $update = $wpdb->update( $scheduled_tasks_table, $data, $where, $format, $where_format );

        $this->response = ( ! empty( $update ) ? true : false );
    }

    /**
     * Get response.
     */
    public function get_response() {
        return rest_sanitize_boolean( $this->response );
    }

}