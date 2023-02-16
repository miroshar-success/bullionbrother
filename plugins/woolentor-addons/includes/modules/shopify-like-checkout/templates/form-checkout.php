<?php
/**
 * Checkout Form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_info       = get_bloginfo( 'name' );
$get_custom_logo = woolentor_get_option( 'logo', 'woolentor_shopify_checkout_settings', '' );
$get_custom_menu_id = woolentor_get_option( 'custommenu', 'woolentor_shopify_checkout_settings', '0' );
$menu_html = '';

if( !empty( $get_custom_menu_id ) ){
    $custom_menuargs = [
        'echo'       => false,
        'menu'       => $get_custom_menu_id,
        'menu_class' => 'woolentor-checkout__policy-list',
        'menu_id'    => 'menu-'. $get_custom_menu_id,
        'add_li_class'=> 'woolentor-checkout__policy-item',
        'fallback_cb' => '__return_empty_string',
        'container'   => '',
    ];
    // General Menu.
    $menu_html = wp_nav_menu( $custom_menuargs );
}

// Skip shipping tab
$hide_shipping_step_opt = woolentor_get_option( 'hide_shipping_step', 'woolentor_shopify_checkout_settings', '' );
if( $hide_shipping_step_opt != 'on' && WC()->cart->needs_shipping() && WC()->cart->show_shipping() ){
    $hide_shipping_step = false;
} else{
    $hide_shipping_step = true;
}

// Hide cart navigation
if( woolentor_get_option( 'hide_cart_nivigation', 'woolentor_shopify_checkout_settings', '' ) == 'on' ){
    $hide_cart_nivigation = true;
} else{
    $hide_cart_nivigation = false;
}

$cat_page_id = get_option( 'woocommerce_cart_page_id' );
// Label customizations
$labels = array(
    'cart'                      => !empty( $cat_page_id ) ? get_the_title( $cat_page_id ) : __('Cart','woolentor'),
    'information'               => __('Information', 'woolentor'),
    'shipping'                  => __('Shipping', 'woolentor'),
    'payment'                   => __('Payment', 'woolentor'),
    
    'continue_to_shipping'      => __('Continue to shipping', 'woolentor'),
    'contact_information'       => __('Contact information', 'woolentor'),
    'already_have_an_account'   => __('Already have an account?', 'woolentor'),
    'log_in'                    => __('Log in', 'woolentor'),
    'login_form_message'        => __('If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woolentor'),
    'shipping_method'           => __('Shipping Method', 'woolentor'),
    'choose_a_payment_gateway'  => __('Choose a Payment Gateway', 'woolentor'),

    'return_to_cart'            => __('Return to cart', 'woolentor'),
    'continue_to_payment'       => __('Continue to payment', 'woolentor'),
    'return_to_informations'    => __('Return to information', 'woolentor'),
    'place_order'               => __('Place order', 'woolentor'),
    'return_to_shipping'        => __('Return to shipping', 'woolentor'),
);

$customize_labels = woolentor_get_option( 'customize_labels', 'woolentor_shopify_checkout_settings', '' );
if( $customize_labels ){
    $labels_list = woolentor_get_option( 'labels_list', 'woolentor_shopify_checkout_settings', array() );

    if( isset( $labels_list ) && is_array( $labels_list ) ){

        foreach( $labels_list as $repeater ){
            if( empty($repeater['select_tab']) ){
                continue;
            }

            if( $repeater['select_tab'] == 'information' ){
                $dynamic_key = $hide_shipping_step ? 'continue_to_payment' : 'continue_to_shipping';

                $labels['information']    = !empty($repeater['tab_label']) ? $repeater['tab_label'] : $labels['information'];
                $labels[$dynamic_key]     = !empty($repeater['label_1']) ? $repeater['label_1'] : $labels[$dynamic_key];
                $labels['return_to_cart'] = !empty($repeater['label_2']) ? $repeater['label_2'] : $labels['return_to_cart'];
            }

            if( $repeater['select_tab'] == 'shipping' && !$hide_shipping_step ){
                $labels['shipping']               = !empty($repeater['tab_label']) ? $repeater['tab_label'] : $labels['shipping'];
                $labels['continue_to_payment']    = !empty($repeater['label_1']) ? $repeater['label_1'] : $labels['continue_to_payment'];
                $labels['return_to_informations'] = !empty($repeater['label_2']) ? $repeater['label_2'] : $labels['return_to_informations'];
            }

            if( $repeater['select_tab'] == 'payment' ){
                $dynamic_key = $hide_shipping_step ? 'return_to_informations' : 'return_to_shipping';

                $labels['payment']     = !empty($repeater['tab_label']) ? $repeater['tab_label'] : $labels['payment'];
                $labels['place_order'] = !empty($repeater['label_1']) ? $repeater['label_1'] : $labels['place_order'];
                $labels[$dynamic_key]  = !empty($repeater['label_2']) ? $repeater['label_2'] : $labels[$dynamic_key];
            } 
        }

    }
}

$labels = apply_filters( 'woolentor_slc_labels', $labels );
?>

<div class="woolentor-checkout__box woolentor-step--info">
    <div class="woolentor-checkout__container">

        <div class="woolentor-checkout__left-sidebar">
            <div class="woolentor-checkout__header">
                <div class="woolentor-checkout__logo">
                    <?php 
                        if( !empty( $get_custom_logo ) ){
                            echo sprintf('<img src="%s" alt="%s" />',esc_url( $get_custom_logo ), $blog_info );
                        }else if( has_custom_logo() ){
                            ?><div class="site-logo"><?php the_custom_logo(); ?></div><?php
                        }else{
                            echo sprintf('<h1 class="site-title"><a href="%s">%s</a></h1>', esc_url( home_url( '/' ) ), esc_html( $blog_info ) );
                        }
                    ?>
                </div>
                <ul class="woolentor-checkout__breadcrumb">

                    <?php if(!$hide_cart_nivigation): ?>
                    <li class="woolentor-checkout__breadcrumb-item">
                        <a class="woolentor-checkout__breadcrumb-link" href="<?php echo esc_url(wc_get_cart_url()) ?>"><?php echo esc_html($labels['cart']) ?></a>
                    </li>
                     <?php endif; ?>

                    <li class="woolentor-checkout__breadcrumb-item active" data-step="step--info">
                        <span class="woolentor-checkout__breadcrumb-text"><?php echo esc_html($labels['information']) ?></span>
                    </li>

                    <?php if( !$hide_shipping_step ): ?>
                    <li class="woolentor-checkout__breadcrumb-item" data-step="step--shipping">
                        <span class="woolentor-checkout__breadcrumb-text"><?php echo esc_html($labels['shipping']) ?></span>
                    </li>
                    <?php endif; ?>

                    <li class="woolentor-checkout__breadcrumb-item" data-step="step--payment">
                        <span class="woolentor-checkout__breadcrumb-text"><?php echo esc_html($labels['payment']) ?></span>
                    </li>
                </ul>
            </div>
            <div class="woolentor-checkout__body">
                <?php if( !is_user_logged_in() ): ?>
                    <div class="woolentor-checkout__section woolentor-contact-info">
                        <div class="woolentor-checkout__section-header">
                            <h2 class="woolentor-checkout__section-title">
                                <?php echo esc_html($labels['contact_information']) ?>
                            </h2>

                            
                            <p class="woolentor-checkout__item-col">
                                <?php echo esc_html($labels['already_have_an_account']) ?>
                                <a  class="showlogin" href="#"><?php echo esc_html($labels['log_in']) ?></a>
                            </p>
                        </div>

                        <?php
                        woocommerce_login_form(
                            array(
                                'message'  => $labels['login_form_message'],
                                'redirect' => wc_get_checkout_url(),
                                'hidden'   => true,
                            )
                        );
                        ?>
                    </div>
                    <?php endif; ?>

                    <?php 
                    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
                    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
                    do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

                    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

                    <?php if( is_user_logged_in() ): ?>
                    <div class="woolentor-checkout__section woolentor-contact-info">
                        <div class="woolentor-checkout__section-header">
                            <h2 class="woolentor-checkout__section-title">
                                <?php echo esc_html($labels['contact_information']) ?>
                            </h2>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Shipping address Start -->
                    <div class="woolentor-checkout__section woolentor-step--info">
                        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                        
                        <?php $checkout->checkout_form_billing(); ?>

                        <?php
                            if ( true === WC()->cart->needs_shipping_address() ){
                                $checkout->checkout_form_shipping();
                            }
                        ?>
                        <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

                        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                    </div>
                    <!-- Shipping address End -->

                    <?php if( !$hide_shipping_step ): ?>
                    <div class="woolentor-checkout__section woolentor-step--shipping">

                        <?php do_action( 'woocommerce_review_order_before_shipping' ) ?>

                        <div class="woolentor-checkout__section-header">
                            <h2 class="woolentor-checkout__section-title">
                                <?php echo esc_html($labels['shipping_method']) ?>
                            </h2>
                        </div>
                        <table class="woolentor-checkout__shipping-method">
                            <tbody>
                                <?php wc_cart_totals_shipping_html(); ?>
                            </tbody>
                        </table>

                        <?php do_action( 'woocommerce_review_order_after_shipping' ) ?>
                    </div>
                    <?php endif; ?>


                    <!-- Payment -->
                    <div class="woolentor-checkout__section woolentor-step--payment">
                        <div class="woolentor-checkout__section-header">
                            <h2 class="woolentor-checkout__section-title">
                                <?php echo esc_html($labels['choose_a_payment_gateway']) ?>
                            </h2>
                        </div>
                        <div class="woolentor-checkout__row">
                            <?php woocommerce_checkout_payment(); ?>
                        </div>
                    </div>
                    <!-- Payment -->

                    <?php
                        $terms_page_id   = wc_terms_and_conditions_page_id();
                        $terms_page      = $terms_page_id ? get_post( $terms_page_id ) : false;
                        $terms_page_link = get_permalink($terms_page_id);

                        $policy_page_id     = (int) get_option( 'wp_page_for_privacy_policy' );
                        $has_footer_menu = '';
                        if( $terms_page_id || $policy_page_id ){
                            $has_footer_menu = true;
                        }

                        $steps = array(
                            array(
                                'label_1'  => $labels['continue_to_shipping'],
                                'label_2'  => '',
                                'target_1' => 'shipping',
                                'target_2' => '',
                            ),
                            array(
                                'label_1'  => $labels['continue_to_payment'],
                                'label_2'  => $labels['return_to_informations'],
                                'target_1' => 'payment',
                                'target_2' => 'info',
                            ),
                            array(
                                'label_1'  => '',
                                'label_2'  => $labels['return_to_shipping'],
                                'target_1' => '',
                                'target_2' => 'shipping',
                            )
                        );

                        if( $hide_shipping_step ){
                            $steps = array(
                                array(
                                    'label_1'  => $labels['continue_to_payment'],
                                    'label_2'  => '',
                                    'target_1' => 'payment',
                                    'target_2' => '',
                                ),
                                array(
                                    'label_1'  => '',
                                    'label_2'  => '',
                                    'target_1' => '',
                                    'target_2' => '',
                                ),
                                array(
                                    'label_1'  => '',
                                    'label_2'  => $labels['return_to_informations'],
                                    'target_1' => '',
                                    'target_2' => 'info',
                                ),
                            );
                        }
                    ?>
                    <div class="woolentor-checkout__section woolentor-has-footer-menu--<?php echo esc_attr($has_footer_menu ? 'yes' : ''); ?>">

                        <div class="woolentor-checkout__step-footer woolentor-footer--1">
                            <a href="#" data-step="step--<?php echo esc_attr($steps[0]['target_1']) ?>" class="woolentor-checkout__button" type="submit"><?php echo esc_attr($steps[0]['label_1']) ?></a>

                            <?php if(!$hide_cart_nivigation): ?>
                            <a href="<?php echo esc_url(wc_get_cart_url()) ?>" class="woolentor-checkout__text-link"><?php echo esc_html($labels['return_to_cart']) ?></a>
                            <?php endif; ?>
                        </div>

                        <?php if( !$hide_shipping_step ): ?>
                        <div class="woolentor-checkout__step-footer step--shipping woolentor-footer--2">
                            <a href="#" data-step="step--<?php echo esc_attr($steps[1]['target_1']) ?>" class="woolentor-checkout__button" type="submit"><?php echo esc_html($steps[1]['label_1']) ?></a>
                            <a href="#" data-step="step--<?php echo esc_attr($steps[1]['target_2']) ?>" class="woolentor-checkout__text-link"><?php echo esc_html($steps[1]['label_2']) ?></a>
                        </div>
                        <?php endif; ?>

                        <div class="woolentor-checkout__step-footer step--payment woolentor-footer--3">

                            <?php do_action('woocommerce_review_order_before_submit')  ?>

                            <div>
                                <?php
                                    $order_button_text = apply_filters( 'woocommerce_order_button_text', $labels['place_order'] );

                                    echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="woolentor-checkout__button" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

                                <a href="#" data-step="step--<?php echo esc_attr($steps[2]['target_2']) ?>" class="woolentor-checkout__text-link"><?php echo esc_html($steps[2]['label_2']) ?></a>
                            </div>

                            <?php do_action('woocommerce_review_order_after_submit')  ?>

                        </div>

                    </div>

                    <?php
                        if( $has_footer_menu ):
                    ?>
                    <div class="woolentor-checkout__footer">
                        <?php
                            if( !empty( $menu_html ) ):
                                echo $menu_html;
                            else:
                        ?>
                            <ul class="woolentor-checkout__policy-list">
                                <?php if($policy_page_id): ?>
                                    <li><a href="<?php echo esc_url(get_permalink($policy_page_id)) ?>"><?php echo esc_html(get_the_title($policy_page_id)); ?></a></li>
                                <?php endif; ?>

                                <?php if($terms_page_link): ?>
                                    <li><a href="<?php echo esc_url($terms_page_link) ?>"><?php echo esc_html(get_the_title($terms_page_id)) ?></a></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <?php
                        endif;
                    ?>
                </form>
				
				<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

            </div>
        </div>

        <div class="woolentor-checkout__right-sidebar woolentor-shipping-status--<?php echo esc_attr(WC()->cart->needs_shipping_address() ? 'yes' : 'no') ?>">
            
            <div class="woolentor-checkout__header woolentor-checkout-header__responsive">
                <div class="woolentor-checkout__logo">
                    <?php 
                        if( !empty( $get_custom_logo ) ){
                            echo sprintf('<img src="%s" alt="%s" />',esc_url( $get_custom_logo ), $blog_info );
                        }else if( has_custom_logo() ){
                            ?><div class="site-logo"><?php the_custom_logo(); ?></div><?php
                        }else{
                            echo sprintf('<h1 class="site-title"><a href="%s">%s</a></h1>', esc_url( home_url( '/' ) ), esc_html( $blog_info ) );
                        }
                    ?>
                </div>
                <ul class="woolentor-checkout__breadcrumb">
                    <li class="woolentor-checkout__breadcrumb-item">
                        <a class="woolentor-checkout__breadcrumb-link" href="<?php echo esc_url(wc_get_cart_url()) ?>"><?php echo esc_html($labels['cart']) ?></a>
                    </li>
                    <li class="woolentor-checkout__breadcrumb-item active" data-step="step--info">
                        <span class="woolentor-checkout__breadcrumb-text"><?php echo esc_html($labels['information']) ?></span>
                    </li>
                    <li class="woolentor-checkout__breadcrumb-item" data-step="step--shipping">
                        <span class="woolentor-checkout__breadcrumb-text"><?php echo esc_html($labels['shipping']) ?></span>
                    </li>
                    <li class="woolentor-checkout__breadcrumb-item" data-step="step--payment">
                        <span class="woolentor-checkout__breadcrumb-text"><?php echo esc_html($labels['payment']) ?></span>
                    </li>
                </ul>
            </div>

            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div class="woolentor-checkout__prduct-box">
                <?php
                    // Converted to method to avoid duplicate coding and comatibiltity with Order bump plugin
                    Woolentor_Shopify_Like_Checkout::review_order_mini_cart_html();
                ?>
            </div>

            <!-- Coupon Code -->
            <?php woocommerce_checkout_coupon_form(); ?>

            <!-- Review order -->
            <?php woocommerce_order_review(); ?>

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

        </div>
    </div>
</div>