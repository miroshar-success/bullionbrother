<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_checkout_shipping_form' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$checkout = \wc()->checkout();

echo '<div class="'.implode(' ', $areaClasses ).'">';
    
    if( is_checkout() || $block['is_editor'] ){
        if( sizeof( $checkout->checkout_fields ) > 0 ){
            if( $block['is_editor'] ){ echo '<form class="checkout woocommerce-checkout">'; }
            ?>
                <div class="woolentor woocommerce-shipping-fields">
                    <?php if ( $block['is_editor'] || true === WC()->cart->needs_shipping_address() ) : ?>
                        
                        <h3 id="ship-to-different-address" class="woolentor-form-title">
                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php esc_html_e( $settings['formTitle'] ); ?></span>
                            </label>
                        </h3>
                
                        <div class="shipping_address">
                            <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>
                            <div class="woocommerce-shipping-fields__field-wrapper">
                                <?php
                                    $fields = $checkout->get_checkout_fields( 'shipping' );
                                    foreach ( $fields as $key => $field ) {
                                        if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
                                            $field['country'] = $checkout->get_value( $field['country_field'] );
                                        }
                                        woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                                    }
                                ?>
                            </div>
                            <?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
                        </div>
                
                    <?php endif; ?>
                </div>

            <?php
            if( $block['is_editor'] ){ echo '</form>'; }
        }
    }
    
echo '</div>';