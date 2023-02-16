<?php
namespace EverCompare\Admin;
/**
 * Admin Page Fields handlers class
 */
class Admin_Fields {

    private $settings_api;

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Admin]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {
        require_once( WOOLENTOR_ADDONS_PL_PATH .'includes/admin/include/settings_field_manager_default.php' );
        $this->settings_api = new \WooLentor_Settings_Field_Manager_Default();
        add_action( 'admin_init', [ $this, 'admin_init' ] );
    }

    public function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Options page Section register
    public function get_settings_sections() {
        $sections = array(

            array(
                'id'    => 'ever_compare_settings_tabs',
                'title' => esc_html__( 'Button Settings', 'woolentor' ),
            ),
            
            array(
                'id'    => 'ever_compare_table_settings_tabs',
                'title' => esc_html__( 'Table Settings', 'woolentor' )
            ),
            array(
                'id'    => 'ever_compare_style_tabs',
                'title' => esc_html__( 'Style Settings', 'woolentor' ),
            )

        );
        return $sections;
    }

    // Options page field register
    protected function fields_settings() {

        $settings_fields = array(

            'ever_compare_settings_tabs' => array(

                array(
                    'name'  => 'btn_show_shoppage',
                    'label'  => __( 'Show button in product list page', 'woolentor' ),
                    'desc'  => __( 'Show compare button in product list page.', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                ),

                array(
                    'name'  => 'btn_show_productpage',
                    'label'  => __( 'Show button in single product page', 'woolentor' ),
                    'desc'  => __( 'Show compare button in single product page.', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name'    => 'shop_btn_position',
                    'label'   => __( 'Shop page button position', 'woolentor' ),
                    'desc'    => __( 'You can manage compare button position in product list page.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'after_cart_btn',
                    'options' => [
                        'before_cart_btn' => __( 'Before Add To Cart', 'woolentor' ),
                        'after_cart_btn'  => __( 'After Add To Cart', 'woolentor' ),
                        'top_thumbnail'   => __( 'Top On Image', 'woolentor' ),
                        'use_shortcode'   => __( 'Use Shortcode', 'woolentor' ),
                        'custom_position' => __( 'Custom Position', 'woolentor' ),
                    ],
                ),

                array(
                    'name'    => 'shop_use_shortcode_message',
                    'headding'=> wp_kses_post('<code>[evercompare_button]</code> Use this shortcode into your theme/child theme to place the compare button.'),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_shop_btn_position_use_shortcode element_section_title_area message-info',
                ),

                array(
                    'name'    => 'shop_custom_hook_message',
                    'headding'=> esc_html__( 'Some themes remove the above positions. In that case, custom position is useful. Here you can place the custom/default hook name & priority to inject & adjust the compare button for the product loop.', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_shop_btn_position_custom_hook element_section_title_area message-info',
                ),

                array(
                    'name'        => 'shop_custom_hook_name',
                    'label'       => __( 'Hook name', 'woolentor' ),
                    'desc'        => __( 'e.g: woocommerce_after_shop_loop_item_title', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_shop_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'shop_custom_hook_priority',
                    'label'       => __( 'Hook priority', 'woolentor' ),
                    'desc'        => __( 'Default: 10', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_shop_btn_position_custom_hook'
                ),

                array(
                    'name'    => 'product_btn_position',
                    'label'   => __( 'Product page button position', 'woolentor' ),
                    'desc'    => __( 'You can manage compare button position in single product page.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'after_cart_btn',
                    'options' => [
                        'before_cart_btn' => __( 'Before Add To Cart', 'woolentor' ),
                        'after_cart_btn'  => __( 'After Add To Cart', 'woolentor' ),
                        'after_thumbnail' => __( 'After Image', 'woolentor' ),
                        'after_summary'   => __( 'After Summary', 'woolentor' ),
                        'use_shortcode'   => __( 'Use Shortcode', 'woolentor' ),
                        'custom_position' => __( 'Custom Position', 'woolentor' ),
                    ],
                ),

                array(
                    'name'    => 'product_use_shortcode_message',
                    'headding'=> wp_kses_post('<code>[evercompare_button]</code> Use this shortcode into your theme/child theme to place the compare button.'),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_product_btn_position_use_shortcode element_section_title_area message-info',
                ),

                array(
                    'name'    => 'product_custom_hook_message',
                    'headding'=> esc_html__( 'Some themes remove the above positions. In that case, custom position is useful. Here you can place the custom/default hook name & priority to inject & adjust the compare button for the single product page.', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_product_btn_position_custom_hook element_section_title_area message-info',
                ),

                array(
                    'name'        => 'product_custom_hook_name',
                    'label'       => __( 'Hook name', 'woolentor' ),
                    'desc'        => __( 'e.g: woocommerce_after_single_product_summary', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_product_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'product_custom_hook_priority',
                    'label'       => __( 'Hook priority', 'woolentor' ),
                    'desc'        => __( 'Default: 10', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_product_btn_position_custom_hook'
                ),

                array(
                    'name'  => 'open_popup',
                    'label'  => __( 'Open popup', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'desc'    => __( 'You can manage the popup window from here.', 'woolentor' ),
                ),

                array(
                    'name'        => 'button_text',
                    'label'       => __( 'Button text', 'woolentor' ),
                    'desc'        => __( 'Enter your compare button text.', 'woolentor' ),
                    'type'        => 'text',
                    'default'     => __( 'Compare', 'woolentor' ),
                    'placeholder' => __( 'Compare', 'woolentor' ),
                ),

                array(
                    'name'        => 'added_button_text',
                    'label'       => __( 'Added button text', 'woolentor' ),
                    'desc'        => __( 'Enter your compare added button text.', 'woolentor' ),
                    'type'        => 'text',
                    'default'     => __( 'Added', 'woolentor' ),
                    'placeholder' => __( 'Added', 'woolentor' ),
                ),

                array(
                    'name'    => 'button_icon_type',
                    'label'   => esc_html__( 'Button icon type', 'woolentor' ),
                    'desc'    => esc_html__( 'Choose an icon type for the compare button from here.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'none'     => esc_html__( 'None', 'woolentor' ),
                        'default'  => esc_html__( 'Default', 'woolentor' ),
                        'custom'   => esc_html__( 'Custom', 'woolentor' ),
                    ]
                ),

                array(
                    'name'    => 'button_custom_icon',
                    'label'   => esc_html__( 'Button custom icon', 'woolentor' ),
                    'type'    => 'image_upload',
                    'options' => [
                        'button_label'        => esc_html__( 'Upload', 'woolentor' ),   
                        'button_remove_label' => esc_html__( 'Remove', 'woolentor' ),
                    ],
                    'desc'    => esc_html__( 'Upload you custom icon from here.', 'woolentor' ),
                    'class'   => 'depend_button_icon_type_custom',
                ),

                array(
                    'name'    => 'added_button_icon_type',
                    'label'   => __( 'Added button icon type', 'woolentor' ),
                    'desc'    => __( 'Choose an icon for the compare button from here.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'none'     => esc_html__( 'None', 'woolentor' ),
                        'default'  => esc_html__( 'Default', 'woolentor' ),
                        'custom'   => esc_html__( 'Custom', 'woolentor' ),
                    ]
                ),

                array(
                    'name'    => 'added_button_custom_icon',
                    'label'   => __( 'Added button custom icon', 'woolentor' ),
                    'type'    => 'image_upload',
                    'options' => [
                        'button_label'        => esc_html__( 'Upload', 'woolentor' ),   
                        'button_remove_label' => esc_html__( 'Remove', 'woolentor' ),   
                    ],
                    'class' => 'depend_added_button_icon_type_custom',
                ),

            ),

            'ever_compare_table_settings_tabs' => array(

                array(
                    'name'    => 'compare_page',
                    'label'   => __( 'Compare page', 'woolentor' ),
                    'desc' => wp_kses_post('Select a compare page for compare table. It should contain the shortcode <code>[evercompare_table]</code>'),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => ever_compare_get_post_list()
                ),

                array(
                    'name'  => 'enable_shareable_link',
                    'label'  => __( 'Enable shareable link', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'desc'    => __( 'If you enable this you can easily share your compare page link with specific products.', 'woolentor' ),
                ),

                array(
                    'name'    => 'linkshare_btn_pos',
                    'label'   => __( 'Share link button position', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'right',
                    'options' => [
                        'left' => __('Left','woolentor'),
                        'center' => __('Center','woolentor'),
                        'right' => __('Right','woolentor')
                    ],
                    'class'       => 'depend_enable_shareable_link'
                ),

                array(
                    'name'        => 'shareable_link_button_text',
                    'label'       => __( 'Share link button text', 'woolentor' ),
                    'placeholder' => __( 'Copy shareable link', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_enable_shareable_link'
                ),

                array(
                    'name'        => 'shareable_link_after_button_text',
                    'label'       => __( 'Text to show after link is copied', 'woolentor' ),
                    'placeholder' => __( 'Copied', 'woolentor' ),
                    'type'        => 'text',
                    'class'       => 'depend_enable_shareable_link'
                ),

                array(
                    'name'    => 'limit',
                    'label'   => esc_html__( 'Limit', 'woolentor' ),
                    'desc'    => esc_html__( 'You can manage your maximum compare quantity from here.', 'woolentor' ),
                    'type'    => 'number',
                    'min'              => 1,
                    'max'              => 1500,
                    'step'             => 1,
                    'default'          => 10,
                    'sanitize_callback' => 'floatval',
                ),

                array(
                    'name' => 'show_fields',
                    'label' => __('Show fields in table', 'woolentor'),
                    'desc' => __('Choose which fields should be presented on the product compare page with table.', 'woolentor'),
                    'type' => 'multicheckshort',
                    'options' => ever_compare_get_available_attributes(),
                    'default' => [
                        'title'         => esc_html__( 'title', 'woolentor' ),
                        'ratting'       => esc_html__( 'ratting', 'woolentor' ),
                        'price'         => esc_html__( 'price', 'woolentor' ),
                        'add_to_cart'   => esc_html__( 'add_to_cart', 'woolentor' ),
                        'description'   => esc_html__( 'description', 'woolentor' ),
                        'availability'  => esc_html__( 'availability', 'woolentor' ),
                        'sku'           => esc_html__( 'sku', 'woolentor' ),
                        'weight'        => esc_html__( 'weight', 'woolentor' ),
                        'dimensions'    => esc_html__( 'dimensions', 'woolentor' ),
                    ],
                ),

                array(
                    'name'    => 'table_heading_section_title',
                    'headding'=> esc_html__( 'Custom heading', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'element_section_title_area',
                ),

                array(
                    'name'    => 'table_heading',
                    'label'   => __( 'Fields heading text', 'woolentor' ),
                    'desc'    => __( 'You can change heading text from here.', 'woolentor' ),
                    'type'    => 'multitext',
                    'options' => ever_compare_table_heading()
                ),

                array(
                    'name' => 'reached_max_limit_message',
                    'label' => __('Reached maximum limit message', 'woolentor'),
                    'desc' => __('You can manage message for maximum product added in the compare table.', 'woolentor'),
                    'type' => 'textarea'
                ),

                array(
                    'name' => 'empty_table_text',
                    'label' => __('Empty compare page text', 'woolentor'),
                    'desc' => __('Text will be displayed if user don\'t add any products to compare', 'woolentor'),
                    'type' => 'textarea'
                ),

                array(
                    'name'        => 'shop_button_text',
                    'label'       => __( 'Return to shop button text', 'woolentor' ),
                    'desc'        => __( 'Enter your return to shop button text.', 'woolentor' ),
                    'type'        => 'text',
                    'default'     => __( 'Return to shop', 'woolentor' ),
                    'placeholder' => __( 'Return to shop', 'woolentor' ),
                ),

                array(
                    'name'        => 'image_size',
                    'label'       => __( 'Image size', 'woolentor' ),
                    'desc'        => __( 'Enter your required image size.', 'woolentor' ),
                    'type'        => 'multitext',
                    'options'     =>[
                        'width' => esc_html__( 'Width', 'woolentor' ),
                        'height' => esc_html__( 'Height', 'woolentor' ),
                    ],
                    'default' => [
                        'width'   => 300,
                        'height'  => 300,
                    ],
                ),

                array(
                    'name'  => 'hard_crop',
                    'label'  => __( 'Image Hard Crop', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

            ),

            'ever_compare_style_tabs' => array(

                array(
                    'name'    => 'button_style',
                    'label'   => esc_html__( 'Button style', 'woolentor' ),
                    'desc'    => esc_html__( 'Choose a style for the compare button from here.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'theme',
                    'options' => [
                        'default'   => esc_html__( 'Default', 'woolentor' ),
                        'theme'     => esc_html__( 'Theme', 'woolentor' ),
                        'custom'    => esc_html__( 'Custom', 'woolentor' ),
                    ]
                ),

                array(
                    'name'    => 'table_style',
                    'label'   => esc_html__( 'Table style', 'woolentor' ),
                    'desc'    => esc_html__( 'Choose a table style from here.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'default'   => esc_html__( 'Default', 'woolentor' ),
                        'custom'    => esc_html__( 'Custom', 'woolentor' ),
                    ]
                ),

                array(
                    'name'    => 'button_custom_style_area_title',
                    'headding'=> esc_html__( 'Button custom style', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_button_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'button_color',
                    'label' => esc_html__( 'Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the color of the button.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_button_custom_style',
                ),

                array(
                    'name'  => 'button_hover_color',
                    'label' => esc_html__( 'Hover Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the hover color of the button.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_button_custom_style',
                ),

                array(
                    'name'  => 'background_color',
                    'label' => esc_html__( 'Background Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the background color of the button.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_button_custom_style',
                ),

                array(
                    'name'  => 'hover_background_color',
                    'label' => esc_html__( 'Hover Background Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the hover background color of the button.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_padding',
                    'label'   => __( 'Padding', 'woolentor' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'woolentor' ),   
                        'right' => esc_html__( 'Right', 'woolentor' ),   
                        'bottom'=> esc_html__( 'Bottom', 'woolentor' ),   
                        'left'  => esc_html__( 'Left', 'woolentor' ),
                        'unit'  => esc_html__( 'Unit', 'woolentor' ),
                    ],
                    'class' => 'depend_button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_margin',
                    'label'   => __( 'Margin', 'woolentor' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'woolentor' ),   
                        'right' => esc_html__( 'Right', 'woolentor' ),   
                        'bottom'=> esc_html__( 'Bottom', 'woolentor' ),   
                        'left'  => esc_html__( 'Left', 'woolentor' ),
                        'unit'  => esc_html__( 'Unit', 'woolentor' ),
                    ],
                    'class' => 'depend_button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_border',
                    'label'   => __( 'Border width', 'woolentor' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'woolentor' ),   
                        'right' => esc_html__( 'Right', 'woolentor' ),   
                        'bottom'=> esc_html__( 'Bottom', 'woolentor' ),   
                        'left'  => esc_html__( 'Left', 'woolentor' ),
                        'unit'  => esc_html__( 'Unit', 'woolentor' ),
                    ],
                    'class' => 'depend_button_custom_style',
                ),
                array(
                    'name'  => 'button_custom_border_color',
                    'label' => esc_html__( 'Border Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the button color of the button.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_border_radius',
                    'label'   => __( 'Border Radius', 'woolentor' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'woolentor' ),   
                        'right' => esc_html__( 'Right', 'woolentor' ),   
                        'bottom'=> esc_html__( 'Bottom', 'woolentor' ),   
                        'left'  => esc_html__( 'Left', 'woolentor' ),
                        'unit'  => esc_html__( 'Unit', 'woolentor' ),
                    ],
                    'class' => 'depend_button_custom_style',
                ),

                array(
                    'name'    => 'table_custom_style_area_title',
                    'headding'=> esc_html__( 'Table custom style', 'woolentor' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_table_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'table_border_color',
                    'label' => esc_html__( 'Border color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the border color of the table.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_table_custom_style',
                ),

                array(
                    'name'    => 'table_column_padding',
                    'label'   => __( 'Column Padding', 'woolentor' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'woolentor' ),   
                        'right' => esc_html__( 'Right', 'woolentor' ),   
                        'bottom'=> esc_html__( 'Bottom', 'woolentor' ),   
                        'left'  => esc_html__( 'Left', 'woolentor' ),
                        'unit'  => esc_html__( 'Unit', 'woolentor' ),
                    ],
                    'class' => 'depend_table_custom_style',
                ),

                array(
                    'name'  => 'table_event_color',
                    'label' => esc_html__( 'Column background color (Event)', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the background color of the table event column.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_table_custom_style',
                ),

                array(
                    'name'  => 'table_odd_color',
                    'label' => esc_html__( 'Column background color (Odd)', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the background color of the table odd column.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_table_custom_style',
                ),

                array(
                    'name'  => 'table_heading_event_color',
                    'label' => esc_html__( 'Heading color (Event)', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the heading color of the table event column.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_table_custom_style',
                ),

                array(
                    'name'  => 'table_heading_odd_color',
                    'label' => esc_html__( 'Heading color (Odd)', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the heading color of the table odd column.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_table_custom_style',
                ),

                array(
                    'name'  => 'table_content_event_color',
                    'label' => esc_html__( 'Content color (Event)', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the content color of the table event column.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_table_custom_style',
                ),

                array(
                    'name'  => 'table_content_odd_color',
                    'label' => esc_html__( 'Content color (Odd)', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the content color of the table odd column.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_table_custom_style',
                ),

                array(
                    'name'  => 'table_content_link_color',
                    'label' => esc_html__( 'Content link color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the content link color of the table.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_table_custom_style',
                ),

                array(
                    'name'  => 'table_content_link_hover_color',
                    'label' => esc_html__( 'Content link hover color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Set the content link hover color of the table.', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'depend_table_custom_style',
                ),

            ),

        );
        
        return $settings_fields;
    }

    public function plugin_page() {
        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'Compare Settings','woolentor' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';
    }

    public function save_message() {
        if( isset( $_GET['settings-updated'] ) ) { 
            ?>
                <div class="updated notice is-dismissible"> 
                    <p><strong><?php esc_html_e('Successfully Settings Saved.', 'woolentor') ?></strong></p>
                </div>
            <?php
        }
    }

}