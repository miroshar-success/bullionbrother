<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Points List Page
 * 
 * The html markup for the Points list
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class woo_pr_log extends WP_List_Table {

    public $model, $woo_pr_points_model, $per_page;

    function __construct() {

        global $woo_pr_model;

        $this->model = $woo_pr_model;

        //Set parent defaults
        parent::__construct(array(
            'singular' => 'pointlog',
            'plural' => 'pointslog',
            'ajax' => false
        ));

        $this->per_page = apply_filters('woo_pr_posts_per_page', 20); // Per page
    }

    /**
     * Displaying Points
     * 
     * Does prepare the data for displaying the Points in the table.
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function woo_pr_display_points() {

        global $all_logs_user_ids;

        $all_logs_user_ids = $resultdata = $item = array();
        $date_format = get_option( 'date_format' );

        // Taking parameter
        $orderby    = isset($_GET['orderby']) ? urldecode($_GET['orderby']) : 'ID';
        $order      = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $search     = isset($_GET['s']) ? sanitize_text_field(trim($_GET['s'])) : null;
        $id_search  = isset($_GET['userid']) ? $_GET['userid'] : '';
        $event      = isset($_GET['woo_event_type']) ? $_GET['woo_event_type'] : '';
        $paged      = isset($_GET['paged']) ? $_GET['paged'] : null;
        $monthyear  = isset($_REQUEST['m']) ? $_REQUEST['m'] : '';

        $points_arr = array(
            'posts_per_page' => $this->per_page,
            'page'           => $paged,
            'orderby'        => $orderby,
            'order'          => $order,
            'offset'         => ( $this->get_pagenum() - 1 ) * $this->per_page,
            'woo_pr_list'    => true
        );


        if (isset($search) && !empty($search)) {
            //in case of search make parameter for retriving search data
            $points_arr['s'] = $search;
        }

        if (isset($monthyear) && !empty($monthyear)) {
            //in case of month search make parameter for retriving search data
            $points_arr['monthyear'] = $monthyear;
        }

        if (isset($id_search) && !empty($id_search)) {
            $points_arr['author'] = $id_search;
        }

        if (isset($_GET['woo_event_type']) && !empty($_GET['woo_event_type'])) {
            $points_arr['event'] = $_GET['woo_event_type'];
        }

        //call function to retrive data from table
        $data = $this->model->woo_pr_get_points($points_arr);

        if (!empty($data['data'])) {

            foreach ($data['data'] as $key => $value) {

                $customerid = $value['post_author'];
                $userdata   = get_user_by('id', $customerid);

                if ($userdata) {

                    $user_id            = $userdata->ID;
                    $item['user_id']    = $user_id;
                    $item['useremail']  = isset($userdata->user_email) ? $userdata->user_email : '';
                    $item['user_name']  = $userdata->display_name;
                } else {
                    $item['user_id']    = '';
                    $item['useremail']  = '';
                    $item['user_name']  = '';
                }

                $resultdata[$key]['customer']       = $this->column_user($item);
                $resultdata[$key]['points_id']      = $value['ID'];
                $resultdata[$key]['order_id']       = get_post_meta($value['ID'], '_woo_log_order_id', true);
                $resultdata[$key]['points']         = get_post_meta($value['ID'], '_woo_log_userpoint', true);
                $resultdata[$key]['event']          = get_post_meta($value['ID'], '_woo_log_events', true);
                $resultdata[$key]['date']           = $value['post_date_gmt'];
                $resultdata[$key]['post_content']   = $value['post_content'];
                $expiry_date = get_post_meta($value['ID'], WOO_PR_META_PREFIX.'expiry_date', true);
                $resultdata[$key]['expiry_date']    = (!empty($expiry_date)) ? date_i18n( $date_format, strtotime($expiry_date),false ) : '' ;
            }
        }

        $result_arr['data'] = !empty($resultdata) ? $resultdata : array();
        $result_arr['total'] = isset($data['total']) ? $data['total'] : ''; // Total no of data

        //call function to retrive data from table
        $all_users_log_arr = array(
            'posts_per_page' => -1,
            'woo_pr_list'    => true,
        );
        $all_users_log_data = $this->model->woo_pr_get_points($all_users_log_arr);

        // Get users id from post data
        if (!empty($all_users_log_data['data'])) {

            foreach ($all_users_log_data['data'] as $key => $value) {

                $customerid = $value['post_author'];
                $userdata = get_user_by('id', $customerid);
                if ($userdata) {

                    $user_id = $userdata->ID;
                    if( !in_array($user_id, $all_logs_user_ids) ){
                        $all_logs_user_ids[] = $user_id;
                    }
                }
            }
        }

        return $result_arr;
    }

    /**
     * User Column Data
     * 
     * Handles to show user column
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * */
    function column_user($item) {

        $display_name = $item['user_name'];

        $user_id = $item['user_id'];
        $user = isset($user_id) && !empty($user_id) ? $user_id : $item['useremail'];

        $userlink = add_query_arg(array('page' => 'woo-points-log', 'userid' => $user), admin_url('admin.php'));
        return '<a href="' . esc_url($userlink) . '">' . $display_name . '</a><br/>' . $item['useremail'];
    }

    /**
     * Manage column data
     * 
     * Default Column for listing table
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function column_default($item, $column_name) {
		if($item['event']  == 'redeemed_purchase'){
			$point_discounted_amount = $this->model->woo_pr_get_points_discount_amount_by_order( $item['order_id']);
		}
		else{
			$point_discounted_amount = $this->model->woo_pr_calculate_discount_amount( $item['points'] );
		}
						
		
        switch ($column_name) {
            case 'customer':
            case 'points':
                return $item[$column_name];
			case 'points_amount':
				return wc_price(woo_pr_wcm_currency_convert($point_discounted_amount));
			case 'date':
                return $this->model->woo_pr_log_time(strtotime($item['date']));
            case 'event' :
                if ($item[$column_name] == 'manual') {
                    $post_content = $item["post_content"];
                    $event_description = sprintf(esc_html__('%s Manual %s','woopoints'),'<p class="tooltip">','<span class="custom info">'.$post_content.'</span></p>');
                } else {
                    $event_description = $this->model->woo_pr_get_events($item[$column_name]);
                }
                $event_description .= $this->model->woo_pr_get_event_order_link($item, $item['order_id']);
                return $event_description;
            case 'expiry_date':
                $expiry_date = $item[$column_name];
                if( empty($expiry_date) ){
                    $log_operation = get_post_meta($item['points_id'], '_woo_log_operation', true);
                    $expiry_date = ($log_operation=='add') ? '<label title="'.esc_html__('Infinite', 'woopoints').'">&#8734;</label>' : $expiry_date ;
                }
                return $expiry_date;
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Add Filter for Sorting
     * 
     * Handles to add filter for sorting
     * in listing
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * */
    function extra_tablenav($which) {

        global $all_logs_user_ids,$wpdb, $wp_locale;

        if ($which == 'top') {

            echo '<div class="alignleft actions woo-pr-points-dropdown-wrapper">';

            $all_events = apply_filters( 'woo_pr_all_events_options', array(
                'earned_purchase'           => esc_html__('Order Placed', 'woopoints'),
                'earned_sell'               => esc_html__('Downloads Sell', 'woopoints'),
                'redeemed_purchase'         => esc_html__('Order Redeem', 'woopoints'),
                'refunded_purchase_debited' => esc_html__('Debit for Refund Order', 'woopoints'),
                'refunded_purchase_credited'=> esc_html__('Credit for Refund Order', 'woopoints'),
                'refunded_sell_debited'     => esc_html__('Debit Sell for Refund Order', 'woopoints'),
                'signup'                    => esc_html__('Account Signup', 'woopoints'),
                'post_creation'             => esc_html__('Blog Creation', 'woopoints'),
                'product_creation'          => esc_html__('Product Creation', 'woopoints'),
                'daily_login'               => esc_html__('Daily Login', 'woopoints'),
                'manual'                    => esc_html__('Manual', 'woopoints'),
                'reset_points'              => esc_html__('Reset Points', 'woopoints'),
                'earned_product_review'     => esc_html__('Product Review', 'woopoints'),
                'expiration'                => esc_html__('Expiration Points', 'woopoints'),
                'refunded_first_purchase_debited' => esc_html__('Debit for First Purchase Points', 'woopoints'),
                'earned_first_purchase'     => esc_html__('First Purchase Points', 'woopoints'),
            ) );
            $checked = '';
            ?>

            <select id="woo_pr_userid" name="userid">
                <option value=""><?php esc_html_e('Show all customer', 'woopoints'); ?></option><?php
                foreach ($all_logs_user_ids as $user_id_key => $user_id_value) {

                    $user_data = get_user_by('id', $user_id_value);
                    $selected = selected(isset($_GET['userid']) ? $_GET['userid'] : '', $user_id_value, false);
                    echo '<option value="' . $user_data->ID . '" ' . $selected . '>' . $user_data->display_name . ' (#' . $user_data->ID . ' &ndash; ' . sanitize_email($user_data->user_email) . ')' . '</option>';
                }
                ?>
            </select>
            <select id="woo_event_type" name="woo_event_type">
                <option value=""><?php esc_html_e('Show All Event Types', 'woopoints'); ?></option><?php
                foreach ($all_events as $event_key => $event_value) {
                    $selected = selected(isset($_GET['woo_event_type']) ? $_GET['woo_event_type'] : '', $event_key, false);
                    echo '<option value="' . $event_key . '" ' . $selected . '>' . $event_value . '</option>';
                }
                ?>
            </select><?php
            $this->months_dropdown(WOO_POINTS_LOG_POST_TYPE);

            $m = isset( $_GET['points_expiry_date'] ) ? $_GET['points_expiry_date'] : '';

            $months = $wpdb->get_results("SELECT DISTINCT YEAR(meta_value) AS year, MONTH(meta_value) AS month FROM $wpdb->postmeta pm, $wpdb->posts p WHERE meta_key  = '_woo_pr_expiry_date' and meta_value  != '' and pm.post_id=p.ID  and p.post_type='woopointslog' Order By p.post_date DESC ",ARRAY_A);

            ?>
                <select name="points_expiry_date">
                    <option<?php selected( $m, '' ); ?> value=""><?php esc_html_e( 'Expiry date', 'woopoints'); ?></option>
                    <?php
                    if( !empty( $months ) ){

                        foreach ( $months as $arc_row ) {
                            if ( '' == $arc_row['year'] ) {
                                continue;
                            }

                            $month = zeroise( $arc_row['month'], 2 );
                            $year  = $arc_row['year'];

                            printf(
                                "<option %s value='%s'>%s</option>\n",
                                selected( $m, $year .'-'.$month, false ),
                                esc_attr( $arc_row['year'] .'-'.$month ),
                                /* translators: 1: month name, 2: 4-digit year */
                                sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
                            );
                        }
                    }
                    ?>
                </select>
            <?php

            submit_button(esc_html__('Filter', 'woopoints'), 'button', false, false, array('id' => 'post-query-submit'));
            echo '</div>';
        }
    }

    /**
     * Display Columns
     * 
     * Handles which columns to show in table
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function get_columns() {     

        $columns = array(
            'customer' 	=> esc_html__('Customer', 'woopoints'),
            'points' 	=> esc_html__('Points', 'woopoints'),
            'points_amount' 	=> esc_html__('Amount', 'woopoints'),
            'event' 	=> esc_html__('Event', 'woopoints'),
            'date' 		=> esc_html__('Date', 'woopoints'),
            'expiry_date'      => esc_html__('Expiry Date', 'woopoints')
        );

        return $columns;
    }

    /**
     * Sortable Columns
     * 
     * Handles soratable columns of the table
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function get_sortable_columns() {

        $sortable_columns = array(
            'customer' 	=> array('customer', true),
            'points' 	=> array('points', true),
            'event' 	=> array('event', true),
            'date' 		=> array('date', true),
        );

        return $sortable_columns;
    }

    function no_items() {

        //message to show when no records in database table
        esc_html_e('No points log found.', 'woopoints');
    }

    function prepare_items() {

        // Get how many records per page to show
        $per_page = $this->per_page;

        // Get All, Hidden, Sortable columns
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // Get final column header
        $this->_column_headers = array($columns, $hidden, $sortable);

        // Get Data of particular page
        $data_res = $this->woo_pr_display_points();
        $data = $data_res['data'];

        // Get current page number
        $current_page = $this->get_pagenum();

        // Get total count
        $total_items = $data_res['total'];

        // Get page items
        $this->items = $data;

        // We also have to register our pagination options & calculations.
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }

}

//Create an instance of our package class...
$PointsListTable = new woo_pr_log();

//Fetch, prepare, sort, and filter our data...
$PointsListTable->prepare_items();
?>
<div class="wrap">
    <h2><?php esc_html_e('Points Log', 'woopoints'); ?></h2>

    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="Points-filter" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <input type="hidden" name="post_type" />

        <!-- Search Title -->
        <?php $PointsListTable->search_box(esc_html__('Search', 'woopoints'), 'woo_pr_search'); ?>

        <!-- Now we can render the completed list table -->
        <?php $PointsListTable->display() ?>
    </form>

</div><!--.wrap-->