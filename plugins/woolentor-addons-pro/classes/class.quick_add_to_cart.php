<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Quick_Add_To_Cart{

	/**
	 * [$instance]
	 * @var null
	 */
	private static $instance = null;

	/**
	 * [instance]
	 * @return [Woolentor_Quick_Add_To_Cart]
	 */
    public static function instance(){
        if( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct]
     */
    function __construct(){
    	add_action( 'wp_ajax_woolentor_pro_quick_cart', [ $this, 'variation_form_html' ] );
		add_action( 'wp_ajax_nopriv_woolentor_pro_quick_cart', [ $this, 'variation_form_html' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * [enqueue_scripts] Load Necessary Script
     * @return [void]
     */
    public function enqueue_scripts(){
    	wp_register_script( 'woolentor-quick-cart',  WOOLENTOR_ADDONS_PL_URL_PRO . 'assets/js/woolentor-quick-cart.js', array('wc-add-to-cart-variation'), WOOLENTOR_VERSION_PRO, true );

    	//Localize Scripts
        $localizeargs = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        );
        wp_localize_script( 'woolentor-quick-cart', 'woolentor_quick_cart', $localizeargs );

    }

    /**
     * [variation_form_html]
     * @param  boolean $id product id
     * @return [void]
     */
    public function variation_form_html( $id = false ){

    	if( isset( $_POST['id'] ) ) {
			$id = sanitize_text_field( (int) $_POST['id'] );
		}
		if( ! $id || ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		global $post;

		$args = array( 
			'post_type' => 'product',
			'post__in' => array( $id ) 
		);

		$get_posts = get_posts( $args );

		foreach( $get_posts as $post ) :
			setup_postdata( $post );
        	woocommerce_template_single_add_to_cart();
		endforeach; 

		wp_reset_postdata(); 

		wp_die();

    }

    /**
     * [quick_cart_area] Quick Cart HTML
     * @return [void]
     */
    public static function quick_cart_area() {
		?>
			<div class="woolentor-quick-cart-area">
	            <div class="woolentor-quick-cart-close">
	                <span>&#10005;</span>
	            </div>
	            <div class="woolentor-quick-cart-form"></div>
	        </div>
		<?php
	}


}

Woolentor_Quick_Add_To_Cart::instance();
