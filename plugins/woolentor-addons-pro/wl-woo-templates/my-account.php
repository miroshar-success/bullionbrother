<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();
?>

<div class="woocommerce woolentor_myaccount_page">
    <?php
        /**
         * My Account content.
         *
         * @since 7.0.1
         */
        do_action( 'woolentor_woocommerce_account_content' );
        
        remove_action( 'woocommerce_account_content', 'woocommerce_account_content' );
        remove_action( 'woocommerce_account_content', 'woocommerce_output_all_notices', 5 );
        do_action( 'woocommerce_account_content' );
    ?>
</div>