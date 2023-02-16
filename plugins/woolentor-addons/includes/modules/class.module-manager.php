<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Module_Manager{

    private static $_instance = null;

    /**
     * Instance
     */
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){
        if( is_admin() ){
            $this->include_under_admin();
        }
        $this->include_file();
    }

    /**
     * [include_under_admin] Nessary File Required if admin page.
     * @return [void]
     */
    public function include_under_admin(){

        // Post Duplicator
        if( !is_plugin_active('ht-mega-for-elementor/htmega_addons_elementor.php') ){
            if( woolentor_get_option( 'postduplicator', 'woolentor_others_tabs', 'off' ) === 'on' ){
                require_once ( WOOLENTOR_ADDONS_PL_PATH.'includes/modules/post-duplicator/class.post-duplicator.php' );
            }
        }

    }

    /**
     * [include_file] Nessary File Required
     * @return [void]
     */
    public function include_file(){

        // Rename Label
        if( !is_admin() && woolentor_get_option( 'enablerenamelabel', 'woolentor_rename_label_tabs', 'off' ) == 'on' ){
            require( WOOLENTOR_ADDONS_PL_PATH.'includes/modules/rename-label/rename_label.php' );
        }

        // Search
        if( woolentor_get_option( 'ajaxsearch', 'woolentor_others_tabs', 'off' ) == 'on' ){
            require( WOOLENTOR_ADDONS_PL_PATH. 'includes/modules/ajax-search/base.php' );
        }

        // Sale Notification
        if( woolentor_get_option( 'enableresalenotification', 'woolentor_sales_notification_tabs', 'off' ) == 'on' ){
            if( woolentor_get_option( 'notification_content_type', 'woolentor_sales_notification_tabs', 'actual' ) == 'fakes' ){
                include( WOOLENTOR_ADDONS_PL_PATH. 'includes/modules/sales-notification/class.sale_notification_fake.php' );
            }else{
                require( WOOLENTOR_ADDONS_PL_PATH. 'includes/modules/sales-notification/class.sale_notification.php' );
            }
        }

        // Single Product Ajax cart
        if( woolentor_get_option( 'ajaxcart_singleproduct', 'woolentor_others_tabs', 'off' ) == 'on' ){
            if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
                require( WOOLENTOR_ADDONS_PL_PATH. 'includes/modules/single-product-ajax-add-to-cart/class.ajax_add_to_cart.php' );
            }
        }

        // Wishlist
        if( woolentor_get_option( 'wishlist', 'woolentor_others_tabs', 'off' ) == 'on' ){
            // $this->deactivate( 'wishsuite/wishsuite.php' );
            if( ! class_exists('WishSuite_Base') ){
                require_once( WOOLENTOR_ADDONS_PL_PATH .'includes/modules/wishlist/init.php' );
            }
        }

        // Compare
        if( woolentor_get_option( 'compare', 'woolentor_others_tabs', 'off' ) == 'on' ){
            // $this->deactivate( 'ever-compare/ever-compare.php' );
            if( ! class_exists('Ever_Compare') ){
                require_once( WOOLENTOR_ADDONS_PL_PATH .'includes/modules/compare/init.php' );
            }
        }
        
        // Shopify Style Checkout page
        if( woolentor_get_option( 'enable', 'woolentor_shopify_checkout_settings', 'off' ) == 'on' ){
            require_once( WOOLENTOR_ADDONS_PL_PATH .'includes/modules/shopify-like-checkout/class.shopify-like-checkout.php' );
        }
        
        // Variation swatch
        if( woolentor_get_option( 'enable', 'woolentor_swatch_settings', 'off' ) == 'on' ){
            $swatchly_plugin_status = is_plugin_active( 'swatchly/swatchly.php') || is_plugin_active( 'swatchly-pro/swatchly-pro.php') ? true : false;
            if( !$swatchly_plugin_status ){
                require_once( WOOLENTOR_ADDONS_PL_PATH .'includes/modules/variation-swatch/init.php' );
            }else{
                add_filter('woolentor_admin_fields',function( $fields ){
                    $element_keys = array_column( $fields['woolentor_others_tabs']['modules'], 'name' );
                    $unset_key = array_search('swatch_settings', $element_keys);
                    unset( $fields['woolentor_others_tabs']['modules'][$unset_key] );
                    return $fields;
                });
            }
        }

        // Flash Sale
        if( woolentor_get_option( 'enable', 'woolentor_flash_sale_settings', 'off' ) == 'on' ){
            require_once( WOOLENTOR_ADDONS_PL_PATH .'includes/modules/flash-sale/class.flash-sale.php' );
        }

        // Backorder
        if( woolentor_get_option( 'enable', 'woolentor_backorder_settings', 'off' ) == 'on' ){
            require_once( WOOLENTOR_ADDONS_PL_PATH .'includes/modules/backorder/class.backorder.php' );
        }

        // Pro-Modules
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') && defined( "WOOLENTOR_ADDONS_PL_PATH_PRO" ) ){

            // Partial payment
            if( ( woolentor_get_option( 'enable', 'woolentor_partial_payment_settings', 'off' ) == 'on' ) ){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/partial-payment/partial-payment.php' );
            }

            // Pre Orders
            if( ( woolentor_get_option( 'enable', 'woolentor_pre_order_settings', 'off' ) == 'on' ) ){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/pre-orders/pre-orders.php' );
            }

            // GTM Conversion tracking
            if( ( woolentor_get_option( 'enable', 'woolentor_gtm_convertion_tracking_settings', 'off' ) == 'on' ) ){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/gtm-conversion-tracking/gtm-conversion-tracking.php' );
            }

            // Size Chart
            if( (  woolentor_get_option( 'enable', 'woolentor_size_chart_settings', 'off' ) == 'on' ) ){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/size-chart/class.size-chart.php' );
            }

            // Email Customizer
            if( (  woolentor_get_option( 'enable', 'woolentor_email_customizer_settings', 'off' ) == 'on' ) ){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/email-customizer/email-customizer.php' );
            }

            // Email Automation
            if( (  woolentor_get_option( 'enable', 'woolentor_email_automation_settings', 'off' ) == 'on' ) ){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/email-automation/email-automation.php' );
            }

            // Order Bump
            if( (  woolentor_get_option( 'enable', 'woolentor_order_bump_settings', 'off' ) == 'on' ) && file_exists(WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/order-bump/order-bump.php') ){
                require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/order-bump/order-bump.php' );
            }

            // Product Filter
            $this->include_product_filter_module_file();
            

        }
        
    }

    /**
     * [include_product_filter_module_file] Include product filter module file
     * @return [void]
     */
    public function include_product_filter_module_file(){
        if( file_exists( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/product-filter/product-filter.php' ) ){
            require_once( WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/product-filter/product-filter.php' );

            if( woolentor_get_option( 'enable', 'woolentor_product_filter_settings', 'off' ) == 'on' ){
                woolentor_product_filter( true );
            } else {
                woolentor_product_filter( false );
            }
        }
    }

    /**
     * [deactivate] Deactivated
     * @return [void]
     */
    public function deactivate( $slug ){
        if( is_plugin_active( $slug ) ){
            return deactivate_plugins( $slug );
        }
    }


}

Woolentor_Module_Manager::instance();