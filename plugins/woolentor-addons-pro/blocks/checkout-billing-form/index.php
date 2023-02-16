<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_checkout_billing_form' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$checkout = \wc()->checkout();

echo '<div class="'.implode(' ', $areaClasses ).'">';
    
    if( is_checkout() || $block['is_editor'] ){
        if( sizeof( $checkout->checkout_fields ) > 0 ){
            if( $block['is_editor'] ){ echo '<form class="checkout woocommerce-checkout">'; }
            ?>
                <div class="woocommerce-billing-fields">
                    <?php
                        if( !empty( $settings['formTitle'] ) ){
                            echo '<h3 class="woolentor-form-title">'.esc_html( $settings['formTitle'] ).'</h3>';
                        }
                    ?>
                    <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

                    <div class="woocommerce-billing-fields__field-wrapper">
                        <?php
                            $fields = $checkout->get_checkout_fields( 'billing' );
                            foreach ( $fields as $key => $field ) {
                                woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                            }
                        ?>
                    </div>

                    <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
                </div>

                <?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
                    <div class="woocommerce-account-fields">
                        <?php if ( ! $checkout->is_registration_required() ) : ?>

                            <p class="form-row form-row-wide create-account">
                                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                    <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( $settings['createfieldLabel'] ); ?></span>
                                </label>
                            </p>

                        <?php endif; ?>

                        <?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

                        <?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

                            <div class="create-account">
                                <?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
                                    <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                                <?php endforeach; ?>
                                <div class="clear"></div>
                            </div>

                        <?php endif; ?>

                        <?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
                    </div>
                <?php endif; ?>

            <?php
            if( $block['is_editor'] ){ echo '</form>'; }
        }
    }
    
echo '</div>';