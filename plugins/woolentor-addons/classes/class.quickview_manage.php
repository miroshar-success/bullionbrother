<?php
namespace WooLentor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*  Quickview Manager
*/
class Quick_View_Manager{

    private static $instance = null;
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    function __construct(){
        add_action( 'woolentor_footer_render_content', [ $this, 'quick_view_html' ], 10 );
    }


    // Quick View Markup
    public function quick_view_html(){
        echo '<div class="woocommerce" id="htwlquick-viewmodal"><div class="htwl-modal-dialog product"><div class="htwl-modal-content"><button type="button" class="htcloseqv"><span class="sli sli-close"><span class="woolentor-placeholder-remove">'.esc_html__('X','woolentor').'</span></span></button><div class="htwl-modal-body"></div></div></div></div>';
    }

    // Open Quick view Ajax Callback
    public static function wc_quickview() {
        if ( isset( $_POST['id'] ) && (int) $_POST['id'] ) {
            global $post, $product, $woocommerce;
            $id      = ( int ) $_POST['id'];
            $post    = get_post( $id );
            $product = wc_get_product( $id );
            if ( $product ) { 
                include ( apply_filters( 'woolentor_quickview_tmp', WOOLENTOR_ADDONS_PL_PATH.'includes/quickview-content.php' ) ); 
            }
        }
        wp_die();
    }


}

Quick_View_Manager::instance();