<?php
/**
 * Login form
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( is_user_logged_in() ) {
    return;
}

?>
<form class="woocommerce-form woocommerce-form-login login" method="post" <?php echo ( $hidden ) ? 'style="display:none;"' : ''; ?>>

    <?php do_action( 'woocommerce_login_form_start' ); ?>

    <p class="form-row">
        <?php echo ( $message ) ? wptexturize( $message ) : ''; // @codingStandardsIgnoreLine ?>
    </p>

    <p class="form-row form-row-first">
        <input type="text" class="input-text" name="username" id="username" placeholder="<?php esc_html_e( 'Username or email', 'woolentor' ); ?>" autocomplete="username" />
        <label for="username"><?php esc_html_e( 'Username or email', 'woolentor' ); ?>&nbsp;<span class="required">*</span></label>
    </p>
    <p class="form-row form-row-last">
        <input class="input-text" type="password" name="password" id="password" placeholder="<?php esc_html_e( 'Password', 'woolentor' ); ?>" autocomplete="current-password" />
        <label for="password"><?php esc_html_e( 'Password', 'woolentor' ); ?>&nbsp;<span class="required">*</span></label>
    </p>
    <div class="clear"></div>

    <?php do_action( 'woocommerce_login_form' ); ?>

    <p class="form-row">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woolentor' ); ?></span>
        </label>
        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
        <input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Login', 'woolentor' ); ?>"><?php esc_html_e( 'Login', 'woolentor' ); ?></button>
    </p>
    <p class="form-row lost_password">
        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woolentor' ); ?></a>
    </p>

    <div class="clear"></div>

    <?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
