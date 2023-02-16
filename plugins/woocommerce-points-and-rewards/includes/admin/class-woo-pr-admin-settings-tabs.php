<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

if (!class_exists('Woo_pr_Settings')) :

    /**
     * Setting page Class
     * 
     * Handles Settings page functionality of plugin
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     */
    class Woo_pr_Settings extends WC_Settings_Page {

        /**
         * Constructor
         * 
         * Handles to add hooks for adding settings
         * 
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function __construct() {

            global $woo_pr_model; // Declare global variables
			
			
		
            $this->id = 'woopr-settings'; // Get id
            $this->label = esc_html__('Points and Rewards', 'woopoints'); // Get tab label
            $this->model = $woo_pr_model; // Declare variable $this->model
            // Add filter for adding tab
            add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_page'), 20);

            // Add action to show output
            add_action('woocommerce_settings_' . $this->id, array($this, 'woo_pr_output'));

            // Add action for adding sections
            add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );

            // Add action for saving data
            add_action('woocommerce_settings_save_' . $this->id, array($this, 'woo_pr_save'));

            // Add a ratio fields woocommerce_admin_fields() field type
            add_action('woocommerce_admin_field_pr_ratio', array($this, 'render_pr_ratio_field'));
			
			// Add a label fields woocommerce_admin_fields() field type
            add_action('woocommerce_admin_field_pr_label', array($this, 'render_pr_label_field'));

            // Add a checkbox group fields woocommerce_admin_fields() field type
            add_action('woocommerce_admin_field_woopr_checkbox_group', array($this, 'render_woopr_checkbox_group'));

            // Add a apply points woocommerce_admin_fields() field type
            add_action('woocommerce_admin_field_woopr_apply_points', array($this, 'render_woopr_apply_points_section'));

            // Add a apply expiration points woocommerce_admin_fields() field type
            add_action('woocommerce_admin_field_woopr_apply_expiration_points', array($this, 'render_woopr_apply_expiration_points_section'));

            // Add a radio fields woocommerce_admin_fields() field type
            add_action('woocommerce_admin_field_woo_pr_radio', array($this, 'render_woo_pr_radio_field'));          
            add_action('woocommerce_admin_field_woo_pr_file', array($this, 'render_woo_pr_file_field'));          
             add_action('woocommerce_admin_field_woopr_export_points', array($this, 'render_woopr_export_points_section'));

            // Add a woopr textarea woocommerce_admin_fields() field type
            add_action('woocommerce_admin_field_woopr_textarea', array($this, 'render_woopr_textarea_section'));
            add_action('woocommerce_admin_field_woopr_textarea_editor', array($this, 'render_woopr_textarea_editor_section'));
            
            // Add a filter to allow html on setting value
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_single_product_message', array($this, 'woo_pr_allow_html_store'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_earn_points_cart_message', array($this, 'woo_pr_allow_html_store'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_redeem_points_cart_message', array($this, 'woo_pr_allow_html_store'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_guest_checkout_page_message', array($this, 'woo_pr_allow_html_store'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_guest_checkout_page_buy_message', array($this, 'woo_pr_allow_html_store'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_guest_user_history_message', array($this, 'woo_pr_allow_html_store'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_minimum_points_required_message', array($this, 'woo_pr_allow_html_store'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_expiration_notice_message', array($this, 'woo_pr_allow_html_store'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_user_history_points_message', array($this, 'woo_pr_allow_html_store'),10,3);
			add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_user_first_purchase_points_message', array($this, 'woo_pr_allow_html_store'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_user_first_purchase_points_cart_message', array($this, 'woo_pr_allow_html_store'),10,3);
			
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_earn_point_email_content', array($this, 'woo_pr_allow_html_store_with_p'),10,3);           
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_redeem_point_email_content', array($this, 'woo_pr_allow_html_store_with_p'),10,3);
            add_filter('woocommerce_admin_settings_sanitize_option_woo_pr_expire_point_email_content', array($this, 'woo_pr_allow_html_store_with_p'),10,3);
            
			
            

			
            // Add a woopr rating points woocommerce_admin_fields() field type
            add_action('woocommerce_admin_field_woopr_ratingpoints', array($this, 'render_woopr_ratingpoints_section'));

            add_action( 'woocommerce_admin_field_woopr_days', array( $this, 'woo_pr_render_days_callback' ) );
        }

        /**
         * Handles to return html allow values to allowed html tags on setting message
         * 
         * @package WooCommerce - Points and Rewards
         * @since 1.1.1
         */
        public function woo_pr_allow_html_store( $value, $option, $raw_value){

            $value = wp_kses_post( trim( $raw_value ) );

            return $value;
        }
		
		
		
		/**
         * Handles to return html allow values to allowed html tags on setting message with p tags
         * 
         * @package WooCommerce - Points and Rewards
         * @since 1.0.2
         */
        public function woo_pr_allow_html_store_with_p( $value, $option, $raw_value){

            $value = apply_filters( 'the_content', wp_kses_post( trim( $raw_value ) ) );

            return $value;
        }
		

        /**
         * Handles to add sections for Settings tab
         * 
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function get_sections() {

            // Create array
            $sections = array(
                '' => esc_html__('General', 'woopoints'),
                'woo_pr_product_purchase_setting' => esc_html__('Earned Points', 'woopoints'),
                'woo_pr_redeeming_points_setting' => esc_html__('Redeeming Points', 'woopoints'),
                'woo_pr_expiration_setting_points' => esc_html__('Points Expiry', 'woopoints'),
                'woo_pr_messages_setting_points' => esc_html__('Message', 'woopoints'),
                'woo_pr_earning_setting_points' => esc_html__('Actions to Earn Points', 'woopoints'),
                'woo_pr_email_settings' => esc_html__('Email', 'woopoints'),
                'woo_pr_import_export' => esc_html__('Import/Export Points', 'woopoints'),
                'woo_pr_misc_setting_points' => esc_html__('Misc Settings', 'woopoints')
            );

            return apply_filters('woo_pr_setting_sections', $sections);
        }

        /**
         * Handles to output data
         * $sections
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function woo_pr_output() {

            // Get global variable
            global $current_section;

            // Get settings for current section
            $settings = $this->get_settings($current_section);
            WC_Admin_Settings::output_fields($settings);
        }

        /**
         * Handles to save data
         * 
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function woo_pr_save($option) {

            global $current_section;

            $settings = $this->get_settings($current_section);
            WC_Admin_Settings::save_fields($settings);
        }

        /**
         * Handles to get setting
         * 
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function get_settings($current_section = '') {

            if( $current_section == 'woo_pr_product_purchase_setting'){
				

                  global $wp_roles;
                $exclude_role_options = array();
                // set option for role exclude
                if( !empty( $wp_roles->role_names ) ) {

                    foreach ($wp_roles->role_names as $role_key => $role_name) {

                        if( $role_key !='administrator' ){
                            $exclude_role_options[$role_key] = $role_name;
                        }
                    }
                }

                	
				$settings = apply_filters('woo_pr_product_purchase_setting', array(
					//Setting title
					array(
						'id'    => 'pr_product_purchase_settings',
						'name'  => esc_html__('Earned Points Settings', 'woopoints'),
						'type'  => 'title'
					),
                    //Exclude Categories
                    array(
                        'id'        => 'woo_pr_include_exclude_categories_type',
                        'type'      => 'woo_pr_radio',
                        'options' => array(
                            'exclude' => esc_html__('Exclude', 'woopoints'),
                            'include' => esc_html__('Include', 'woopoints'),
                            ),
                        'default'   => 'include',
                    ),
                    array(
                        'name'      => esc_html__('Exclude / Include Categories:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Select categories that you want to exclude or include when users earn points.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_exc_inc_categories_points',                        
                        'class'     => 'wc-category-search exc_inc_categories',                     
                        'type'      => 'multiselect',
                        'options'   => $this->excluded_categories('earn')
                    ),
                    //Exclude Products
                    array(
                        'id'        => 'woo_pr_include_exclude_products_type',
                        'type'      => 'woo_pr_radio',
                        'options' => array(
                            'exclude' => esc_html__('Exclude', 'woopoints'),
                            'include' => esc_html__('Include', 'woopoints')
                            ),
                        'default'   => 'include',
                    ),
                    array(
                        'name'      => esc_html__('Exclude / Include Products:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Select products that you want to exclude or include when users earn points.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_exclude_products_points',                        
                        'class'     => 'wc-product-search exc_inc_products',                     
                        'type'      => 'multiselect',
                        'options'   => $this->excluded_products('earn')
                    ),
                     array(
                        'name'      => esc_html__('Exclude User Roles:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Select the user roles that you want to exclude for earning the points.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_exclude_roles_points',                        
                        'class'     => 'wc-enhanced-select',                     
                        'type'      => 'multiselect',
                        'options'   => $exclude_role_options
                    ),
					 //Enable Tax Points
                    array(
                        'name'      => esc_html__('Enable Earn Points With TAX:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Enable this option to calculate points earned by user for product purchase including TAX.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_enable_tax_points',
                        'default'   => '',
                        'css'       => 'width: 280px; height: 24px;',
                        'type'      => 'checkbox',
                    ),
                    //Enable First Purchase
                    array(
                        'name'      => esc_html__('Enable Earn Points for First Purchase:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Enable this option to give earn points on first purchase.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_enable_first_purchase_points',
                        'default'   => '',
                        'type'      => 'checkbox',
                    ),
                    array(
                        'name'      => esc_html__('Earn Points For First Purchase:', 'woopoints'),
                        'id'        => 'woo_pr_first_purchase_earn_points',
                        'desc'      => '<p class="description">Enter the earn points on first purchasing. </p>',
                        'css'       => 'width: 100px',
                        'type'      => 'number',
                        'custom_attributes' => array(
                            'min'  => 0,
                            'step' => 1,
                        ),
                    ),
					array(
                        'name'      => esc_html__('Minimum Cart Total to Earn Points:', 'woopoints'),
                        'id'        => 'woo_pr_minimum_cart_total_earn',
						'desc'		=> get_woocommerce_currency_symbol().'<p class="description">Enter the minimum cart total value required for customer to earn points for purchasing a product. </p>',
                        'default'   => '',
                        'css'       => 'width: 100px',
                        'type'      => 'number',
                        'custom_attributes' => array(
                            'min'  => 0,
                            'step' => 1,
                        ),
                    ),
					array(
						'name'      => esc_html__('Minimum Cart Total Error Message:', 'woopoints'),
						'desc'      => esc_html__('Displayed on the cart and checkout page when cart total does not meet the "Minimum Cart Total to Earn Points" you set. Customize the message using {carttotal} and {points_label}. Limited HTML is allowed.', 'woopoints'),
						'id'        => 'woo_pr_minimum_cart_total_earn_error_msg',
						'css'       => 'width: 99%; height: 100px;',
						'default'   => sprintf(esc_html__('You need Minimum of {carttotal} cart total to Earn {points_label}!', 'woopoints'), '{carttotal} {points_label}'),
						'type'      => 'woopr_textarea',
					),
					array(
						'type'  => 'sectionend', 
						'id'    => 'pr_product_purchase_settings'
					),
				));
			}
			elseif( $current_section == 'woo_pr_redeeming_points_setting'){		
				
                 global $wp_roles;
                $exclude_role_options = array();
                
                // set option for role exclude
                if( !empty( $wp_roles->role_names ) ) {

                    foreach ($wp_roles->role_names as $role_key => $role_name) {
                        if( $role_key !='administrator' ){
                          $exclude_role_options[$role_key] = $role_name;
                        }
                    }
                }
                
				$settings = apply_filters('woo_pr_redeeming_points_setting', array(
					//Setting title
					array(
						'id'    => 'pr_redeeming_points_settings',
						'name'  => esc_html__('Redeeming Points Settings', 'woopoints'),
						'type'  => 'title'
					),
                    //Exclude Categories
                    array(
                        'id'        => 'woo_pr_redd_include_exclude_categories_type',
                        'type'      => 'woo_pr_radio',
                        'options' => array(
                            'exclude' => esc_html__('Exclude', 'woopoints'),
                            'include' => esc_html__('Include', 'woopoints') 
                            ),
                        'default'   => 'include',
                    ),
                    array(
                        'name'      => esc_html__('Exclude / Include Categories:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Select categories that you want to exclude or include when users redeem points.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_redd_exc_inc_categories_points',                        
                        'class'     => 'wc-category-search exc_inc_categories',                     
                        'type'      => 'multiselect',
                        'options'   => $this->excluded_categories('redeem')
                    ),
                    //Exclude Products
                    array(
                        'id'        => 'woo_pr_redd_include_exclude_products_type',
                        'type'      => 'woo_pr_radio',
                        'options' => array(
                            'exclude' => esc_html__('Exclude', 'woopoints'),
                            'include' => esc_html__('Include', 'woopoints')),
                        'default'   => 'include',
                    ),
                    array(
                        'name'      => esc_html__('Exclude / Include Products:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Select products that you want to exclude or include when users redeem points.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_redd_exclude_products_points',                        
                        'class'     => 'wc-product-search exc_inc_products',                     
                        'type'      => 'multiselect',
                        'options'   => $this->excluded_products('redeem')
                    ),
                     array(
                        'name'      => esc_html__('Exclude User Roles:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Select the user roles that you want to exclude for redeeming the points.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_exclude_roles_redeem_points',                        
                        'class'     => 'wc-enhanced-select',                     
                        'type'      => 'multiselect',
                        'options'   => $exclude_role_options
                    ),
                    //Maximum cart discount field
                    array(
                        'name'      => esc_html__('Maximum Cart Discount:', 'woopoints'),
                        'desc'      => get_woocommerce_currency_symbol().'<p class="description">' . esc_html__('Set the maximum cart discount allowed for redeeming points. Leave blank to disable.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_cart_max_discount',
                        'default'   => '',
                        'css'       => 'width: 280px; height: 24px;',
                        'type'      => 'text',
                    ),
					//Maximum per product discount
                    array(
                        'name'      => esc_html__('Maximum Per-Product Discount:', 'woopoints'),
                        'desc'      => get_woocommerce_currency_symbol(). '<p class="description">' . esc_html__('Set the maximum per-product discount allowed for the cart when redeeming points. Leave blank to disable.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_per_product_max_discount',
                        'default'   => '',
                        'css'       => 'width: 280px; height: 24px;',
                        'type'      => 'text',
                    ),
					 //Minimum points required
                    array(
                        'name'      => esc_html__('Minimum Points Discount:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Enter the minimum points required for customer to get discount on cart. Leave blank to disable.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_minimum_points',
                        'default'   => '',
                        'css'       => 'width: 100px',
                        'type'      => 'number',
                        'custom_attributes' => array(
                            'min'  => 0,
                            'step' => 1,
                        ),
                    ),
					// Minimum points required message field
					array(
						'id'        => 'woo_pr_minimum_points_required_message',
						'name'      => esc_html__('Minimum Points Required Error Message:', 'woopoints'),
						'desc'      => sprintf(esc_html__('Displayed on the cart and checkout page when customer doesn\'t have minumum required points to get discount on cart. Customize the message using %s and %s. Limited HTML is allowed.', 'woopoints'), '{minimum_points}', '{points_label}'),
						'css'       => 'width: 99%; height: 100px;',
						'default'   => sprintf(esc_html__('Minimum %s %s required to get discount on cart.', 'woopoints'), '{minimum_points}', '{points_label}'),
						'type'      => 'woopr_textarea'
					),
					array(
                        'name'      => esc_html__('Minimum Cart Total to Redeem Points:', 'woopoints'),
                        'id'        => 'woo_pr_minimum_cart_total_redeem',
						'desc'		=> get_woocommerce_currency_symbol().'<p class="description">'.esc_html__('Enter the minimum cart total value required for customer to redeem available points','woopoints').'</p>',
                        'default'   => '',
                        'css'       => 'width: 100px',
                        'type'      => 'number',
                        'custom_attributes' => array(
                            'min'  => 0,
                            'step' => 1,
                        ),
                    ),
					array(
						'name'      => esc_html__('Minimum Cart Total to Redeem Error Message:', 'woopoints'),
						'desc'      => esc_html__('Displayed on the cart and checkout page when cart total does not meet the "Minimum Cart Total to Redeem Points" you set. Customize the message using {carttotal} and {point_label}. Limited HTML is allowed.', 'woopoints'),
						'id'        => 'woo_pr_minimum_cart_total_redeem_err_msg',
						'css'       => 'width: 99%; height: 100px;',
						'default'   => sprintf(esc_html__('You need minimum cart Total of {carttotal} in order to Redeem {point_label}!', 'woopoints'), '{carttotal} {points_label}'),
						'type'      => 'woopr_textarea',
					),
					// Automatic Points Redeeming in Cart Page
					array(
                        'title'     => esc_html__('Enable Automatic Points Redeeming in Cart Page:', 'woopoints'),
                        'desc'      => esc_html__("Check this box if you want available points to be automatically applied on cart to get a discount.", 'woopoints'),
                        'id'        => 'woo_pr_enable_automatic_redeem_point_cart_page',
                        'default'   => 'no',
                        'type'      => 'checkbox'
                    ),
					// Prevent Coupon Usage if point is applied
					array(
                        'title'     => esc_html__('Prevent Coupon Usage When Points Are Redeemed:', 'woopoints'),
                        'desc'      => esc_html__("Check this box if you want to prevent coupon usage when points are redeemed.", 'woopoints'),
                        'id'        => 'woo_pr_prevent_coupon_usag',
                        'default'   => 'no',
                        'type'      => 'checkbox'
                    ),

          // Apply discount on cart total
          array(
                        'title'     => esc_html__('Apply Discount on Cart Total:', 'woopoints'),
                        'desc'      => esc_html__("Check this box to apply points on cart total. i.e. Points will gets applied on the TAX as well.", 'woopoints'),
                        'id'        => 'woo_pr_discount_on_carttotal',
                        'default'   => 'no',
                        'type'      => 'checkbox'
            ),			
					
					array(
						'type'  => 'sectionend', 
						'id'    => 'pr_redeeming_points_settings'
					),
				));
			}
            elseif( $current_section == 'woo_pr_expiration_setting_points'){
                
                $settings = apply_filters('woo_products_point_expiration_settings', array(
                     //Setting title
                    array(
                        'id'    => 'pr_points_expiration_settings',
                        'name'  => esc_html__('Points Expiration Settings', 'woopoints'),
                        'type'  => 'title'
                    ),
                    //Enable Expiration Points
                    array(
                        'name'      => esc_html__('Enable Points Expiration:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Check this box if you want to enable points expiry feature. By default the points can be used lifetime.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_enable_points_expiration',
                        'default'   => '',
                        'css'       => 'width: 280px; height: 24px;',
                        'type'      => 'checkbox',
                    ),
                    //Enable Never Expiration Points for product of points
                    array(
                        'name'      => esc_html__('Bought Points Never Expire:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__("Check this box if you don't want points expiration for Bought Points.", 'woopoints') . '</p>',
                        'id'        => 'woo_pr_enable_never_points_expiration_purchased_points',
                        'default'   => '',
                        'css'       => 'width: 280px; height: 24px;',
                        'type'      => 'checkbox',
                    ),
                    //Enable Never Expiration Points for product of points
                    array(
                        'name'      => esc_html__('Earned Points by Selling Never Expire:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__("Check this box if you don't want expiration of points earned by Selling Products.", 'woopoints') . '</p>',
                        'id'        => 'woo_pr_enable_never_points_expiration_sell_points',
                        'default'   => '',
                        'css'       => 'width: 280px; height: 24px;',
                        'type'      => 'checkbox',
                    ),
                    //Validity Period for Points
                    array(
                        'name'      => esc_html__('Validity Period for Points:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Earned points will expire after the number of days specified. The number of days will be calculated from the date of earning points.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_validity_period_days',
                        'css'       => 'width: 50px',
                        'type'      => 'woopr_days',
                        'min'  => 1,
                        'step' => 1,
                        'options'   => esc_html__('Days', 'woopoints')
                    ),
                    //Button for all previous points
                    array(
                        'title'         => esc_html__('Apply Points Expiration to Previously Earned Points:', 'woopoints'),
                        'desc'          => esc_html__('Apply points expiration on points that are already earned.', 'woopoints'),
                        'button_text'   => esc_html__('Apply Points Expiration', 'woopoints'),
                        'type'          => 'woopr_apply_expiration_points',
                        'id'            => 'woo_pr_apply_expiration_previous_points',
                        'class'         => 'wc-points-rewards-apply-button',
                    ),
                    //Enable Expiration Points Notice
                    array(
                        'name'      => esc_html__('Enable Points Expiration Notice:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Check this box if you want to show points expiration notice in my account dashboard.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_enable_notice_points_expiration',
                        'default'   => '',
                        'css'       => 'width: 280px; height: 24px;',
                        'type'      => 'checkbox',
                    ),
                    // Set expiration notice days
                    array(
                        'name'      => esc_html__('Set Expiration Notice Days:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('It will display point expiration notice before number of specified days.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_expiration_notice_days',
                        'css'       => 'width: 50px',
                        'type'      => 'woopr_days',
                        'min'  => 0,
                        'step' => 1,
                        'options'   => esc_html__('Days', 'woopoints')
                    ),
                    //Expiration Notice Message field
                    array(
                        'name'      => esc_html__('Expiration Notice Message:', 'woopoints'),
                        'desc'      => sprintf(esc_html__('Displayed on my account dashboard page when points are about to expire. Customize the message using %s, %s and %s. Limited HTML is allowed.', 'woopoints'), '{points}', '{points_label}', '{expiry_days}'),
                        'id'        => 'woo_pr_expiration_notice_message',
                        'css'       => 'width: 99%; height: 100px;',
                        'default'   => sprintf(esc_html__('Your %s %s expiry in next %s days.', 'woopoints'), '{points}', '{points_label}','{expiry_days}'),
                        'type'      => 'woopr_textarea',
                    ),
                    array(
                        'type'  => 'sectionend', 
                        'id'    => 'pr_points_expiration_settings'
                    )
                ));
            }
            elseif( $current_section == 'woo_pr_messages_setting_points'){
                $settings = apply_filters('woo_products_point_message_settings', array(
                        //Setting title
                        array(
                            'id'    => 'pr_points_messages_settings',
                            'name'  => esc_html__('Product / Cart / Checkout Messages', 'woopoints'),
                            'type'  => 'title'
                        ),
                        //Single product page message field
                        array(
                            'name'      => esc_html__('Earn Point(s) Message on Single Product Page:', 'woopoints'),
                            'desc'      => esc_html__('Add an optional message to the single product page below the price. Customize the message using {points} and {points_label}. Limited HTML is allowed. Leave blank to disable.', 'woopoints'),
                            'id'        => 'woo_pr_single_product_message',
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('Purchase this product now and earn %s!', 'woopoints'), '{points} {points_label}'),
                            'type'      => 'woopr_textarea',
                        ),
                        //Single product page product type "points" message field
                        array(
                            'name'      => esc_html__('Buy Point(s) Message on Single Product Page:', 'woopoints'),
                            'desc'      => esc_html__('Add an optional message to the single product page below the price. Customize the message using {points} and {points_label}. Limited HTML is allowed. Leave blank to disable.', 'woopoints'),
                            'id'        => 'woo_pr_by_points_single_product_message',
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('Purchase this product to fund %s into your account.', 'woopoints'), '{points} {points_label}'),
                            'type'      => 'woopr_textarea',
                        ),
                        // earn points cart/checkout page message
                        array(
                            'name'      => esc_html__('Earn Point(s) Message on Cart / Checkout Page:', 'woopoints'),
                            'desc'      => esc_html__('Displayed on the cart and checkout page when points are earned. Customize the message using {points} and {points_label}. Limited HTML is allowed.', 'woopoints'),
                            'id'        => 'woo_pr_earn_points_cart_message',
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('Complete your order and earn %s for a discount on a future purchase', 'woopoints'), '{points} {points_label}'),
                            'type'      => 'woopr_textarea',
                        ),
                        // redeem points cart/checkout page message
                        array(
                            'name'      => esc_html__('Redeem Point(s) Message on Cart / Checkout Page:', 'woopoints'),
                            'desc'      => esc_html__('Displayed on the cart and checkout page when points are available for redemption. Customize the message using {points}, {points_value}, and {points_label}. Limited HTML is allowed.', 'woopoints'),
                            'id'        => 'woo_pr_redeem_points_cart_message',
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('Use %s for a %s discount on this order!', 'woopoints'), '{points} {points_label}', '{points_value}'),
                            'type'      => 'woopr_textarea',
                        ),
                        //Guest checkout page message field
                        array(
                            'id'        => 'woo_pr_guest_checkout_page_message',
                            'desc'      => esc_html__('Displayed on the cart and checkout page for guest users to indicate them to create an account for earning points. Customize the message using {points}, {points_label} and {signup_points}. Limited HTML is allowed.', 'woopoints'),
                            'name'      => esc_html__('Earn Point(s) Message on Cart / Checkout Page for Guest Users:', 'woopoints'),
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('You need to register an account in order to earn %s', 'woopoints'), ' {points} {points_label}'),
                            'type'      => 'woopr_textarea'
                        ),
                        //Guest checkout page buy message field
                        array(
                            'id'        => 'woo_pr_guest_checkout_page_buy_message',
                            'desc'      => esc_html__('Displayed on the cart and checkout page for guest users to indicate to create an account to get points into their account. Customize the message using {points} and {points_label}. Limited HTML is allowed.', 'woopoints'),
                            'name'      => esc_html__('Buy Point(s) Message on Cart / Checkout Page for Guest Users:', 'woopoints'),
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('You need to register an account in order to fund %s into your account.', 'woopoints'), ' {points} {points_label}'),
                            'type'      => 'woopr_textarea'
                        ),
                        // user history message field
                        array(
                            'id'        => 'woo_pr_guest_user_history_message',
                            'desc'      => esc_html__('Displayed points history message for guest users to indicate to login into an account to view points of their account. Customize the message using {points_label}. Limited HTML is allowed.', 'woopoints'),
                            'name'      => esc_html__('Earn point(s) message on points history page for guest users:', 'woopoints'),
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('Sorry, You have not earned any %s yet.', 'woopoints'), '{points_label}'),
                            'type'      => 'woopr_textarea'
                        ),                     

                        // Points history message field
                        // added code since 1.1.2
                        array(
                            'id'        => 'woo_pr_user_history_points_message',
                            'name'      => esc_html__('Earn point(s) message on points history page for logged in users:', 'woopoints'),
                            'desc'      => sprintf(esc_html__('Displayed available points and discount on top of the points history page. Customize the message using %s, %s and %s. Limited HTML is allowed.', 'woopoints'), '{points}', '{points_label}','{points_amount}'),
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('You have %s %s, which are worth a discount of %s amount.', 'woopoints'), '{points}', '{points_label}', '{points_amount}'),
                            'type'      => 'woopr_textarea'
                        ),

                        // First Purchase Points message field
                        // added code since 1.2.3

                        array(
                            'id'        => 'woo_pr_user_first_purchase_points_message',
                            'name'      => esc_html__('Earn Point(s) Message on Single Product Page for first purchase:', 'woopoints'),
                            'desc'      => esc_html__('Displayed on the single product page when points for first purchase are earned. Customize the message using {points} and {points_label}. Limited HTML is allowed.', 'woopoints'),
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('Purchase this product now and earn %s %s for first purchase!', 'woopoints'), '{points}', '{points_label}'),
                            'type'      => 'woopr_textarea'
                        ),
                         array(
                            'id'        => 'woo_pr_user_first_purchase_points_cart_message',
                            'name'      => esc_html__('Earn Point(s) Message on Cart / Checkout Page for first purchase:', 'woopoints'),
                            'desc'      => esc_html__('Displayed on the cart and checkout page when points for first purchase are earned. Customize the message using {points} and {points_label}. Limited HTML is allowed.', 'woopoints'),
                            'css'       => 'width: 99%; height: 100px;',
                            'default'   => sprintf(esc_html__('Complete your first purchase to earn %s %s!', 'woopoints'), '{points}', '{points_label}'),
                            'type'      => 'woopr_textarea'
                        ),
                        array(
                            'type'  => 'sectionend', 
                            'id'    => 'pr_points_messages_settings'
                        )
                ));
            }
            elseif( $current_section == 'woo_pr_earning_setting_points'){
                $settings = apply_filters('woo_products_point_earning_settings', array(
                        //Setting title
                        array(
                            'id'    => 'pr_points_earn_action_settings',
                            'name'  => esc_html__('Actions to Earn Points', 'woopoints'),
                            'type'  => 'title'
                        ),
                        //Button for all previous order
                        array(
                            'title'         => esc_html__('Apply Points to Previous Orders:', 'woopoints'),
                            'desc'          => esc_html__('By clicking on apply points button, You can generate points for orders that have been placed before activating the plugin.', 'woopoints'),
                            'button_text'   => esc_html__('Apply Points', 'woopoints'),
                            'type'          => 'woopr_apply_points',
                            'id'            => 'woo_pr_apply_points_to_previous_orders',
                            'class'         => 'wc-points-rewards-apply-button',
                        ),
                        array(
                            'id'        => 'woo_pr_enable_account_signup',
                            'title'     => esc_html__('Enable Account Signup Points:', 'woopoints'),
                            'desc'      => esc_html__('Check this box if you want to assign points to customers when they signup', 'woopoints'),
                            'default'   => 'no',
                            'type'      => 'checkbox'
                        ),
                        //Points earn for signup field
                        array(
                            'name'      => esc_html__('Account Signup Points:', 'woopoints'),
                            'desc'      =>  '<span class="clear-both">'.esc_html__('Enter the number of points earned when a customer signs up for a new account.', 'woopoints').'</span>',
                            'type'      => 'text',							
                            'id'        => 'woo_pr_earn_for_account_signup',
                            'default'   => '500'
                        ),
                        
						array(
                            'id'        => 'woo_pr_enable_reviews',
                            'title'     => esc_html__('Enable Points for Reviewing Product:', 'woopoints'),
                            'desc'      => esc_html__('Check this box if you want to assign points to customers when they add a review on any product.', 'woopoints'),
                            'default'   => 'no',
                            'type'      => 'checkbox'
                        ),
                        array(
                            'id'        => 'woo_pr_review_points',
                            'class'     => 'woo_pr_review_points',
                            'title'     => esc_html__('Points for Reviewing Product:', 'woopoints'),
                            'desc'      => esc_html__('Enter the number of points earned when a customer add a review on any product.', 'woopoints'),
                            'type'      => 'woopr_ratingpoints',
                            'css'       => 'width: 80px;',
                        ),
                        array(
                            'id'        => 'woo_pr_enable_post_creation_points',
                            'title'     => esc_html__('Enable Blog Post Creation Points:', 'woopoints'),
                            'desc'      => esc_html__('Check this box if you want to assign points to users when they create new blog post.', 'woopoints'),
                            'default'   => 'no',
                            'type'      => 'checkbox'
                        ),
                        array(
                            'name'      => esc_html__('Blog Post Creation Points:', 'woopoints'),
                            'id'        => 'woo_pr_post_creation_points',
                            'desc'      => '<p class="description">'.esc_html__('Enter the number of points earned for blog creation.','woopoints').'</p>',
                            'css'       => 'width: 100px',
                            'type'      => 'number',
                            'custom_attributes' => array(
                                'min'  => 0,
                                'step' => 1,
                            ),
                        ),
                        array(
                            'id'        => 'woo_pr_enable_product_creation_points',
                            'title'     => esc_html__('Enable Product Creation Points:', 'woopoints'),
                            'desc'      => esc_html__('Check this box if you want to assign points to users when they create new product.', 'woopoints'),
                            'default'   => 'no',
                            'type'      => 'checkbox'
                        ),
                        array(
                            'name'      => esc_html__('Product Creation Points:', 'woopoints'),
                            'id'        => 'woo_pr_product_creation_points',
                            'desc'      => '<p class="description">'.esc_html__('Enter the number of points earned for product creation.', 'woopoints').'</p>',
                            'css'       => 'width: 100px',
                            'type'      => 'number',
                            'custom_attributes' => array(
                                'min'  => 0,
                                'step' => 1,
                            ),
                        ),
                        array(
                            'id'        => 'woo_pr_enable_daily_login_points',
                            'title'     => esc_html__('Enable Daily Login Points:', 'woopoints'),
                            'desc'      => esc_html__('Check this box if you want to assign points to users when they login.', 'woopoints'),
                            'default'   => 'no',
                            'type'      => 'checkbox'
                        ),
                        array(
                            'name'      => esc_html__('Daily Login Points:', 'woopoints'),
                            'id'        => 'woo_pr_daily_login_points',
                            'desc'      => '<p class="description">Enter the amount of points earned for daily login.</p>',
                            'css'       => 'width: 100px',
                            'type'      => 'number',
                            'custom_attributes' => array(
                                'min'  => 0,
                                'step' => 1,
                            ),
                        ),
                        array(
                            'type'  => 'sectionend', 
                            'id'    => 'pr_points_earn_action_settings'
                        )
                ));
            }
            elseif( $current_section == 'woo_pr_misc_setting_points'){
				 $plural_label = get_option('woo_pr_lables_points_monetary_value');
				$pointslabel = isset( $plural_label ) && !empty( $plural_label )
								? ucfirst($plural_label) : esc_html__( 'Points', 'woopoints' );
								
                $settings = apply_filters('woo_products_point_misc_settings', array(
                    //Setting title
                    array(
                        'id'    => 'pr_points_misc_settings',
                        'name'  => esc_html__('Misc Settings', 'woopoints'),
                        'type'  => 'title'
                    ),
                    //Checkbox for delete all data form database
                    array(
                        'title'     => esc_html__('Delete Options:', 'woopoints'),
                        'id'        => 'woo_pr_delete_options',
                        'default'   => 'no',
                        'desc'      => '<p>'.esc_html__('If you don\'t want to use the points and rewards plugin on your site anymore, you can check the delete options box. This makes sure, that all the settings and tables are being deleted from the database when you deactivate the plugin.', 'woopoints').'</p>',
                        'type'      => 'checkbox'
                    ),
                    //Checkbox for refund order with points refunds
                    array(
                        'title'     => esc_html__('Enable Points Removal for Refunded Orders:', 'woopoints'),
                        'desc'      => esc_html__('Specify whether you want to refund earned and redeemed points when order gets refunded.   ', 'woopoints'),
                        'id'        => 'woo_pr_revert_points_refund_enabled',
                        'default'   => 'no',
                        'type'      => 'checkbox'
                    ),
					//  Checkbox for show my Points tab to my account page
					 array(
                        'title'     => sprintf(esc_html__('Hide My %s Tab from My Accounts Page:', 'woopoints'),$pointslabel),
                        'desc'      => sprintf(esc_html__("Check this box if you want to hide my %s tab from the my accounts page.", 'woopoints'),strtolower($pointslabel)),
                        'id'        => 'woo_pr_show_my_points_tab',
                        'default'   => 'no',
                        'type'      => 'checkbox'
                    ),					
                    array(
                        'type'  => 'sectionend', 
                        'id'    => 'pr_points_misc_settings'
                    )
                ));
            }
            elseif( $current_section == 'woo_pr_import_export'){
                
                $users_options = array();
                $usersdata = get_users();               
                    
                // set option for role exclude
                if( !empty( $usersdata ) ) {
                    
                    foreach ($usersdata as $user_key => $userdata) {                        
                        $users_options[$userdata->ID] = $userdata->user_login;                        
                    }
                } 
                
                
                
                 $settings = apply_filters('woo_products_point_import_export_settings', array(
                    //Setting title
                    array(
                        'id'    => 'pr_points_import_export_settings',
                        'name'  => esc_html__('Export Points', 'woopoints'),
                        'type'  => 'title'
                    ),
                    //Radio button for Export available Points for
                    array(
                        'title'     => esc_html__('Export Available Points For:', 'woopoints'),
                        'id'        => 'woo_pr_export_points_for',
                        'class'     => 'woo_pr_export_points_for',
                        'default'   => 'all_users',
                        'options'         => array(
                            'all_users'     => __( 'All Users', 'woopoints' ),
                            'selected_users'      => __( 'Selected Users', 'woopoints'),                            
                        ),                       
                        'type'      => 'woo_pr_radio',
                        'desc'      => '<p>'.esc_html__('Here you can set whether to Export Points for All Users or Selected Users.', 'woopoints').'</p>',
                    ), 
                    array(
                        'title'     => esc_html__('Select The Users:', 'woopoints'),
                        'id'        => 'woo_pr_export_points_for_selected_users',
                        'type'      => 'multiselect',                       
                        'class'      => 'wc-enhanced-select',
                         'options'   => $users_options
                    ),
                     //Radio button for Users are identified based on
                    array(
                        'title'     => esc_html__('Users Are Identified Based On:', 'woopoints'),
                        'id'        => 'woo_pr_export_points_identified_user',
                        'default'   => 'username',
                        'options'         => array(
                            'username'       => __( 'Username', 'woopoints' ),
                            'email'          => __( 'Email', 'woopoints'),                          
                        ),                       
                        'type'      => 'woo_pr_radio',
                        'desc'      => '<p>'.esc_html__('Here you can set whether to Export CSV Format with Username or Email', 'woopoints').'</p>',
                    ),
                    
                    //Radio button for Users are identified based on
                    array(
                        'title'     => esc_html__('Export User Points For:', 'woopoints'),
                        'id'        => 'woo_pr_export_points_time',
                        'default'   => 'all_time',
                        'class'     =>  'woo_pr_export_points_time',
                        'options'         => array(
                            'all_time'        => __( 'All Time', 'woopoints' ),
                            'selected_date'   => __( 'Selected Date', 'woopoints'),                         
                        ),                       
                        'type'      => 'woo_pr_radio',
                        'desc'      => '<p>'.esc_html__('Here you can set whether to Export Points for All Time or Selected Date', 'woopoints').'</p>',
                    ),                  
                    array(
                        'title'     => esc_html__('Start Date:', 'woopoints'),
                        'id'        => 'woo_pr_export_points_start_date',                                       
                        'type'      => 'text',
                        'class'     => 'datepicker',
                        'custom_attributes' =>array(
                            'autocomplete' => 'off'
                        )
                    ),
                      array(
                        'title'     => esc_html__('End Date:', 'woopoints'),
                        'id'        => 'woo_pr_export_points_end_date',                                     
                        'type'      => 'text',
                        'class'     => 'datepicker',
                         'custom_attributes' =>array(
                            'autocomplete' => 'off'
                        )                    
                    ),              
                    
                    
                    //Button for all export points
                    array(
                        'title'         => esc_html__('Export User Points To CSV:', 'woopoints'),                           
                        'button_text'   => esc_html__('Export User Points', 'woopoints'),
                        'type'          => 'woopr_export_points',
                        'id'            => 'woo_pr_export_user_point',
                        'class'         => 'wc-points-rewards-apply-button',
                    ),
                        
                    array(
                        'type'  => 'sectionend', 
                        'id'    => 'pr_points_import_export_settings'
                    ),
                     array(
                        'id'    => 'pr_points_import_settings',
                        'name'  => esc_html__('Import Points', 'woopoints'),
                        'type'  => 'title'
                    ),
                    array(
                        'title'     => esc_html__('Select CSV To Import:', 'woopoints'),
                        'id'        => 'woo_pr_import_csv_file',                        
                        'class'     =>  'woo_pr_import_csv_file',                                             
                        'type'      => 'woo_pr_file',
                    ),
                    array(
                        'title'     => esc_html__('Import User Points:', 'woopoints'),
                        'id'        => 'woo_pr_import_csv_action',
                        'default'   => 'override_user_point',
                        'class'     =>  'woo_pr_import_csv_action',
                        'options'   => array(
                            'override_user_point'      => __( 'Override Existing User Points', 'woopoints' ),
                            'add_points_with_earned'   => __( 'Add Points with Already Earned Points', 'woopoints'),                         
                        ),                       
                        'type'      => 'woo_pr_radio',                        
                    ),
                    //Button for all export points
                    array(
                        'title'         => esc_html__('Import User Points:', 'woopoints'),                           
                        'button_text'   => esc_html__('Import User Points', 'woopoints'),
                        'type'          => 'woopr_export_points',
                        'id'            => 'woo_pr_import_user_point',
                        'class'         => 'wc-points-rewards-apply-button',
                    ),
                    array(
                        'type'  => 'sectionend', 
                        'id'    => 'pr_points_import_export_settings'
                    ),
                ));
                
            }
			 elseif( $current_section == 'woo_pr_email_settings'){
                
                $settings = apply_filters('woo_products_point_email_settings', array(
                        //Checkbox for product review points
                        //Setting title
						// Start Expire points email settings	
						array( 
                            'id'    => 'pr_points_email_settings_for_expire',
                            'name'  => esc_html__('Points Expiration - Email Settings', 'woopoints'),
                            'type'  => 'title'
                        ),						
						array(
                            'id'        => 'woo_pr_enable_expire_point_email',
							'class'		=> 'woo_pr_switch',
                            'title'     => esc_html__('Enable Points Expiry Reminder Email :', 'woopoints'),
                            'desc'      => esc_html__('', 'woopoints'),
                            'default'   => 'no',
                            'type'      => 'checkbox'
                        ),
						array(
                            'id'        => 'woo_pr_expire_point_email_before_day',
                            'title'     => esc_html__('Days Before Points Expire:','woopoints'),
                            'desc'      => sprintf( esc_html__('%sNumber of days before point expiration when the email will be sent.%s', 'woopoints'),'<p class="description">','</p>'),      
							'default'   => 1,
                            'type'      => 'number',
							'custom_attributes' => array(
								'min'  => 1,
								'step' => 1,
							),
                        ),
						array(
                            'id'        => 'woo_pr_expire_point_email_subject',
                            'title'     => esc_html__('Email Subject:', 'woopoints'),
                            'desc'      => '<p class="description">'.sprintf ( esc_html__('This is the subject of the email, The available template tags are: %s - displays site url %s - displays site title','woopoints'),'<br><code>{site_url}</code>', '<br><code>{site_title}</code>').'</p>',
							'default'   => esc_html__('Your Points on {site_url} Are About to Expire','woopoints'),
                            'type'      => 'text'
                        ),
						array(
                            'id'        => 'woo_pr_expire_point_email_content',							
							'class'		=> 'woopr_textarea_tinymce',
                            'title'     => esc_html__('Email Content:', 'woopoints'),
                            'desc'      => '<p class="description">'.sprintf ( esc_html__('This is the content of the email that will be sent to the customers, The available template tags are: %s - displays username of the customer %s - displays total expiring points %s - display points label %s - display points expiry date %s - display site url %s - display site title %s - display expiry days','woopoints'),'<br><code>{username}</code>', '<br><code>{expiring_points}</code>', '<br><code>{point_label}</code>','<br><code>{expiring_date}</code>','<br><code>{site_url}</code>','<br><code>{site_title}</code>','<br><code>{expiry_days}</code>').'</p>',      
							'default'   => sprintf(__('%1$s Hi {username}, %2$s
%1$s This email is to remind you that you have {expiring_points} on {site_url} that are about to expire in next {expiry_days} days. %2$s %3$s {expire_points_details}','woopoints'),'<p>','</p>', '<br><br>'),
                            'type'      => 'woopr_textarea_editor',
							
						),		
						array(
                            'type'  => 'sectionend', 
                            'id'    => 'pr_points_email_settings_for_expire'
                        ),
						// END Expire points email settings	
						
						
                        array(
                            'id'    => 'woo_pr_points_earn_email_settings',
                            'name'  => esc_html__('Earned Points - Email Settings ', 'woopoints'),
                            'type'  => 'title'
                        ),						
                        array( 
                            'id'        => 'woo_pr_enable_earn_points_email',
							'class'		=> 'woo_pr_switch',
                            'title'     => esc_html__('Enable Points Earned Email:', 'woopoints'),
                            'desc'      => esc_html__('', 'woopoints'),
                            'default'   => 'no',
                            'type'      => 'checkbox'
                        ),
                        array(
                        	'title' 	=> esc_html__('Actions for Earned Points:', 'woopoints'),
                        	'type'		=> 'woopr_checkbox_group',
                        	'id'		=> 'woo_pr_enable_earn_email_actions',
                        	'fields'	=> array(
                        		array( 
		                            'id'        => 'woo_pr_enable_earn_email_actions[woo_pr_enable_earn_points_email_for_purchase_product]',		 
		                            'title'     => esc_html__('Purchase Product', 'woopoints'),
		                            'default'   => 'yes',
		                        ),
		                        array( 
		                            'id'        => 'woo_pr_enable_earn_email_actions[woo_pr_enable_earn_points_email_for_seller]',		 
		                            'title'     => esc_html__('Seling Points', 'woopoints'),
		                            'default'   => 'yes',
		                        ),
		                        array( 
		                            'id'        => 'woo_pr_enable_earn_email_actions[woo_pr_enable_earn_points_email_for_signup]',		 
		                            'title'     => esc_html__('Signup Points', 'woopoints'),
		                            'default'   => 'yes',
		                        ),
		                        array( 
		                            'id'        => 'woo_pr_enable_earn_email_actions[woo_pr_enable_earn_points_email_for_rate_product]',		 
		                            'title'     => esc_html__('Reviewing Product', 'woopoints'),
		                            'default'   => 'yes',
		                        ),
		                        array( 
		                            'id'        => 'woo_pr_enable_earn_email_actions[woo_pr_enable_earn_points_email_for_review_status_change]',		 
		                            'title'     => esc_html__('Review Status Change', 'woopoints'),
		                            'default'   => 'yes',
		                        ),
		                        array( 
		                            'id'        => 'woo_pr_enable_earn_email_actions[woo_pr_enable_earn_points_email_for_post_creation]',		 
		                            'title'     => esc_html__('Blog Post Creation', 'woopoints'),
		                            'default'   => 'yes',
		                        ),
		                        array( 
		                            'id'        => 'woo_pr_enable_earn_email_actions[woo_pr_enable_earn_points_email_for_product_creation]',		 
		                            'title'     => esc_html__('Product Creation', 'woopoints'),
		                            'default'   => 'yes',
		                        ),
		                        array( 
		                            'id'        => 'woo_pr_enable_earn_email_actions[woo_pr_enable_earn_points_email_for_daily_login]',		 
		                            'title'     => esc_html__('Daily Login', 'woopoints'),
		                            'default'   => 'yes',
		                        ),

                            array( 
                                'id'        => 'woo_pr_enable_earn_email_actions[woo_pr_enable_earn_points_email_for_first_product_purchase]',     
                                'title'     => esc_html__('First Product Purchase', 'woopoints'),                                
                                'default'   => 'yes',
                            ),

                        	),
                        ), 					
						array(
                            'id'        => 'woo_pr_earn_point_subject',
                            'title'     => esc_html__('Email Subject:', 'woopoints'),
                            'desc'      => '<p class="description">'.sprintf ( esc_html__('This is the Subject of the email, The available template tags are: %s - displays latest update  %s - displays earned points %s - displays site url  %s - displays site title ','woopoints'),'<br><code>{latest_update}</code>','<br><code>{earned_point}</code>','<br><code>{site_url}</code>','<br><code>{site_title}</code>').'</p>',
							'default'   => esc_html__("Congratulations! You've Earned Points","woopoints"),
                            'type'      => 'text'
                        ), 
						array(
                            'id'        => 'woo_pr_earn_point_email_content',
							'css'       => '',
                            'title'     => esc_html__('Email Content:', 'woopoints'),
                            'desc'      => '<p class="description">'.sprintf ( esc_html__('This is the Content of the email, The available template tags are: 
							%s - displays username
							%s - displays poins label
							%s - displays earned points
							%s - displays latest update
							%s - displays current point balance with total amount							
							%s - displays site url					
							%s - displays site title					
							','woopoints'),'<br><code>{username}</code>','<br><code>{point_label}</code>','<br><code>{earned_point}</code>','<br><code>{latest_update}</code>','<br><code>{total_point}</code>','<br><code>{site_url}</code>','<br><code>{site_title}</code>').'</p>',
							'default'   => sprintf(__(' %1$s Hi {username},%2$s
%1$s Below  you can find  latest updates about your {point_label} on {site_url} %2$s
%1$s You have earned {earned_point} {point_label} for the {latest_update}  %2$s
%1$s Your current balance is {total_point} %2$s','woopoints'),'<p>','</p>'),
                            'type'      => 'woopr_textarea_editor'
                        ),			
                        array(
                            'type'  => 'sectionend', 
                            'id'    => 'woo_pr_points_earn_email_settings'
                        ),// END Product Parchange section		
			

						
						// Start debit points email settings	
						array( 
                            'id'    => 'pr_points_email_settings_for_redeem',
                            'name'  => esc_html__('Redeemed Points - Email Settings', 'woopoints'),
                            'type'  => 'title'
                        ),						
						array(
                            'id'        => 'woo_pr_enable_redeem_email',
                            'title'     => esc_html__('Enable Points Redeemed Email:', 'woopoints'),
                            'desc'      => esc_html__('', 'woopoints'),
                            'default'   => 'no',
							'class'		=> 'woo_pr_switch',
                            'type'      => 'checkbox'
                        ),
						array(
                            'id'        => 'woo_pr_redeem_point_email_subject',
                            'title'     => esc_html__('Email Subject:', 'woopoints'),
                            'desc'      => '<p class="description">'.sprintf ( esc_html__('This is the Subject of the email, The available template tags are: %s - displays latest update %s - displays redeemed point %s - displays site url %s - displays site title ','woopoints'),'<br><code>{latest_update}</code>','<br><code>{redeemed_point}</code>','<br><code>{site_url}</code>','<br><code>{site_title}</code>').'</p>', 
							'default'   => esc_html__('Rewards Redemption','woopoints'),
                            'type'      => 'text'
                        ),
						array(
                            'id'        => 'woo_pr_redeem_point_email_content',
							'css'       => 'width: 45%; height: 100px;',
                            'title'     => esc_html__('Email Content:', 'woopoints'),
                            'desc'      => '<p class="description">'.sprintf ( esc_html__('This is the Content of the email, The available template tags are: 
							%s - displays username
							%s - displays poins label
							%s - displays latest update
							%s - displays redeemed point
							%s - displays current point balance with total amount							
							%s - displays site url					
							%s - displays site title					
							','woopoints'),'<br><code>{username}</code>','<br><code>{point_label}</code>','<br><code>{latest_update}</code>','<br><code>{redeemed_point}</code>','<br><code>{total_point}</code>','<br><code>{site_url}</code>','<br><code>{site_title}</code>').'</p>',

							'default'   => sprintf(__('%1$s Hi {username}, %2$s
%1$s Below  you can find  latest updates about your {point_label} on {site_url} %2$s
%1$s You have redeemed {redeemed_point} {point_label} for the {latest_update} %2$s
%1$s Your current balance is {total_point}%2$s','woopoints'),'<p>','</p>'),
                            'type'      => 'woopr_textarea_editor'
                        ),		
						array(
                            'type'  => 'sectionend', 
                            'id'    => 'pr_points_email_settings_for_redeem'
                        ),
						// END debit points email settings							
                ));
            }
            else {

                global $wp_roles;
                $exclude_role_options = array();
                // set option for role exclude
                if( !empty( $wp_roles->role_names ) ) {

                    foreach ($wp_roles->role_names as $role_key => $role_name) {

                        if( $role_key !='administrator' ){
                            $exclude_role_options[$role_key] = $role_name;
                        }
                    }
                }
				
                $settings = apply_filters('woo_products_point_settings', array(
                    array(
                        'id'    => 'pr_points_top_settings',
                        'name'  => esc_html__('General Settings', 'woopoints'),
                        'type'  => 'title'
                    ),
					 // Singular lable field
                    array(
                        'id'        => 'woo_pr_lables_points_monetary_value',
                        'default'   => 'Points',
                        'type'      => 'hidden',
                    ),
                    //pluaral lable field
                    array(
                        'name'      => esc_html__('Points Label:', 'woopoints'),
                        'desc'      => esc_html__('The label used to refer the points on the frontend, singular and plural.', 'woopoints'),
                        'desc'      => esc_html__('The label used to refer the points on the frontend, singular and plural.', 'woopoints'),
                        'id'        => 'woo_pr_lables_points',
                        'default'   => 'Point',
                        'type'      => 'pr_ratio'
                    ),
                   
                    //Enable Decimal in Points
                    array(
                        'name'      => esc_html__('Enable Decimal in Points:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('Enable the decimal points when points are awarded to customer.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_enable_decimal_points',
                        'default'   => '',
                        'css'       => 'width: 280px; height: 24px;',
                        'type'      => 'checkbox',
                    ),
                    //Number of Decimals
                    array(
                        'name'      => esc_html__('Number of Decimals:', 'woopoints'),
                        'desc'      => '<p class="description">' . esc_html__('This sets the number of decimal points.', 'woopoints') . '</p>',
                        'id'        => 'woo_pr_number_decimal',
                        'default'   => '2',
                        'css'       => 'width: 50px',
                        'type'      => 'number',
                        'custom_attributes' => array(
                            'min'  => 0,
                            'step' => 1,
                        ),
                    ),
					
					 array(
                    'type'  => 'sectionend',
                    'id'    => 'pr_points_top_settings'
                    ),
					//Setting title
                    array(
                        'id'    => 'pr_points_general_settings',
                        'name'  => esc_html__('Conversion Settings', 'woopoints'),
                        'type'  => 'title'
                    ),
                    //Ratio field for earn points
                    array(
                        'id'        => 'woo_pr_ratio_settings_points_monetary_value',
                        'default'   => '1',
                        'type'      => 'hidden',
                    ),
                    //point field for earn points
                    array(
                        'title'     => esc_html__('Earning Points Conversion Rate:', 'woopoints'),
                        'desc'      => esc_html__('Set the number of points awarded based on the product price. Leave it blank to disable.', 'woopoints'),
                        'id'        => 'woo_pr_ratio_settings_points',
                        'default'   => '1',
                        'type'      => 'pr_ratio'
                    ),
                    //Ratio field for redeem points
                    array(
                        'id'        => 'woo_pr_redeem_points_monetary_value',
                        'default'   => '1',
                        'type'      => 'hidden',
                    ),
                    //Point field for redeem points
                    array(
                        'name'      => esc_html__('Redeeming Points Conversion Rate:', 'woopoints'),
                        'desc'      => esc_html__('Set the value of points redeemed for a discount. Leave it blank to disable.', 'woopoints'),
                        'id'        => 'woo_pr_redeem_points',
                        'default'   => '100',
                        'type'      => 'pr_ratio'
                    ),
                    //Ratio field for buy points
                    array(
                        'id'        => 'woo_pr_buy_points_monetary_value',
                        'default'   => '1',
                        'type'      => 'hidden',
                    ),
                    //Point field for buy points
                    array(
                        'name'      => esc_html__('Buying Points Conversion Rate:', 'woopoints'),
                        'desc'      => esc_html__('Set the value for buying points. Leave it blank to disable.', 'woopoints'),
                        'id'        => 'woo_pr_buy_points',
                        'default'   => '100',
                        'type'      => 'pr_ratio'
                    ),
                    //Ratio field for selling points
                    array(
                        'id'        => 'woo_pr_selling_points_monetary_value',
                        'default'   => '1',
                        'type'      => 'hidden',
                    ),
                    //Point field for selling points
                    array(
                        'name'      => esc_html__('Selling Points Conversion Rate:', 'woopoints'),
                        'desc'      => esc_html__('Set the value for selling points. Leave it blank to disable.', 'woopoints'),
                        'id'        => 'woo_pr_selling_points',
                        'default'   => '1',
                        'type'      => 'pr_ratio'
                    ),
                   
                    
					array(
						'type'  => 'sectionend',
						'id'    => 'pr_points_general_settings'
                    ),
					
					// Shortcodes used in plugin Setting title
                    array(
                        'id'    => 'pr_points_shortcode_use_title',
                        'name'  => esc_html__('Shortcodes used in plugin', 'woopoints'),
                        'type'  => 'title'
                    ),
					array(
                        'name'      => esc_html__('[woopr_points_history]', 'woopoints'),
                        'desc'      => esc_html__('Show Customers Their Credit Balance.', 'woopoints'),                      
                        'type'      => 'pr_label'
                    ),
					array(
						'type'  => 'sectionend',
						'id'    => 'pr_points_shortcode_use_title'
                    ),
                   
                ));
            }

            return apply_filters('woocommerce_get_settings_'.$this->id, $settings, $current_section);
        }
	
        /**
         * Hendle to get all status
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function get_all_status(){

            $all_status = wc_get_order_statuses();
            unset($all_status['wc-cancelled'],$all_status['wc-refunded'],$all_status['wc-failed']);

            return $all_status;
        }

		/**
         * Hendle to get excluded products
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
		public function excluded_products( $points_type ){
            if ($points_type == 'earn') {
               $excluded_products = get_option('woo_pr_exclude_products_points');
            }else{
                $excluded_products = get_option('woo_pr_redd_exclude_products_points');
            }
			
            $excluded_products = !empty( $excluded_products ) ? $excluded_products : array();
            
			$excluded_products_opt = array();
			if( !empty($excluded_products) && is_array($excluded_products) ){
				
				foreach($excluded_products as $key=> $product_id ){					
					$product_obj = wc_get_product( $product_id);
					if( !empty( $product_obj ) && is_object( $product_obj )){
					   $excluded_products_opt[ $product_id ] = $product_obj->get_formatted_name();					
                    }			
				}
			}
			return $excluded_products_opt;
		}


        /**
         * Hendle to get excluded categories
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function excluded_categories( $points_type ){
                    
            if ($points_type == 'earn') {
               $ex_inc_categories = get_option('woo_pr_exc_inc_categories_points');
            }else{
                $ex_inc_categories = get_option('woo_pr_redd_exc_inc_categories_points');
            }
            
            $ex_inc_categories = !empty( $ex_inc_categories ) ? $ex_inc_categories : array();            
            $ex_inc_categories_opt = array();
            if( !empty($ex_inc_categories) && is_array($ex_inc_categories) ){
                
                foreach($ex_inc_categories as $key=> $category_slug ){     
                   
                    $category_obj = get_term_by('slug',$category_slug,'product_cat');

                    if( !empty( $category_obj ) && is_object( $category_obj )){
                     
                        $option_content = $category_obj->name.' '.'('.$category_obj->count.')';
                       $ex_inc_categories_opt[ $category_slug ] = $option_content;                  
                    }           
                }
            }            
            return $ex_inc_categories_opt;
        }


        /**
         * Render the 'Woopr Textarea' section
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function render_woopr_textarea_section( $field ) {

            $option_value      = get_option( $field['id'], $field['default'] );
            $description       = ( isset($field['desc']) && !empty($field['desc']) ) ? '<p class="woopr-textarea-section-desc">' . wp_kses_post( $field['desc'] ) . '</p>' : '';
            $tooltip_html      = ( isset($field['desc_tip']) && !empty($field['desc_tip']) ) ? wc_help_tip( $field['desc_tip'] ) : '' ;
            $custom_attributes = array();

            // Custom attribute handling.
            if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
                foreach ( $field['custom_attributes'] as $attribute => $attribute_value ) {
                    $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
                }
            }
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?></label>
                    <?php echo $tooltip_html; ?>
                </th>
                <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $field['type'] ) ); ?>">
                    
                    <textarea
                        name="<?php echo esc_attr( $field['id'] ); ?>"
                        id="<?php echo esc_attr( $field['id'] ); ?>"
                        style="<?php echo esc_attr( $field['css'] ); ?>"
                        class="<?php echo esc_attr( $field['class'] ); ?>"
                        placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"
                        <?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
                        ><?php echo esc_textarea( $option_value ); // WPCS: XSS ok. ?></textarea>
                    <?php echo $description; ?>
                </td>
            </tr>
            <?php
        }
		 /**
         * Render the 'Woopr Textarea Editor' section
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function render_woopr_textarea_editor_section( $field ) {

            $option_value      = get_option( $field['id'], $field['default'] );
            $description       = ( isset($field['desc']) && !empty($field['desc']) ) ? '<p class="woopr-textarea-section-desc">' . wp_kses_post( $field['desc'] ) . '</p>' : '';
            $tooltip_html      = ( isset($field['desc_tip']) && !empty($field['desc_tip']) ) ? wc_help_tip( $field['desc_tip'] ) : '' ;
            $custom_attributes = array();

            // Custom attribute handling.
            if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
                foreach ( $field['custom_attributes'] as $attribute => $attribute_value ) {
                    $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
                }
            }
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?></label>
                    <?php echo $tooltip_html; ?>
                </th>
                <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $field['type'] ) ); ?>">
                    
					<?php
						$editor_args = array(
							   'textarea_rows' => 5,
							    'textarea_rows' => 2,
							   'teeny' => true,
						);
						wp_editor($option_value,$field['id'], array('wpautop' => true ) );
					?>
					<?php echo $description; ?>
                </td>
            </tr>
            <?php
        }
		

        /**
         * Render the 'Woopr Rating Points' section
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function render_woopr_ratingpoints_section( $field ) {

            $option_value      = get_option( $field['id'], $field['default'] );

            $description       = ( isset($field['desc']) && !empty($field['desc']) ) ? '<p class="woopr-ratingpoints-section-desc">' . wp_kses_post( $field['desc'] ) . '</p>' : '';
            $tooltip_html      = ( isset($field['desc_tip']) && !empty($field['desc_tip']) ) ? wc_help_tip( $field['desc_tip'] ) : '' ;
            $custom_attributes = array();

            // Custom attribute handling.
            if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
                foreach ( $field['custom_attributes'] as $attribute => $attribute_value ) {
                    $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
                }
            }
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?></label>
                    <?php echo $tooltip_html; ?>
                </th>
                <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $field['type'] ) ); ?>">
                    <?php
                    for ( $star_num = 5; $star_num >= 1; $star_num-- ) {

                        $val = isset( $option_value[$star_num] ) ? $option_value[$star_num] : '';

                        echo '<div class="woo_pr_sub_field_item"><fieldset>';

                        //Display Star description
                        for ( $i = 1; $i <= 5; $i++ ) {
                            $star_filled = ( $star_num >= $i ) ? 'dashicons-star-filled' : 'dashicons-star-empty';
                            echo '<span class="dashicons '. $star_filled .'"></span>';
                        }

                        echo '&nbsp;&nbsp;<input 
                        name="'. esc_attr( $field['id'] ).'['.$star_num.']" 
                        id="'.esc_attr( $field['id'] ).'-star-'.$star_num.'" 
                        style="'.esc_attr( $field['css'] ).'"
                        class="'.esc_attr( $field['class'] ).'"
                        placeholder="'.esc_attr( $field['placeholder'] ).'"
                        type="number" min="0" 
                        value="'.esc_attr( $val ).'" />&nbsp;&nbsp;';
                        echo "<span>". esc_html__( ' Point(s)', 'woopoints' ) ."</span>";
                        echo '</fieldset></div>';
                    }
                    ?>
                    <?php echo $description; ?>
                </td>
            </tr>
            <?php
        }

        /**
         * Render the Earn Points/Redeem Points conversion ratio section
         *
         * @package WooCommerce - Points and Rewards
         * @param array $field associative array of field parameters
         * @since 1.0.0
         */
        public function render_pr_ratio_field($field) {

            // If field title is not empty and field id is not empty
            if (isset($field['title']) && isset($field['id'])) :

                $points = get_option($field['id'], $field['default']);
                $monetary_value = get_option($field['id'] . '_monetary_value');
                ?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for=""><?php echo wp_kses_post($field['title']); ?></label>
                        <?= ( isset($field['desc_tip']) && !empty($field['desc_tip']) ) ? wc_help_tip( $field['desc_tip'] ) : '' ; ?>
                    </th>
                    <td class="forminp forminp-text">
                        <?php if ($field['id'] != 'woo_pr_lables_points') { ?>

                            <fieldset>
                                <input name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>" type="number" class="woopr-ratio-max-width" min="0" value="<?php echo esc_attr($points); ?>" />
                                <span>&nbsp;<?php esc_html_e('Points', 'woopoints'); ?>&nbsp;&#61;&nbsp;&nbsp;<?php echo get_woocommerce_currency_symbol(); ?></span>
                                <input name="<?php echo esc_attr($field['id'] . '_monetary_value'); ?>" id="<?php echo esc_attr($field['id'] . '_monetary_value'); ?>" type="number" min="0" class="woopr-ratio-max-width" value="<?php echo esc_attr($monetary_value); ?>" />
                                <br>
                                <label for="<?php echo $field['id']; ?>"><?php echo wp_kses_post($field['desc']); ?></label>
                            </fieldset>

                        <?php } if ($field['id'] == 'woo_pr_lables_points') { ?>

                            <fieldset>
                                <input name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>" type="text" class="woopr-ratio-max-width" value="<?php echo esc_attr($points); ?>" />
                                <input name="<?php echo esc_attr($field['id'] . '_monetary_value'); ?>" id="<?php echo esc_attr($field['id'] . '_monetary_value'); ?>" type="text" class="woopr-ratio-max-width" value="<?php echo esc_attr($monetary_value); ?>" />
                                <br>
                                <label for=""><?php echo wp_kses_post($field['desc']); ?></label>
                            </fieldset>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
            endif;
        }
		
		/**
         * Render the radio button
         *
         * @package WooCommerce - Points and Rewards
         * @param array $field associative array of field parameters
         * @since 1.0.0
         */
        public function render_woo_pr_radio_field($field) {
              $option_value = get_option($field['id'], $field['default']);
              $custom_attributes = (!empty($field['custom_attributes'])) ? $field['custom_attributes'] : array();
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?></label>
                </th>
                <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $field['type'] ) ); ?>">
                    <fieldset>                      
                        
                        <?php
                        foreach ( $field['options'] as $key => $val ) {
                            ?>                          
                                <label><input
                                    name="<?php echo esc_attr( $field['id'] ); ?>"
                                    value="<?php echo esc_attr( $key ); ?>"
                                    type="radio"
                                    style="<?php echo esc_attr( $field['css'] ); ?>"
                                    class="<?php echo esc_attr( $field['class'] ); ?>"
                                    <?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
                                    <?php checked( $key, $option_value ); ?>
                                    /> <?php echo esc_html( $val ); ?></label>&nbsp;&nbsp;
                            
                            <?php
                        }
                        ?>
                        
                        <?php echo $field['desc']; // WPCS: XSS ok. ?>

                    </fieldset>
                </td>
            </tr>
            <?php
           
        }


        /**
         * Render the file input
         *
         * @package WooCommerce - Points and Rewards
         * @param array $field associative array of field parameters
         * @since 1.0.0
         */
        public function render_woo_pr_file_field($field) {
              $option_value = get_option($field['id'], $field['default']);
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?></label>
                </th>
                <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $field['type'] ) ); ?>">
                    <fieldset>                      
                        
                        <input type="file" name="<?php echo esc_attr( $field['id'] ); ?>"  style="<?php echo esc_attr( $field['css'] ); ?>" class="<?php echo esc_attr( $field['class'] ); ?>">                                                
                        
                        <?php echo $field['desc']; // WPCS: XSS ok. ?>
                    </fieldset>
                </td>
            </tr>
            <?php
           
        }
		
         /**
         * Render the 'Apply Points to all previous orders' section
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function render_woopr_export_points_section($field) {
            if (isset($field['title']) && isset($field['button_text']) && isset($field['id'])) :
                ?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="export_points"><?php echo wp_kses_post($field['title']); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <fieldset>
                            <a href="#" class="button" id="<?php echo $field['id']; ?>"><?php echo esc_html($field['button_text']); ?></a>
                        </fieldset>
                    </td>

                </tr>
                <?php
           endif;
        }
        
		 /**
         * Render the label section
         *
         * @package WooCommerce - Points and Rewards
         * @param array $field associative array of field parameters
         * @since 1.0.0
         */
        public function render_pr_label_field($field) {

            // If field title is not empty and field id is not empty
            if (isset($field['title']) && isset($field['id'])) :

                $points = get_option($field['id'], $field['default']);
                $monetary_value = get_option($field['id'] . '_monetary_value');
                ?>
                <tr valign="top">
                    <td scope="row" class="label-row" >
                        <p><i><strong><code><?php echo wp_kses_post($field['title']); ?></code></strong></i>- <span><?= ( isset($field['desc']) && !empty($field['desc']) ) ?  $field['desc'] : '' ; ?></span> </p>						
                    </td>
                </tr>
                <?php
            endif;
        }

        /**
         * Render the 'Apply Points to all previous orders' section
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function render_woopr_apply_points_section($field) {
            if (isset($field['title']) && isset($field['button_text']) && isset($field['id'])) :
                ?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="apply_points"><?php echo wp_kses_post($field['title']); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <fieldset>
                            <a href="<?php echo esc_url(add_query_arg(array('points_action' => 'apply_points'))); ?>" class="button woo-pr-points-apply-disocunts-prev-orders" id="<?php echo $field['id']; ?>"><?php echo esc_html($field['button_text']); ?></a>
                            <?php if( isset( $field['desc']) && !empty( $field['desc'] ) ){?>
                                <p class="description"><?php echo esc_html($field['desc']); ?></p>
                            <?php }?>
                        </fieldset>
                    </td>

                </tr>
                <?php
           endif;
        }

        /**
         * Render the 'Apply Points to all previous orders' section
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        public function render_woopr_apply_expiration_points_section($field) {
            if (isset($field['title']) && isset($field['button_text']) && isset($field['id'])) :
                ?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="apply_expiration_points"><?php echo wp_kses_post($field['title']); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <fieldset>
                            <a href="<?php echo esc_url(add_query_arg(array('points_action' => 'apply_expiration_points'))); ?>" class="button woo-pr-apply-expiration-previous-points" id="<?php echo $field['id']; ?>"><?php echo esc_html($field['button_text']); ?></a>
                            <span class="description">
                                <p class="description"><?php echo $field['desc'];?></p>
                            </span>    
                        </fieldset>
                    </td>

                </tr>
                <?php
           endif;
        }

        /**
         * custom field callback function for input type number with days text
         *
         * @package WooCommerce - Points and Rewards
         * @since 3.6.4
         */
        public function woo_pr_render_days_callback( $field ) {

            global $woocommerce;
        
            if ( isset( $field['title'] ) && isset( $field['id'] ) ) :

                $filetype   = isset( $field['options'] ) ? $field['options'] : '';
                $file_val   = get_option( $field['id']);
                $file_val   = !empty($file_val) ? $file_val : '';
                ?>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
                        </th>
                        <td class="forminp forminp-text">
                            <fieldset>
                                <input step="<?php if( isset( $field['step'] ) ){ echo esc_attr( $field['step']  ); } ?>" style="<?php if( isset( $field['css'] ) ){ echo esc_attr( $field['css']  ); } ?>" min="<?php if( isset( $field['min'] ) ){ echo esc_attr( $field['min']  ); } ?>" name="<?php echo esc_attr( $field['id']  ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" type="number" value="<?php echo esc_attr( $file_val ); ?>" /> <?php echo $filetype;?>
                            </fieldset>
                            <span class="description"><?php echo $field['desc'];?></span>
                        </td>
                    </tr>
                <?php

            endif;
        }

        /**
         * custom field callback function for checkbox group
         *
         * @package WooCommerce - Points and Rewards
         * @since 3.6.4
         */
        public function render_woopr_checkbox_group( $field ){

        	if ( isset( $field['title'] ) && isset( $field['fields'] ) ) {

        	?>

        	<tr valign="top" class="woopr-checkbox-group">
				<th scope="row" class="titledesc">
					<label><?php echo wp_kses_post( $field['title'] ); ?></label>
				</th>
				<td>
					
					<?php

					foreach ($field['fields'] as $key => $value) {
						
						$this->render_woopr_checkbox( $value );

					}

					?>
                    <p class="description" style="display: block; width: 100%; float: left;"><?php echo esc_html__('Click on the actions for which you would like to send Points Earned email.', 'woopoints');?></p>
				</td>
			</tr>

        	<?php

        	}

        }

        /**
         * custom field callback function for input type horizontal checkbox
         *
         * @package WooCommerce - Points and Rewards
         * @since 3.6.4
         */
        public function render_woopr_checkbox( $field ){

        	if ( isset( $field['id'] ) && isset( $field['title'] ) ) {

        		$description  = '';
				$custom_attributes = array();
				$get_option_value = get_option( 'woo_pr_enable_earn_email_actions' );
				$field_name = str_replace("woo_pr_enable_earn_email_actions[", "", $field['id']);
				$field_name = str_replace("]", "", $field_name);
				$option_value = ( isset( $get_option_value[$field_name] ) ) ? $get_option_value[$field_name] : '';

				if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
					foreach ( $field['custom_attributes'] as $attribute => $attribute_value ) {
						$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
					}
				}

				if ( isset(  $field['desc'] ) && !empty( $field['desc'] ) ) {
					$description = $field['desc'];
				}

				$description = wp_kses_post( $description );
        	
        	?>

        	<div class="woopr-custom-checkbox">
				<fieldset>
					<input
							name="<?php echo esc_attr( $field['id'] ); ?>"
							id="<?php echo esc_attr( $field['id'] ); ?>"
							type="checkbox"
							
							value="yes"
							<?php checked( $option_value, 'yes' ); ?>
							<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
						/> 
					<label for="<?php echo esc_attr( $field['id'] ); ?>" class="woopr-custom-checkbox-label" ><?php echo wp_kses_post( $field['title'] ); ?></label>
					<p><?php echo $description; ?></p> 
				</fieldset>
			</div>

        	<?php

        	}

        }	

    }

    endif;

return new Woo_pr_Settings();
