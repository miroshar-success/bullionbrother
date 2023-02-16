<?php
/**
 * Action.
 */

namespace WLEA\Workflow;

/**
 * Class.
 */
class Action {

    /**
     * Send email.
     */
    public static function send_email( $elements = array() ) {
        $send_email = new Action\Send_Email( $elements );

        return ( is_object( $send_email ) && method_exists( $send_email, 'get_response' ) ) ? $send_email->get_response() : false;
    }

    /**
     * Create log.
     */
    public static function create_log( $task = array(), $status = false ) {
        $create_log = new Action\Create_Log( $task, $status );

        return ( is_object( $create_log ) && method_exists( $create_log, 'get_response' ) ) ? $create_log->get_response() : false;
    }

    /**
     * Update tried.
     */
    public static function update_tried( $task_id = 0, $tried = 0 ) {
        $update_tried = new Action\Update_Tried( $task_id, $tried );

        return ( is_object( $update_tried ) && method_exists( $update_tried, 'get_response' ) ) ? $update_tried->get_response() : false;
    }

}