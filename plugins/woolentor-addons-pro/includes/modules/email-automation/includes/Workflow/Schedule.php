<?php
/**
 * Schedule.
 */

namespace WLEA\Workflow;

/**
 * Class.
 */
class Schedule {

	/**
     * Constructor.
     */
    public function __construct() {
        $this->cron_schedules();
        $this->create_schedules();
    }

    /**
     * Cron schedules.
     */
    protected function cron_schedules() {
        add_filter( 'cron_schedules', function ( $schedules = array() ) {
            if ( ! is_array( $schedules ) ) {
                $schedules = [];
            }

            if ( ! isset( $schedules['wlea_every_minute'] ) ) {
                $schedules['wlea_every_minute'] = array(
                    'interval' => 60,
                    'display'  => esc_html__( 'Every Minute', 'woolentor-pro' ),
                );
            }

            return $schedules;
        }, 20 );
    }

    /**
     * Create schedules.
     */
    protected function create_schedules() {
        $timestamp  = current_time( 'timestamp' );
        $recurrence = 'wlea_every_minute';
        $hook       = 'wlea_scheduled_minute_tasks';
        $scheduled  = get_option( $hook, false );

        if ( true === rest_sanitize_boolean( $scheduled ) && wp_next_scheduled( $hook ) ) {
            return;
        }

        wp_clear_scheduled_hook( $hook );
        wp_schedule_event( $timestamp, $recurrence, $hook );

        update_option( $hook, true );
    }

}