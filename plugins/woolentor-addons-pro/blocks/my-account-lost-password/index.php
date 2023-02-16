<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_my_account_lost_password' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';

    $email_box_label = !empty( $settings['emailBoxLabel'] ) ? $settings['emailBoxLabel'] : '';
    $button_label    = !empty( $settings['resetButtonLabel'] ) ? $settings['resetButtonLabel'] : esc_html__('Reset password','woolentor-pro');

    ?>
        <div class="woolentor-myaccount-form-lostpassword">
            <?php do_action( 'woocommerce_before_lost_password_form' ); ?>
            <form method="post" class="woocommerce-ResetPassword lost_reset_password">

                <p class="woocommerce-form-row form-row">
                    <?php
                        if( !empty( $email_box_label ) ){
                            echo '<label for="user_login">'.esc_html( $email_box_label ).'</label>';
                        }
                    ?>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" />
                </p>

                <div class="clear"></div>

                <?php do_action( 'woocommerce_lostpassword_form' ); ?>

                <p class="woocommerce-form-row form-row submit-button-row">
                    <input type="hidden" name="wc_reset_password" value="true" />
                    <button type="submit" class="woocommerce-Button button" value="<?php echo esc_attr( $button_label ); ?>"><?php echo esc_html( $button_label ); ?></button>
                </p>

                <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

            </form>
            <?php do_action( 'woocommerce_after_lost_password_form' ); ?>
        </div>
    <?php
    
echo '</div>';