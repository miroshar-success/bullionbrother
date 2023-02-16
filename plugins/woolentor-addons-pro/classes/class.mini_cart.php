<?php
/**
* Mini Cart Manager
*/
class WooLentor_Mini_Cart {
    /**
     * [$instance]
     * @var null
     */
    private static $instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [WooLentor_Mini_Cart]
     */
    public static function instance(){
        if( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] Class Construction
     */
    function __construct(){

        add_action( 'woolentor_cart_content', [ $this, 'get_cart_item' ] );

        add_filter( 'woocommerce_add_to_cart_fragments', [ $this,'wc_add_to_cart_fragment' ], 10, 1 );

    }

    /**
     * [get_cart_item] Render fragment cart item
     * @return [html]
     */
    public function get_cart_item(){

        $cart_data  = WC()->cart->get_cart();
        $args = array();
        ob_start();
        $mini_cart_tmp_id = method_exists( 'Woolentor_Template_Manager', 'get_template_id' ) ? Woolentor_Template_Manager::instance()->get_template_id( 'mini_cart_layout', 'woolentor_get_option_pro' ) : '0';
        if( !empty( $mini_cart_tmp_id ) ){
            echo ( function_exists('woolentor_build_page_content') ? woolentor_build_page_content( $mini_cart_tmp_id ) : '' );
        }else{
            wc_get_template( 'tmp-mini_cart_content.php', $args, '', WOOLENTOR_TEMPLATE_PRO );
        }
        return ob_get_clean();

    }

    /**
     * [wc_add_to_cart_fragment] add to cart freagment callable
     * @param  [type] $fragments
     * @return [type] $fragments
     */
    public function wc_add_to_cart_fragment( $fragments ){

        $item_count = WC()->cart->get_cart_contents_count();
        $cart_item = $this->get_cart_item();

        // Cart Item
        $fragments['div.woolentor_cart_content_container'] = '<div class="woolentor_cart_content_container">'.$cart_item.'</div>';

        //Cart Counter
        $fragments['span.woolentor_mini_cart_counter'] = '<span class="woolentor_mini_cart_counter">'.$item_count.'</span>';

        return $fragments;
    }

    /**
     * [inline_style]
     * @return [string]
     */
    public function inline_style(){

        $icon_color     = woolentor_generate_css_pro('mini_cart_icon_color','woolentor_others_tabs','color');
        $icon_bg        = woolentor_generate_css_pro('mini_cart_icon_bg_color','woolentor_others_tabs','background-color');
        $icon_border    = woolentor_generate_css_pro('mini_cart_icon_border_color','woolentor_others_tabs','border-color');

        $counter_color      = woolentor_generate_css_pro('mini_cart_counter_color','woolentor_others_tabs','color');
        $counter_bg_color   = woolentor_generate_css_pro('mini_cart_counter_bg_color','woolentor_others_tabs','background-color');

        $button_color      = woolentor_generate_css_pro('mini_cart_buttons_color','woolentor_others_tabs','color');
        $button_bg_color   = woolentor_generate_css_pro('mini_cart_buttons_bg_color','woolentor_others_tabs','background-color');

        $button_hover_color     = woolentor_generate_css_pro('mini_cart_buttons_hover_color','woolentor_others_tabs','color');
        $button_hover_bg_color  = woolentor_generate_css_pro('mini_cart_buttons_hover_bg_color','woolentor_others_tabs','background-color');

        $custom_css = "
            .woolentor_mini_cart_icon_area{
                {$icon_color}
                {$icon_bg}
                {$icon_border}
            }
            .woolentor_mini_cart_counter{
                {$counter_color}
                {$counter_bg_color}
            }
            .woolentor_button_area a.button{
                {$button_color}
                {$button_bg_color}
            }
            .woolentor_button_area a.button:hover{
                {$button_hover_color}
            }
            .woolentor_button_area a::before{
                {$button_hover_bg_color}
            }
        ";

        return $custom_css;

    }
    

}
WooLentor_Mini_Cart::instance();