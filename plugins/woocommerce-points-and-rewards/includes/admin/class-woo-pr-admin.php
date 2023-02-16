<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Admin Class
 *
 * Manage Admin Panel Class
 *
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
class Woo_Pr_Admin {

    public $model, $scripts, $logs, $public;

    //class constructor
    function __construct() {

        global $woo_pr_model, $woo_pr_scripts, $woo_pr_log, $woo_pr_public;

        $this->scripts = $woo_pr_scripts;
        $this->model = $woo_pr_model;
        $this->logs = $woo_pr_log;
        $this->public = $woo_pr_public;
    }

    /**
     * Downloads Category fields HTML
     * 
     * Handles to add category fields HTML
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * */
    public function woo_pr_product_category_add_fields_html() {

        $points_earned_description = esc_html__('This can be a fixed number of points earned for the purchase of any product that belongs to this category. This setting modifies the global Points Conversion Rate, but can be overridden by a product. Use 0 to assign no earn points for products belonging to this category, and empty to use the global setting. If a product belongs to multiple categories which define different point levels, the highest available point count will be used when awarding points for placing order.', 'woopoints');
        $max_discount_description = sprintf(esc_html__('Enter a fixed maximum discount amount  which restricts  the amount of points that can be redeemed for a discount. For example, if you want to restrict the discount on this category to a maximum of %s5, enter 5. This setting overrides the global default, but can be overridden by a product. Use 0 to disable point discounts for this category, and blank to use the global setting. If a product belongs to multiple categories which define different point discounts, the lowest point count will be used when allowing points discount for placing order.', 'woopoints'), get_woocommerce_currency_symbol());
        ?>
        <div class="form-field">
            <label for="woo_pr_rewards_earn_point"><?php esc_html_e('Points Earned', 'woopoints'); ?></label>
            <input type="number" class="woo-points-earned-cat-field" name="woo_pr_rewards_earn_point" id="woo_pr_rewards_earn_point"/>
            <p><?php echo $points_earned_description; ?></p>
        </div><!--.form-field-->
        <div class="form-field">
            <label for="woo_pr_rewards_max_point_disc"><?php esc_html_e('Maximum Points Discount', 'woopoints'); ?></label>
            <input type="number" class="woo-pr-points-dis-cat-field" name="woo_pr_rewards_max_point_disc" value="jjnfj" id="woo_pr_rewards_max_point_disc"/>
            <?php echo get_woocommerce_currency_symbol(); ?>
            <p><?php echo $max_discount_description; ?></p>
        </div><!--.form-field-->
        <?php wp_enqueue_script('woo-pr-admin-inline-scripts');
    }

    /**
     * Downloads Category Edit fields HTML
     * 
     * Handles to edit category fields HTML
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * */
    public function woo_pr_product_category_edit_fields_html($term) {

        $exclude_include_categories       = get_option('woo_pr_include_exclude_categories_type');
        $exclude_categories       = get_option('woo_pr_exc_inc_categories_points');
        
        $prefix = WOO_PR_META_PREFIX;

        $points_earned_description = esc_html__('This can be a fixed number of points earned for the purchase of any product that belongs to this category. This setting modifies the global Points Conversion Rate, but can be overridden by a product. Use 0 to assign no earn points for products belonging to this category, and empty to use the global setting. If a product belongs to multiple categories which define different point levels, the highest available point count will be used when awarding points for placing order.', 'woopoints');
        $max_discount_description = sprintf(esc_html__('Enter a fixed maximum discount amount  which restricts  the amount of points that can be redeemed for a discount. For example, if you want to restrict the discount on this category to a maximum of %s5, enter 5. This setting overrides the global default, but can be overridden by a product. Use 0 to disable point discounts for this category, and blank to use the global setting. If a product belongs to multiple categories which define different point discounts, the lowest point count will be used when allowing points discount for placing order.', 'woopoints'), get_woocommerce_currency_symbol());

        $term_id = $term->term_id;
        $term_slug = $term->slug;
        //get earn point and maximum pont discount data. 
        $earnedpoints = get_term_meta($term_id, $prefix."rewards_earn_point", true );
        $maxdiscount = get_term_meta($term_id, $prefix."rewards_max_point_disc", true );
        $earnedpoints = $earnedpoints !== '' ? $this->model->woo_pr_escape_attr($earnedpoints) : '';
        $maxdiscount = $maxdiscount !== '' ? $this->model->woo_pr_escape_attr($maxdiscount) : '';
        ?>
        <?php if( $exclude_include_categories != 'exclude' || ( $exclude_include_categories == 'exclude' && !in_array( $term_slug, $exclude_categories ) ) ){?>
        <tr class="form-field">
            <th valign="top" scope="row"><label for="woo_pr_rewards_earn_point"><?php esc_html_e('Points Earned', 'woopoints'); ?></label></th>
            <td>
                <input type="number" class="woo-points-earned-cat-field" name="woo_pr_rewards_earn_point" id="woo_pr_rewards_earn_point" value="<?php echo $earnedpoints; ?>"/>
                <p class="description"><?php echo $points_earned_description; ?></p>
            </td>
        </tr>
        <?php } ?>
        <tr class="form-field">
            <th valign="top" scope="row"><label for="woo_pr_rewards_max_point_disc"><?php esc_html_e('Maximum Points Discount', 'woopoints'); ?></label></th>
            <td>
                <input type="number" class="woo-pr-points-dis-cat-field" name="woo_pr_rewards_max_point_disc" id="woo_pr_rewards_max_point_disc"  value="<?php echo $maxdiscount; ?>"/>
                       <?php echo get_woocommerce_currency_symbol(); ?>
                <p class="description"><?php echo $max_discount_description; ?></p>
            </td>
        </tr>

        <?php
    }

    /**
     * Add a 'Points Earned' column header to the product category list table
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * @param array $columns associative array of column id to title
     * @return array
     */
    public function woo_pr_add_product_category_list_table_points_column_header($columns) {

        $new_columns = array();

        foreach ($columns as $column_key => $column_title) {

            $new_columns[$column_key] = $column_title;

            // add column header immediately after 'Slug'
            if ('slug' == $column_key) {
                $new_columns['points_earned'] = esc_html__('Points Earned', 'woopoints');
                $new_columns['max_points_discount'] = esc_html__('Maximum Points discount', 'woopoints');
            }
        }

        return $new_columns;
    }

    /**
     * Add the 'Points Earned' column content to the product category list table
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0
     * @param array $columns column content
     * @param string $column column ID
     * @param int $term_id the product category term ID
     * @return array
     */
    public function woo_pr_add_product_category_list_table_points_column($columns, $column, $term_id) {

        $prefix = WOO_PR_META_PREFIX;

        $points_earned = get_term_meta($term_id, $prefix.'rewards_earn_point', true );

        $max_point_descount = get_term_meta($term_id, $prefix.'rewards_max_point_disc', true );
        if ('points_earned' == $column) {
            echo ( '' !== $points_earned ) ? esc_html($points_earned) : '&mdash;';
        }
        if ('max_points_discount' == $column) {
            echo ( '' !== $max_point_descount ) ? esc_html($max_point_descount) : '&mdash;';
        }
        return $columns;
    }

    /**
     * Save extra taxonomy fields callback function.
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
     * @param type $term_id
     */
    function woo_pr_save_taxonomy_product_category_meta($term_id) {

        $prefix = WOO_PR_META_PREFIX;

        $woo_pr_rewards_earn_point = $max_number_amount = '';

        $earn_amount 	= filter_input(INPUT_POST, 'woo_pr_rewards_earn_point');
        $max_amount 	= filter_input(INPUT_POST, 'woo_pr_rewards_max_point_disc');

        $earn_number_amount = preg_replace('/[^0-9\.]/', '', $earn_amount);
        $max_number_amount 	= preg_replace('/[^0-9\.]/', '', $max_amount);

        if ( $earn_number_amount !== '' ) {

            $woo_pr_rewards_earn_point = round( $earn_number_amount );
        }

        if ( $max_number_amount !== '' ) {

            $woo_pr_rewards_max_point_disc = round( $max_number_amount );
        }

        update_term_meta($term_id, $prefix.'rewards_earn_point', $woo_pr_rewards_earn_point);
        update_term_meta($term_id, $prefix.'rewards_max_point_disc', $woo_pr_rewards_max_point_disc);
    }

    /**
     * Add Metabox
     * 
     * Add metabox for points and rewards
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * */
    public function woo_pr_product_metabox() {
        $post_types = array('product');     //limit meta box to certain post types
        add_meta_box(
                'woo_pr_and_rewards'
                , esc_html__('Product Points and Rewards Configuration', 'woopoints')
                , array($this, 'woo_pr_product_metabox_content')
                , $post_types
                , 'advanced'
                , 'high'
        );
    }

    /**
     * Metabox Callback function.
     * 
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * */
    public function woo_pr_product_metabox_content($product) {

        $productid = $product->ID;
        $prefix = WOO_PR_META_PREFIX;
        $woo_pr_enable_reviews = get_option('woo_pr_enable_reviews');
        $exclude_include_categories       = get_option('woo_pr_include_exclude_categories_type');
        $all_categories = $this->model->woo_pr_get_product_categories($productid);
        $exc_inc_categories       = get_option('woo_pr_exc_inc_categories_points');

        $all_categories = !empty( $all_categories ) ? $all_categories : array();
        $exc_inc_categories = !empty( $exc_inc_categories ) ? $exc_inc_categories : array();

        $matchs = array_intersect($all_categories,$exc_inc_categories);
        $exclude_include_products       = get_option('woo_pr_include_exclude_products_type');
        $exclude_products       = get_option('woo_pr_exclude_products_points');
        $exclude_products       = !empty( $exclude_products ) ? $exclude_products : array();
        $woo_product = wc_get_product( $productid );
        $pro_type = $woo_product->get_type();

        //get earn point and maximum pont discount data. 
        $earnedpoints   = get_post_meta($productid, $prefix."rewards_earn_point", true);
        $maxdiscount    = get_post_meta($productid, $prefix."rewards_max_point_disc", true);
        $review_points  = get_post_meta($productid, $prefix."review_points", true);
        $earnedpoints   = (!empty($earnedpoints) || ($earnedpoints==0)) ? $this->model->woo_pr_escape_attr($earnedpoints) : '';
        $maxdiscount    = (!empty($maxdiscount) || ($maxdiscount==0)) ? $this->model->woo_pr_escape_attr($maxdiscount) : '';

        $product_point_class = ( $pro_type == 'variable' ) ? ' woo-pr-hide-row' : '';


        //create nonce for metabox
        wp_nonce_field(WOO_POINTS_BASENAME, 'at_woo_pr_points_and_rewards_meta_nonce');
        ?>
        <div id="woo_pr_simple_point">
            <div id="woo_pr_points_rewads_fields">
                <table>
                    <?php if( ( $exclude_include_categories != 'exclude' || ( $exclude_include_categories == 'exclude' && empty($matchs) ) ) && ( $exclude_include_products != 'exclude' || ( $exclude_include_products == 'exclude' && !in_array( $productid, $exclude_products ) ) ) ){?>
                    <tr id="product-points-earned-row" class="product-points-earned-row<?php print $product_point_class;?>">
                        <td width="20%">
                            <label for="woo_pr_rewards_earn_point"><?php esc_html_e('Points Earned:', 'woopoints'); ?></label>
                        </td>
                        <td>
                            <input type="number" class="woo-pr-price-field" value="<?php echo $earnedpoints; ?>" id="woo_pr_rewards_earn_point" name="woo_pr_rewards_earn_point"/>
                        </td>
                    </tr>
                    <tr class="product-points-earned-row<?php print $product_point_class;?>">
                        <td></td>
                        <td><span class="description"><?php esc_html_e('This can be a fixed number of points earned for purchasing this product. This setting modifies the global Points Conversion Rate and overrides any category value. Use 0 to assign no points for this product, and empty to use the global/category settings.', 'woopoints'); ?></span><br/><br/></td>
                    </tr>
                <?php } ?>
                    <tr>
                        <td width="20%">
                            <label for="woo_pr_rewards_max_point_disc"><?php esc_html_e('Maximum Points Discount:', 'woopoints'); ?></label>
                        </td>
                        <td>
                            <input type="number" class="woo-price-field" value="<?php echo $maxdiscount; ?>" id="woo_pr_rewards_max_point_disc" name="woo_pr_rewards_max_point_disc"/>
                            <?php echo get_woocommerce_currency_symbol(); ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><span class="description"><?php printf(esc_html__('Enter a fixed maximum discount amount which restricts the amount of points that can be redeemed for a discount. For example, if you want to restrict the discount on this product to a maximum of %s5, enter 5. This setting overrides the global and category settings. Use 0 to disable point discounts for this product, and blank to use the global/category defaults.', 'woopoints'), get_woocommerce_currency_symbol()); ?></span><br/><br/></td>
                    </tr>
                    <?php // check if review points in enable
                    if( !empty( $woo_pr_enable_reviews ) && ($woo_pr_enable_reviews=='yes') && !in_array( $productid, $exclude_products ) ){ ?>
                        <tr>
                            <td class="woo-pr-rating-point-td" width="20%">
                                <label for="woo_pr_rating_point_disc"><?php esc_html_e('Points earned for Review:', 'woopoints'); ?></label>
                            </td>
                            <td>
                                <?php
                                for ( $star_num = 5; $star_num >= 1; $star_num-- ) {

                                    $val = isset( $review_points[$star_num] ) ? $review_points[$star_num] : '';

                                    echo '<div class="woo_pr_sub_field_item">';

                                    //Display Star description
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        $star_filled = ( $star_num >= $i ) ? 'dashicons-star-filled' : 'dashicons-star-empty';
                                        echo '<span class="dashicons '. $star_filled .'"></span>';
                                    }

                                    echo '<input type="number" min="0" class="small-text" id="woo_pr_review_points" name="woo_pr_review_points['. $star_num .']" value="' . esc_attr( $val ) . '"/>';
                                    echo esc_html__( ' Point(s)', 'woopoints' );

                                    echo '</div>';
                                }?>
                                <span class="description"><?php echo esc_html__('Enter the number of points earned when a customer add a review on this product.', 'woopoints'); ?></span><br/><br/>
                            </td>
                        </tr>

                    <?php
                    } 

                    /**
                     * Fires Points & Rewards metabox settings after.
                     *
                     * add custom setting after metabox
                     * 
                     * @package WooCommerce - Points and Rewards
                     * @since 1.0.0
                     */
                    do_action( 'woo_pr_product_metabox_fields_after', $product );?>

                </table>
            </div> <!--#woo_pr_points_rewads_fields-->
        </div><!--#woo_pr_simple_point-->
        <?php
    }

    /**
     * Save our extra meta box fields
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    public function woo_pr_product_meta_fields_save($postid) {

        global $post_type, $post;

        $prefix = WOO_PR_META_PREFIX;
        $woo_pr_enable_reviews = get_option('woo_pr_enable_reviews');

        //check post type is product
        if( $post_type == 'product' && ( !array_key_exists( 'product-type', $_POST ) 
        	|| $_POST['product-type'] != 'woo_pr_points' ) ){

            $woo_pr_rewards_earn_point = $woo_pr_rewards_max_point_disc = $woo_pr_earned = $woo_pr_max_discount = '';
            $woo_pr_review_points = array();

            if( array_key_exists( 'woo_pr_rewards_earn_point', $_POST ) && $_POST['woo_pr_rewards_earn_point'] !== '' ) {

            	$woo_pr_earned = trim($_POST['woo_pr_rewards_earn_point']);
            }

            $woo_pr_earned = (!empty($woo_pr_earned) ) ? $this->model->woo_pr_escape_attr($woo_pr_earned) : $woo_pr_earned;

            //update maximum discount points
            if( array_key_exists( 'woo_pr_rewards_max_point_disc', $_POST ) && $_POST['woo_pr_rewards_max_point_disc'] !== '' ) {

            	$woo_pr_max_discount = trim($_POST['woo_pr_rewards_max_point_disc']);
            	$woo_pr_max_discount    = (!empty($woo_pr_max_discount) ) ? $this->model->woo_pr_escape_attr($woo_pr_max_discount) : $woo_pr_max_discount;
            }

            $earn_number_amount     = preg_replace('/[^0-9\.]/', '', $woo_pr_earned);
            $max_number_amount      = preg_replace('/[^0-9\.]/', '', $woo_pr_max_discount);

            if ( $earn_number_amount !== '' ) {
                $woo_pr_rewards_earn_point = round( $earn_number_amount );
            }
            if ( $max_number_amount !== '' ) {
                $woo_pr_rewards_max_point_disc  = round( $max_number_amount );
            }

            //update review points
            if( array_key_exists( 'woo_pr_review_points', $_POST ) ) {

                $woo_pr_review_points = $_POST['woo_pr_review_points'];
                foreach ($woo_pr_review_points as $key => $value) {
                    $woo_pr_review_points[$key] = trim($value);
                }
            }

            update_post_meta($postid, $prefix.'rewards_earn_point', $woo_pr_rewards_earn_point);
            update_post_meta($postid, $prefix.'rewards_max_point_disc', $woo_pr_rewards_max_point_disc);
            // check if review points is enable
            if( !empty( $woo_pr_enable_reviews ) && ($woo_pr_enable_reviews=='yes') ) {
                update_post_meta($postid, $prefix.'review_points', $woo_pr_review_points);
            }
        }
    }

    /**
     * Add Custom column label for users screen
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function woo_pr_add_points_column($columns) {
        $columns['_woo_userpoints'] = esc_html__('Points', 'woopoints');
        return $columns;
    }

    /**
     * Add custom column content for users screen
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function woo_pr_show_points_column_content($value, $column_name, $user_id) {

        switch ($column_name) {
            case '_woo_userpoints' :

                $points = get_user_meta($user_id, WOO_PR_META_PREFIX.'userpoints', true);
                // Get decimal points option
                $enable_decimal_points = get_option('woo_pr_enable_decimal_points');
                $woo_pr_number_decimal = get_option('woo_pr_number_decimal');

                if ('_woo_userpoints' == $column_name) {
                    $ubalance = !empty($points) ? $points : '0';
                }
                // Apply decimal if enabled
                if( $enable_decimal_points=='yes' && !empty($woo_pr_number_decimal) ){
                    $ubalance = round( $ubalance, $woo_pr_number_decimal );
                } else {
                    $ubalance = round( $ubalance );
                }

                $balance = '<div id="woo_pr_points_user_' . $user_id . '_balance">' . $ubalance . '</div>';

                // Row actions
                $row = array();
                $row['history'] = '<a href="' . admin_url('admin.php?page=woo-points-log&userid=' . $user_id) . '">' . esc_html__('History', 'woopoints') . '</a>';
                if (current_user_can('edit_users')) { // Check edit user capability
                    $row['adjust'] = '<a href="javascript:void(0)" id="woo_pr_points_user_' . $user_id . '_adjust" class="woo-pr-points-editor-popup" data-userid="' . $user_id . '" data-current="' . $ubalance . '">' . esc_html__('Adjust', 'woopoints') . '</a>';
                }

                $balance .= $this->woo_pr_row_actions($row);
                return $balance;
                break;
			default:
				return $value;
				 break;
        }
    }
 

    /**
     * Generate row actions div
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    public function woo_pr_row_actions($actions, $always_visible = false) {
        $action_count = count($actions);
        $i = 0;

        if (!$action_count)
            return '';

        $out = '<div class="' . ( $always_visible ? 'row-actions-visible' : 'row-actions' ) . '">';
        foreach ($actions as $action => $link) {
            ++$i;
            ( $i == $action_count ) ? $sep = '' : $sep = ' | ';
            $out .= "<span class='$action'>$link$sep</span>";
        }
        $out .= '</div>';

        return $out;
    }

    /**
     * Pop Up On Editor
     *
     * Includes the pop up on the user listing page
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    public function woo_pr_points_user_balance_popup() {

        include_once( WOO_PR_ADMIN_DIR . '/forms/woo-pr-user-balance-popup.php' );
    }

    /**
     * AJAX Call for adjust user points
     *
     * Handles to adjust user points using ajax
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    public function woo_pr_adjust_user_points() {

        if (isset($_POST['userid']) && !empty($_POST['userid']) && isset($_POST['points']) && !empty($_POST['points']) && isset($_POST['log']) && !empty($_POST['log']) && trim($_POST['log'], ' ') != '' ) { // Check user id and points are not empty
            $user_id = $_POST['userid'];
            $current_points = $_POST['points'];
            $expiry_date = isset( $_POST['expiry_date'] ) ? $_POST['expiry_date'] : '';

            // Get decimal points option
            $enable_decimal_points = get_option('woo_pr_enable_decimal_points');
            $woo_pr_number_decimal = get_option('woo_pr_number_decimal');

            //check number contains minus sign or not
            if (strpos($current_points, '-') !== false) {
                $operation = 'minus';
                $current_points = str_replace('-', '', $current_points);

                // Update user points to user account
                woo_pr_minus_points_from_user($current_points, $user_id);
            } else {
                $operation = 'add';
                $current_points = str_replace('+', '', $current_points);
                // Update user points to user account
                woo_pr_add_points_to_user($current_points, $user_id);
            }

            // Get user points from user meta
            $ubalance = woo_pr_get_user_points($user_id);
            // Apply decimal if enabled
            if( $enable_decimal_points=='yes' && !empty($woo_pr_number_decimal) ){
                $ubalance = round( $ubalance, $woo_pr_number_decimal );
            } else {
                $ubalance = round( $ubalance );
            }

            $post_data = array(
                'post_title' => $_POST['log'],
                'post_content' => $_POST['log'],
                'post_author' => $user_id
            );

            $log_meta = array(
                'userpoint' => abs($current_points),
                'events' => 'manual',
                'operation' => $operation, //add or minus
                'expiry_date' => $expiry_date
            );


            $this->logs->woo_pr_insert_logs($post_data, $log_meta);

            $issendemail        = ( isset( $_POST['issendemail'] ) && !empty( $_POST['issendemail'] ) ) ? $_POST['issendemail'] : 'no';

            if ( $operation == 'add' ) {
              
                $is_enable_email    = get_option('woo_pr_enable_earn_points_email');
                $email_subject      = get_option('woo_pr_earn_point_subject');
                $email_content      = get_option('woo_pr_earn_point_email_content');
                
                if( $issendemail == 'yes' && !empty($email_subject) && !empty($email_content) ){                 

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    $userdata   = get_userdata( $user_id );
                    $user_email = $userdata->user_email;
                    $username   = $userdata->user_login;
                    
                    $total_point    = woo_pr_get_user_points( $user_id ); 
                    $pointslable    = $this->model->woo_pr_get_points_label($total_point);
                    $total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );  
                    
                    $site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
                    $site_url_text = site_url();
                    $site_title = get_option('blogname');
                    
                    $latest_update  = esc_html__("manually updated ","woopoints");
                    
                    $total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
                    $tags_arr       = array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}'); 
                    $replace_arr    = array($username,$current_points,$pointslable,$latest_update,$total_amount_message,$site_url,$site_title); 
                    $email_content  = str_replace($tags_arr,$replace_arr,$email_content);               
                
                    $find_subject       = array('{latest_update}','{site_url}','{site_title}','{earned_point}');
                    $replace_subject    = array($latest_update,$site_url_text,$site_title,$current_points);
                    
                    $email_subject  = str_replace($find_subject,$replace_subject,$email_subject);       
                    
                    $sent = wp_mail($user_email,$email_subject,$email_content,$headers);

                }

            }else{

                $enable_redeem_email    = get_option('woo_pr_enable_redeem_email');
                $redeem_email_subject   = get_option('woo_pr_redeem_point_email_subject');
                $redeem_email_content   = get_option('woo_pr_redeem_point_email_content');
                
                if( $issendemail == 'yes' && !empty($redeem_email_subject)  && !empty($redeem_email_content) ){

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    $userdata   = get_userdata( $user_id );
                    $user_email = $userdata->user_email;
                    $username   = $userdata->user_login;
                    
                    $total_point    = woo_pr_get_user_points( $user_id ); 
                    $pointslable    = $this->model->woo_pr_get_points_label($total_point);
                    $total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );  
                    
                    $site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
                    $site_url_text = site_url();
                    $site_title = get_option('blogname');
                    
                    $latest_update  = esc_html__("manually updated ","woopoints");
                    
                    $total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
                    $tags_arr       = array('{username}','{redeemed_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}'); 
                    $replace_arr    = array($username,$current_points,$pointslable,$latest_update,$total_amount_message,$site_url,$site_title); 
                    $redeem_email_content  = str_replace($tags_arr,$replace_arr,$redeem_email_content);               
                
                    $find_subject       = array('{latest_update}','{site_url}','{site_title}','{redeemed_point}');
                    $replace_subject    = array($latest_update,$site_url_text,$site_title,$current_points);
                    
                    $redeem_email_subject  = str_replace($find_subject,$replace_subject,$redeem_email_subject);       
                    
                    $sent = wp_mail($user_email,$redeem_email_subject,$redeem_email_content,$headers);

                }   

            }        

            echo $ubalance;
        } else {
            echo 'error';
        }
        die();
    }


    /**
     * Add Reset points options in bulk action for users screen
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    public function woo_pr_add_reset_points_to_bulk_actions($actions) {
        wp_enqueue_script('woo-pr-admin-inline-scripts');
    }

    /**
     * Reset points to zero of selected users for users screen
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    public function woo_pr_reset_points() {
        
        $prefix = WOO_PR_META_PREFIX;
        // get the action
        $wp_list_table = _get_list_table('WP_Users_List_Table');
        $action = $wp_list_table->current_action();

        switch ($action) {
            // Perform the action
            case 'reset_points':

                if (!empty($_GET['users'])) {

                    foreach ($_GET['users'] as $key => $user_id) {
                        if (!empty($user_id)) {

                            $user_points = get_user_meta($user_id, $prefix.'userpoints', true);

                            update_user_meta($user_id, $prefix.'userpoints', 0);

                            //points label
                            $pointslable = $this->model->woo_pr_get_points_label($user_points);

                            $post_data = array(
                                'post_title' => sprintf(esc_html__('%s for Reset points', 'woopoints'), $pointslable),
                                'post_content' => sprintf(esc_html__('%s Points Reset', 'woopoints'), $pointslable),
                                'post_author' => $user_id
                            );
                            $log_meta = array(
                                'userpoint' => $user_points,
                                'events' => 'reset_points',
                                'operation' => 'minus' //add or minus
                            );

                            $this->logs->woo_pr_insert_logs($post_data, $log_meta);
                        }
                    }
                }

                // Redirect back to users
                $referrer = wp_get_referer();
                wp_redirect(add_query_arg('reset_points_message', true, $referrer));
                exit;

                break;
            default:
                break;
        }
    }

    /**
     * Add submenu un WooCommerce section.
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function woo_pr_users_points_log_page() {
        add_submenu_page('woocommerce', esc_html__('Points Log', 'woopoints'), esc_html__('Points Log', 'woopoints'), 'manage_options', 'woo-points-log', array($this, 'woo_pr_users_points_log'));
    }

    /**
     * Callback function of submenu Points log.
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function woo_pr_users_points_log() {
        include_once( WOO_PR_ADMIN_DIR . '/forms/class-woo-pr-users-points-list.php' );
    }

    /**
     * Add custom fields.
     *
     * Handles to add custom fields in user profile page
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * */
    public function woo_pr_add_custom_user_profile_fields($user) {

        //get user points
        $userpoints = woo_pr_get_user_points($user->ID);
        // Get decimal points option
        $enable_decimal_points = get_option('woo_pr_enable_decimal_points');
        $woo_pr_number_decimal = get_option('woo_pr_number_decimal');
        // Apply decimal if enabled
        if( $enable_decimal_points == 'yes' && !empty($woo_pr_number_decimal) ){
            $userpoints = round( $userpoints, $woo_pr_number_decimal );
        } else {
            $userpoints = round( $userpoints );
        }
        ?>
        <table class="form-table woo-points-user-profile-balance">
            <tr>
                <th>
                    <label for="woo_userpoints"><?php esc_html_e('Current points balance ', 'woopoints'); ?></label>
                </th>
                <td>
                    <h2><?php echo $userpoints; ?></h2>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Adjust the Tool Bar
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function woo_pr_tool_bar($wp_admin_bar) {

        global $current_user;
        // Get decimal points option
        $enable_decimal_points = get_option('woo_pr_enable_decimal_points');
        $woo_pr_number_decimal = get_option('woo_pr_number_decimal');

        $wp_admin_bar->add_group(array(
            'parent' => 'my-account',
            'id' => 'woo-points-actions',
        ));

        //get total users points
        $tot_points = woo_pr_get_user_points();

        // Apply decimal if enabled
        if( $enable_decimal_points=='yes' && !empty($woo_pr_number_decimal) ){
            $tot_points = round( $tot_points, $woo_pr_number_decimal );
        } else {
            $tot_points = round( $tot_points );
        }

        $wp_admin_bar->add_menu(array(
            'parent' => 'woo-points-actions',
            'id' => 'user-balance',
            'title' => esc_html__('My Balance:', 'woopoints') . ' ' . $tot_points,
            'href' => admin_url('profile.php')
        ));
    }

    /**
     * Show general and invetory setting into points product type.
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function woo_pr_variable_bulk_admin_custom_js() {

        if ('product' != get_post_type()) :
            return;
        endif;
        wp_enqueue_script('woo-pr-admin-inline-scripts');
    }

    /**
     * Add custom product type points.
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function woo_pr_add_custom_product_type($types) {

        $types['woo_pr_points'] = esc_html__('Points product', 'woopoints');
        return $types;
    }

    /**
     * Add custom product type points into WooCommerce class.
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function woo_pr_woocommerce_product_class($classname, $product_type) {

        if ($product_type == 'points') { // notice the checking here.
            $classname = 'WC_Product_Points';
        }

        return $classname;
    }

    /**
	 * Apply Points to Previous Orders
	 *
	 * Handles to apply points to previous orders
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 */
	public function woo_pr_apply_points_for_previous_orders() {

		// Check if action is set
		if( !empty( $_GET['points_action'] ) && $_GET['points_action'] == 'apply_points'
			&& !empty( $_GET['page'] ) && $_GET['page'] == 'wc-settings'
			&& !empty( $_GET['tab'] ) && $_GET['tab'] == 'woopr-settings' ) {

			$prefix = WOO_PR_META_PREFIX;

			// perform the action in manageable chunks
			$success_count  = 0;
			$old_order_args = array(
									'fields'		=> 'ids',
									'post_type'		=> 'shop_order',
									'post_status'	=> array( 'wc-completed', 'wc-processing' ),
									'posts_per_page'=> '-1',
									'meta_query' 	=> array(
															array(
																'key'     => $prefix.'points_order_earned',
																'compare' => 'NOT EXISTS'
															),
									)
								);

			// Get all order ids for which our meta is not set
			$order_query 	= new WP_Query( $old_order_args );
			$order_ids		= !empty( $order_query->posts ) ? $order_query->posts : array();

			// otherwise go through the results and set the order numbers
			if ( !empty( $order_ids ) && is_array( $order_ids ) ) {

				foreach( $order_ids as $order_id ) {

                    $this->public->woo_pr_order_processing_completed_update_points( $order_id );

					$success_count++;
				} //end foreach loop
			} //end if check retrive payment ids are array

			$redirectargs = array(
									'page'				=>	'wc-settings',
									'tab'				=>	'woopr-settings',
									'message'			=>	'woopr-orders-updated',
									'success-count' 	=>	$success_count,
									'points_action' 	=>	false
								);

			$redirect_url = add_query_arg( $redirectargs, admin_url( 'admin.php' ) );
			wp_redirect( $redirect_url );
			exit;
		} //end if check if there is fulfilling condition proper for applying discount for previous orders
	}

    /**
     * Get added and minus points by userid and date
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.11
     */
    public function woo_pr_unexpired_previous_points_log_data( $user_id, $expiry_date = '' ) {

        $prefix = WOO_PR_META_PREFIX;
        $add_points_data = $minus_points_data = array();
        if( empty($expiry_date) ){
            $expiry_date = date( 'Y-m-d', current_time( 'timestamp'));
        }

        // Get all points ids for which are points expiry but processed
        $expiry_points_args = array(
            'fields'        => 'ids',
            'post_type'     => WOO_POINTS_LOG_POST_TYPE,
            'post_status'   => 'publish' ,
            'posts_per_page'=> '-1',
            'author'        => $user_id,
            'meta_query'    => array(
                array(
                    'key'     => $prefix.'expiry_date',
                    'value'   => $expiry_date,
                    'type'    => 'DATE',
                    'compare' => '<=',
                ),
                array(
                    'key'     => $prefix.'expiry_processed',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => '_woo_log_operation',
                    'value'   => 'add'
                ),
            )
            
        );

        $points_query    = new WP_Query( $expiry_points_args );
        $points_ids      = !empty( $points_query->posts ) ? $points_query->posts : array();

        // otherwise go through the results and get the points
        if ( !empty( $points_ids ) && is_array( $points_ids ) ) {

            foreach( $points_ids as $point_id ) {

                $point_date = get_the_date( 'Y-m-d', $point_id );
                $point_expiry_date = get_post_meta( $point_id, $prefix.'expiry_date', true );
                $log_userpoint = (float)get_post_meta( $point_id, '_woo_log_userpoint', true );

                $add_points_data[$point_id] = $log_userpoint;

            } //end foreach loop
        } //end if check points ids are array

        // Get all points ids for which are debited points before the date
        $minus_points_args = array(
            'fields'        => 'ids',
            'post_type'     => WOO_POINTS_LOG_POST_TYPE,
            'post_status'   => 'publish' ,
            'posts_per_page'=> '-1',
            'author'        => $user_id,
            'date_query' => array(
                array(
                    'before'    => $expiry_date,
                    'inclusive' => true,
                ),
            ),
            'meta_query'    => array(
                array(
                    'key'     => '_woo_log_operation',
                    'value'   => 'minus'
                ),
                array(
                    'key'     => '_woo_log_events',
                    'value'   => 'expiration',
                    'compare' => '!='
                ),
            ),
        );

        $minus_points_query    = new WP_Query( $minus_points_args );
        $minus_points_ids      = !empty( $minus_points_query->posts ) ? $minus_points_query->posts : array();

        // otherwise go through the results and get the points
        if ( !empty( $minus_points_ids ) && is_array( $minus_points_ids ) ) {

            foreach( $minus_points_ids as $minus_points_id ) {

                $minus_log_userpoint = (float)get_post_meta( $minus_points_id, '_woo_log_userpoint', true );
                if( $minus_log_userpoint < 0 ){
                    $minus_log_userpoint *= -1 ;
                }

                $minus_points_data[$minus_points_id] = $minus_log_userpoint;

            } //end foreach loop
        } //end if check points ids are array

        return array( 'add_points' => $add_points_data, 'minus_points' => $minus_points_data );
    }

    /**
     * Apply Expiration Points to Previous Points
     *
     * Handles to apply expiration points to previous points
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.11
     */
    public function woo_pr_apply_expiration_points_for_previous_points() {

        // Check if action is set
        if( !empty( $_GET['points_action'] ) && $_GET['points_action'] == 'apply_expiration_points'
            && !empty( $_GET['page'] ) && $_GET['page'] == 'wc-settings'
            && !empty( $_GET['tab'] ) && $_GET['tab'] == 'woopr-settings' ) {

            $prefix = WOO_PR_META_PREFIX;
            $success_count  = 0;
            $enable_expiration_points = get_option('woo_pr_enable_points_expiration');
            $woo_pr_validity_period_days = get_option('woo_pr_validity_period_days');
            $woo_pr_enable_never_points_expiration_sell_points = get_option('woo_pr_enable_never_points_expiration_sell_points');            
            $woo_pr_enable_never_points_expiration_purchased_points = get_option('woo_pr_enable_never_points_expiration_purchased_points');

            // check if validity period days set the run the process otherwise show the error of
            if( !empty($woo_pr_validity_period_days) ){

                // perform the action in manageable chunks
                $old_points_args = array(
                    'fields'        => 'ids',
                    'post_type'     => WOO_POINTS_LOG_POST_TYPE,
                    'post_status'   => 'publish' ,
                    'posts_per_page'=> '-1',
                    'meta_query'    => array(
                        array(
                            'key'     => $prefix.'expiry_date',
                            'compare' => 'NOT EXISTS'
                        ),
                        array(
                            'key'     => '_woo_log_operation',
                            'value'   => 'add'
                        ),
                    )
                );

                if ( !empty( $woo_pr_enable_never_points_expiration_sell_points ) && $woo_pr_enable_never_points_expiration_sell_points == 'yes' ) {
                    
                   $old_points_args['meta_query'][] = array(
                        'key'     => '_woo_log_events',
                        'value'   => 'earned_sell',
                        'compare' => 'NOT LIKE'
                   );

                }

                if ( !empty( $woo_pr_enable_never_points_expiration_purchased_points ) && $woo_pr_enable_never_points_expiration_purchased_points == 'yes' ) {
                    
                    $old_points_args['meta_query'][] = array(
                        'key'     => '_woo_log_product_type',
                        'compare' => 'NOT EXISTS'
                   );

                }


                // Get all points ids for which our meta is not set
                $points_query    = new WP_Query( $old_points_args );

                $points_ids      = !empty( $points_query->posts ) ? $points_query->posts : array();

                // otherwise go through the results and set the expiry date
                if ( !empty( $points_ids ) && is_array( $points_ids ) ) {

                    foreach( $points_ids as $point_id ) {

                        $point_date = get_the_date( 'Y-m-d', $point_id );
                        $point_expiry_date = date( 'Y-m-d', strtotime($point_date." +$woo_pr_validity_period_days days") );
                        // Update the expiry date
                        update_post_meta( $point_id, $prefix.'expiry_date', $point_expiry_date );

                    } //end foreach loop
                } //end if check retrive points ids are array


                // Check all user
                $all_user_ids = get_users( array( 'fields' => 'ids' ) );

                if( !empty($all_user_ids) ){

                    foreach ($all_user_ids as $user_id) {

                        // get user points by user and date
                        $unexpired_points_data = $this->woo_pr_unexpired_previous_points_log_data( $user_id );

                        if( !empty($unexpired_points_data['add_points']) ){

                            $add_points_data = !empty($unexpired_points_data['add_points']) ? $unexpired_points_data['add_points'] : '' ;
                            $minus_points_data = !empty($unexpired_points_data['minus_points']) ? $unexpired_points_data['minus_points'] : '' ;

                            $add_points_total = (is_array($add_points_data)) ? round(array_sum($add_points_data), 2 ) : 0;
                            $minus_points_total = (is_array($minus_points_data)) ? round(array_sum($minus_points_data), 2 ) : 0;

                            $expired_points = $add_points_total - $minus_points_total;

                            // If remaining points greater than zero then add point log for expiration points
                            if( $expired_points > 0 ){

                                //points label
                                $pointslable = $this->model->woo_pr_get_points_label($expired_points);

                                $post_data = array(
                                    'post_title' => sprintf(esc_html__('%s Expiration', 'woopoints'), $pointslable),
                                    'post_content' => sprintf(esc_html__('Earned %s debited towards expiration', 'woopoints'), $pointslable),
                                    'post_author' => $user_id
                                );
                                $log_meta = array(
                                    'userpoint' => $expired_points,
                                    'events' => 'expiration',
                                    'operation' => 'minus' //add or minus
                                );

                                $this->logs->woo_pr_insert_logs($post_data, $log_meta);

                                // Deduct points from user points log
                                woo_pr_minus_points_from_user($expired_points, $user_id);

                                $success_count++;

                            }
                            // Add meta for expiry points logs
                            if( !empty($add_points_data) ){

                                foreach ($add_points_data as $log_id => $log_points) {
                                    update_post_meta( $log_id, $prefix.'expiry_processed', 1 );
                                }
                            }

                        }
                    }
                }

                // return for show success message
                $redirectargs = array(
                                        'page'              =>  'wc-settings',
                                        'tab'               =>  'woopr-settings',
                                        'message'           =>  'woopr-points-expiry-updated',
                                        'success-count'     =>  $success_count,
                                        'points_action'     =>  false
                                    );

                $redirect_url = add_query_arg( $redirectargs, admin_url( 'admin.php' ) );
                wp_redirect( $redirect_url );
                exit;

            } else {

                // return for show error message for expiry days
                $redirectargs = array(
                                        'page'              =>  'wc-settings',
                                        'tab'               =>  'woopr-settings',
                                        'message'           =>  'woopr-points-expiry-updated'
                                    );

                $redirect_url = add_query_arg( $redirectargs, admin_url( 'admin.php' ) );
                wp_redirect( $redirect_url );
                exit;

            }

        } //end if check if there is fulfilling condition proper for applying discount for previous orders
    }


	/**
	 * Show success message
	 *
	 * Handles to show success message when points
	 * are updated for previous orders
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 */
	public function woo_pr_admin_settings_order_updated_notice(){

		// Check if action is set
		if( !empty( $_GET['message'] ) && $_GET['message'] == 'woopr-orders-updated'
			&& !empty( $_GET['page'] ) && $_GET['page'] == 'wc-settings'
			&& !empty( $_GET['tab'] ) && $_GET['tab'] == 'woopr-settings' ) {

			?>
	        <div class="updated">
	            <p>
	                <?php                     
	                echo sprintf( esc_html__( '%d order(s) updated.','woopoints' ), $_GET['success-count'] );
	                ?>
	            </p>
	        </div><?php
		}

        // Check if action is set
        if( !empty( $_GET['message'] ) && $_GET['message'] == 'woopr-points-expiry-updated'
            && !empty( $_GET['page'] ) && $_GET['page'] == 'wc-settings'
            && !empty( $_GET['tab'] ) && $_GET['tab'] == 'woopr-settings' ) {

            if( isset($_GET['success-count']) ){ ?>
                <div class="updated">
                    <p>
                        <?php                     
                        echo sprintf( esc_html__( 'Expiry points updated for users: %d','woopoints' ), $_GET['success-count'] );
                        ?>
                    </p>
                </div><?php
            } else { ?>
                <div class="error">
                    <p>
                        <?php                     
                        echo sprintf( esc_html__( 'Error: Points expiry days is empty.','woopoints' ) );
                        ?>
                    </p>
                </div><?php
            }
        }
	}

    /**
     * Show WooCommerce Order Points
     *
     * Handles to show earned points and redeemed points
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    public function woo_pr_admin_order_data_after_order_details( $order ){

        $prefix = WOO_PR_META_PREFIX;

        $order_id = $order->get_id();
        // Get earned points and redeemed points
        $points_earned   = get_post_meta( $order_id, $prefix.'points_order_earned', true);
        $points_redeemed = get_post_meta( $order_id, $prefix.'redeem_order', true);

        if( !empty($points_earned) || !empty($points_redeemed) ){
            ?>
            <div class='clear'></div>
            <h3><?= esc_html__( 'Points', 'woopoints' ); ?></h3>
            <p class="form-field form-field-wide wp-pr-user-order-points">
                <strong class="earned-label"><?= esc_html__( 'Earned:', 'woopoints' ); ?></strong> <span><?= !empty($points_earned) ? $points_earned : esc_html__( 'N/A', 'woopoints' ); ?></span>
            </p>
            <p class="form-field form-field-wide wp-pr-user-order-points">
                <strong class="redeemed-label"><?= esc_html__( 'Redeemed:', 'woopoints' ); ?></strong> <span><?= !empty($points_redeemed) ? $points_redeemed : esc_html__( 'N/A', 'woopoints' ); ?></span>
            </p>
            <?php
        }
    }


    /**
     *
     * Handles to filter points based on expiry date 
     *
     * @package WooCommerce - Points and Rewards
     * @since 3.6.4
     */
    public function woo_pr_points_table_expirydate_filter( $query ){

        if( is_admin() && isset( $query->query['post_type'] ) && $query->query['post_type'] == 'woopointslog' && isset( $_GET['points_expiry_date'] ) ) {

            $qv = &$query->query_vars;

            if( isset( $_GET['points_expiry_date'] ) && !empty( $_GET['points_expiry_date'] ) ) {

                $qv['meta_query'][] = array(
                    'field' => '_woo_pr_expiry_date',
                    'value' => $_GET['points_expiry_date'],
                    'compare' => 'LIKE',
                  );
            }
        }
    }
	
	
	  /**
     *
     * Handles to Add Text field for  Points Earned Variation  Settings
     *
     * @package WooCommerce - Points and Rewards
     * @since 3.6.4
     */
	public function woo_pr_variation_settings_fields( $loop, $variation_data, $variation ){
		
		global $post;
		
		// Get Excluded Product
        $exclude_products       = get_option('woo_pr_exclude_products_points');
        $exclude_products       = !empty( $exclude_products ) ? $exclude_products : array();
		$Product_id 			= $variation->post_parent; // Get Product ID
		
		
		if(!in_array($Product_id,$exclude_products)){ // Rremove all "Points Earned" text box from variation if Product is excluded
		
			if(!in_array(  $variation->ID ,$exclude_products)){ // Rremove "Points Earned" text box if Product variation is excluded
				// Text Field
				woocommerce_wp_text_input( 
					array( 
						'id'          	=> '_woo_pr_points_earned[' . $variation->ID . ']', 
						'label'      	=> __( 'Points Earned:', 'woopoints' ), 
						'placeholder' 	=> '',
						'desc_tip' 		=> 'true',				
						'description'	=> esc_html__( ' This can be a fixed number of points earned for purchasing this product variation. This setting overrides the Global/Category Points Conversion Rate value. Use 0 to assign no points for this product variation, and empty to use the Global/Category settings.', 'woopoints' ),				
						'value'       => get_post_meta( $variation->ID, '_woo_pr_points_earned', true )
					)
				);
			}
		}
	}
	
	  /**
     *
     * Handles to save Points Earned Variation Settings
     *
     * @package WooCommerce - Points and Rewards
     * @since 3.6.4
     */
	
	public function woo_pr_save_variation_settings( $post_id  ){		
		if( isset( $_POST['_woo_pr_points_earned'] ) ) {
    		$text_field = $_POST['_woo_pr_points_earned'][ $post_id ];		
    		update_post_meta( $post_id, '_woo_pr_points_earned',  $text_field  );	
        }
		
	}



     /**
     * Export Points CSV
     *
     * Handles to export csv for user
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    public function woo_pr_export_points_csv() {        
       
        $admin_url = admin_url('admin.php');
        $queryArgs = array(
            'page'      => 'wc-settings',
            'tab'       => 'woopr-settings',
            'section'   => 'woo_pr_import_export'            
        );
     
     
        $response = array();
        $filename = 'rewards-points-'.date('Y-m-d');
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename='.$filename.'.csv');
        // create a file pointer connected to the output stream
        $flag = 0;

        $output = fopen('php://output', 'w');

        $all_usersids   = array();
        $csv_rows       = array();

        $identify_users_by = 'Username';
        if( isset( $_POST['points_identified_user'] ) && $_POST['points_identified_user'] == 'email'){
            $identify_users_by = 'Email';
        }

        $export_points_for  =  isset($_POST['export_points_for'])?$_POST['export_points_for']:'';
        $selected_users     = isset($_POST['selected_users'])?$_POST['selected_users']:array();
        if($export_points_for == 'selected_users' && empty($selected_users))
        {            
            $queryArgs['e_type'] = 'user_emp';             
            $response['status'] = 'error';
            $response['e_type'] = add_query_arg( $queryArgs, $admin_url );;  
            echo json_encode($response);                   
            die();
        }
        
        $export_points_time         = isset($_POST['export_points_time'])?$_POST['export_points_time']:'all_time';
        $export_points_start_date   = isset($_POST['export_points_start_date']) ? $_POST['export_points_start_date'] : '';
        $export_points_end_date     = isset($_POST['export_points_end_date']) ? $_POST['export_points_end_date'] : '';     


        $all_users = get_users( array( 'fields' => array( 'ID' ) ) );
        if(!empty($all_users)){
            foreach($all_users as $user_id){
                $all_usersids[] = $user_id->ID;
            }
        }

        if($export_points_time == 'selected_date' && ( !empty($export_points_start_date) || !empty($export_points_end_date) ) ) {
            
            if(!empty($selected_users) && $export_points_for == 'selected_users' ){
               $all_usersids = $selected_users;
            }

            if(!empty($all_usersids)) {              
                
                
                foreach ($all_usersids as $key => $userid){
                    
                    $user_points = get_user_meta($userid,'_woo_pr_userpoints',true);
                    $userdata    = get_userdata($userid);

                    // Get Total Points Balence between two date
                    $args = array(
                        'orderby'           =>  'post_date',
                        'order'             =>  'DESC',
                        'post_type'         => WOO_POINTS_LOG_POST_TYPE,
                        'posts_per_page'    => -1, // no limit
                    );
                    $args['date_query'] = array(
                        array(
                            'after'     => $export_points_start_date,
                            'before'    => $export_points_end_date,
                            'inclusive' => true,
                        )
                    );

                    $args['meta_query'] = array(
                        'relation' => 'AND',
                        array(
                            'key'     => '_woo_log_operation',
                            'value'   => 'add',
                            'compare'  => '='
                        ),
                        array(
                            'relation' => 'OR',
                            array(
                               'key' => '_woo_pr_expiry_date',
                               'value' => date( 'Y-m-d', current_time( 'timestamp') ),
                               'type'    => 'DATE',
                               'compare' => '>=',
                            ),
                            array(
                               'key' => '_woo_pr_expiry_date',
                               'compare' => 'NOT EXISTS',
                            ),
                        )
                   );

                    $args['author__in']     = $userid;
                    $users_points_log       = get_posts( $args );
                    $logPointTotal          = 0;
                    $PintLogsRows           = array();
                    $i = 0;

                    if(!empty($users_points_log) ){
                                                
                        foreach ($users_points_log as $key => $points_log) {

                            $log_userpoint  = get_post_meta($points_log->ID,'_woo_log_userpoint',true);
                            $logPointTotal  +=  $log_userpoint;

                            if( !empty($user_points) && $logPointTotal <= $user_points )
                            {                                                               
                                
                                $PintLogsRows[$i]['expiration_date'] = '';
                                $PintLogsRows[$i]['points_total'] = $log_userpoint;
                            } 
                            elseif ( !empty($user_points) && $logPointTotal > $user_points ) {
                                                           
                                $diff = $logPointTotal - $user_points;
                                $log_userpoint = $log_userpoint - $diff;
                                $PintLogsRows[$i]['expiration_date'] = '';
                                $PintLogsRows[$i]['points_total'] = $log_userpoint;
                                
                                if(!empty(get_post_meta($points_log->ID,'_woo_pr_expiry_date',true)))
                                {                                    
                                    $logPointExpiry = get_post_meta($points_log->ID,'_woo_pr_expiry_date',true);
                                    $PintLogsRows[$i]['expiration_date'] =  $logPointExpiry;
                                }
                                break;
                            }
                            elseif(empty($user_points)){
                                $PintLogsRows[$i]['expiration_date'] = '';
                                $PintLogsRows[$i]['points_total'] = 0;
                            }
                            if(!empty(get_post_meta($points_log->ID,'_woo_pr_expiry_date',true)))
                            {                                    
                                $logPointExpiry = get_post_meta($points_log->ID,'_woo_pr_expiry_date',true);
                                $PintLogsRows[$i]['expiration_date'] =  $logPointExpiry;
                            }
                            $i++;
                        }
                    }

                    if( isset( $_POST['points_identified_user'] ) && $_POST['points_identified_user'] == 'username'){
                        $csv_username = $userdata->user_login;                        
                    }
                    else{
                        $csv_username = $userdata->user_email;                      
                    }

                    $infinitePoints   = 0;
                    $expirePointCount = 0;               

                    if(!empty($PintLogsRows))
                    {
                        if($flag == 0){
                            $csv_rows = array($identify_users_by,'Points','Expiry Date');
                            fputcsv($output, $csv_rows);
                            $flag = 1;
                        }
                        
                        foreach($PintLogsRows as $point_log)
                        {
                            if(!empty($point_log['expiration_date']) && $point_log['points_total'] > 0)
                            {
                                $csv_rows = array($csv_username,(int)$point_log['points_total'],$point_log['expiration_date']);
                                fputcsv($output, $csv_rows);
                            }
                            else{
                                $infinitePoints +=$point_log['points_total'];
                            }                           
                        }
                        if(!empty($infinitePoints) && $infinitePoints > 0){
                            $csv_rows = array($csv_username,(int)$infinitePoints,'-');
                            fputcsv($output, $csv_rows);
                        }
                    }
                }//END FOREACH
                
                if($flag == 0){
                    $queryArgs['e_type'] = 'no_logs';
                    $response['status'] = 'error';
                    $response['e_type'] = add_query_arg( $queryArgs, $admin_url );;  
                    echo json_encode($response);                         
                }
            }
        }
        else
        {
            if(!empty($selected_users) && $export_points_for == 'selected_users' ){
               $all_usersids = $selected_users;
            }
            
            if(!empty($all_usersids)){                
               
                
                foreach ($all_usersids as $key => $userid) {
                   $userdata    = get_userdata($userid);
                   
                   $user_points = get_user_meta($userid,'_woo_pr_userpoints',true);
                   
                     $args = array(
                        'orderby'           => 'post_date',
                        'order'             => 'DESC',
                        'post_type'         => WOO_POINTS_LOG_POST_TYPE,
                        'posts_per_page'    => -1, // no limit  
                        'author__in'        => array($userid),//$userid,           
                        'meta_query'        => array(
                            'relation' => 'AND',
                            array(
                                'key'       => '_woo_log_operation',
                                'value'     => 'add',
                                'compare'   => '='
                            ),
                            array(
                                'relation' => 'OR',
                                array(
                                   'key' => '_woo_pr_expiry_date',
                                   'value' => date( 'Y-m-d', current_time( 'timestamp') ),
                                   'type'    => 'DATE',
                                   'compare' => '>=',
                                ),
                                array(
                                   'key' => '_woo_pr_expiry_date',
                                   'compare' => 'NOT EXISTS',
                                ),
                            ),
                        ),
                    );
                     
                    $users_points_log    = get_posts( $args );
                    $logPointTotal       = 0;
                    $PintLogsRows        = array();
                    $i = 0;
                    
                    if(!empty($users_points_log) ){
                                                
                        foreach ($users_points_log as $key => $points_log) {

                            $log_userpoint  = get_post_meta($points_log->ID,'_woo_log_userpoint',true);
                            $logPointTotal  +=  $log_userpoint;

                            if( !empty($user_points) && $logPointTotal <= $user_points )
                            {                                                                
                                
                                $PintLogsRows[$i]['expiration_date'] = '';
                                $PintLogsRows[$i]['points_total'] = $log_userpoint;
                                
                                if(!empty(get_post_meta($points_log->ID,'_woo_pr_expiry_date',true)))
                                {                                    
                                    $logPointExpiry = get_post_meta($points_log->ID,'_woo_pr_expiry_date',true);
                                    $PintLogsRows[$i]['expiration_date'] =  $logPointExpiry;
                                }
                            } 
                            elseif ( !empty($user_points) && $logPointTotal > $user_points ) {
                               
                                $diff = $logPointTotal - $user_points;
                                $log_userpoint = $log_userpoint - $diff;
                                $PintLogsRows[$i]['expiration_date'] = '';
                                $PintLogsRows[$i]['points_total'] = $log_userpoint;
                                
                                if(!empty(get_post_meta($points_log->ID,'_woo_pr_expiry_date',true)))
                                {                                    
                                    $logPointExpiry = get_post_meta($points_log->ID,'_woo_pr_expiry_date',true);
                                    $PintLogsRows[$i]['expiration_date'] =  $logPointExpiry;
                                }
                                break;
                            }
                            elseif(empty($user_points)){
                                $PintLogsRows[$i]['expiration_date'] = '';
                                $PintLogsRows[$i]['points_total'] = 0;
                            }
                            $i++;
                        }
                    }                   
                   
                    if( isset( $_POST['points_identified_user'] ) && $_POST['points_identified_user'] == 'username'){
                        $csv_username = $userdata->user_login;                        
                    }
                    else{
                        $csv_username = $userdata->user_email;                      
                    }
                    
                    $infinitePoints = 0;
                   
                    
                    $expirePointCount = 0;                    
                    if(!empty($PintLogsRows))
                    {  
                        
                        if($flag == 0){
                            $csv_rows = array($identify_users_by,'Points','Expiry Date');
                            fputcsv($output, $csv_rows);
                            $flag = 1;
                        }
                        
                        foreach($PintLogsRows as $point_log)
                        {  
                            if(!empty($point_log['expiration_date']) && $point_log['points_total'] > 0)
                            {                                
                                $csv_rows = array($csv_username,(int)$point_log['points_total'],$point_log['expiration_date']);
                                fputcsv($output, $csv_rows);
                            }
                            else{                                
                                $infinitePoints +=$point_log['points_total'];
                            }                           
                        }
                        if(!empty($infinitePoints) && $infinitePoints > 0){
                            $csv_rows = array($csv_username,(int)$infinitePoints,'-');
                            fputcsv($output, $csv_rows);
                        }                       
                    }
                    
                } //End Foreach
                 if($flag == 0){
                    $queryArgs['e_type'] = 'no_logs';
                    $response['status'] = 'error';
                    $response['e_type'] = add_query_arg( $queryArgs, $admin_url );;  
                    echo json_encode($response); 
                    die();
                }
            }
        }
        die();  
    }
    /**
     * Import Points CSV
     *
     * Handles to export csv for user
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    
    public function woo_pr_import_points_csv(){
       
        $admin_url = admin_url('admin.php');
        $queryArgs = array(
            'page'      => 'wc-settings',
            'tab'       => 'woopr-settings',
            'section'   => 'woo_pr_import_export',
            'e_type'    => 'import_fail',
        );
       
        $result          =  array('status' => 'error','e_type'=> add_query_arg($queryArgs,$admin_url));
                
        $total_imp_point = 0;       
        $fileName        = $_FILES["file"]["tmp_name"];
        
        if ($_FILES["file"]["size"] > 0) {
            
           $file    = fopen($fileName, "r");            
           
           while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($column[1] != 'Points' || $column[1] != 'Point' || $column[0] != 'Email' || $column[0] != 'Username'){
                    
                
                
                $csv_points = 0;                
                if(isset($column[1]) && !empty($column[1])){
                    $csv_points  = $column[1];
                }

                $userdata = '';
                if(!empty(get_user_by('email',$column[0])))
                {
                    $userdata    = get_user_by('email',$column[0]);
                }
                else{
                    $userdata    = get_user_by('login',$column[0]);
                }

                if(!empty($userdata))
                {   

                 if ( isset($userdata->ID) && !empty(($userdata->ID) && isset($csv_points) && !empty($csv_points) && $csv_points > 0))  { // Check user id and points are not empty
                        
                        $total_imp_point += $csv_points;
                        $user_id        = $userdata->ID;
                        $current_points = $csv_points;
                      
                        $expiry_date = '';
                        $ex_date = '';
                        if(isset($column[2]) && !empty($column[2])){
                            $expiry_date    = $column[2];

                            if( !empty( $expiry_date ) && $expiry_date != '-' ) {

                                $expiry_date = strtotime($expiry_date);
                                $expiry_date = date('Y-m-d',$expiry_date);
                                $ex_date = $expiry_date;
                            }
                        }
                                               
                        $ubalance = woo_pr_get_user_points($user_id);
                                                
                        $exportlog =  esc_html__('Points were overrided by importing','woopoints');
                        if($_POST['import_points_action'] != 'override_user_point'){
                            $exportlog = esc_html__('Points were added with existing points by importing','woopoints');
                        }
                        
                        $post_data = array(
                            'post_title' => $exportlog,
                            'post_content' => $exportlog,
                            'post_author' => $user_id,
                            'post_type' => WOO_POINTS_LOG_POST_TYPE,
                            'post_status' => 'publish',
                            'post_parent' => 0,
                        );
                        $log_id = wp_insert_post($post_data);
                        $log_meta = array(
                            'userpoint' => '+'.abs($current_points),
                            'events' => 'manual',
                            'operation' => 'add',
                        );
                      
                        // Set log meta, if any
                        if ($log_id && !empty($log_meta)) {
                            
                            foreach ((array) $log_meta as $key => $meta) {
                                update_post_meta($log_id, '_woo_log_' . sanitize_key($key), $meta);
                            }

                            if( !empty( $ex_date ) ) {
                                update_post_meta($log_id,'_woo_pr_expiry_date',$ex_date);
                            }
                        }

                        if($_POST['import_points_action'] != 'override_user_point'){
                           
                           $UserPointBalence = $ubalance+$csv_points;
                           update_user_meta($userdata->ID,'_woo_pr_userpoints',$UserPointBalence);
                        }
                        else{
                            update_user_meta($userdata->ID,'_woo_pr_userpoints',$csv_points);
                        }

                        $queryArgs['e_type'] = 'import_success';                        
                        $result   =  array('status' => 'success','e_type'=> add_query_arg($queryArgs,$admin_url));
                    }
                }
                }
            } // END While
        }
        echo json_encode($result);
        die();
    }

    
    /**
     * Add Export CSV erors
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    public function woo_pr_add_export_csv_errors(){

         $admin_url = admin_url('admin.php');
        $queryArgs = array(
            'page'      => 'wc-settings',
            'tab'       => 'woopr-settings',
            'section'   => 'woo_pr_import_export',        
        );
        $redirect_url = add_query_arg($queryArgs,$admin_url);
        $css_class = 'error';
        if(isset($_GET['e_type']) && $_GET['e_type'] == 'no_logs'){
            $message = esc_html__('Points not found.', 'woopoints');
        }
         
        if(isset($_GET['e_type']) && $_GET['e_type'] == 'user_emp'){
            $message = esc_html__('Please select at least one user.', 'woopoints');
        }
        if(isset($_GET['e_type']) && $_GET['e_type'] == 'import_fail'){
            $message = esc_html__('Points import failed.', 'woopoints');
        }
        if(isset($_GET['e_type']) && $_GET['e_type'] == 'import_success'){
            $css_class = 'success';
            $message = esc_html__('Points imported successfully.', 'woopoints');
        }

        if(isset($message) && !empty($message)){
        ?>
        <div class="notice notice-<?php echo $css_class ?>"> 
            <p><strong><?php echo $message; ?></strong></p>
             <a  class="dismmis-notice" href="<?php echo $redirect_url ?>">Close</a>
        </div>
        <?php
        }
        
    }

    /**
     * Adding Hooks
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    function add_hooks() {

        // Add filter for adding plugin settings
        add_filter('woocommerce_get_settings_pages', 'woo_pr_admin_settings_tab');

        // Add action to product category fields
        add_action('product_cat_add_form_fields', array($this, 'woo_pr_product_category_add_fields_html'), 20, 1);
        add_action('product_cat_edit_form_fields', array($this, 'woo_pr_product_category_edit_fields_html'), 20, 1);

        // Add action to save product category fields
        add_action('edited_product_cat', array($this, 'woo_pr_save_taxonomy_product_category_meta'), 10, 1);
        add_action('create_product_cat', array($this, 'woo_pr_save_taxonomy_product_category_meta'), 10, 1);

        add_action('admin_footer', array($this, 'woo_pr_variable_bulk_admin_custom_js'), 15);
        // add a product type
        add_filter('product_type_selector', array($this, 'woo_pr_add_custom_product_type'));
        add_filter('woocommerce_product_class', array($this, 'woo_pr_woocommerce_product_class'), 10, 2);

        // add 'Points Earned' column header to the product category list table
        add_filter('manage_edit-product_cat_columns', array($this, 'woo_pr_add_product_category_list_table_points_column_header'));

        // add 'Points Earned' column content to the product category list table
        add_filter('manage_product_cat_custom_column', array($this, 'woo_pr_add_product_category_list_table_points_column'), 10, 3);

        // Add action to add metabox for product
        add_action('add_meta_boxes', array($this, 'woo_pr_product_metabox'));

        // Add action to product meta fields
        add_action('save_post', array($this, 'woo_pr_product_meta_fields_save'));

        // Add Cusom column Content
        add_action('manage_users_custom_column', array($this, 'woo_pr_show_points_column_content'), 10, 3);

        // Add Cusom column title
        add_filter('manage_users_columns', array($this, 'woo_pr_add_points_column'));

        // mark up for popup
        add_action('admin_footer-users.php', array($this, 'woo_pr_points_user_balance_popup'));

        //AJAX Call for adjust user points
        add_action('wp_ajax_woo_pr_adjust_user_points', array($this, 'woo_pr_adjust_user_points'));
        add_action('wp_ajax_nopriv_woo_pr_adjust_user_points', array($this, 'woo_pr_adjust_user_points'));

        //AJAX Call for send email to user manually
        add_action('wp_ajax_woo_pr_send_email_user_manually', array($this, 'woo_pr_send_email_user_manually'));

        // Add actions to add reset points in bulk actions
        add_action('admin_head-users.php', array($this, 'woo_pr_add_reset_points_to_bulk_actions'));

        // Add actions to reset points when reset points bulk actions performed
        add_action('load-users.php', array($this, 'woo_pr_reset_points'));

        add_action('admin_menu', array($this, 'woo_pr_users_points_log_page'), 99);

        // Add Custom field to user profile
        add_action('profile_personal_options', array($this, 'woo_pr_add_custom_user_profile_fields'));

        // Add menu in admin bar
        add_action('admin_bar_menu', array($this, 'woo_pr_tool_bar'));

        // add action to apply points to previous orders
		add_action('admin_init', array($this, 'woo_pr_apply_points_for_previous_orders'));

        // add action to apply expiration points to previous points
        add_action('admin_init', array($this, 'woo_pr_apply_expiration_points_for_previous_points'));

		// Admin notice for settings moved
        add_action('admin_notices', array($this, 'woo_pr_admin_settings_order_updated_notice'));

        add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'woo_pr_admin_order_data_after_order_details' ) );

        add_filter( 'parse_query', array( $this, 'woo_pr_points_table_expirydate_filter') );
		
		// Add Points Earned Variation  Settings
		add_action( 'woocommerce_product_after_variable_attributes', array($this,'woo_pr_variation_settings_fields'), 10, 3 );
		add_action('woocommerce_save_product_variation',array($this,'woo_pr_save_variation_settings'),10,2);


        add_action( 'wp_ajax_woo_pr_export_points_csv', array($this,'woo_pr_export_points_csv') );
        add_action( 'wp_ajax_nopriv_woo_pr_export_points_csv',array($this,'woo_pr_export_points_csv') );

        add_action( 'wp_ajax_woo_pr_import_points_csv', array($this,'woo_pr_import_points_csv') );
        add_action( 'wp_ajax_nopriv_woo_pr_import_points_csv',array($this,'woo_pr_import_points_csv') );

        add_action('admin_notices', array($this,'woo_pr_add_export_csv_errors'));
    }
}