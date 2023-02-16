<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Shopify_Like_Checkout extends \WC_Checkout{

    private static $_instance = null;

    public $hide_billing_company = true;
    public $hide_billing_phone   = true;
    public $field_manager_enable = false;

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
    function __construct(){
        $this->hide_billing_company = woolentor_get_option( 'show_company', 'woolentor_shopify_checkout_settings', 'off' ) === 'on' ? false : apply_filters('woolentor_slc_hide_billing_company', true);
        $this->hide_billing_phone   = woolentor_get_option( 'show_phone', 'woolentor_shopify_checkout_settings', 'off' ) === 'on' ? false : apply_filters('woolentor_slc_hide_billing_phone', true);

        // Field Manager Active status
        if( woolentor_get_option( 'billing_enable', 'woolentor_checkout_fields', 'off' ) == 'on' || woolentor_get_option( 'shipping_enable', 'woolentor_checkout_fields', 'off' ) == 'on' ){
            $this->field_manager_enable = true;
        }

        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 999 );

        // Remove theme styling
        add_filter( 'style_loader_tag', [ $this, 'style_loader_tag_filter' ], 10, 4 );

        // Remove Terms and conditions.
        remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );

        // Override wc templates
        add_filter('wc_get_template', [ $this, 'wc_get_template_filter' ], 9999, 5);

        // Override and set to canvas template
        add_filter( 'template_include', [ $this, 'template_include_filter' ], 9999 );

        // Reorder checkout fields
        add_filter('woocommerce_checkout_fields', [ $this, 'woocommerce_checkout_fields_filter' ] );
        add_filter('woocommerce_default_address_fields', [ $this, 'reorder_checkout_default_fields' ] );

        // Update order review fragments
        add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'woocommerce_update_order_review_fragments_filter' ], 99, 2 );

        // Ajax actions
        add_action('wp_ajax_validate_1st_step', [ $this, 'validate_1st_step' ] );
        add_action('wp_ajax_nopriv_validate_1st_step', [ $this, 'validate_1st_step' ] );

        add_filter('woocommerce_form_field_args', [ $this, 'woocommerce_form_field_args_filter' ], 10 , 3);
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts(){
        if( !$this->is_checkout() ){
            return;
        }

        if ( apply_filters( 'woolentor_wc_styles_dependency', true ) ) {
            
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_style( 'woocommerce-general' );

            //Over Confirm for Default style
            wp_deregister_style( 'woocommerce-layout' );
            wp_deregister_style( 'woocommerce-smallscreen' );
            wp_deregister_style( 'woocommerce-general' );

        }

        // Styles
        wp_enqueue_style( 'woolentor-shopify-like-checkout', plugin_dir_url( __FILE__ ) . 'assets/shopify-like-checkout.css' ,'', WOOLENTOR_VERSION, 'all' );

        // Scripts
        $suffix = Automattic\Jetpack\Constants::is_true( 'SCRIPT_DEBUG' ) ? '' : '.min';
        wp_enqueue_script( 'serializejson', WC()->plugin_url() . '/assets/js/jquery-serializejson/jquery.serializejson' . $suffix . '.js', array( 'jquery' ), '2.8.1' );
        wp_enqueue_script( 'woolentor-checkout');
        wp_enqueue_script( 'woolentor-shopify-like-checkout', plugin_dir_url( __FILE__ ) . 'assets/shopify-like-checkout.js', array('jquery', 'wc-checkout'), WOOLENTOR_VERSION, 'all' );
        wp_localize_script( 'woolentor-shopify-like-checkout', 'woolentor_slc_params',
            array( 
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce('woolentor_slc_nonce') // Nonce for shopify like checkout
            )
        );
        
    }

    /**
     * Check if the current page is checkout
     */
    public function is_checkout(){
        if( is_checkout() && !is_wc_endpoint_url() ){
            return true;
        }

        return false;
    }

    /**
     * Set canvas template file path for the checkout page
     */
    public function template_include_filter( $template ){
        if( !$this->is_checkout() ){
            return $template;
        }

        // If checkout registration is disabled and not logged in, the user cannot checkout.
        if ( ! WC()->Checkout()->is_registration_enabled() && WC()->Checkout()->is_registration_required() && ! is_user_logged_in() ) {
            return $template;
        } 

        if( is_checkout() && !is_wc_endpoint_url() ){
            $template = __DIR__. '/templates/canvas.php';
        }

        return $template;
    }

    /**
     * Override the woocommerce default template files
     */
    public function wc_get_template_filter( $template, $template_name, $args, $template_path, $default_path ){
        if( !$this->is_checkout() ){
            return $template;
        }

        // If checkout registration is disabled and not logged in, the user cannot checkout.
        if ( ! WC()->Checkout()->is_registration_enabled() && WC()->Checkout()->is_registration_required() && ! is_user_logged_in() ) {
            return $template;
        } 
        
        if($template_name == 'checkout/form-checkout.php'){
            $template = wc_locate_template('form-checkout.php', 'wl-woo-templates/shopify-like-checkout', __DIR__. '/templates/');
        }

        if($template_name == 'checkout/form-billing.php'){
            $template = wc_locate_template('form-billing.php', 'wl-woo-templates/shopify-like-checkout', __DIR__. '/templates/');
        }

        if($template_name == 'checkout/form-shipping.php'){
            $template = wc_locate_template('form-shipping.php', 'wl-woo-templates/shopify-like-checkout', __DIR__. '/templates/');
        }

        if($template_name == 'cart/cart-shipping.php'){
            $template = wc_locate_template('cart-shipping.php', 'wl-woo-templates/shopify-like-checkout', __DIR__. '/templates/');
        }

        if($template_name == 'checkout/payment.php'){
            $template = wc_locate_template('payment.php', 'wl-woo-templates/shopify-like-checkout', __DIR__. '/templates/');
        }

        if($template_name == 'checkout/review-order.php'){
            $template = wc_locate_template('review-order.php', 'wl-woo-templates/shopify-like-checkout', __DIR__. '/templates/');
        }

        if($template_name == 'checkout/form-coupon.php'){
            $template = wc_locate_template('form-coupon.php', 'wl-woo-templates/shopify-like-checkout', __DIR__. '/templates/');
        }

        if(is_checkout() && $template_name == 'global/form-login.php'){
            $template = wc_locate_template('form-login.php', 'wl-woo-templates/shopify-like-checkout', __DIR__. '/templates/');
        }

        return $template;
    }

    /**
     * Reorder the checkout page address fields before render
     */
    public function woocommerce_checkout_fields_filter( $fieldset ){
        if( !$this->is_checkout() ){
            return $fieldset;
        }

        // Remove fields
        if( isset($fieldset['billing']['billing_company']) && $this->hide_billing_company ){
            unset($fieldset['billing']['billing_company']);
        }
        
        if( isset($fieldset['billing']['billing_phone']) && $this->hide_billing_phone ){
            unset($fieldset['billing']['billing_phone']);
        }

        if( isset($fieldset['billing']['billing_email']) ){
            $fieldset['billing']['billing_email']['priority'] = 1;
            $fieldset['billing']['billing_email']['placeholder'] = $fieldset['billing']['billing_email']['label'];
        }

        // Set the order of the fields
        $count = 0;
        $priority = 10;

        // Updating the 'priority' argument
        foreach($this->get_address_field_keys('billing', $fieldset) as $field_name){
            $count++;
            $fieldset['billing'][$field_name]['priority'] = $count * $priority;

            if( !isset($fieldset['billing'][$field_name]['placeholder']) ){
                $fieldset['billing'][$field_name]['placeholder'] = $fieldset['billing'][$field_name]['label'];
            }

            // Customize classes
            if( ( $this->field_manager_enable === false ) && ( $field_name == 'billing_country' ||  $field_name == 'billing_state' ||  $field_name == 'billing_postcode' ) ){
                $fieldset['billing'][$field_name]['class'][] = 'woolentor-one-third';
            }
        }

        foreach($this->get_address_field_keys('shipping', $fieldset) as $field_name){
            if( !isset($fieldset['shipping'][$field_name]['placeholder']) ){
                $fieldset['shipping'][$field_name]['placeholder'] = $fieldset['shipping'][$field_name]['label'];
            }

            // Customize classes
            if( ( $this->field_manager_enable === false ) && ($field_name == 'shipping_country' ||  $field_name == 'shipping_state' ||  $field_name == 'shipping_postcode') ){
                $fieldset['shipping'][$field_name]['class'][] = 'woolentor-one-third';
            }
        }

        return $fieldset;
    }

    /**
     * Reorder the checkout page address fields after JS rendered
     */
    public function reorder_checkout_default_fields( $fields ){
        if( !$this->is_checkout() ){
            return $fields;
        }

        // remove fields
        if( isset($fields['company']) && $this->hide_billing_company ){
            unset($fields['company']);
        }
        
        if( isset($fields['phone']) && $this->hide_billing_phone ){
            unset($fields['phone']);
        }

        $count    = 0;
        $priority = 10;

        // Updating the 'priority' argument
        $fields_order = array(
            'first_name',
            'last_name',
            'address_1',
            'address_2',
            'city',
            'country',
            'state',
            'postcode',
        );

        foreach($fields_order as $field_name){
            $count++;
            $fields[$field_name]['priority'] = $count * $priority;
        }
        
        return $fields;
    }

    
    /**
     * It returns an array of keys from the $fieldset array
     * 
     * @param group The group name. e.g: shipping
     * @param fieldset Array of fields that you want to get the keys from.
     */
    public function get_address_field_keys( $group = '', $fieldset = array() ){
        if( $group && $fieldset ){
            return array_keys( $fieldset[$group] );
        }

        return array();
    }

    /**
     * Update fragments
     */
    public function woocommerce_update_order_review_fragments_filter( $fragments ) {
        ob_start();
        woocommerce_order_review();
        $fragments['.woocommerce-checkout-review-order-table'] = ob_get_clean();

        ob_start();
        if ( wc_ship_to_billing_address_only() || true === WC()->cart->needs_shipping_address() ){
            wc_cart_totals_shipping_html();
        } else {
        ?>
            <tr class="woocommerce-shipping-totals shipping">
                <th><?php echo esc_html__('Shipping', 'woolentor') ?></th>
                <td data-title="<?php echo esc_attr__('Shipping','woolentor'); ?>">
                    <ul id="shipping_method" class="woocommerce-shipping-methods">
                        <li>
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woolentor' ) ) ); ?>
                        </li>
                    </ul>
                </td>
            </tr>         
        <?php
        }
        $fragments['.woolentor-checkout__shipping-method'] = '<table class="woolentor-checkout__shipping-method"><tbody>'. ob_get_clean() .'</tbody></table>';

        $fragments['.woolentor-order-reivew-shipping-cost'] = '<sapn class="woolentor-order-reivew-shipping-fee">'. wc_price( $this::get_cart_totals_shipping_cost() ) .'</span>';

        // Compatibilty for the WooFunnels order bump plugin
        ob_start();
        $this::review_order_mini_cart_html();

        $fragments['.woolentor-checkout__right-sidebar .woolentor-checkout__prduct-box'] = '<div class="woolentor-checkout__prduct-box">' .ob_get_clean() . '</div>';
        
        return $fragments;
    }

    /**
     * Return the total shipping cost
     */
    public static function get_cart_totals_shipping_cost(){
        $packages      = WC()->shipping()->get_packages();
        $method_cost   = 0;
        $package       = array();
        $chosen_method = '';

        foreach ( $packages as $i => $package ) {
            $chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';
        }

        if( is_array($package)  && !empty($package['rates']) ){
            $method_obj  = $package['rates'][$chosen_method];
            if ( WC()->cart->display_prices_including_tax() ) {
                $method_cost = $method_obj->cost + $method_obj->get_shipping_tax();
            }else{
                $method_cost = $method_obj->get_cost();
            }
        }

        return $method_cost;
    }

    /**
     * Rewrite get_posted_data method as per our need.
     * Receives $_POST data and prepare the posted data to validate required fields and
     * generate the notices propery
     */
    public function wl_get_posted_data( $post_data ) {
        // phpcs:disable WordPress.Security.NonceVerification.Missing
        $data = array(
            'createaccount'                      => (int) ( $this->is_registration_enabled() ? ! empty( $post_data['createaccount'] ) : false ),
            'ship_to_different_address'          => ! empty( $post_data['ship_to_different_address'] ) && ! wc_ship_to_billing_address_only(),
            'woocommerce_checkout_update_totals' => isset( $post_data['woocommerce_checkout_update_totals'] ),
        );

        $skipped = array();
        $form_was_shown = isset( $post_data['woocommerce-process-checkout-nonce'] ); // phpcs:disable WordPress.Security.NonceVerification.Missing
        
        foreach ( $this->get_checkout_fields() as $fieldset_key => $fieldset ) {
            if ( $this->maybe_skip_fieldset( $fieldset_key, $data ) ) {
                $skipped[] = $fieldset_key;
                continue;
            }

            foreach ( $fieldset as $key => $field ) {
                $type = sanitize_title( isset( $field['type'] ) ? $field['type'] : 'text' );

                if( !isset( $post_data[$key] ) ){
                    continue;
                }

                if ( isset( $post_data[ $key ] ) && '' !== $post_data[ $key ] ) { // phpcs:disable WordPress.Security.NonceVerification.Missing
                    $value = wp_unslash( $post_data[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                } elseif ( isset( $field['default'] ) && 'checkbox' !== $type && ! $form_was_shown ) {
                    $value = $field['default'];
                } else {
                    $value = '';
                }

                if ( '' !== $value ) {
                    switch ( $type ) {
                        case 'checkbox':
                            $value = 1;
                            break;
                        case 'multiselect':
                            $value = implode( ', ', wc_clean( $value ) );
                            break;
                        case 'textarea':
                            $value = wc_sanitize_textarea( $value );
                            break;
                        case 'password':
                            break;
                        default:
                            $value = wc_clean( $value );
                            break;
                    }
                }

                $data[ $key ] = apply_filters( 'woocommerce_process_checkout_' . $type . '_field', apply_filters( 'woocommerce_process_checkout_field_' . $key, $value ) );
            }
        }

        if ( in_array( 'shipping', $skipped, true ) && ( WC()->cart->needs_shipping_address() || wc_ship_to_billing_address_only() ) ) {
            foreach ( $this->get_checkout_fields( 'shipping' ) as $key => $field ) {
                $data[ $key ] = isset( $data[ 'billing_' . substr( $key, 9 ) ] ) ? $data[ 'billing_' . substr( $key, 9 ) ] : '';
            }
        }

        return $data;

    }

    /**
     * Validate 1st step fields
     * 
     * Attached with: wp_ajax_validate_1st_step action
     */
    public function validate_1st_step(){
        $post_data = wp_unslash($_POST);

        // Verify nonce
        $nonce = sanitize_text_field($_REQUEST['nonce']);
        if ( !wp_verify_nonce( $nonce, 'woolentor_slc_nonce' ) ) {
            wp_send_json_error(array(
                'message' => esc_html__( 'No naughty business please!', 'woolentor' )
            ));
        }

        $posted_data = $post_data['fields'];
        parse_str($posted_data, $posted_data);

        $posted_data = $this->wl_get_posted_data($posted_data);

        if( empty($posted_data['ship_to_different_address']) ){
            $posted_data['ship_to_different_address'] = '';
        }

        if( empty($posted_data['billing_country']) ){
            $posted_data['billing_country'] = '';
        }

        if( empty($posted_data['billing_country']) ){
            $posted_data['billing_country'] = '';
        }

        $errors      = new WP_Error();

        $this->validate_posted_data( $posted_data, $errors );

        foreach ( $errors->errors as $code => $messages ) {
            $data = $errors->get_error_data( $code );
            foreach ( $messages as $message ) {
                wc_add_notice( $message, 'error', $data );
            }
        }

        $messages_html = wc_print_notices( true );
        wp_send_json_success( array( 'messages' =>  $messages_html ) );
    }

    /**
     * Remove theme's styling
     */
    public function style_loader_tag_filter( $tag, $handle, $href, $media ){
        if( !$this->is_checkout() || ( $this->is_registration_required() && ! $this->is_registration_enabled() && ! is_user_logged_in() ) ){
            return $tag;
        }

        if( strpos( $href, '/themes/' ) ){
            return '';
        }

        return $tag;
    }

    /**
     * Filter through each fields
     */
    public function woocommerce_form_field_args_filter($args, $key, $value){
        if($args['type'] == 'select'){
            array_push($args['class'], 'woolentor-checkout__field-select-wrapper');
            array_push($args['input_class'], 'woolentor-checkout__field-select');
            array_push($args['label_class'], 'woolentor-checkout__select-label');
        }

        return $args;
    }

    /**
     * This method is used to display the mini cart in the checkout page review order section.
     */
    public static function review_order_mini_cart_html(){
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                ?>
                <!-- Single product Start -->
                <div class="woolentor-checkout__product">
                    <div class="woolentor-checkout__product-left">
                        <div class="woolentor-checkout__product-image">
                            <div class="woolentor-checkout__product-thumbnail">
                                <?php
                                    if($_product->get_image_id()){
                                        echo wp_kses_post(wp_get_attachment_image($_product->get_image_id()));
                                    } else {
                                        echo wc_placeholder_img();
                                    }
                                ?>
                            </div>
                            <span class="woolentor-checkout__product-quantity"><?php echo esc_html($cart_item['quantity']) ?></span>
                        </div>
                        <div class="woolentor-checkout__product-description">
                            <span class="woolentor-checkout__product-name"><a href="<?php echo esc_url( get_permalink($_product->get_id()) ); ?>"><?php echo esc_html($_product->get_name()); ?></a></span>
                        </div>
                    </div>
                    <div class="woolentor-checkout__product-price-box">
                        <span class="woolentor-checkout__product-price">
                            <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, 1 ), $cart_item, $cart_item_key ); ?>
                        </span>
                    </div>
                </div>
                <!-- Single product End -->
                <?php
            }
        }
    }

    /**
     * Outputs a checkout/address form field.
     *
     * @param string $key Key.
     * @param mixed  $args Arguments.
     * @param string $value (default: null).
     * @return string
     */
    public static function woocommerce_form_field( $key, $args, $value = null ) {
        $defaults = array(
            'type'              => 'text',
            'label'             => '',
            'description'       => '',
            'placeholder'       => '',
            'maxlength'         => false,
            'required'          => false,
            'autocomplete'      => false,
            'id'                => $key,
            'class'             => array(),
            'label_class'       => array(),
            'input_class'       => array(),
            'return'            => false,
            'options'           => array(),
            'custom_attributes' => array(),
            'validate'          => array(),
            'default'           => '',
            'autofocus'         => '',
            'priority'          => '',
        );

        $args = wp_parse_args( $args, $defaults );
        $args = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );

        if ( $args['required'] ) {
            $args['class'][] = 'validate-required';
            $required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
        } else {
            $required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
        }

        if ( is_string( $args['label_class'] ) ) {
            $args['label_class'] = array( $args['label_class'] );
        }

        if ( is_null( $value ) ) {
            $value = $args['default'];
        }

        // Custom attribute handling.
        $custom_attributes         = array();
        $args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

        if ( $args['maxlength'] ) {
            $args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
        }

        if ( ! empty( $args['autocomplete'] ) ) {
            $args['custom_attributes']['autocomplete'] = $args['autocomplete'];
        }

        if ( true === $args['autofocus'] ) {
            $args['custom_attributes']['autofocus'] = 'autofocus';
        }

        if ( $args['description'] ) {
            $args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
        }

        if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
            foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
                $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
            }
        }

        if ( ! empty( $args['validate'] ) ) {
            foreach ( $args['validate'] as $validate ) {
                $args['class'][] = 'validate-' . $validate;
            }
        }

        $field           = '';
        $label_id        = $args['id'];
        $sort            = $args['priority'] ? $args['priority'] : '';
        $field_container = '<p class="form-row %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</p>';

        switch ( $args['type'] ) {
            case 'country':
                $countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

                if ( 1 === count( $countries ) ) {

                    $field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

                    $field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';

                } else {
                    $data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

                    $field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_attr__( 'Select a country / region&hellip;', 'woocommerce' ) ) . '" ' . $data_label . '><option value="">' . esc_html__( 'Select a country / region&hellip;', 'woocommerce' ) . '</option>';

                    foreach ( $countries as $ckey => $cvalue ) {
                        $field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
                    }

                    $field .= '</select>';

                    $field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country / region', 'woocommerce' ) . '">' . esc_html__( 'Update country / region', 'woocommerce' ) . '</button></noscript>';

                }

                break;
            case 'state':
                /* Get country this state field is representing */
                $for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( 'billing_state' === $key ? 'billing_country' : 'shipping_country' );
                $states      = WC()->countries->get_states( $for_country );

                if ( is_array( $states ) && empty( $states ) ) {

                    $field_container = '<p class="form-row %1$s" id="%2$s" style="display: none">%3$s</p>';

                    $field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" readonly="readonly" data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

                } elseif ( ! is_null( $for_country ) && is_array( $states ) ) {
                    $data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

                    $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_html__( 'Select an option&hellip;', 'woocommerce' ) ) . '"  data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . $data_label . '>
                        <option value="">' . esc_html__( 'Select an option&hellip;', 'woocommerce' ) . '</option>';

                    foreach ( $states as $ckey => $cvalue ) {
                        $field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
                    }

                    $field .= '</select>';

                } else {

                    $field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['placeholder'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

                }

                break;
            case 'textarea':
                $field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';

                break;
            case 'checkbox':
                $field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
                        <input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';

                break;
            case 'text':
            case 'password':
            case 'datetime':
            case 'datetime-local':
            case 'date':
            case 'month':
            case 'time':
            case 'week':
            case 'number':
            case 'email':
            case 'url':
            case 'tel':
                $field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

                break;
            case 'hidden':
                $field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-hidden ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

                break;
            case 'select':
                $field   = '';
                $options = '';

                if ( ! empty( $args['options'] ) ) {
                    foreach ( $args['options'] as $option_key => $option_text ) {
                        if ( '' === $option_key ) {
                            // If we have a blank option, select2 needs a placeholder.
                            if ( empty( $args['placeholder'] ) ) {
                                $args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'woocommerce' );
                            }
                            $custom_attributes[] = 'data-allow_clear="true"';
                        }
                        $options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_html( $option_text ) . '</option>';
                    }

                    $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
                            ' . $options . '
                        </select>';
                }

                break;
            case 'radio':
                $label_id .= '_' . current( array_keys( $args['options'] ) );

                if ( ! empty( $args['options'] ) ) {
                    foreach ( $args['options'] as $option_key => $option_text ) {
                        $field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
                        $field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . esc_html( $option_text ) . '</label>';
                    }
                }

                break;
        }

        if ( ! empty( $field ) ) {
            $field_html = '';

            $field_html .= '<span class="woocommerce-input-wrapper">' . $field;

            if ( $args['description'] ) {
                $field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
            }

            if ( $args['label'] && 'checkbox' !== $args['type'] ) {
                $field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . wp_kses_post( $args['label'] ) . $required . '</label>';
            }

            $field_html .= '</span>';

            $container_class = esc_attr( implode( ' ', $args['class'] ) );
            $container_id    = esc_attr( $args['id'] ) . '_field';
            $field           = sprintf( $field_container, $container_class, $container_id, $field_html );
        }

        /**
         * Filter by type.
         */
        $field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

        /**
         * General filter on form fields.
         *
         * @since 3.4.0
         */
        $field = apply_filters( 'woocommerce_form_field', $field, $key, $args, $value );

        if ( $args['return'] ) {
            return $field;
        } else {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $field;
        }
    }
}

Woolentor_Shopify_Like_Checkout::get_instance();    