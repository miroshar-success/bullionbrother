<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * WooCommerce Openpos Plugin Compability Class
 *
 * @package WooCommerce - Points and Rewards
 * @since 2.2.1
 */

class Woo_Pr_Openpos
{

    /**
     * Add customer available points to customer data
     *
     * Handles to add customer available points to customer data
     *
     * @package WooCommerce - Points and Rewards
     * @since 2.2.1
     *
     */

    public function woo_pr_op_wc_points_rewards_user_data($user_data)
    {

        $customer_id = $user_data['id'];

        // Get redemption points from settings page
        $points = intval(get_option('woo_pr_redeem_points'));

        // Get redemption ration from settings page
        $rate = intval(get_option('woo_pr_redeem_points_monetary_value'));

        $user_data['point_rate'] = 1;

        if (!empty($points) && !empty($rate))
        {

            $user_data['point_rate'] = ($points / $rate);
        }

        // get maximum possible discount
        $discount = $this->woo_pr_op_get_discount_for_redeeming_points_from_cart(json_decode(stripslashes($_REQUEST['cart'])) , $customer_id);

        $ubalance = $discount;

        $user_data['point'] = $ubalance;

        $user_data['balance'] = $discount;

        $user_data['point_setting'] = array();

        return $user_data;

    }

    /**
     * Add customer available points to customer data
     *
     * Handles to add customer available points to customer data
     *
     * @package WooCommerce - Points and Rewards
     * @since 2.2.1
     *
     */

    public function woo_pr_op_wc_points_rewards_add_order_before($order, $order_data)
    {

        global $woo_pr_model, $woo_pr_log;

        $point_discount = isset($order_data['point_discount']) ? $order_data['point_discount'] : array();

        if (!empty($point_discount) && ($point_discount['point'] > 0 || $point_discount['point_money'] > 0))
        {

            $points = ($point_discount['point_money'] * $point_discount['point_rate']);

            $point_label = ($point_discount['point'] > 0) ? $point_discount['point'] : $points;

            $title = __('Redeem ' . $point_label . '  points for a ' . strip_tags(wc_price($point_discount['point_money'])) . ' discount', 'openpos');

            $discount_code = $title;

            $discount_amount = $point_discount['point_money'];

            if ($discount_amount)
            {

                $discount_amount = 0 - $discount_amount;

                $point_item = new WC_Order_Item_Fee();

                $point_item->set_name($discount_code);

                $point_item->set_tax_status('non-taxable');

                $point_item->set_taxes([]);

                $point_item->set_amount($discount_amount);

                $point_item->set_total($discount_amount);

                $order->add_item($point_item);

            }

            $order_id = $order->get_id();

            // Redeem points
            $args = array(
                'post_type' => 'woopointslog',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => '_woo_log_order_id',
                        'value' => $order_id,
                        'type' => 'numeric',
                        'compare' => '=',
                    ) ,

                    array(
                        'key' => '_woo_log_events',
                        'value' => 'redeemed_purchase',
                        'compare' => 'LIKE',
                    ) ,
                )

            );

            $redeem_log = get_posts($args);

            //global $current_user;
            $prefix = WOO_PR_META_PREFIX;

            $order_data = $order->data;

            $redeem_point_amount = $point_label;

            //points plural label
            $plurallable = !empty(get_option('woo_pr_lables_points_monetary_value')) ? get_option('woo_pr_lables_points_monetary_value') : esc_html__('Points', 'woopoints');

            if (!empty($redeem_point_amount) && empty($redeem_log))
            {

                $user_id = $order->get_customer_id();

                $current_points = $redeem_point_amount;

                $points_label = $woo_pr_model->woo_pr_get_points_label($current_points);

                $log_title = $points_label . esc_html__(' redeemed towards purchase', 'woopoints');

                //check number contains minus sign or not
                if (strpos($current_points, '-') !== false)
                {

                    $current_points = str_replace('-', '', $current_points);
                }

                // Update user points to user account
                woo_pr_minus_points_from_user($current_points, $user_id);

                $post_data = array(
                    'post_title' => $log_title,
                    'post_content' => $log_title,
                    'post_author' => $user_id
                );

                $log_meta = array(
                    'order_id' => $order_id,
                    'userpoint' => abs($current_points) ,
                    'events' => 'redeemed_purchase',
                    'operation' => 'minus'
                    //add or minus
                    
                );

                $woo_pr_log->woo_pr_insert_logs($post_data, $log_meta);

                // Add order note for Points and Rewards
                $order->add_order_note(sprintf(esc_html__('%1$d %2$s debited discount towards to customer.', 'woopoints') , $current_points, $plurallable));

                $enable_redeem_email = get_option('woo_pr_enable_redeem_email');

                $redeem_email_subject = get_option('woo_pr_redeem_point_email_subject');

                $redeem_email_content = get_option('woo_pr_redeem_point_email_content');

                if ($enable_redeem_email == 'yes' && !empty($redeem_email_subject) && !empty($redeem_email_content))
                {

                    $headers = "MIME-Version: 1.0" . "\r\n";

                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    $to_email = $order->get_billing_email();

                    $customer_data = get_userdata($order->get_customer_id());

                    $username = $customer_data->user_login;

                    $total_point = woo_pr_get_user_points($order->get_customer_id());

                    $total_points_amount = $woo_pr_model->woo_pr_calculate_discount_amount($total_point);

                    $site_url = '<a href="' . site_url() . '">' . site_url() . '</a>';

                    $site_url_text = site_url();

                    $site_title = get_option('blogname');

                    $latest_update = esc_html__("product purchase", "woopoints");

                    $total_amount_message = sprintf(esc_html__("%s which are worth an amount of %s", "woopoints") , $total_point, wc_price(woo_pr_wcm_currency_convert($total_points_amount)));

                    $tags_arr = array(
                        '{username}',
                        '{redeemed_point}',
                        '{point_label}',
                        '{latest_update}',
                        '{total_point}',
                        '{site_url}',
                        '{site_title}'
                    );

                    $replace_arr = array(
                        $username,
                        $current_points,
                        $points_label,
                        $latest_update,
                        $total_amount_message,
                        $site_url,
                        $site_title
                    );

                    $email_content = str_replace($tags_arr, $replace_arr, $redeem_email_content);

                    $find_subject = array(
                        '{latest_update}',
                        '{site_url}',
                        '{site_title}',
                        '{redeemed_point}'
                    );

                    $replace_subject = array(
                        $latest_update,
                        $site_url_text,
                        $site_title,
                        $current_points
                    );

                    $email_subject = str_replace($find_subject, $replace_subject, $redeem_email_subject);

                    wp_mail($to_email, $email_subject, $email_content, $headers);

                }

            }

        }

    }

    /**
     * Redeem points before order is created
     *
     * Handles to redeem points before order is created
     *
     * @package WooCommerce - Points and Rewards
     * @since 2.2.1
     *
     */

    public function woo_pr_op_get_discount_for_redeeming_points_from_cart($cart, $customer_id)
    {

        global $woo_pr_model;

        $cartsubtotal = $cart->sub_total;

        $cartdata = $cart->items;

        $userid = $customer_id;

        $available_user_points = $woo_pr_model->woo_pr_get_userpoints_value();

        //get user points from user account
        $user_points = get_user_meta($userid, WOO_PR_META_PREFIX . 'userpoints', true);

        //user points
        $available_user_disc = !empty($user_points) ? $user_points : '0';

        //check user has points or not
        if (empty($available_user_disc) || $available_user_disc <= 0)
        {
            return 0;

        }
        else
        {

            $available_user_disc = $woo_pr_model->woo_pr_calculate_discount_amount($available_user_disc);
        }

        $discount_applied = 0;

        if (!empty($cartdata))
        {

            foreach ($cartdata as $cart_item)
            {

                //max discount
                $max_discount = $woo_pr_model->woo_pr_get_max_points_discount_for_product($cart_item->product_id);

                $data_id = $cart_item->product_id;

                $is_allowed = $woo_pr_model->wpp_pr_check_product_allow_for_points_redeem($data_id);

                if ($is_allowed == false)
                {
                    continue;
                }

                if (is_numeric($max_discount))
                {

                    // adjust the max discount by the quantity being ordered
                    $max_discount *= $cart_item->qty;

                }
                else
                {

                    $prevent_coupon = !empty(get_option('woo_pr_prevent_coupon_usag')) ? get_option('woo_pr_prevent_coupon_usag') : 'no';

                    if ($prevent_coupon == 'yes')
                    {

                        $max_discount = woo_pr_wcm_currency_convert_original($cart_item->total);

                    }
                    else
                    {

                        //when maximum discount is not set for product then allow maximum total cost of product as a discount
                        $cart_item->total_incl_tax = apply_filters('woo_pr_add_tax_to_reedemed_points_label', $cart_item->total_incl_tax, $cart_item->tax_amount);

                        $max_discount = woo_pr_wcm_currency_convert_original($cart_item->total_incl_tax);

                    }

                }

                $discount_applied += $max_discount;

            }

        }

        if ($discount_applied >= $cartsubtotal)
        {

            //Convert to ordignal currency
            $cartsubtotal = woo_pr_wcm_currency_convert_original($cartsubtotal);

            $discount_applied = max(0, min($discount_applied, $cartsubtotal));

        }

        // limit the discount available by the global maximum discount if set
        $cart_max_discount = !empty(get_option('woo_pr_cart_max_discount')) ? get_option('woo_pr_cart_max_discount') : '';

        if (!empty($cart_max_discount) && ($cart_max_discount < $discount_applied))
        {

            $discount_applied = $cart_max_discount;

        }

        // if the discount available is greater than the max discount, apply the max discount
        $discount_applied = ($available_user_disc <= $discount_applied) ? $available_user_disc : $discount_applied;

        return $woo_pr_model->woo_pr_calculate_points($discount_applied);

    }

    /**
     * Adding Hooks
     *
     * @package WooCommerce - Points and Rewards
     * @since 2.2.1
     */

    public function add_hooks()
    {

        // Add filter to add customer available points to customer data
        add_filter('op_customer_data', array(
            $this,
            'woo_pr_op_wc_points_rewards_user_data'
        ) , 10, 1);

        // Add action to redeem points before order is created
        add_action('op_add_order_before', array(
            $this,
            'woo_pr_op_wc_points_rewards_add_order_before'
        ) , 10, 2);

    }

}
