<?php
/**
 * Tasks.
 */

namespace WLEA\Admin\Table;

/**
 * Class
 */
class Tasks extends \WP_List_Table {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct( array(
            'plural'   => 'tasks',
            'singular' => 'task',
            'ajax'     => false
        ) );
    }

    /**
     * No items message.
     */
    public function no_items() {
        esc_html_e( 'No tasks found!', 'woolentor-pro' );
    }

    /**
     * Get columns.
     */
    public function get_columns() {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'task'      => esc_html__( 'Task', 'woolentor-pro' ),
            'recipient' => esc_html__( 'Recipient', 'woolentor-pro' ),
            'event'     => esc_html__( 'Event', 'woolentor-pro' ),
            'template'  => esc_html__( 'Template', 'woolentor-pro' ),
            'schedule'  => esc_html__( 'Schedule', 'woolentor-pro' ),
            'status'    => esc_html__( 'Status', 'woolentor-pro' ),
            'added'     => esc_html__( 'Added', 'woolentor-pro' ),
        );

        return $columns;
    }

    /**
     * Get sortable columns.
     */
    public function get_sortable_columns() {
        $columns = array(
            'task'      => array( 'task', 'asc' ),
            'recipient' => array( 'recipient', 'asc' ),
            'event'     => array( 'event', 'asc' ),
            'template'  => array( 'template', 'asc' ),
            'schedule'  => array( 'schedule', 'asc' ),
            'status'    => array( 'status', 'asc' ),
            'added'     => array( 'added', 'asc' ),
        );

        return $columns;
    }

    /**
     * Extra tablenav.
     */
    public function extra_tablenav( $which ) {
        $events = array(
            '' => esc_html__( 'All events', 'woolentor-pro' ),
        );

        $events = array_merge( $events, wlea_get_trigger_events() );

        $statuses = array(
            ''         => esc_html__( 'All status', 'woolentor-pro' ),
            'active'   => esc_html__( 'Active', 'woolentor-pro' ),
            'inactive' => esc_html__( 'Inactive', 'woolentor-pro' ),
        );

        $event = '';
        $status = '';

        $event = isset( $_GET['event'] ) ? sanitize_text_field( $_GET['event'] ) : '';
        $event = isset( $events[ $event ] ) ? $event : '';

        $status = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '';
        $status = isset( $statuses[ $status ] ) ? $status : '';
        ?>
        <div class="alignleft events">
            <?php
            if ( 'top' === $which ) {
                ?>
                <label for="filter-by-event" class="screen-reader-text"><?php esc_html_e( 'Filter by event', 'woolentor-pro' ); ?></label>
                <select name="event" id="filter-by-event">
                    <?php
                    foreach ( $events as $key => $value ) {
                        if ( $key === $event ) {
                            ?>
                            <option value="<?php echo esc_attr( $key ); ?>" selected="selected"><?php echo esc_html( $value ); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <label for="filter-by-status" class="screen-reader-text"><?php esc_html_e( 'Filter by status', 'woolentor-pro' ); ?></label>
                <select name="status" id="filter-by-status">
                    <?php
                    foreach ( $statuses as $key => $value ) {
                        if ( $key === $status ) {
                            ?>
                            <option value="<?php echo esc_attr( $key ); ?>" selected="selected"><?php echo esc_html( $value ); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <?php
                submit_button( esc_html__( 'Filter', 'woolentor-pro' ), '', '', false, array( 'id' => 'filter-submit' ) );
            }
            ?>
        </div>
        <?php
        do_action( 'wlea_manage_tasks_extra_tablenav', $which );
    }

    /**
     * Get bulk actions.
     */
    public function get_bulk_actions() {
        $bulk_actions = array(
            'activate' => esc_html__( 'Activate', 'woolentor-pro' ),
            'deactivate' => esc_html__( 'Deactivate', 'woolentor-pro' ),
            'delete' => esc_html__( 'Delete', 'woolentor-pro' ),
        );

        return $bulk_actions;
    }

    /**
     * Column cb.
     */
    public function column_cb( $item ) {
        $ID = isset( $item['ID'] ) ? absint( $item['ID'] ) : 0;

        if ( ! empty( $ID ) ) {
            return '<input type="checkbox" name="task_ids[]" value="' . esc_attr( $ID ) . '" />';
        }
    }

    /**
     * Column default.
     */
    public function column_default( $task, $column ) {
        $output = '&mdash;';

        if ( ! is_array( $task ) || empty( $task ) ) {
            return $output;
        }

        $id = ( isset( $task['ID'] ) ? wlea_cast( $task['ID'], 'absint' ) : 0 );
        $event = ( isset( $task['event'] ) ? wlea_cast( $task['event'], 'key' ) : '' );
        $recipient = ( isset( $task['recipient'] ) ? wlea_cast( $task['recipient'], 'email' ) : '' );
        $template = ( isset( $task['template'] ) ? wlea_cast( $task['template'], 'absint' ) : 0 );
        $wait_for = ( isset( $task['wait_for'] ) ? wlea_cast( $task['wait_for'], 'absint' ) : 0 );
        $elements = ( ( isset( $task['elements'] ) && is_serialized( $task['elements'] ) ) ? wlea_cast( unserialize( $task['elements'] ), 'array', false ) : array() );
        $active = ( isset( $task['active'] ) ? wlea_cast( $task['active'], 'bool' ) : false );
        $schedule_date_gmt = ( isset( $task['schedule_date_gmt'] ) ? wlea_cast( $task['schedule_date_gmt'], 'text' ) : '' );
        $added_date_gmt = ( isset( $task['added_date_gmt'] ) ? wlea_cast( $task['added_date_gmt'], 'text' ) : '' );

        switch ( $column ) {
            case 'task':
                $output = $this->default_task( $id, $elements, $recipient, $active );
                break;

            case 'recipient':
                $output = $this->default_recipient( $recipient );
                break;

            case 'event':
                $output = $this->default_event( $event, $elements );
                break;

            case 'template':
                $output = $this->default_template( $template );
                break;

            case 'schedule':
                $output = $this->default_schedule( $schedule_date_gmt );
                break;

            case 'status':
                $output = $this->default_status( $active );
                break;

            case 'added':
                $output = $this->default_added( $added_date_gmt );
                break;
        }

        return $output;
    }

    /**
     * Default task.
     */
    public function default_task( $id = 0, $elements = array(), $recipient = '', $active = false ) {
        $output = '&mdash;';

        $task = '';
        $name = '';

        $meta = ( isset( $elements['meta'] ) ? wlea_cast( $elements['meta'], 'array' ) : array() );

        $order_id    = ( isset( $meta['order_id'] ) ? wlea_cast( $meta['order_id'], 'absint' ) : 0 );
        $customer_id = ( isset( $meta['customer_id'] ) ? wlea_cast( $meta['customer_id'], 'absint' ) : 0 );

        if ( ! empty( $order_id ) ) {
            $order = wc_get_order( $order_id );
            $name = ( is_object( $order ) ? $order->get_formatted_billing_full_name() : '' );
        } elseif ( ! empty( $customer_id ) ) {
            $order = wlea_get_customer_by_id( $customer_id );
            $name = ( is_object( $order ) ? $order->get_display_name() : '' );
        }

        if ( empty( $name ) ) {
            $name = ( isset( $elements['recipient_name'] ) ? wlea_cast( $elements['recipient_name'], 'text' ) : '' );
        }

        if ( ! empty( $name ) ) {
            $task = sprintf( esc_html__( '#%1$s &mdash; %2$s', 'woolentor-pro' ), $id, $name );
        } else {
            $task = sprintf( esc_html__( '#%1$s', 'woolentor-pro' ), $id );
        }

        if ( ! empty( $task ) ) {
            if ( ! empty( $recipient ) ) {
                $output = sprintf( '<strong><a class="row-title" href="mailto:%1$s">%2$s</a></strong>', $recipient, $task );
            } else {
                $output = sprintf( '<strong><span class="row-title">%1$s</span></strong>', $task );
            }
        }

        $activate_link = '';
        $deactivate_link = '';

        $query_args = array(
            'task-id' => $id,
            'wlea-activated' => false,
            'wlea-deactivated' => false,
            'wlea-deleted' => false,
            'wlea-fake-query-arg' => false,
            'action2' => false,
        );

        if ( false === $active ) {
            $activate_link = esc_url( wp_nonce_url( add_query_arg( array_merge( $query_args, array( 'action' => 'activate' ) ) ), 'activate-task', '_wpnonce' ) );
        } else {
            $deactivate_link = esc_url( wp_nonce_url( add_query_arg( array_merge( $query_args, array( 'action' => 'deactivate' ) ) ), 'deactivate-task', '_wpnonce' ) );
        }

        $delete_link = esc_url( wp_nonce_url( add_query_arg( array_merge( $query_args, array( 'action' => 'delete' ) ) ), 'delete-task', '_wpnonce' ) );

        $row_actions = array();

        if ( ! empty( $activate_link ) ) {
            $row_actions['activate'] = '<a href="' . esc_url( $activate_link ) . '">' . esc_html__( 'Activate', 'woolentor-pro' ) . '</a>';
        } else {
            $row_actions['deactivate'] = '<a href="' . esc_url( $deactivate_link ) . '">' . esc_html__( 'Deactivate', 'woolentor-pro' ) . '</a>';
        }

        $row_actions['delete'] = '<a href="' . esc_url( $delete_link ) . '">' . esc_html__( 'Delete', 'woolentor-pro' ) . '</a>';

        $row_actions = $this->row_actions( $row_actions );

        $output .= $row_actions;

        return $output;
    }

    /**
     * Default recipient.
     */
    public function default_recipient( $recipient = '' ) {
        $output = '&mdash;';

        if ( ! empty( $recipient ) ) {
            $output = sprintf( '<a href="mailto:%1$s">%1$s</a>', $recipient );
        }

        return $output;
    }

    /**
     * Default event.
     */
    public function default_event( $event = '', $elements = array() ) {
        $output = '&mdash;';

        if ( ! empty( $event ) ) {
            $events = wlea_get_trigger_events();

            if ( isset( $events[ $event ] ) ) {
                $output = wlea_cast( $events[ $event ], 'text' );
            } elseif ( isset( $elements['event_label'] ) ) {
                $output = wlea_cast( $elements['event_label'], 'text' );
            }
        }

        return $output;
    }

    /**
     * Default template.
     */
    public function default_template( $template = '' ) {
        $output = '&mdash;';

        $title = '';
        $link = '';

        $template_name = '';

        if ( ! empty( $template ) ) {
            $title = get_the_title( $template );
            $link = get_edit_post_link( $template );
        }

        if ( ! empty( $title ) ) {
            $template_name = sprintf( esc_html__( '#%1$s &mdash; %2$s', 'woolentor-pro' ), $template, $title );
        } else {
            $template_name = sprintf( esc_html__( '#%1$s', 'woolentor-pro' ), $template );
        }

        if ( ! empty( $link ) ) {
            $output = '<a target="_blank" href="' . $link . '">' . $template_name . '</a>';
        } else {
            $output = $template_name;
        }

        return $output;
    }

    /**
     * Default schedule.
     */
    public function default_schedule( $gmt_date = '' ) {
        $output = '&mdash;';

        if ( ! empty( $gmt_date ) ) {
            $local_date = get_date_from_gmt( $gmt_date, 'Y-m-d H:i:s' );
            $local_timestamp = mysql2date( 'U', $local_date, false );

            $current_local_timestamp = current_time( 'U' );

            $local_human_time_diff = ( ( $local_timestamp > $current_local_timestamp ) ? human_time_diff( $current_local_timestamp, $local_timestamp ) : sprintf( esc_html__( '%1$s second' ), 0 ) );
            $local_human_time_diff = ( ( false === strpos( $local_human_time_diff, 'minute' ) ) ? str_replace( 'min', 'minute', $local_human_time_diff ) : $local_human_time_diff );

            $output = sprintf( esc_html__( '%1$s at %2$s', 'woolentor-pro' ), date_i18n( 'Y/m/d', $local_timestamp ), date_i18n( 'g:i a', $local_timestamp ) );
            $output .= '<br>';
            $output .= sprintf( esc_html__( 'Remaining: %1$s', 'woolentor-pro' ), $local_human_time_diff );
        }

        return $output;
    }

    /**
     * Default status.
     */
    public function default_status( $status = false ) {
        $output = '&mdash;';

        if ( true === $status ) {
            $output = '<span class="wlea-list-table-status wlea-list-table-status-active"><span class="dashicons dashicons-yes-alt"></span><span>' . esc_html__( 'Active', 'woolentor-pro' ) . '</span></span>';
        } else {
            $output = '<span class="wlea-list-table-status wlea-list-table-status-inactive"><span class="dashicons dashicons-dismiss"></span><span>' . esc_html__( 'Inactive', 'woolentor-pro' ) . '</span></span>';
        }

        return $output;
    }

    /**
     * Default added.
     */
    public function default_added( $gmt_date = '' ) {
        $output = '';

        if ( ! empty( $gmt_date ) ) {
            $local_date = get_date_from_gmt( $gmt_date, 'Y-m-d H:i:s' );
            $local_timestamp = mysql2date( 'U', $local_date, false );

            $output = sprintf( esc_html__( '%1$s at %2$s', 'woolentor-pro' ), date_i18n( 'Y/m/d', $local_timestamp ), date_i18n( 'g:i a', $local_timestamp ) );
        }

        return $output;
    }

    /**
     * Process actions.
     *
     * @since 1.0.0
     */
    public function process_actions() {
        if ( isset( $_GET['_wpnonce'] ) ) {
            if ( isset( $_GET['action'] ) && isset( $_GET['action2'] ) ) {
                $bulk_tasks_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'bulk-tasks' );

                if ( false !== $bulk_tasks_nonce ) {
                    $this->process_bulk_actions();
                }
            } elseif ( isset( $_GET['action'] ) && isset( $_GET['task-id'] ) ) {
                $activate_task_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'activate-task' );
                $deactivate_task_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'deactivate-task' );
                $delete_task_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'delete-task' );

                if ( ( false !== $activate_task_nonce ) || ( false !== $deactivate_task_nonce ) || ( false !== $delete_task_nonce ) ) {
                    $this->process_single_actions();
                }
            } else {
                $this->process_redirection();
            }
        }
    }

    /**
     * Process single actions.
     */
    public function process_single_actions() {
        $action = isset( $_GET['action'] ) ? wlea_cast( $_GET['action'], 'key' ) : '';
        $task_id = isset( $_GET['task-id'] ) ? wlea_cast( $_GET['task-id'], 'absint' ) : 0;

        if ( empty( $action ) || empty( $task_id ) ) {
            return;
        }

        global $wpdb;
        $scheduled_tasks_table = $wpdb->prefix . 'wlea_scheduled_tasks';

        $query_args = array(
            'action' => false,
            'task-id' => false,
            '_wpnonce' => false,
        );

        if ( isset( $_GET['paged'] ) && ( 2 > wlea_cast( $_GET['paged'], 'absint' ) ) ) {
            $query_args['paged'] = false;
        }

        if ( 'activate' === $action ) {
            $data = array(
                'active' => true,
            );

            $where = array(
                'ID' => $task_id,
            );

            $format = array( '%d' );

            $where_format = array( '%d' );

            $task_activated = $wpdb->update( $scheduled_tasks_table, $data, $where, $format, $where_format );

            if ( 0 < $task_activated ) {
                $query_args['wlea-activated'] = $task_activated;
            }
        } elseif ( 'deactivate' === $action ) {
            $data = array(
                'active' => false,
            );

            $where = array(
                'ID' => $task_id,
            );

            $format = array( '%d' );

            $where_format = array( '%d' );

            $task_deactivated = $wpdb->update( $scheduled_tasks_table, $data, $where, $format, $where_format );

            if ( 0 < $task_deactivated ) {
                $query_args['wlea-deactivated'] = $task_deactivated;
            }
        } elseif ( 'delete' === $action ) {
            $data = array(
                'ID' => $task_id,
            );

            $format = array( '%d' );

            $task_deleted = $wpdb->delete( $scheduled_tasks_table, $data, $format );

            if ( 0 < $task_deleted ) {
                $query_args['wlea-deleted'] = $task_deleted;
            }
        }

        $redirect_url = add_query_arg( $query_args );

        if ( ! empty( $redirect_url ) ) {
            wp_redirect( $redirect_url );
            exit();
        }
    }

    /**
     * Process bulk actions.
     */
    public function process_bulk_actions() {
        $action = isset( $_GET['action'] ) ? wlea_cast( $_GET['action'], 'key' ) : '';
        $action2 = isset( $_GET['action2'] ) ? wlea_cast( $_GET['action2'], 'key' ) : '';

        if ( empty( $action ) || empty( $action2 ) ) {
            return;
        }

        global $wpdb;
        $scheduled_tasks_table = $wpdb->prefix . 'wlea_scheduled_tasks';

        $query_args = array(
            'action' => false,
            'action2' => false,
            'task_ids' => false,
            '_wpnonce' => false,
        );

        if ( isset( $_GET['paged'] ) && ( 2 > wlea_cast( $_GET['paged'], 'absint' ) ) ) {
            $query_args['paged'] = false;
        }

        $task_ids = isset( $_GET['task_ids'] ) ? wlea_cast( $_GET['task_ids'], 'array' ) : array();
        $task_ids = wlea_clean_array_of_id( $task_ids );

        if ( 'activate' === $action || 'activate' === $action2 ) {
            $tasks_activated_total = 0;

            foreach ( $task_ids as $task_id ) {
                $data = array(
                    'active' => true,
                );

                $where = array(
                    'ID' => $task_id,
                );

                $format = array( '%d' );

                $where_format = array( '%d' );

                $task_activated = $wpdb->update( $scheduled_tasks_table, $data, $where, $format, $where_format );

                if ( ( false !== $task_activated ) && ( 0 < $task_activated ) ) {
                    $tasks_activated_total += $task_activated;
                }
            }

            if ( 0 < $tasks_activated_total ) {
                $query_args['wlea-activated'] = $tasks_activated_total;
            }
        } elseif ( 'deactivate' === $action || 'deactivate' === $action2 ) {
            $tasks_deactivated_total = 0;

            foreach ( $task_ids as $task_id ) {
                $data = array(
                    'active' => false,
                );

                $where = array(
                    'ID' => $task_id,
                );

                $format = array( '%d' );

                $where_format = array( '%d' );

                $task_deactivated = $wpdb->update( $scheduled_tasks_table, $data, $where, $format, $where_format );

                if ( ( false !== $task_deactivated ) && ( 0 < $task_deactivated ) ) {
                    $tasks_deactivated_total += $task_deactivated;
                }
            }

            if ( 0 < $tasks_deactivated_total ) {
                $query_args['wlea-deactivated'] = $tasks_deactivated_total;
            }
        } elseif ( 'delete' === $action || 'delete' === $action2 ) {
            $tasks_deleted_total = 0;

            foreach ( $task_ids as $task_id ) {
                $where = array(
                    'ID' => $task_id,
                );

                $where_format = array( '%d' );

                $task_deleted = $wpdb->delete( $scheduled_tasks_table, $where, $where_format );

                if ( ( false !== $task_deleted ) && ( 0 < $task_deleted ) ) {
                    $tasks_deleted_total += $task_deleted;
                }
            }

            if ( 0 < $tasks_deleted_total ) {
                $query_args['wlea-deleted'] = $tasks_deleted_total;
            }
        }

        $redirect_url = add_query_arg( $query_args );

        if ( ! empty( $redirect_url ) ) {
            wp_redirect( $redirect_url );
            exit();
        }
    }

    /**
     * Process redirection.
     */
    public function process_redirection() {
        $query_args = array(
            '_wpnonce' => false,
        );

        if ( isset( $_GET['paged'] ) && ( 2 > wlea_cast( $_GET['paged'], 'absint' ) ) ) {
            $query_args['paged'] = false;
        }

        $redirect_url = add_query_arg( $query_args );

        if ( ! empty( $redirect_url ) ) {
            wp_redirect( $redirect_url );
            exit();
        }
    }

    /**
     * Get tasks count.
     */
    public function get_tasks_count( $args ) {
        global $wpdb;
        $scheduled_tasks_table = $wpdb->prefix . 'wlea_scheduled_tasks';

        $defaults = array(
            'limit'   => 20,
            'offset'  => 0,
            'event'   => '',
            'status'  => '',
            'search'  => '',
            'orderby' => '',
            'order'   => '',
        );

        $args = wp_parse_args( $args, $defaults );

        $event  = wlea_cast( $args['event'], 'key' );
        $status = wlea_cast( $args['status'], 'key' );

        $active = ( ( 'active' === $status ) ? '1' : '0' );

        $search = wlea_cast( $args['search'], 'text' );

        $orderby = wlea_cast( $args['orderby'], 'key' );
        $orderby = ( ! empty( $orderby ) ? $orderby : 'ID' );

        $order = wlea_cast( $args['order'], 'key' );
        $order = ( ! empty( $order ) ? $order : 'desc' );

        $order = strtoupper( $order );

        if ( ! empty( $event ) && ! empty( $status ) ) {
            $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$scheduled_tasks_table} WHERE event=%s AND active=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order}", $event, $active, '%' . $search . '%' ) );
        } elseif ( ! empty( $event ) ) {
            $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$scheduled_tasks_table} WHERE event=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order}", $event, '%' . $search . '%' ) );
        } elseif ( ! empty( $status ) ) {
            $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$scheduled_tasks_table} WHERE active=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order}", $active, '%' . $search . '%' ) );
        } else {
            $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$scheduled_tasks_table} WHERE CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order}", '%' . $search . '%' ) );
        }

        return $count;
    }

    /**
     * Get tasks data.
     */
    public function get_tasks_data( $args ) {
        global $wpdb;
        $scheduled_tasks_table = $wpdb->prefix . 'wlea_scheduled_tasks';

        $defaults = array(
            'limit'   => 20,
            'offset'  => 0,
            'event'   => '',
            'status'  => '',
            'search'  => '',
            'orderby' => '',
            'order'   => '',
        );

        $args = wp_parse_args( $args, $defaults );

        $limit  = absint( $args['limit'] );
        $offset = absint( $args['offset'] );

        $event  = wlea_cast( $args['event'], 'key' );
        $status = wlea_cast( $args['status'], 'key' );

        $active = ( ( 'active' === $status ) ? '1' : '0' );

        $search = wlea_cast( $args['search'], 'text' );

        $orderby = wlea_cast( $args['orderby'], 'key' );
        $orderby = ( ! empty( $orderby ) ? $orderby : 'ID' );

        $order = wlea_cast( $args['order'], 'key' );
        $order = ( ! empty( $order ) ? $order : 'desc' );

        $order = strtoupper( $order );

        if ( ! empty( $event ) && ! empty( $status ) ) {
            $tasks = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$scheduled_tasks_table} WHERE event=%s AND active=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order} LIMIT {$offset}, {$limit}", $event, $active, '%' . $search . '%' ), ARRAY_A );
        } elseif ( ! empty( $event ) ) {
            $tasks = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$scheduled_tasks_table} WHERE event=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order} LIMIT {$offset}, {$limit}", $event, '%' . $search . '%' ), ARRAY_A );
        } elseif ( ! empty( $status ) ) {
            $tasks = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$scheduled_tasks_table} WHERE active=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order} LIMIT {$offset}, {$limit}", $active, '%' . $search . '%' ), ARRAY_A );
        } else {
            $tasks = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$scheduled_tasks_table} WHERE CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order} LIMIT {$offset}, {$limit}", '%' . $search . '%' ), ARRAY_A );
        }

        return is_array( $tasks ) ? $tasks : array();
    }

    /**
     * Prepare tasks items.
     */
    public function prepare_tasks_items() {
        $column   = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $column, $hidden, $sortable );

        $per_page     = 20;
        $current_page = max( 1, $this->get_pagenum() );
        $offset       = max( 0, ( ( $current_page - 1 ) * $per_page ) );

        $args = array(
            'limit'  => $per_page,
            'offset' => $offset,
        );

        $event   = ( isset( $_GET['event'] ) ? wlea_cast( $_GET['event'], 'key' ) : '' );
        $status  = ( isset( $_GET['status'] ) ? wlea_cast( $_GET['status'], 'key' ) : '' );
        $search  = ( isset( $_GET['s'] ) ? wlea_cast( $_GET['s'], 'text' ) : '' );
        $orderby = ( isset( $_GET['orderby'] ) ? wlea_cast( $_GET['orderby'], 'key' ) : '' );
        $order   = ( isset( $_GET['order'] ) ? wlea_cast( $_GET['order'], 'key' ) : '' );

        if ( 'task' === $orderby ) {
            $orderby = 'ID';
        } elseif ( 'schedule' === $orderby ) {
            $orderby = 'schedule_date_gmt';
        } elseif ( 'added' === $orderby ) {
            $orderby = 'added_date_gmt';
        } elseif ( 'status' === $orderby ) {
            $orderby = 'active';
            $order   = ( ( 'asc' === $order ) ? 'desc' : 'asc' );
        }

        $args['event']   = $event;
        $args['status']  = $status;
        $args['search']  = $search;
        $args['orderby'] = $orderby;
        $args['order']   = $order;

        $items = $this->get_tasks_data( $args );
        $items_count = $this->get_tasks_count( $args );

        $this->items = $items;
        $this->items_count = $items_count;

        $this->set_pagination_args( array(
            'total_items' => $items_count,
            'per_page'    => $per_page,
        ) );
    }

}