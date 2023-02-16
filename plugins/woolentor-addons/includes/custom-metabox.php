<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Free_Custom_Meta_Fields{

    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function get_instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){

        // Add Field in inventory tab
        add_action( 'woocommerce_product_options_inventory_product_data', [ $this, 'add_extra_field_in_inventory_tab' ] );

        // Custom Product tab
        add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_woolentor_tab' ], 10, 1 );
		add_action( 'woocommerce_product_data_panels', [ $this, 'product_woolentor_data_panel' ], 99 );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_woolenor_product_meta' ] );

    }

    /**
     * add_extra_field_in_inventory_tab Custom field add in inventory tab
     *
     * @return void
     */
    public function add_extra_field_in_inventory_tab(){

        // Stock progress bar extra field
        echo '<div class="options_group">';
            woocommerce_wp_text_input(
                array(
                    'id'          => 'woolentor_total_stock_quantity',
                    'label'       => __( 'Initial number in stock', 'woolentor' ),
                    'desc_tip'    => 'true',
                    'description' => __( 'Required for stock progress bar', 'woolentor' ),
                    'type'        => 'text',
                )
            );
        echo '</div>';

    }

    // add extra metabox tab to woocommerce
    public function product_woolentor_tab( $tabs ){
        $woolentor_tab = array(
            'label'    => __( 'Product Badge', 'woolentor' ),
            'target'   => 'woolentor_product_data',
            'class'    => '',
            'priority' => 80,
        );
        $tabs['woolentor_product_badge'] = $woolentor_tab;
        return $tabs;
    }

    // add metabox to general tab
    public function product_woolentor_data_panel(){
        echo '<div id="woolentor_product_data" class="panel woocommerce_options_panel hidden">';
            woocommerce_wp_text_input( array(
                'id'          => '_saleflash_text',
                'label'       => __( 'Custom Product Badge Text', 'woolentor' ),
                'placeholder' => __( 'New', 'woolentor' ),
                'description' => __( 'Enter your preferred Sale badge text. Ex: New / Free etc (Only for Universal layout addon)', 'woolentor' ),
                'desc_tip' => true
            ) );
        echo '</div>';
    }

    // Update data
    public function save_woolenor_product_meta( $post_id ){

        if( wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ){

            // Quantity field
            $stock_quantity = !empty( $_POST['woolentor_total_stock_quantity'] ) ? wc_clean( $_POST['woolentor_total_stock_quantity'] ) : '';
            update_post_meta( $post_id, 'woolentor_total_stock_quantity', $stock_quantity );

            // Sale Flash
            $saleflash_text = wp_kses_post( stripslashes( $_POST['_saleflash_text'] ) );
            update_post_meta( $post_id, '_saleflash_text', $saleflash_text);

        }else{
            delete_post_meta( $post_id, 'woolentor_total_stock_quantity' );
            delete_post_meta( $post_id, '_saleflash_text' );
        }

    }


}
Woolentor_Free_Custom_Meta_Fields::get_instance();