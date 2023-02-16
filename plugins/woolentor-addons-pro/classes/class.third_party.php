<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

/**
* Third party
*/
class WooLentorProThirdParty{

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Base]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){
        $checkout_page_id = method_exists( 'Woolentor_Template_Manager', 'get_template_id' ) ? Woolentor_Template_Manager::instance()->get_template_id( 'productcheckoutpage', 'woolentor_get_option_pro' ) : '0';
        if( !empty( $checkout_page_id ) && class_exists('WC_Checkout_Add_Ons_Loader') ){
            add_filter( 'wc_checkout_add_ons_position', array( $this,'change_add_ons_pos' ), 10, 1 );
        }
    }

    /**
     * [change_add_ons_pos] Support woocommerce-checkout-add-ons
     * @param  [string] $position
     * @return [string]
     */
    public function change_add_ons_pos( $position ){
        if( 'woocommerce_checkout_billing' === $position ){
            $position = 'woocommerce_after_checkout_billing_form';
        }elseif( 'woocommerce_checkout_before_customer_details' === $position ){
            $position = 'woocommerce_before_checkout_billing_form';
        }elseif( 'woocommerce_checkout_after_customer_details' === $position ){
            $position = 'woolentor_before_checkout_order';
        }
        return $position;
    }

    /**
     * [woof_filter]
     * @param  [array] $args query argument
     * @return [array] query argument
     */
    public function support_filter( $args ){

        $queries =[];
        $new_queries = [];
        parse_str( $_SERVER['QUERY_STRING' ], $queries );
        foreach ( $queries as $key => $querie ) {
            $new_queries[] = $key;
        }

        if ( isset( $_GET['swoof'] ) || isset( $_GET['wlfilter'] ) ) {

            if( isset( $_GET['product_cat'] ) ){
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $_GET['product_cat'] ),
                    'include_children' => true
                );
            }

            if( isset( $_GET['product_tag'] ) ){
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $_GET['product_tag'] ),
                    'include_children' => true
                );
            }

            if( isset( $_GET['product_visibility'] ) ){
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => explode( ',', $_GET['product_visibility'] ),
                    'operator' => ( $_GET['product_visibility'] === 'exclude-from-catalog' ? 'NOT IN' : 'IN' ),
                );
            }

            // Filter By Attribute
            if( isset( $new_queries[1] ) && !in_array( $new_queries[1], [ 'wlsort', 'wlorder_by', 'min_price', 'max_price' ] ) ){
                $attr_pre_str = substr( $new_queries[1], 0, 6 );
                if( 'filter' === $attr_pre_str ){
                    $taxonomy = str_replace('filter', 'pa', $new_queries[1] );
                }else{
                    $taxonomy = $new_queries[1];
                }
                if( isset( $_GET[$new_queries[1] ] ) ){
                    $args['tax_query'][] = array(
                        'taxonomy' => $taxonomy,
                        'field' => 'name',
                        'terms' => explode( ',', $_GET[$new_queries[1]] ),
                    );
                }
            }

            // WooLentor Filter
            if( isset( $_GET['wlorder_by'] ) ){
                if( in_array( $_GET['wlorder_by'], [ '_price', 'total_sales', '_wc_average_rating' ] ) ) {
                    $args['meta_key']   = $_GET['wlorder_by'];
                    $args['orderby']    = 'meta_value_num';
                }else if( $_GET['wlorder_by'] === 'featured' ){
                    $args['tax_query'][] = array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => explode( ',', $_GET['wlorder_by'] ),
                        'operator' => ( $_GET['wlorder_by'] === 'exclude-from-catalog' ? 'NOT IN' : 'IN' ),
                    );
                }else{
                    $args['orderby'] = $_GET['wlorder_by'];
                }
            }
            if( isset( $_GET['wlsort'] ) ){
                $args['order'] = $_GET['wlsort'];
            }

        }

        // WooCommerce Default Filter
        if( isset( $new_queries[0] ) ){
            $attr_pre_str = substr( $new_queries[0], 0, 6 );
            if( 'filter' === $attr_pre_str ){
                $taxonomy = str_replace('filter', 'pa', $new_queries[0] );
                if( isset( $_GET[$new_queries[0] ] ) ){
                    $args['tax_query'][] = array(
                        'taxonomy' => $taxonomy,
                        'field' => 'name',
                        'terms' => explode( ',', $_GET[$new_queries[0]] ),
                    );
                }
            }
        }

        return $args;

    }

    
}

WooLentorProThirdParty::instance();