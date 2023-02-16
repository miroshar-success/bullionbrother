<?php
/**
 * Logs.
 */

namespace WLEA\Admin\Table;

/**
 * Class
 */
class Logs extends \WP_List_Table {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct( array(
            'plural'   => 'logs',
            'singular' => 'log',
            'ajax'     => false
        ) );
    }

    /**
     * No items message.
     */
    public function no_items() {
        esc_html_e( 'No logs found!', 'woolentor-pro' );
    }

    /**
     * Get columns.
     */
    public function get_columns() {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'log'       => esc_html__( 'Log', 'woolentor-pro' ),
            'recipient' => esc_html__( 'Recipient', 'woolentor-pro' ),
            'event'     => esc_html__( 'Event', 'woolentor-pro' ),
            'template'  => esc_html__( 'Template', 'woolentor-pro' ),
            'schedule'  => esc_html__( 'Scheduled', 'woolentor-pro' ),
            'status'    => esc_html__( 'Status', 'woolentor-pro' ),
            'perform'   => esc_html__( 'Performed', 'woolentor-pro' ),
        );

        return $columns;
    }

    /**
     * Get sortable columns.
     */
    public function get_sortable_columns() {
        $columns = array(
            'log'       => array( 'log', 'asc' ),
            'recipient' => array( 'recipient', 'asc' ),
            'event'     => array( 'event', 'asc' ),
            'template'  => array( 'template', 'asc' ),
            'schedule'  => array( 'schedule', 'asc' ),
            'status'    => array( 'status', 'asc' ),
            'perform'   => array( 'perform', 'asc' ),
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
            ''        => esc_html__( 'All status', 'woolentor-pro' ),
            'success' => esc_html__( 'Success', 'woolentor-pro' ),
            'failed'  => esc_html__( 'Failed', 'woolentor-pro' ),
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
        do_action( 'wlea_manage_logs_extra_tablenav', $which );
    }

    /**
     * Get bulk actions.
     */
    public function get_bulk_actions() {
        $bulk_actions = array(
            'mark-success' => esc_html__( 'Mark Success', 'woolentor-pro' ),
            'mark-failed' => esc_html__( 'Mark Failed', 'woolentor-pro' ),
            'delete' => esc_html__( 'Delete', 'woolentor-pro' ),
        );

        return $bulk_actions;
    }

    /**
     * Column cb.
     */
    public function column_cb( $log ) {
        $ID = isset( $log['ID'] ) ? absint( $log['ID'] ) : 0;

        if ( ! empty( $ID ) ) {
            return '<input type="checkbox" name="log_ids[]" value="' . esc_attr( $ID ) . '" />';
        }
    }

    /**
     * Column default.
     */
    public function column_default( $log, $column ) {
        $output = '&mdash;';

        if ( ! is_array( $log ) || empty( $log ) ) {
            return $output;
        }

        $id = ( isset( $log['ID'] ) ? wlea_cast( $log['ID'], 'absint' ) : 0 );
        $event = ( isset( $log['event'] ) ? wlea_cast( $log['event'], 'key' ) : '' );
        $recipient = ( isset( $log['recipient'] ) ? wlea_cast( $log['recipient'], 'email' ) : '' );
        $template = ( isset( $log['template'] ) ? wlea_cast( $log['template'], 'absint' ) : 0 );
        $wait_for = ( isset( $log['wait_for'] ) ? wlea_cast( $log['wait_for'], 'absint' ) : 0 );
        $elements = ( ( isset( $log['elements'] ) && is_serialized( $log['elements'] ) ) ? wlea_cast( unserialize( $log['elements'] ), 'array', false ) : array() );
        $success = ( isset( $log['success'] ) ? wlea_cast( $log['success'], 'bool' ) : false );
        $schedule_date_gmt = ( isset( $log['schedule_date_gmt'] ) ? wlea_cast( $log['schedule_date_gmt'], 'text' ) : '' );
        $perform_date_gmt = ( isset( $log['perform_date_gmt'] ) ? wlea_cast( $log['perform_date_gmt'], 'text' ) : '' );
        $added_date_gmt = ( isset( $log['added_date_gmt'] ) ? wlea_cast( $log['added_date_gmt'], 'text' ) : '' );

        switch ( $column ) {
            case 'log':
                $output = $this->default_log( $id, $elements, $recipient, $success );
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

            case 'perform':
                $output = $this->default_perform( $perform_date_gmt );
                break;

            case 'status':
                $output = $this->default_status( $success );
                break;

            case 'added':
                $output = $this->default_added( $added_date_gmt );
                break;
        }

        return $output;
    }

    /**
     * Default log.
     */
    public function default_log( $id = 0, $elements = array(), $recipient = '', $success = false ) {
        $output = '&mdash;';

        $log = '';
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
            $log = sprintf( esc_html__( '#%1$s &mdash; %2$s', 'woolentor-pro' ), $id, $name );
        } else {
            $log = sprintf( esc_html__( '#%1$s', 'woolentor-pro' ), $id );
        }

        if ( ! empty( $log ) ) {
            if ( ! empty( $recipient ) ) {
                $output = sprintf( '<strong><a class="row-title" href="mailto:%1$s">%2$s</a></strong>', $recipient, $log );
            } else {
                $output = sprintf( '<strong><span class="row-title">%1$s</span></strong>', $log );
            }
        }

        $mark_success_link = '';
        $mark_failed_link = '';

        $query_args = array(
            'log-id' => $id,
            'wlea-activated' => false,
            'wlea-deactivated' => false,
            'wlea-deleted' => false,
            'wlea-fake-query-arg' => false,
            'action2' => false,
        );

        if ( false === $success ) {
            $mark_success_link = esc_url( wp_nonce_url( add_query_arg( array_merge( $query_args, array( 'action' => 'mark-success' ) ) ), 'mark-success-log', '_wpnonce' ) );
        } else {
            $mark_failed_link = esc_url( wp_nonce_url( add_query_arg( array_merge( $query_args, array( 'action' => 'mark-failed' ) ) ), 'mark-failed-log', '_wpnonce' ) );
        }

        $delete_link = esc_url( wp_nonce_url( add_query_arg( array_merge( $query_args, array( 'action' => 'delete' ) ) ), 'delete-log', '_wpnonce' ) );

        $row_actions = array();

        if ( ! empty( $mark_success_link ) ) {
            $row_actions['mark-success'] = '<a href="' . esc_url( $mark_success_link ) . '">' . esc_html__( 'Mark Success', 'woolentor-pro' ) . '</a>';
        } else {
            $row_actions['mark-failed'] = '<a href="' . esc_url( $mark_failed_link ) . '">' . esc_html__( 'Mark Failed', 'woolentor-pro' ) . '</a>';
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

            $output = sprintf( esc_html__( '%1$s at %2$s', 'woolentor-pro' ), date_i18n( 'Y/m/d', $local_timestamp ), date_i18n( 'g:i a', $local_timestamp ) );
        }

        return $output;
    }

    /**
     * Default status.
     */
    public function default_status( $status = false ) {
        $output = '&mdash;';

        if ( true === $status ) {
            $output = '<span class="wlea-list-table-status wlea-list-table-status-success"><span class="dashicons dashicons-yes-alt"></span><span>' . esc_html__( 'Success', 'woolentor-pro' ) . '</span></span>';
        } else {
            $output = '<span class="wlea-list-table-status wlea-list-table-status-failed"><span class="dashicons dashicons-dismiss"></span><span>' . esc_html__( 'Failed', 'woolentor-pro' ) . '</span></span>';
        }

        return $output;
    }

    /**
     * Default perform.
     */
    public function default_perform( $gmt_date = '' ) {
        $output = '&mdash;';

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
                $bulk_logs_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'bulk-logs' );

                if ( false !== $bulk_logs_nonce ) {
                    $this->process_bulk_actions();
                }
            } elseif ( isset( $_GET['action'] ) && isset( $_GET['log-id'] ) ) {
                $mark_success_log_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'mark-success-log' );
                $mark_failed_log_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'mark-failed-log' );
                $delete_log_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'delete-log' );

                if ( ( false !== $mark_success_log_nonce ) || ( false !== $mark_failed_log_nonce ) || ( false !== $delete_log_nonce ) ) {
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
        $log_id = isset( $_GET['log-id'] ) ? wlea_cast( $_GET['log-id'], 'absint' ) : 0;

        if ( empty( $action ) || empty( $log_id ) ) {
            return;
        }

        global $wpdb;
        $performed_tasks_table = $wpdb->prefix . 'wlea_performed_tasks';

        $query_args = array(
            'action' => false,
            'log-id' => false,
            '_wpnonce' => false,
        );

        if ( isset( $_GET['paged'] ) && ( 2 > wlea_cast( $_GET['paged'], 'absint' ) ) ) {
            $query_args['paged'] = false;
        }

        if ( 'mark-success' === $action ) {
            $data = array(
                'success' => true,
            );

            $where = array(
                'ID' => $log_id,
            );

            $format = array( '%d' );

            $where_format = array( '%d' );

            $log_marked_success = $wpdb->update( $performed_tasks_table, $data, $where, $format, $where_format );

            if ( 0 < $log_marked_success ) {
                $query_args['wlea-marked-success'] = $log_marked_success;
            }
        } elseif ( 'mark-failed' === $action ) {
            $data = array(
                'success' => false,
            );

            $where = array(
                'ID' => $log_id,
            );

            $format = array( '%d' );

            $where_format = array( '%d' );

            $log_marked_failed = $wpdb->update( $performed_tasks_table, $data, $where, $format, $where_format );

            if ( 0 < $log_marked_failed ) {
                $query_args['wlea-marked-failed'] = $log_marked_failed;
            }
        } elseif ( 'delete' === $action ) {
            $data = array(
                'ID' => $log_id,
            );

            $format = array( '%d' );

            $log_deleted = $wpdb->delete( $performed_tasks_table, $data, $format );

            if ( 0 < $log_deleted ) {
                $query_args['wlea-deleted'] = $log_deleted;
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
        $performed_tasks_table = $wpdb->prefix . 'wlea_performed_tasks';

        $query_args = array(
            'action' => false,
            'action2' => false,
            'log_ids' => false,
            '_wpnonce' => false,
        );

        if ( isset( $_GET['paged'] ) && ( 2 > wlea_cast( $_GET['paged'], 'absint' ) ) ) {
            $query_args['paged'] = false;
        }

        $log_ids = isset( $_GET['log_ids'] ) ? wlea_cast( $_GET['log_ids'], 'array' ) : array();
        $log_ids = wlea_clean_array_of_id( $log_ids );

        if ( 'mark-success' === $action || 'mark-success' === $action2 ) {
            $logs_marked_success_total = 0;

            foreach ( $log_ids as $log_id ) {
                $data = array(
                    'success' => true,
                );

                $where = array(
                    'ID' => $log_id,
                );

                $format = array( '%d' );

                $where_format = array( '%d' );

                $log_marked_success = $wpdb->update( $performed_tasks_table, $data, $where, $format, $where_format );

                if ( ( false !== $log_marked_success ) && ( 0 < $log_marked_success ) ) {
                    $logs_marked_success_total += $log_marked_success;
                }
            }

            if ( 0 < $logs_marked_success_total ) {
                $query_args['wlea-marked-success'] = $logs_marked_success_total;
            }
        } elseif ( 'mark-failed' === $action || 'mark-failed' === $action2 ) {
            $logs_marked_failed_total = 0;

            foreach ( $log_ids as $log_id ) {
                $data = array(
                    'success' => false,
                );

                $where = array(
                    'ID' => $log_id,
                );

                $format = array( '%d' );

                $where_format = array( '%d' );

                $log_marked_failed = $wpdb->update( $performed_tasks_table, $data, $where, $format, $where_format );

                if ( ( false !== $log_marked_failed ) && ( 0 < $log_marked_failed ) ) {
                    $logs_marked_failed_total += $log_marked_failed;
                }
            }

            if ( 0 < $logs_marked_failed_total ) {
                $query_args['wlea-marked-failed'] = $logs_marked_failed_total;
            }
        } elseif ( 'delete' === $action || 'delete' === $action2 ) {
            $logs_deleted_total = 0;

            foreach ( $log_ids as $log_id ) {
                $where = array(
                    'ID' => $log_id,
                );

                $where_format = array( '%d' );

                $log_deleted = $wpdb->delete( $performed_tasks_table, $where, $where_format );

                if ( ( false !== $log_deleted ) && ( 0 < $log_deleted ) ) {
                    $logs_deleted_total += $log_deleted;
                }
            }

            if ( 0 < $logs_deleted_total ) {
                $query_args['wlea-deleted'] = $logs_deleted_total;
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
     * Get logs count.
     */
    public function get_logs_count( $args ) {
        global $wpdb;
        $performed_tasks_table = $wpdb->prefix . 'wlea_performed_tasks';

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

        $success = ( ( 'success' === $status ) ? '1' : '0' );

        $search = wlea_cast( $args['search'], 'text' );

        $orderby = wlea_cast( $args['orderby'], 'key' );
        $orderby = ( ! empty( $orderby ) ? $orderby : 'ID' );

        $order = wlea_cast( $args['order'], 'key' );
        $order = ( ! empty( $order ) ? $order : 'desc' );

        $order = strtoupper( $order );

        if ( ! empty( $event ) && ! empty( $status ) ) {
            $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$performed_tasks_table} WHERE event=%s AND success=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order}", $event, $success, '%' . $search . '%' ) );
        } elseif ( ! empty( $event ) ) {
            $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$performed_tasks_table} WHERE event=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order}", $event, '%' . $search . '%' ) );
        } elseif ( ! empty( $status ) ) {
            $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$performed_tasks_table} WHERE success=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order}", $success, '%' . $search . '%' ) );
        } else {
            $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$performed_tasks_table} WHERE CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order}", '%' . $search . '%' ) );
        }

        return $count;
    }

    /**
     * Get logs data.
     */
    public function get_logs_data( $args ) {
        global $wpdb;
        $performed_tasks_table = $wpdb->prefix . 'wlea_performed_tasks';

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

        $success = ( ( 'success' === $status ) ? '1' : '0' );

        $search = wlea_cast( $args['search'], 'text' );

        $orderby = wlea_cast( $args['orderby'], 'key' );
        $orderby = ( ! empty( $orderby ) ? $orderby : 'ID' );

        $order = wlea_cast( $args['order'], 'key' );
        $order = ( ! empty( $order ) ? $order : 'desc' );

        $order = strtoupper( $order );

        if ( ! empty( $event ) && ! empty( $status ) ) {
            $logs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$performed_tasks_table} WHERE event=%s AND success=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order} LIMIT {$offset}, {$limit}", $event, $success, '%' . $search . '%' ), ARRAY_A );
        } elseif ( ! empty( $event ) ) {
            $logs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$performed_tasks_table} WHERE event=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order} LIMIT {$offset}, {$limit}", $event, '%' . $search . '%' ), ARRAY_A );
        } elseif ( ! empty( $status ) ) {
            $logs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$performed_tasks_table} WHERE success=%s AND CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order} LIMIT {$offset}, {$limit}", $success, '%' . $search . '%' ), ARRAY_A );
        } else {
            $logs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$performed_tasks_table} WHERE CONCAT( action, recipient, template, elements ) LIKE %s ORDER BY {$orderby} {$order} LIMIT {$offset}, {$limit}", '%' . $search . '%' ), ARRAY_A );
        }

        return is_array( $logs ) ? $logs : array();
    }

    /**
     * Prepare logs items.
     */
    public function prepare_logs_items() {
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

        if ( 'log' === $orderby ) {
            $orderby = 'ID';
        } elseif ( 'schedule' === $orderby ) {
            $orderby = 'schedule_date_gmt';
        } elseif ( 'added' === $orderby ) {
            $orderby = 'added_date_gmt';
        } elseif ( 'status' === $orderby ) {
            $orderby = 'success';
            $order   = ( ( 'asc' === $order ) ? 'desc' : 'asc' );
        }

        $args['event']   = $event;
        $args['status']  = $status;
        $args['search']  = $search;
        $args['orderby'] = $orderby;
        $args['order']   = $order;

        $items = $this->get_logs_data( $args );
        $items_count = $this->get_logs_count( $args );

        $this->items = $items;
        $this->items_count = $items_count;

        $this->set_pagination_args( array(
            'total_items' => $items_count,
            'per_page'    => $per_page,
        ) );
    }

}