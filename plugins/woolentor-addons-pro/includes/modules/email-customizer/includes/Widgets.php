<?php
/**
 * Widgets.
 */

namespace Woolentor_Email_Customizer;

/**
 * Widgets class.
 */
class Widgets {

	/**
     * Widgets constructor.
     */
    public function __construct() {
        add_filter( 'woolentor_widget_list', array( $this, 'widget_list' ) );
        add_filter( 'woolentor_load_widget_list', array( $this, 'load_widget_list' ), 999, 3 );
    }

    /**
     * Widget list.
     */
    public function widget_list( $widget_list = array() ) {
        $widget_list['emails'] = array(
            'wl_email_heading' => array(
                'title'    => esc_html__('Heading','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_image' => array(
                'title'    => esc_html__('Image','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_text_editor' => array(
                'title'    => esc_html__('Text Editor','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_video' => array(
                'title'    => esc_html__('Video','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_button' => array(
                'title'    => esc_html__('Button','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_divider' => array(
                'title'    => esc_html__('Divider','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_spacer' => array(
                'title'    => esc_html__('Spacer','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_nav_menu' => array(
                'title'    => esc_html__('Nav Menu','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_social_icons' => array(
                'title'    => esc_html__('Social Icons','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_products' => array(
                'title'    => esc_html__('Products','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_order_details' => array(
                'title'    => esc_html__('Order Details','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_downloads' => array(
                'title'    => esc_html__('Downloads','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_billing_address' => array(
                'title'    => esc_html__('Billing Address','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_shipping_address' => array(
                'title'    => esc_html__('Shipping Address','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
            'wl_email_customer_note' => array(
                'title'    => esc_html__('Customer Note','woolentor-pro'),
                'location' => WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH,
                'is_pro'   => true,
            ),
        );

        return $widget_list;
    }

    /**
     * Load widget list.
     */
    public function load_widget_list( $widget_list = array(), $widget_list_group = array(), $tmpType = '' ) {
        if ( 'emails' === $tmpType || isset( $_REQUEST['woolentor_email_args'] ) ) {
            $is_builder = ( woolentor_get_option( 'enablecustomlayout', 'woolentor_woo_template_tabs', 'on' ) == 'on' ) ? true : false;
            $template_wise  = ( $is_builder == true && $tmpType !== '' && array_key_exists( $tmpType, $widget_list_group ) ) ? $widget_list_group[$tmpType] : [];

            if ( ! empty( $template_wise ) ) {
                $widget_list = $template_wise;
            }
        } else {
            foreach ( $widget_list as $key => $value ) {
                if ( false !== strpos( $key, 'wl_email' ) ) {
                    unset( $widget_list[ $key ] );
                }
            }
        }

        return $widget_list;
    }

}