<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="woolentor-admin-sidebar">

    <?php if( !is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') ) :?>
        <div class="woolentor-pro-banner">
            <div class="woolentor-pro-banner-logo"><img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>includes/admin/assets/images/logo.png" alt="<?php echo esc_attr__('Shoplentor Logo','woolentor'); ?>"></div>
            <p class="woolentor-pro-banner-summary"><?php echo esc_html__('ShopLentor is one of the most popular WooCommerce Elementor Addons on WordPress.org. It has been downloaded more than 1,627,298 times and 90,000 stores are using ShopLentor plugin. Why not you?','woolentor'); ?></p>
            <ul class="woolentor-pro-banner-options">
                <li><?php echo esc_html__('100+ Elementor Elements','woolentor'); ?></li>
                <li><?php echo esc_html__('15+ Product Custom Templates','woolentor'); ?></li>
                <li><?php echo esc_html__('10+ Custom Shop Page Templates','woolentor'); ?></li>
                <li><?php echo esc_html__('Cart Page, Checkout, My Account, Registration and Thank you page custom layout template','woolentor'); ?></li>
            </ul>
            <a href="https://woolentor.com/pricing/?utm_source=admin&utm_medium=notice&utm_campaign=free" class="woolentor-pro-banner-btn" target="_blank"><?php echo esc_html__('Get Pro Now','woolentor'); ?><span class="icon">+</span></a>
        </div>
    <?php endif; ?>

    <div class="woolentor-rating">
        <div class="woolentor-rating-icon">
            <img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>includes/admin/assets/images/icons/rating.png" alt="<?php echo esc_attr__('Rating icon','woolentor'); ?>">
        </div>
        <div class="woolentor-rating-intro">
            <p><?php echo esc_html__('If you’re loving how our product has helped your business, please let the WordPress community know by ','woolentor'); ?><a target="_blank" href="https://wordpress.org/support/plugin/woolentor-addons/reviews/?filter=5#new-post"><?php echo esc_html__('leaving us a review on our WP repository','woolentor'); ?></a><?php echo esc_html__('. Which will motivate us a lot.','woolentor'); ?></p>
        </div>
    </div>
    <div class="woolentor-rating-trustpilot">
        <a href="https://www.trustpilot.com/review/woolentor.com" target="_blank" >
            <img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>includes/admin/assets/images/trustpilot.png" alt="<?php echo esc_attr__('Shoplentor trustpilot rating','woolentor'); ?>">
        </a>
    </div>
</div>