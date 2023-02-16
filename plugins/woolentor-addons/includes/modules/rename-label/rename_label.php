<?php
/*
* Shop Page
*/

// Add to Cart Button Text
if( !function_exists('woolentor_custom_add_cart_button_shop_page') ){
    add_filter( 'woocommerce_product_add_to_cart_text', 'woolentor_custom_add_cart_button_shop_page', 99, 2 );
    function woolentor_custom_add_cart_button_shop_page( $label ) {
        return __( woolentor_get_option_label_text( 'wl_shop_add_to_cart_txt', 'woolentor_rename_label_tabs', 'Add to Cart' ), 'woolentor-pro' );
    }
}

/*
* Product Details Page
*/

// Add to Cart Button Text
if( !function_exists('woolentor_custom_add_cart_button_single_product') ){
    add_filter( 'woocommerce_product_single_add_to_cart_text', 'woolentor_custom_add_cart_button_single_product' );
    function woolentor_custom_add_cart_button_single_product( $label ) {
        return __( woolentor_get_option_label_text( 'wl_add_to_cart_txt', 'woolentor_rename_label_tabs', 'Add to Cart' ), 'woolentor-pro' );
    }
}

//Description tab
if( !function_exists('woolentor_rename_description_product_tab_label') ){
    add_filter( 'woocommerce_product_description_tab_title', 'woolentor_rename_description_product_tab_label' );
    function woolentor_rename_description_product_tab_label() {
        return __( woolentor_get_option_label_text( 'wl_description_tab_menu_title', 'woolentor_rename_label_tabs', 'Description' ), 'woolentor-pro' );
    }
}

if( !function_exists('woolentor_rename_description_tab_heading') ){
    add_filter( 'woocommerce_product_description_heading', 'woolentor_rename_description_tab_heading' );
    function woolentor_rename_description_tab_heading() {
        return __( woolentor_get_option_label_text( 'wl_description_tab_menu_title', 'woolentor_rename_label_tabs', 'Description' ), 'woolentor-pro' );
    }
}

//Additional Info tab
if( !function_exists('woolentor_rename_additional_information_product_tab_label') ){
    add_filter( 'woocommerce_product_additional_information_tab_title', 'woolentor_rename_additional_information_product_tab_label' );
    function woolentor_rename_additional_information_product_tab_label() {
        return __( woolentor_get_option_label_text( 'wl_additional_information_tab_menu_title', 'woolentor_rename_label_tabs','Additional Information' ), 'woolentor-pro' );
    }
}

if( !function_exists('woolentor_rename_additional_information_tab_heading') ){
    add_filter( 'woocommerce_product_additional_information_heading', 'woolentor_rename_additional_information_tab_heading' );
    function woolentor_rename_additional_information_tab_heading() {
        return __( woolentor_get_option_label_text( 'wl_additional_information_tab_menu_title', 'woolentor_rename_label_tabs','Additional Information' ), 'woolentor-pro' );
    }
}

//Reviews Info tab
if( !function_exists('woolentor_rename_reviews_product_tab_label') ){
    add_filter( 'woocommerce_product_reviews_tab_title', 'woolentor_rename_reviews_product_tab_label' );
    function woolentor_rename_reviews_product_tab_label() {
        return __( woolentor_get_option_label_text( 'wl_reviews_tab_menu_title', 'woolentor_rename_label_tabs','Reviews' ), 'woolentor-pro');
    }
}


/*
* Checkout Page
*/
if( !function_exists('woolentor_rename_place_order_button') ){
    add_filter( 'woocommerce_order_button_text', 'woolentor_rename_place_order_button' );
    function woolentor_rename_place_order_button() {
        return __( woolentor_get_option_label_text( 'wl_checkout_placeorder_btn_txt', 'woolentor_rename_label_tabs','Place order' ), 'woolentor-pro');
    }
}