<?php
/**
 * Tables.
 */

namespace WLEA\Admin;

/**
 * Class.
 */
class Table {

    /**
     * Tasks.
     */
    public static function tasks() {
        $table = new Table\Tasks();
        $table->process_actions();
        $table->prepare_tasks_items();
        $table->search_box( esc_html__( 'Search', 'woolentor-pro' ), 'task' );
        $table->views();
        $table->display();
    }

    /**
     * Logs.
     */
    public static function logs() {
        $table = new Table\Logs();
        $table->process_actions();
        $table->prepare_logs_items();
        $table->search_box( esc_html__( 'Search', 'woolentor-pro' ), 'log' );
        $table->views();
        $table->display();
    }

}