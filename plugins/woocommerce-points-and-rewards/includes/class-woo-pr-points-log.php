<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Logging Class
 *
 * Handles all the different functionalities of logs
 *
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
class Woo_Pr_Logging {

    var $model, $logs;

    public function __construct() {
        global $woo_pr_model, $woo_pr_points_scripts;
        $this->model = $woo_pr_model;
    }
    
    /**
     * Stores a log entry
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * */
    function woo_pr_insert_logs($log_data = array(), $log_meta = array()) {

        global $current_user;

        $prefix = WOO_PR_META_PREFIX;
        $enable_expiration_points = get_option('woo_pr_enable_points_expiration');
        $woo_pr_validity_period_days = get_option('woo_pr_validity_period_days');
        $woo_pr_enable_never_points_expiration_sell_points = get_option('woo_pr_enable_never_points_expiration_sell_points');            
        $woo_pr_enable_never_points_expiration_purchased_points = get_option('woo_pr_enable_never_points_expiration_purchased_points');

        $product_type_save_expiry = true;

        if ( !empty( $woo_pr_enable_never_points_expiration_purchased_points ) && $woo_pr_enable_never_points_expiration_purchased_points == 'yes' ) {
            
            if ( isset( $log_meta['order_id'] ) && !empty( $log_meta['order_id'] ) ) {

                $get_order = wc_get_order( $log_meta['order_id'] );
                $get_items = $get_order->get_items();

                if ( !empty( $get_items ) ) {
                   
                    foreach ( $get_items as $item ) {

                        $product_id = $item['product_id'];
                  
                        $_product = wc_get_product( $product_id );

                        if( $_product->is_type( 'woo_pr_points' ) ) {
                            
                            $product_type_save_expiry = false;

                        }

                    }

                }

            }
             
        }

        if ( !empty( $woo_pr_enable_never_points_expiration_sell_points ) && $woo_pr_enable_never_points_expiration_sell_points == 'yes' && isset($log_meta['events']) && $log_meta['events'] == 'earned_sell' ) {

            $events_type_save_expiry = false;

        }else{

            $events_type_save_expiry = true;

        }

        $log_id = 0;

        $logspoints = abs(floatval($log_meta['userpoint']));
        //if user should enter user points more than zero
        if (!empty($logspoints)) {

            $defaults = array(
                'post_type' => WOO_POINTS_LOG_POST_TYPE,
                'post_status' => 'publish',
                'post_parent' => 0,
                'post_title' => '',
                'post_content' => ''
            );

            $args = wp_parse_args($log_data, $defaults);

            //check there is operation type is set or not
            if (isset($log_meta['operation']) && $log_meta['operation'] == 'minus') {
                $log_meta['userpoint'] = '-' . $log_meta['userpoint'];
            } else {
                $log_meta['userpoint'] = '+' . $log_meta['userpoint'];
            }

            // Store the log entry
            $log_id = wp_insert_post($args);

            // If add user points by admin manual
            if( isset($log_meta['operation']) && $log_meta['operation'] == 'add' && isset($log_meta['events']) && $log_meta['events'] == 'manual' && !empty($log_meta['expiry_date']) ){

                // Update the expiry date
                $point_expiry_date = date( 'Y-m-d', strtotime($log_meta['expiry_date']) );
                update_post_meta( $log_id, $prefix.'expiry_date', $point_expiry_date );
                unset($log_meta['expiry_date']);

            } elseif ( isset($log_meta['expiry_date']) ) {
                unset($log_meta['expiry_date']);
            }

            // Set log meta, if any
            if ($log_id && !empty($log_meta)) {
                foreach ((array) $log_meta as $key => $meta) {
                    update_post_meta($log_id, '_woo_log_' . sanitize_key($key), $meta);
                }
            }
            // If operation is Add and points expiration enable
            if ( !empty($enable_expiration_points) && ($enable_expiration_points == 'yes') && !empty($woo_pr_validity_period_days) && isset($log_meta['operation']) && $log_meta['operation'] == 'add' && isset($log_meta['events']) && $log_meta['events'] != 'manual' ) {

                if ( ( $log_meta['events'] != 'earned_sell' && $product_type_save_expiry ) || ( $log_meta['events'] == 'earned_sell' && $events_type_save_expiry ) ) {
                    
                    // Update the expiry date
                    $point_expiry_date = date( 'Y-m-d', strtotime(" +$woo_pr_validity_period_days days", current_time( 'timestamp')) );
                    update_post_meta( $log_id, $prefix.'expiry_date', $point_expiry_date );

                }

            }

            // Call action after insert log
            do_action( 'woo_pr_after_insert_logs', $log_id );
        }

        return $log_id;
    }

    /**
     * Update and existing log item
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * */
    function woo_pr_update_logs($log_data = array(), $log_meta = array()) {

        $defaults = array(
            'post_type' => WOO_POINTS_LOG_POST_TYPE,
            'post_status' => 'publish',
            'post_parent' => 0
        );

        $args = wp_parse_args($log_data, $defaults);

        // Store the log entry
        $log_id = wp_update_post($args);

        if ($log_id && !empty($log_meta)) {
            foreach ((array) $log_meta as $key => $meta) {
                if (!empty($meta))
                    update_post_meta($log_id, '_woo_log_' . sanitize_key($key), $meta);
            }
        }
    }
    /**
     * Show Listing for User Points
     * 
     * Handles to return / echo users
     * points log listing at front side
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     **/
    public function woo_pr_user_log_list(){
        
        global $current_user;
        $prefix = WOO_PR_META_PREFIX;
        $date_format = get_option( 'date_format' );

        // Get decimal points option
        $enable_decimal_points = get_option('woo_pr_enable_decimal_points');
        $woo_pr_number_decimal = get_option('woo_pr_number_decimal');
        $enable_expiration_points = get_option('woo_pr_enable_points_expiration');
        
        //enqueue script to work with public script
        wp_enqueue_script( 'woo-pr-public-script' );
        
        $html = '';
        $perpage = 10;
        
        $argscount = array(
                            'author'    =>  $current_user->ID,
                            'getcount'  =>  '1'
                        );

        //get user logs count value
        $userpointslogcount = $this->model->woo_pr_get_points( $argscount );
        
        $paging = new Woo_Pr_Pagination_Public();
        $paging->items( $userpointslogcount ); 
        $paging->limit( $perpage ); // limit entries per page
        
        //check paging is set or not
        if( isset( $_POST['paging'] ) ) {
            $paging->currentPage( $_POST['paging'] ); // gets and validates the current page
        }
        
        $paging->calculate(); // calculates what to show
        $paging->parameterName( 'paging' );
        
        // setting the limit to start
        $limit_start = ( $paging->page - 1 ) * $paging->limit;
        
        if( isset( $_POST['paging'] ) ) { 
            //ajax call pagination
            $queryargs = array(
                                'posts_per_page'    =>  $perpage,
                                'paged'             =>  $_POST['paging'],
                                'author'            =>  $current_user->ID
                            );
            
        } else {
            //on page load 
            $queryargs = array(
                                'posts_per_page'    =>  $perpage,
                                'paged'             =>  '1',
                                'author'            =>  $current_user->ID
                            );
        }
        //get user logs data
        $userpointslog = $this->model->woo_pr_get_points( $queryargs );
        
        //get user points
        $tot_points = woo_pr_get_user_points( $current_user->ID ); 
		$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $tot_points );		
		$total_points_amount_currency = woo_pr_wcm_currency_convert($total_points_amount);
        // Apply decimal if enabled
        if( $enable_decimal_points=='yes' && !empty($woo_pr_number_decimal) ){
            $tot_points = round( $tot_points, $woo_pr_number_decimal );
        } else {
            $tot_points = round( $tot_points );
        }
        
            $html .= '<div class="woo-pr-user-log">';
            
            //get points plural label
            $plural_label = get_option('woo_pr_lables_points_monetary_value');
            $pointslabel = isset( $plural_label ) && !empty( $plural_label )
                            ? $plural_label : esc_html__( 'Points', 'woopoints' );

            $history_points_message = get_option('woo_pr_user_history_points_message');

            if( !empty( $history_points_message ) ){
                $points_replace     = array("{points}", "{points_label}", "{points_amount}");

                $replace_message    = array( $tot_points, ucfirst($pointslabel), wc_price($total_points_amount_currency) );

                $history_points_message = $this->model->woo_pr_replace_array($points_replace, $replace_message, $history_points_message);
                
                $html .= '  <h4>'.$history_points_message.'</h4>';
            } 
            
            $html .= '  <div class="woo-pr-user-points"><table border="1" class="woo-pr-details">
                                <tr>
                                    <th width="45%">'.esc_html__( 'EVENT','woopoints' ).'</th>
                                    <th width="25%">'.esc_html__( 'DATE','woopoints' ).'</th>
                                    <th width="15%">'.strtoupper($pointslabel).'</th>
									<th width="15%">'.esc_html__( 'AMOUNT','woopoints' ).'</th>
									';
									
            $html .= '<th>'.esc_html__( 'EXPIRY DATE','woopoints' ).'</th>';
            $html .= '</tr>';
        
                if( !empty( $userpointslogcount ) ) { //check user log in not empty
                    
                    foreach ( $userpointslog as $key => $value ){
                        
                        $logspointid = $value['ID'];
                        $event          = get_post_meta( $logspointid, '_woo_log_events', true );                           
                                           
                        $order_id       = get_post_meta( $logspointid, '_woo_log_order_id', true );
                        $event_data     = $this->model->woo_pr_get_events( $event );
                        $event_data     .= $this->model->woo_pr_get_event_user_order_link( $order_id, $logspointid );
                        $date           = $this->model->woo_pr_log_time( strtotime( $value['post_date_gmt'] ) );
                        $points         = get_post_meta( $logspointid, '_woo_log_userpoint', true );
                        $expiry_date    = get_post_meta($value['ID'], WOO_PR_META_PREFIX.'expiry_date', true);
                        $expiry_date    = (!empty($expiry_date)) ? date_i18n( $date_format, strtotime($expiry_date), false ) : '' ;
                        
                        //check event is manual or not
                        if( ($event == 'manual') ) {
                            $post_content = isset( $value['post_content'] ) && !empty( $value['post_content'] ) ? $value['post_content'] : '';
                            $event_data = sprintf('%s Manual %s','<p class="tooltip">','<span class="custom info">'.$post_content.'</span></p>');
                            
                        }
						$point_discounted_amount = 0;
						if ( $event == 'redeemed_purchase' ) {

							$point_discounted_amount = $this->model->woo_pr_get_points_discount_amount_by_order( $order_id );

                            $order_currency = get_post_meta( $order_id, '_order_currency', true );

                            $point_discounted_amount = woo_pr_wcm_currency_convert_original( $point_discounted_amount, $order_currency );
                            
						} else {
							$point_discounted_amount = $this->model->woo_pr_calculate_discount_amount( $points );
						}
						
                        $html .= '<tr>
                                    <td>'.$event_data.'</td>
                                    <td>'.$date.'</td>
                                    <td>'.$points.'</td>
                                    <td>'.wc_price(woo_pr_wcm_currency_convert($point_discounted_amount)).'</td>';
                        
                            
                            $operation = get_post_meta($logspointid, '_woo_log_operation', true);
                            
                            if( $operation == 'add') {
                                $expiry_date = (!empty($expiry_date)) ? $expiry_date : '<label class="unlimited-expiry" title="'.esc_html__('Infinite', 'woopoints').'">&#8734;</label>';
                            } else{
                                $expiry_date = '';                                
                            }

                        $html .= '<td>'.$expiry_date.'</td>';
                       
                        $html .= '</tr>';
                        
                    } //end foreach loop
                    
                } else {
                    $html .=        '<tr><td colspan="5">'.esc_html__( 'No points log found.', 'woopoints' ).'</td></tr>';
                }
                        
        $html .=        '</table></div>';
        $html .= '      <div class="woo-pr-paging">
                            <div id="woo-pr-tablenav-pages" class="woo-pr-tablenav-pages">'.
                                 $paging->getOutput() .'
                            </div>
                        </div><!--woo-pr-paging-->
                        <div class="woo-pr-sales-loader">
                            <img src="'.esc_url(WOO_PR_INC_URL).'/images/loader.gif"/>
                        </div>';
        $html .= '</div><!--woo-pr-user-log-->';
        
        if( isset( $_POST['paging'] ) ) { //check paging is set in $_POST or not
            echo $html;
        } else {
            return $html;
        }
        
    }

}
