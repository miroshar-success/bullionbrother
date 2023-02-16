<?php
    class WooLentor_MyAccount{

    private $itemsorder = [];
    private $userinfo = [];

    public function __construct( $menuorders = array(), $userinfo = array( 'status'=>'no','image'=>'') ) {
        $this->itemsorder = $menuorders;
        $this->userinfo   = $userinfo;
        if( $userinfo['status'] == 'yes' ){
            add_action( 'woocommerce_before_account_navigation', [ $this, 'navigation_user' ] );
        }

        add_filter( 'woocommerce_account_menu_items',[ $this, 'navigation_items' ], 15, 2 );
        add_filter( 'woocommerce_get_endpoint_url', [ $this, 'navigation_endpoint_url' ], 15, 4 );

        $this->custom_content();

    }

    // My account navigation Item
    public function navigation_items( $items, $endpoints ){
        $items = array();
        foreach ( $this->itemsorder as $key => $item ) {
            $items[$key] = $item['title'];
        }
        return $items;
    }

    // My account navigation URL
    public function navigation_endpoint_url( $url, $endpoint, $value, $permalink ){
        foreach ( $this->itemsorder as $key => $item ) {
            if( ( 'customadd' === $item['type'] ) && ( $key === $endpoint ) ){
                $url = $item['url'];
            }
        }
        return $url;
    }

    // My Account User Info
    public function navigation_user(){
        $current_user = wp_get_current_user();
        if ( $current_user->display_name ) {
            $name = $current_user->display_name;
        } else {
            $name = esc_html__( 'Welcome!', 'woolentor-pro' );
        }
        $name = apply_filters( 'woolentor_profile_name', $name );
        ?>
            <div class="woolentor-user-area">
                <div class="woolentor-user-image">
                    <?php
                        if( $this->userinfo['image'] ){
                            echo wp_kses_post( $this->userinfo['image'] );
                        }else{
                            echo get_avatar( $current_user->user_email, 125 );
                        }
                    ?>
                </div>
                <div class="woolentor-user-info">
                    <span class="woolentor-username"><?php echo esc_attr( $name ); ?></span>
                    <span class="woolentor-logout"><a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>"><?php echo esc_html__( 'Logout', 'woolentor-pro' ); ?></a></span>
                </div>
            </div>
        <?php

    }


    /*
    * For Custom Endpoint
    * Add Custom Content
    */
    public function custom_content() {
        foreach ( $this->itemsorder as $key => $item ) {
            if( isset( $item['content'] ) && 'dashboard' !== $key ){
                add_action( 'woocommerce_account_' . $key . '_endpoint', [ $this, 'render_custom_content' ] );
            }else{
                if( isset( $item['content'] ) && 'dashboard' === $key ){
                    add_action( 'woocommerce_account_' . $key, [ $this, 'render_custom_content' ] );
                }
            }
        }
    }

    public function render_custom_content(){

        foreach ( $this->itemsorder as $key => $item ) {
            $urlsegments = explode('/', trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/') );
            
            $css = '';
            if( $item['remove_content'] == 'yes' ){
                if( 'dashboard' === $key && !is_wc_endpoint_url() ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > p,.woocommerce-MyAccount-content > p{display: none;}';
                }elseif( 'orders' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > table.woocommerce-orders-table,.woocommerce-MyAccount-content > table.woocommerce-orders-table,.woocommerce-MyAccount-content .woocommerce-Message{display: none;}';
                }elseif( 'downloads' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > .woocommerce-order-downloads,.woocommerce-MyAccount-content > .woocommerce-order-downloads,.woocommerce-MyAccount-content .woocommerce-Message{display: none;}';
                }elseif( 'edit-address' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > p,.woocommerce-MyAccount-content > p,body.woocommerce-account .woocommerce-MyAccount-content > .woocommerce-Addresses,.woocommerce-MyAccount-content > .woocommerce-Addresses,.woocommerce-MyAccount-content .woocommerce-Message{display: none;}';
                }elseif( 'edit-account' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > form.woocommerce-EditAccountForm,.woocommerce-MyAccount-content > form.woocommerce-EditAccountForm{display: none;}';
                }
                elseif( 'payment-methods' === $key ){
                    $css = 'body.woocommerce-account .woocommerce-MyAccount-content > .button,.woocommerce-MyAccount-content .woocommerce-Message{display: none;}';
                }
                echo '<style>'.$css.'</style>';
            }
            
            if( 'dashboard' === $key && !is_wc_endpoint_url() ){
                if( $item['content'] ){
                    if( 'elementor' === $item['content_source'] ){
                        echo '<div class="woolentor-dash-content">'.( function_exists('woolentor_build_page_content') ? woolentor_build_page_content( $item['content'] ) : '' ).'</div>';
                    }else{
                        echo '<div class="woolentor-dash-content">'.wp_kses_post( $item['content'] ).'</div>';
                    }
                }
            }else{
                if( $item['content'] && ( $urlsegments[count($urlsegments)-1] == $key ) ){
                    if( 'elementor' === $item['content_source'] ){
                        echo '<div class="woolentor-dash-content">'.( function_exists('woolentor_build_page_content') ? woolentor_build_page_content( $item['content'] ) : '' ).'</div>';
                    }else{
                        echo '<div class="woolentor-dash-content">'.wp_kses_post( $item['content'] ).'</div>';
                    }
                }
            }

        }


    }

    

}
