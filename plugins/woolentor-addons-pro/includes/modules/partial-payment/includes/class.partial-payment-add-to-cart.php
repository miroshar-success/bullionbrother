<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Partial_Payment_AddTo_Cart extends Woolentor_Partial_Payment{

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

        add_action('woocommerce_before_add_to_cart_button', [ $this, 'before_add_to_cart_button' ], 999 );
        add_filter('woocommerce_add_cart_item_data', [ $this, 'add_cart_item_data' ], 10, 3);

        // Update Partial Amount for variation product
        add_action('wp_ajax_woolentor_partial_amount_update', [ $this, 'partial_amount_update_ajax' ] );
        add_action('wp_ajax_nopriv_woolentor_partial_amount_update', [ $this, 'partial_amount_update_ajax' ] );

        // Manage loop add to cart button
        add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'add_to_cart_link' ], 99, 2 );
		add_action( 'woocommerce_product_add_to_cart_text', [ $this, 'add_to_cart_text' ], 99, 2 );

    }

    /**
     * add partial payment field
     *
     * @return void
     */
    public function before_add_to_cart_button(){
        global $post;
		$enable_status = $this->get_partial_payment_status( $post->ID );

		if (  $enable_status ) {
			echo $this->partial_payment_content( $post->ID );
		}

    }

    /**
     * Product add to cart with partial payment status
     *
     * @param [array] $cart_item_data
     * @param [int] $product_id
     * @param [int] $variation_id
     * @return void
     */
    public function add_cart_item_data( $cart_item_data, $product_id, $variation_id ){

        if( $this->get_partial_payment_status( $product_id ) && isset( $_POST['woolentor_partial_payment_status'] ) ){
            
            /**
             * Check product already add in the cart as full payment wise
             */
            foreach ( WC()->cart->get_cart() as $item_key => $cart_item ) {

                if( ( $cart_item['product_id'] == $product_id ) && ( $cart_item['variation_id'] == $variation_id ) && ! isset( $cart_item['woolentor_partial_payment'] ) ){
                    
                    // if full payment type already added in the cart
                    if( $_POST['woolentor_partial_payment_status'] == 'yes' ){
                        throw new \Exception( esc_html__('Item already added in the cart full payment wise', 'woolentor-pro') );
                    }

                }
            }

            $enable_status = ( $_POST['woolentor_partial_payment_status'] == 'yes' ? true : false );
            if( $enable_status ){
                $cart_item_data['woolentor_partial_payment']['enable'] = $enable_status;
            }

        }

        return $cart_item_data;

    }

    /**
     * Partial amount content
     *
     * @param [int] $product_id
     * @return void
     */
    public function partial_payment_content( $product_id ){

        $product_data = $this->get_product_data( $product_id );

        $partial_amount_text        = $this->get_saved_data( $product_id, 'partial_payment_discount_text', 'partial_payment_discount_text', 'First Instalment : {price} Per item' );
        $partial_payment_btn_text   = $this->get_saved_data( $product_id, 'partial_payment_button_text', 'partial_payment_button_text', 'Partial Payment' );
        $full_payment_btn_text      = $this->get_saved_data( $product_id, 'full_payment_button_text', 'full_payment_button_text', 'Full Payment' );
        $default_selected           = $this->get_option_data( 'default_selected', 'partial' );
        $discount_price             = wc_price( $this->get_partial_amount_calculate( $product_id, $product_data->get_price() ) );
        $discount_label             = $this->discount_label( $discount_price, $partial_amount_text );

        ob_start();
        ?>
            <div class="woolentor-partial-payment-area">
                
                <div class="woolentor-partial-payment-selector-fields">

                    <div class="woolentor-partial-payment-field">
                        <input type="radio" id="woolentor-partial-payment-first-installment" name="woolentor_partial_payment_status" value="yes" <?php checked( $default_selected, 'partial' ); ?> />
                        <label class="woolentor-partial-payment-label" for="woolentor-partial-payment-first-installment">
                            <?php echo esc_html( $partial_payment_btn_text ); ?>
	                    </label>
                    </div>

                    <div class="woolentor-partial-payment-field">
                        <input type="radio" id="woolentor-partial-payment-full-payment" name="woolentor_partial_payment_status" value="no" <?php checked( $default_selected, 'full' ); ?> />
                        <label class="woolentor-partial-payment-label" for="woolentor-partial-payment-full-payment">
                            <?php echo esc_html( $full_payment_btn_text ); ?>
	                    </label>
                    </div>

                </div>

                <div class="woolentor-partial-payment-calculate-amount" style="<?php echo $default_selected == 'partial' ? 'display:block;' : 'display:none;'; ?>">
                    <div class="woolentor-partial-ammount"><?php echo $discount_label; ?></div>
                </div>

            </div>
        <?php
        return ob_get_clean();

    }

    /**
     * Generate Add to cart button link
     *
     * @param [string] $html
     * @param [object] $product
     * @param [array] $args
     * @return void
     */
    public function add_to_cart_link( $html, $product ) {

		if ( $this->get_partial_payment_status( $product->get_id() ) ) {
            $find_content = array( 'href="?add-to-cart=' . $product->get_id() . '"', 'add_to_cart_button', 'ajax_add_to_cart' );
            $remplace_by  = array( 'href="' . get_permalink( $product->get_id() )  . '"', '', ''  );
			$html = str_replace( $find_content, $remplace_by, $html );
		}

		return $html;

	}

    /**
     * Button text
     *
     * @param [string] $button_text
     * @param [object] $product
     * @return void
     */
    public function add_to_cart_text( $button_text, $product ){
		if ( $this->get_partial_payment_status( $product->get_id() ) ) {
			$button_text = $this->get_saved_data( $product->get_id(), 'partial_payment_loop_btn_text', 'partial_payment_loop_btn_text', 'Partial Payment' );
		}
		return $button_text;
    }

    /**
     * Generate Discount label
     *
     * @param [html] $price
     * @param [string] $string
     * @return void
     */
    public function discount_label( $price, $string ){
       return str_replace( '{price}', $price, $string );
    }

    /**
     * partial amount update for variable product ajax callable
     *
     * @return void
     */
    public function partial_amount_update_ajax(){

        check_ajax_referer( 'woolentor_partial_payment_nonce', 'nonce' );

        $product_id = !empty( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : false;
        $price      = !empty( $_POST['price'] ) ? floatval( $_POST['price'] ) : false;

        if ( $product_id ) {
            $discount_price = wc_price( $this->get_partial_amount_calculate( $product_id, $price ) );
            $partial_amount_text = $this->get_saved_data( $product_id, 'partial_payment_discount_text', 'partial_payment_discount_text', 'First Instalment : {price} Per item' );
            wp_send_json_success([
                'updateprice' => $this->discount_label( $discount_price, $partial_amount_text )
            ]);
        } else {
            wp_send_json_error();
        }

        wp_die();

    }


}