<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_my_account_reset_password' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';

    $layout_style = !empty( $settings['inputBoxLayout'] ) ? $settings['inputBoxLayout'] : 'inline';

    $new_password_box_label = !empty( $settings['newPasswordBoxLabel'] ) ? $settings['newPasswordBoxLabel'] : '';
    $renew_password_box_label = !empty( $settings['confirmPasswordBoxLabel'] ) ? $settings['confirmPasswordBoxLabel'] : '';
    $button_label    = !empty( $settings['buttonLabel'] ) ? $settings['buttonLabel'] : esc_html__('Save','woolentor-pro');

    if( $block['is_editor'] == 'yes' ){
        ?>
            <div class="woolentor-myaccount-form-lostpassword">
                <?php do_action( 'woocommerce_before_reset_password_form' ); ?>
                <form method="post" class="woocommerce-ResetPassword lost_reset_password">
                    <p class="woocommerce-form-row woocommerce-form-row--first form-row <?php echo ( $layout_style == 'inline' ? 'form-row-first': '' ); ?>">
                        <?php
                            if( !empty( $new_password_box_label ) ){
                                echo '<label for="password_1">'.esc_html__( $new_password_box_label, 'woolentor-pro' ).'&nbsp;<span class="required">*</span></label>';
                            }
                        ?>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" />
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--last form-row <?php echo ( $layout_style == 'inline' ? 'form-row-last': '' ); ?>">
                        <?php
                            if( !empty( $renew_password_box_label ) ){
                                echo '<label for="password_2">'.esc_html__( $renew_password_box_label, 'woolentor-pro' ).'&nbsp;<span class="required">*</span></label>';
                            }
                        ?>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" />
                    </p>

                    <div class="clear"></div>

                    <?php do_action( 'woocommerce_resetpassword_form' ); ?>

                    <p class="woocommerce-form-row form-row submit-button-row">
                        <input type="hidden" name="wc_reset_password" value="true" />
                        <button type="submit" class="woocommerce-Button button" value="<?php echo esc_attr( $button_label ); ?>"><?php echo esc_html( $button_label ); ?></button>
                    </p>

                    <?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
                </form>
                <?php do_action( 'woocommerce_after_reset_password_form' ); ?>
            </div>
        <?php
    }else{
        if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {
            list( $reset_password_id, $reset_password_key ) = array_map( 'wc_clean', explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) );
            $userdata               = get_userdata( absint( $reset_password_id ) );
            $reset_password_login   = $userdata ? $userdata->user_login : '';
            ?>
            <div class="woolentor-myaccount-form-lostpassword">
                <?php do_action( 'woocommerce_before_reset_password_form' ); ?>
                <form method="post" class="woocommerce-ResetPassword lost_reset_password">
                    <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                        <?php
                            if( !empty( $new_password_box_label ) ){
                                echo '<label for="password_1">'.esc_html__( $new_password_box_label, 'woolentor-pro' ).'&nbsp;<span class="required">*</span></label>';
                            }
                        ?>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" />
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                        <?php
                            if( !empty( $renew_password_box_label ) ){
                                echo '<label for="password_2">'.esc_html__( $renew_password_box_label, 'woolentor-pro' ).'&nbsp;<span class="required">*</span></label>';
                            }
                        ?>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" />
                    </p>

                    <input type="hidden" name="reset_key" value="<?php echo esc_attr( $reset_password_key ); ?>" />
                    <input type="hidden" name="reset_login" value="<?php echo esc_attr( $reset_password_login ); ?>" />

                    <div class="clear"></div>

                    <?php do_action( 'woocommerce_resetpassword_form' ); ?>

                    <p class="woocommerce-form-row form-row submit-button-row">
                        <input type="hidden" name="wc_reset_password" value="true" />
                        <button type="submit" class="woocommerce-Button button" value="<?php echo esc_attr( $button_label ); ?>"><?php echo esc_html( $button_label ); ?></button>
                    </p>

                    <?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
                </form>
                <?php do_action( 'woocommerce_after_reset_password_form' ); ?>
            </div>
            <?php
        }
    }
    
echo '</div>';