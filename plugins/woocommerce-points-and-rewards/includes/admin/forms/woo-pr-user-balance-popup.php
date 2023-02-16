<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * User Balance Popup
 *
 * This is the code for the pop up user balance, which shows up when an user clicks
 * on the adjust under points column in user listing.
 *
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 **/
?>
        <div class="woo-pr-points-popup-content">

            <div class="woo-pr-points-header">
                <div class="woo-pr-points-header-title"><?php esc_html_e('Edit Users Points Balance', 'woopoints'); ?></div>
                <div class="woo-pr-points-popup-close"><a href="javascript:void(0);" class="woo-pr-points-close-button"><img src="<?php echo esc_url(WOO_POINTS_IMG_URL); ?>/tb-close.png" title="<?php esc_html_e('Close', 'woopoints'); ?>"></a></div>
            </div>

            <div class="woo-pr-points-popup">

                <table class="form-table">
                    <tr>
                        <td width="25%">
                            <?php esc_html_e('ID', 'woopoints'); ?>
                        </td>
                        <td width="35%">
                            <?php esc_html_e('User', 'woopoints'); ?>
                        </td>
                        <td width="40%">
                            <?php esc_html_e('Current Balance', 'woopoints'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong><span id="woo_pr_points_user_id"></span></strong>
                        </td>
                        <td>
                            <strong><span id="woo_pr_points_user_name"></span></strong>
                        </td>
                        <td>
                            <strong><span id="woo_pr_points_user_current_balance"></span></strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label for="woo_pr_points_update_users_balance_amount"><?php esc_html_e('Amount:', 'woopoints'); ?></label><br />
                            <input type="text" value="" id="woo_pr_points_update_users_balance_amount" name="woo_pr_points_update_users_balance[amount]" /><br>
                            <span class="description"><?php esc_html_e('A positive or negative value.', 'woopoints'); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label for="woo_pr_points_update_users_balance_entry"><?php esc_html_e('Log Entry:', 'woopoints'); ?></label><br />
                            <input type="text" value="" id="woo_pr_points_update_users_balance_entry" class="large-text" name="woo_pr_points_update_users_balance[entry]" /><br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label for="woo_pr_points_update_users_balance_expiry_date"><?php esc_html_e('Point Expiry Date:', 'woopoints'); ?></label><br />
                            <input type="text" value="" id="woo_pr_points_update_users_balance_expiry_date" class="woo-pr-datepicker" name="woo_pr_points_update_users_balance[expiry_date]" /><br>
                            <span class="description"><?php esc_html_e('Note: It will not count for negative amount value. (Leave it empty for no expiration.)', 'woopoints'); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <input type="checkbox" value="yes" id="woo_pr_points_update_users_send_email" class="send-email-user" name="woo_pr_points_update_users_send_email" />
                            <label for="woo_pr_points_update_users_send_email"><?php esc_html_e('Send email for this update', 'woopoints'); ?></label><br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <input type="button" class="button button-primary woo-pr-points-left" value="<?php esc_html_e('Update Balance', 'woopoints'); ?>" id="woo_pr_points_update_users_balance_submit" name="woo_pr_points_update_users_balance_submit" />
                            <div class="woo-pr-points-loader woo-pr-points-left"><img src="<?php echo esc_url(WOO_POINTS_IMG_URL); ?>/loader.gif"/></div>
                        </td>
                    </tr>
                </table>

            </div><!--.woo-pr-points-popup-->

        </div><!--.woo-pr-points-popup-content-->
        <div class="woo-pr-points-popup-overlay"></div>