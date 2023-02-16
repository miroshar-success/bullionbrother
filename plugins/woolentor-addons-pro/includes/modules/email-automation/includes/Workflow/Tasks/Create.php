<?php
/**
 * Create.
 */

namespace WLEA\Workflow\Tasks;

/**
 * Class.
 */
class Create {

	/**
     * Event.
     */
    protected $event;

	/**
     * Args.
     */
    protected $args;

	/**
     * Recipient.
     */
    protected $recipient;

	/**
     * Rules.
     */
    protected $rules;

	/**
     * Constructor.
     */
    public function __construct( $event = '', $args = '' ) {
        $event = wlea_cast( $event, 'key' );
        $args = wlea_cast( $args, 'array' );

        $recipient = ( isset( $args['recipient'] ) ? wlea_cast( $args['recipient'], 'email' ) : '' );

        if ( empty( $event ) || empty( $recipient ) ) {
            return;
        }

        $this->event = $event;
        $this->args = $args;
        $this->recipient = $recipient;

        $this->process();
    }

    /**
     * Process.
     */
    protected function process() {
        global $wpdb;

        $workflow_events_table = $wpdb->prefix . 'wlea_workflow_events';

        $workflow_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT workflow_id FROM ' . $workflow_events_table . ' WHERE event=%s AND active=%d', $this->event, true ) );

        if ( is_array( $workflow_ids ) && ! empty( $workflow_ids ) ) {
            foreach ( $workflow_ids as $workflow_id ) {
                $this->rules( $workflow_id );

                if ( false === $this->rules ) {
                    continue;
                }

                $this->actions( $workflow_id );
            }
        }
    }

    /**
     * Rules.
     */
    protected function rules( $workflow_id = 0 ) {
        $rules = get_post_meta( $workflow_id, '_wlea_workflow_rules', true );
        $rules = wlea_cast( $rules, 'array' );
        $rules = ( isset( $rules['rules'] ) ? wlea_cast( $rules['rules'], 'array' ) : array() );

        $rules = \WLEA\Workflow\Tasks\Rules::verify_rules( $rules, $this->event, $this->args );

        $this->rules = $rules;
    }

    /**
     * Actions.
     */
    protected function actions( $workflow_id = 0 ) {
        $actions = get_post_meta( $workflow_id, '_wlea_workflow_actions', true );
        $actions = wlea_cast( $actions, 'array' );
        $actions = ( isset( $actions['actions'] ) ? wlea_cast( $actions['actions'], 'array' ) : array() );

        if ( empty( $actions ) ) {
            return;
        }

        foreach ( $actions as $action ) {
            $action = wlea_cast( $action, 'array', false );
            $action = array_merge( $action, array(
                'email_recipient' => $this->recipient,
            ) );

            $this->store_task( $action );
        }
    }

    /**
     * Store task.
     */
    protected function store_task( $action ) {
        global $wpdb;

        $scheduled_tasks_table = $wpdb->prefix . 'wlea_scheduled_tasks';

        $event = $this->event;
        $args  = $this->args;

        $order_id    = ( isset( $args['order_id'] ) ? wlea_cast( $args['order_id'], 'absint' ) : 0 );
        $customer_id = ( isset( $args['customer_id'] ) ? wlea_cast( $args['customer_id'], 'absint' ) : 0 );

        $recipient = ( isset( $action['email_recipient'] ) ? wlea_cast( $action['email_recipient'], 'email' ) : '' );
        $subject   = ( isset( $action['email_subject'] ) ? wlea_cast( $action['email_subject'], 'text' ) : '' );
        $preheader = ( isset( $action['email_preheader'] ) ? wlea_cast( $action['email_preheader'], 'text' ) : '' );
        $template  = ( isset( $action['email_template'] ) ? wlea_cast( $action['email_template'], 'text' ) : '' );
        $schedule  = ( isset( $action['schedule'] ) ? wlea_cast( $action['schedule'], 'array' ) : array() );

        $wait_for = wlea_schedule_in_seconds( $schedule );

        $elements = array(
            'event'     => $event,
            'recipient' => $recipient,
            'subject'   => $subject,
            'preheader' => $preheader,
            'template'  => $template,
            'schedule'  => $schedule,
            'meta'      => array(),
        );

        if ( ! empty( $event ) ) {
            $events = wlea_get_trigger_events();

            if ( isset( $events[ $event ] ) ) {
                $event_label = $events[ $event ];

                $elements['event_label'] = $event_label;
            }
        }

        if ( ! empty( $order_id ) ) {
            $order = wc_get_order( $order_id );
            $recipient_name = ( is_object( $order ) ? $order->get_formatted_billing_full_name() : '' );

            $elements['recipient_name'] = $recipient_name;
            $elements['meta']['order_id'] = $order_id;
        } elseif ( ! empty( $customer_id ) ) {
            $customer = wlea_get_customer_by_id( $customer_id );
            $recipient_name = ( is_object( $customer ) ? $customer->get_display_name() : '' );

            $elements['recipient_name'] = $recipient_name;
            $elements['meta']['customer_id'] = $customer_id;
        }

        $elements = serialize( $elements );

        $current_time = current_time( 'mysql' );
        $current_time_gmt = current_time( 'mysql', true );

        $current_timestamp = current_time( 'timestamp' );
        $current_timestamp_gmt = current_time( 'timestamp', true );

        $schedule_time = gmdate( 'Y-m-d H:i:s', ( $current_timestamp + $wait_for ) );
        $schedule_time_gmt = gmdate( 'Y-m-d H:i:s', ( $current_timestamp_gmt + $wait_for ) );

        $active = true;
        $tried = 0;

        $data = array(
            'action'            => 'send_email',
            'event'             => $event,
            'recipient'         => $recipient,
            'template'          => $template,
            'wait_for'          => $wait_for,
            'elements'          => $elements,
            'schedule_date'     => $schedule_time,
            'schedule_date_gmt' => $schedule_time_gmt,
            'active'            => $active,
            'tried'             => $tried,
            'modified_date'     => $current_time,
            'modified_date_gmt' => $current_time_gmt,
            'added_date'        => $current_time,
            'added_date_gmt'    => $current_time_gmt,
        );

        $format = array( '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s' );

        $wpdb->insert( $scheduled_tasks_table, $data, $format );
    }

}