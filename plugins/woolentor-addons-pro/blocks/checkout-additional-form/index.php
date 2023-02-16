<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_checkout_additional_form' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$checkout = \wc()->checkout();

echo '<div class="'.implode(' ', $areaClasses ).'">';
    
    if( is_checkout() || $block['is_editor'] ){
        if( sizeof( $checkout->checkout_fields ) > 0 ){
            if( $block['is_editor'] ){ echo '<form class="checkout woocommerce-checkout">'; }
            ?>
                <div class="woolentor woocommerce-additional-fields">
                    <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>
            
                    <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
                        <?php
                            if( !empty( $settings['formTitle'] ) ){
                                echo '<h3 class="woolentor-form-title">'.esc_html( $settings['formTitle'] ).'</h3>';
                            }
                        ?>
                        <div class="woocommerce-additional-fields__field-wrapper">
                            <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                                <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                
                    <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
                </div>
            <?php
            if( $block['is_editor'] ){ echo '</form>'; }
        }
    }
    
echo '</div>';