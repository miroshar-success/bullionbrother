<?php
/**
 * Fields.
 */

namespace Woolentor_Email_Customizer\Admin;

/**
 * Fields class.
 */
class Fields {

	/**
     * Fields constructor.
     */
    public function __construct() {
        // Element tabs admin fields.
        add_filter( 'woolentor_elements_tabs_admin_fields', array( $this, 'email_admin_fields' ) );

        // Template builder.
        if ( did_action( 'elementor/loaded' ) ) {
            add_filter( 'woolentor_template_menu_tabs', array( $this, 'email_template_menu_navs' ) );
            add_filter( 'woolentor_template_types', array( $this, 'email_template_type' ) );
        }
    }

    /**
     * Email admin fields.
     */
    public function email_admin_fields( $fields = array() ) {
        $email_fields = array(
            array(
                'name'      => 'email_widget_heading',
                'headding'  => esc_html__( 'Email', 'woolentor-pro' ),
                'type'      => 'title',
                'class'     => 'woolentor_heading_style_two'
            ),
            array(
                'name'  => 'wl_email_heading',
                'label' => esc_html__( 'Heading', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_image',
                'label' => esc_html__( 'Image', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_text_editor',
                'label' => esc_html__( 'Text Editor', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_video',
                'label' => esc_html__( 'Video', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_button',
                'label' => esc_html__( 'Button', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_divider',
                'label' => esc_html__( 'Divider', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_spacer',
                'label' => esc_html__( 'Spacer', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_nav_menu',
                'label' => esc_html__( 'Nav Menu', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_social_icons',
                'label' => esc_html__( 'Social Icons', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_products',
                'label' => esc_html__( 'Products', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_order_details',
                'label' => esc_html__( 'Order Details', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_downloads',
                'label' => esc_html__( 'Downloads', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_billing_address',
                'label' => esc_html__( 'Billing Address', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_shipping_address',
                'label' => esc_html__( 'Shipping Address', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            array(
                'name'  => 'wl_email_customer_note',
                'label' => esc_html__( 'Customer Note', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
        );

        $fields = array_merge( $fields, $email_fields );

        return $fields;
    }

    /**
     * Email template menu navs.
     */
    public function email_template_menu_navs( $navs ) {
        $emails = woolentor_wc_get_emails( 'title' );

        if ( is_array( $emails ) && ! empty( $emails ) ) {
            foreach ( $emails as $email_id => $email_title ) {
                $email_id = sanitize_text_field( $email_id );
                $email_title = sanitize_text_field( $email_title );

                $email_key = 'email_' . $email_id;
                $email_label = ucwords( $email_title );

                $submenu[ $email_key ] = array(
                    'label' => $email_label,
                );
            }
        } else {
            $submenu = array();
        }

        if ( ! empty( $submenu ) ) {
            $navs['emails'] = array(
                'label' => esc_html__( 'Emails', 'woolentor-pro' ),
                'submenu' => $submenu,
            );
        }

        return $navs;
    }

    /**
     * Email template type.
     */
    public function email_template_type( $types ) {
        $emails = woolentor_wc_get_emails( 'title' );

        if ( is_array( $emails ) && ! empty( $emails ) ) {
            foreach ( $emails as $email_id => $email_title ) {
                $email_id = sanitize_text_field( $email_id );
                $email_title = sanitize_text_field( $email_title );

                $email_key = 'email_' . $email_id;
                $email_label = ucwords( sprintf( esc_html__('Email %1$s','woolentor-pro'), $email_title ) );

                $types[ $email_key ] = array(
                    'label' => $email_label,
                    'optionkey' => $email_key,
                );
            }
        }

        return $types;
    }

}