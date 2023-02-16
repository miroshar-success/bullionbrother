<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_my_account_login_form' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

if( $block['is_editor'] != 'yes' ){
    if( is_user_logged_in() ){ return; }
}

echo '<div class="'.implode(' ', $areaClasses ).'">';

    if( !is_account_page() || $block['is_editor'] == 'yes' ){ do_action( 'woocommerce_before_customer_login_form' ); }

    ?>
        <div class="woolentor-myaccount-form-login">

            <?php
                if( !empty( $settings['heading'] ) ){
                    echo '<h2>'.esc_html( $settings['heading'] ).'</h2>';
                }
            ?>
        
            <form class="woocommerce-form woocommerce-form-login login" method="post">
            
                <?php do_action( 'woocommerce_login_form_start' ); ?>
        
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="username"><?php esc_html_e( 'Username or email address', 'woolentor-pro' ); ?>&nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password"><?php esc_html_e( 'Password', 'woolentor-pro' ); ?>&nbsp;<span class="required">*</span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
                </p>
        
                <?php do_action( 'woocommerce_login_form' ); ?>
        
                <p class="form-row">
                    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                    <button type="submit" class="woocommerce-Button button" name="login" value="<?php esc_attr_e( 'Log in', 'woolentor-pro' ); ?>"><?php esc_html_e( 'Log in', 'woolentor-pro' ); ?></button>
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woolentor-pro' ); ?></span>
                    </label>
                </p>

                <p class="woocommerce-LostPassword lost_password">
                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woolentor-pro' ); ?></a>
                </p>
        
                <?php do_action( 'woocommerce_login_form_end' ); ?>
        
            </form>
        </div>
    <?php
    
echo '</div>';