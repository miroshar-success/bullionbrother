<?php
/**
 * Workflows.
 */

namespace WLEA\Admin\Meta_Boxes;

/**
 * Class.
 */
class Workflows {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->trigger_meta_box();
        $this->rules_meta_box();
        $this->actions_meta_box();
    }

    /**
     * Trigger meta box.
     */
    public function trigger_meta_box() {
        $args = array(
            'id'       => '_wlea_workflow_trigger',
            'title'    => esc_html__( 'Trigger', 'woolentor-pro' ),
            'screen'   => 'wlea-workflow',
            'context'  => 'advanced',
            'priority' => 'default',
            'fields'   => array(

                array(
                    'id'      => 'event',
                    'type'    => 'select',
                    'title'   => esc_html__( 'Event', 'woolentor-pro' ),
                    'desc'    => esc_html__( 'Select event to trigger workflow.', 'woolentor-pro' ),
                    'options' => wlea_get_trigger_events(),
                ),

            ),
        );

        \WLOPTF\Meta_Boxes::instance( $args );
    }

    /**
     * Rules meta box.
     */
    public function rules_meta_box() {
        $args = array(
            'id'       => '_wlea_workflow_rules',
            'title'    => esc_html__( 'Rules', 'woolentor-pro' ),
            'screen'   => 'wlea-workflow',
            'context'  => 'advanced',
            'priority' => 'default',
            'fields'   => array(

                array(
                    'id'       => 'rules',
                    'type'     => 'rules',
                    'settings' => array(
                        'wc_order_created'                => $this->order_rules_opt(),
                        'wc_order_paid'                   => $this->order_rules_opt(),
                        'wc_order_pending'                => $this->order_rules_opt(),
                        'wc_order_processing'             => $this->order_rules_opt(),
                        'wc_order_on_hold'                => $this->order_rules_opt(),
                        'wc_order_completed'              => $this->order_rules_opt(),
                        'wc_order_cancelled'              => $this->order_rules_opt(),
                        'wc_order_refunded'               => $this->order_rules_opt(),
                        'wc_order_failed'                 => $this->order_rules_opt(),
                        'wc_order_checkout_draft'         => $this->order_rules_opt(),
                        'wc_order_note_added'             => $this->order_rules_opt(),
                        'wc_customer_account_created'     => $this->customer_account_created_rules_opt(),
                        'wc_customer_total_spend_reaches' => $this->customer_total_spend_rules_opt(),
                        'wc_customer_order_count_reaches' => $this->customer_order_count_rules_opt(),
                    ),
                    'control'  => array(
                        'name'  => '_wlea_workflow_trigger[event]',
                        'event' => 'change',
                        'value' => $this->trigger_event(),
                    ),
                ),

            ),
        );

        \WLOPTF\Meta_Boxes::instance( $args );
    }

    /**
     * Actions meta box.
     */
    public function actions_meta_box() {
        $args = array(
            'id'       => '_wlea_workflow_actions',
            'title'    => esc_html__( 'Actions', 'woolentor-pro' ),
            'screen'   => 'wlea-workflow',
            'context'  => 'advanced',
            'priority' => 'default',
            'fields'   => array(

                array(
                    'id'    => 'actions',
                    'type'  => 'group',
                    'fields' => array(

                        array(
                            'id'    => 'title',
                            'type'  => 'text',
                            'title' => esc_html__( 'Title (optional)', 'woolentor-pro' ),
                        ),

                        array(
                            'id'    => 'email_subject',
                            'type'  => 'text',
                            'title' => esc_html__( 'Email Subject', 'woolentor-pro' ),
                        ),

                        array(
                            'id'    => 'email_preheader',
                            'type'  => 'text',
                            'title' => esc_html__( 'Email Preheader (optional)', 'woolentor-pro' ),
                        ),

                        array(
                            'id'          => 'email_template',
                            'type'        => 'select',
                            'title'       => esc_html__( 'Email Template', 'woolentor-pro' ),
                            'placeholder' => esc_html__( 'Choose Email Template', 'woolentor-pro' ),
                            'ajax'        => true,
                            'multiple'    => false,
                            'query_type'  => 'post',
                            'query_args'  => array(
                                'post_type' => 'wlea-email',
                            ),
                        ),

                        array(
                            'id'    => 'schedule',
                            'type'  => 'schedule',
                            'title' => esc_html__( 'Send Email After', 'woolentor-pro' ),
                        ),

                    ),
                ),

            ),
        );

        \WLOPTF\Meta_Boxes::instance( $args );
    }

	/**
	 * Get post ID.
	 */
	protected function get_post_id() {
		if ( isset( $_GET ) && is_array( $_GET ) && isset( $_GET['post'] ) && ! empty( $_GET['post'] ) ) {
			$post_id = absint( $_GET['post'] );
		} else {
			$post_id = 0;
		}

		return $post_id;
	}

    /**
     * Trigger event.
     */
    protected function trigger_event() {
        $event = '';

        $post_id = $this->get_post_id();

        if ( ! empty( $post_id ) && ( 'wlea-workflow' === get_post_type( $post_id ) ) ) {
            $event = get_post_meta( $post_id, '_wlea_workflow_trigger', true );
            $event = ( ( is_array( $event ) && isset( $event['event'] ) ) ? sanitize_key( $event['event'] ) : '' );
        }

        return ( ! empty( $event ) ? $event : 'wc_order_created' );
    }

    /**
     * Order rules options.
     */
    protected function order_rules_opt() {
        $opt = array(
            'products' => array(
                'title'    => esc_html__( 'Products', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'in'     => esc_html__( 'IN', 'woolentor-pro' ),
                        'not_in' => esc_html__( 'NOT IN', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type'        => 'select',
                    'placeholder' => esc_html__( 'Choose Products', 'woolentor-pro' ),
                    'ajax'        => true,
                    'multiple'    => true,
                    'query_type'  => 'post',
                    'query_args'  => array(
                        'post_type' => 'product',
                    ),
                ),
            ),
            'customer_type' => array(
                'title'    => esc_html__( 'Customer Type', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'equal'     => esc_html__( 'Equal', 'woolentor-pro' ),
                        'not_equal' => esc_html__( 'Not Equal', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type'    => 'select',
                    'options' => array(
                        'guest'      => esc_html__( 'Guest', 'woolentor-pro' ),
                        'registered' => esc_html__( 'Registered', 'woolentor-pro' ),
                    ),
                ),
            ),
            'order_spend' => array(
                'title'    => esc_html__( 'Order Spend', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'equal'                 => esc_html__( 'Equal', 'woolentor-pro' ),
                        'less_than'             => esc_html__( 'Less Than', 'woolentor-pro' ),
                        'greater_than'          => esc_html__( 'Greater Than', 'woolentor-pro' ),
                        'equal_or_less_than'    => esc_html__( 'Equal or Less Than', 'woolentor-pro' ),
                        'equal_or_greater_than' => esc_html__( 'Equal or Greater Than', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type' => 'number',
                ),
            ),
            'product_count' => array(
                'title'    => esc_html__( 'Product Count', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'equal'                 => esc_html__( 'Equal', 'woolentor-pro' ),
                        'less_than'             => esc_html__( 'Less Than', 'woolentor-pro' ),
                        'greater_than'          => esc_html__( 'Greater Than', 'woolentor-pro' ),
                        'equal_or_less_than'    => esc_html__( 'Equal or Less Than', 'woolentor-pro' ),
                        'equal_or_greater_than' => esc_html__( 'Equal or Greater Than', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type' => 'number',
                ),
            ),
            'customer_country' => $this->customer_country_opt(),
            'trigger_date' => $this->trigger_date_opt(),
        );

        return $opt;
    }

    /**
     * Customer account created rules options.
     */
    protected function customer_account_created_rules_opt() {
        $opt = array(
            'customer_country' => $this->customer_country_opt(),
            'trigger_date' => $this->trigger_date_opt(),
        );

        return $opt;
    }

    /**
     * Customer total spend rules options.
     */
    protected function customer_total_spend_rules_opt() {
        $opt = array(
            'total_spend' => array(
                'title'    => esc_html__( 'Total Spend', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'equal'                 => esc_html__( 'Equal', 'woolentor-pro' ),
                        'less_than'             => esc_html__( 'Less Than', 'woolentor-pro' ),
                        'greater_than'          => esc_html__( 'Greater Than', 'woolentor-pro' ),
                        'equal_or_less_than'    => esc_html__( 'Equal or Less Than', 'woolentor-pro' ),
                        'equal_or_greater_than' => esc_html__( 'Equal or Greater Than', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type' => 'number',
                ),
            ),
            'customer_country' => $this->customer_country_opt(),
            'trigger_date' => $this->trigger_date_opt(),
        );

        return $opt;
    }

    /**
     * Customer order count rules options.
     */
    protected function customer_order_count_rules_opt() {
        $opt = array(
            'order_count' => array(
                'title'    => esc_html__( 'Order Count', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'equal'                 => esc_html__( 'Equal', 'woolentor-pro' ),
                        'less_than'             => esc_html__( 'Less Than', 'woolentor-pro' ),
                        'greater_than'          => esc_html__( 'Greater Than', 'woolentor-pro' ),
                        'equal_or_less_than'    => esc_html__( 'Equal or Less Than', 'woolentor-pro' ),
                        'equal_or_greater_than' => esc_html__( 'Equal or Greater Than', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type' => 'number',
                ),
            ),
            'customer_country' => $this->customer_country_opt(),
            'trigger_date' => $this->trigger_date_opt(),
        );

        return $opt;
    }

    /**
     * Customer country options.
     */
    protected function customer_country_opt() {
        $opt = array(
            'title'    => esc_html__( 'Customer Country', 'woolentor-pro' ),
            'operator' => array(
                'type'    => 'select',
                'options' => array(
                    'in'     => esc_html__( 'IN', 'woolentor-pro' ),
                    'not_in' => esc_html__( 'NOT IN', 'woolentor-pro' ),
                ),
            ),
            'value' => array(
                'type'        => 'select',
                'placeholder' => esc_html__( 'Choose Countries', 'woolentor-pro' ),
                'multiple'    => true,
                'options'     => wlea_get_countries(),
            ),
        );

        return $opt;
    }

    /**
     * Trigger date options.
     */
    protected function trigger_date_opt() {
        $opt = array(
            'title'    => esc_html__( 'Event Trigger (Date)', 'woolentor-pro' ),
            'operator' => array(
                'type'    => 'select',
                'options' => array(
                    'equal'           => esc_html__( 'Equal', 'woolentor-pro' ),
                    'before'          => esc_html__( 'Before', 'woolentor-pro' ),
                    'after'           => esc_html__( 'After', 'woolentor-pro' ),
                    'equal_or_before' => esc_html__( 'Equal or Before', 'woolentor-pro' ),
                    'equal_or_after'  => esc_html__( 'Equal or After', 'woolentor-pro' ),
                ),
            ),
            'value'    => array(
                'type'        => 'date',
                'placeholder' => esc_html__( 'YYYY-MM-DD', 'woolentor-pro' ),
            ),
        );

        return $opt;
    }

}