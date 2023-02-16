<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( $block['is_editor'] ){
    \WC()->frontend_includes();
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_checkout_login_form' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';
    if ( $block['is_editor'] ) {
        ?>
            <div class="woocommerce-form-login-toggle">
                <?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'woolentor-pro' ) ) . ' <a href="#" class="showlogin">' . esc_html__( 'Click here to login', 'woolentor-pro' ) . '</a>', 'notice' ); ?>
            </div>
        <?php
        ob_start();
            woocommerce_login_form(
                array(
                    'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woolentor-pro' ),
                    'redirect' => wc_get_checkout_url(),
                    'hidden'   => true,
                )
            );
        $html = ob_get_clean();
        $html = str_replace( ['<form','</form'],['<div','</div'], $html );
        echo $html;
    }else{
        if( is_checkout() ){
            ob_start();
                woocommerce_checkout_login_form();
            $html = ob_get_clean();
            $html = str_replace( ['<form','</form'],['<div','</div'], $html );
            echo $html;
        }
    }
    
echo '</div>';