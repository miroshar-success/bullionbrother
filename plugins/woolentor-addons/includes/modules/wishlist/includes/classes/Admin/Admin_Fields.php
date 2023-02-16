<?php
namespace WishSuite\Admin;
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
                'id'    => 'wishsuite_general_tabs',
                'title' => esc_html__( 'General Settings', 'wishsuite' )
            ),

            array(
                'id'    => 'wishsuite_settings_tabs',
                'title' => esc_html__( 'Button Settings', 'wishsuite' )
            ),
            
            array(
                'id'    => 'wishsuite_table_settings_tabs',
                'title' => esc_html__( 'Table Settings', 'wishsuite' )
            ),
            
            array(
                'id'    => 'wishsuite_style_settings_tabs',
                'title' => esc_html__( 'Style Settings', 'wishsuite' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function fields_settings() {

        $settings_fields = array(

            'wishsuite_general_tabs' => array(
                array(
                    'name'      => 'enable_login_limit',
                    'label'     => __( 'Limit Wishlist Use', 'wishsuite' ),
                    'type'      => 'checkbox',
                    'default'   => 'off',
                    'desc'      => esc_html__( 'Enable this option to allow only the logged-in users to use the Wishlist feature.', 'wishsuite' ),
                ),

                array(
                    'name'      => 'logout_button',
                    'label'     => __( 'Wishlist Icon Tooltip Text', 'wishsuite' ),
                    'desc'      => __( 'Enter a text for the tooltip that will be shown when someone hover over the Wishlist icon.', 'wishsuite' ),
                    'type'      => 'text',
                    'default'   => __( 'Please login', 'wishsuite' ),
                     'class'    => 'depend_user_login_enable'
                ),

            ),

            'wishsuite_settings_tabs' => array(

                array(
                    'name'  => 'btn_show_shoppage',
                    'label'  => __( 'Show button in product list', 'wishsuite' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                ),

                array(
                    'name'  => 'btn_show_productpage',
                    'label'  => __( 'Show button in single product page', 'wishsuite' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name'    => 'shop_btn_position',
                    'label'   => __( 'Shop page button position', 'wishsuite' ),
                    'desc'    => __( 'You can manage wishlist button position in product list page.', 'wishsuite' ),
                    'type'    => 'select',
                    'default' => 'after_cart_btn',
                    'options' => [
                        'before_cart_btn' => __( 'Before Add To Cart', 'wishsuite' ),
                        'after_cart_btn'  => __( 'After Add To Cart', 'wishsuite' ),
                        'top_thumbnail'   => __( 'Top On Image', 'wishsuite' ),
                        'use_shortcode'   => __( 'Use Shortcode', 'wishsuite' ),
                        'custom_position' => __( 'Custom Position', 'wishsuite' ),
                    ],
                ),

                array(
                    'name'    => 'shop_use_shortcode_message',
                    'headding'=> wp_kses_post('<code>[wishsuite_button]</code> Use this shortcode into your theme/child theme to place the wishlist button.'),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_shop_btn_position_use_shortcode element_section_title_area message-info',
                ),

                array(
                    'name'    => 'shop_custom_hook_message',
                    'headding'=> esc_html__( 'Some themes remove the above positions. In that case, custom position is useful. Here you can place the custom/default hook name & priority to inject & adjust the wishlist button for the product loop.', 'wishsuite' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_shop_btn_position_custom_hook element_section_title_area message-info',
                ),

                array(
                    'name'        => 'shop_custom_hook_name',
                    'label'       => __( 'Hook name', 'wishsuite' ),
                    'desc'        => __( 'e.g: woocommerce_after_shop_loop_item_title', 'wishsuite' ),
                    'type'        => 'text',
                    'class'       => 'depend_shop_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'shop_custom_hook_priority',
                    'label'       => __( 'Hook priority', 'wishsuite' ),
                    'desc'        => __( 'Default: 10', 'wishsuite' ),
                    'type'        => 'text',
                    'class'       => 'depend_shop_btn_position_custom_hook'
                ),

                array(
                    'name'    => 'product_btn_position',
                    'label'   => __( 'Product page button position', 'wishsuite' ),
                    'desc'    => __( 'You can manage wishlist button position in single product page.', 'wishsuite' ),
                    'type'    => 'select',
                    'default' => 'after_cart_btn',
                    'options' => [
                        'before_cart_btn' => __( 'Before Add To Cart', 'wishsuite' ),
                        'after_cart_btn'  => __( 'After Add To Cart', 'wishsuite' ),
                        'after_thumbnail' => __( 'After Image', 'wishsuite' ),
                        'after_summary'   => __( 'After Summary', 'wishsuite' ),
                        'use_shortcode'   => __( 'Use Shortcode', 'wishsuite' ),
                        'custom_position' => __( 'Custom Position', 'wishsuite' ),
                    ],
                ),

                array(
                    'name'    => 'product_use_shortcode_message',
                    'headding'=> wp_kses_post('<code>[wishsuite_button]</code> Use this shortcode into your theme/child theme to place the wishlist button.'),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_product_btn_position_use_shortcode element_section_title_area message-info',
                ),

                array(
                    'name'    => 'product_custom_hook_message',
                    'headding'=> esc_html__( 'Some themes remove the above positions. In that case, custom position is useful. Here you can place the custom/default hook name & priority to inject & adjust the wishlist button for the single product page.', 'wishsuite' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'depend_product_btn_position_custom_hook element_section_title_area message-info',
                ),

                array(
                    'name'        => 'product_custom_hook_name',
                    'label'       => __( 'Hook name', 'wishsuite' ),
                    'desc'        => __( 'e.g: woocommerce_after_single_product_summary', 'wishsuite' ),
                    'type'        => 'text',
                    'class'       => 'depend_product_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'product_custom_hook_priority',
                    'label'       => __( 'Hook priority', 'wishsuite' ),
                    'desc'        => __( 'Default: 10', 'wishsuite' ),
                    'type'        => 'text',
                    'class'       => 'depend_product_btn_position_custom_hook'
                ),

                array(
                    'name'        => 'button_text',
                    'label'       => __( 'Button Text', 'wishsuite' ),
                    'desc'        => __( 'Enter your wishlist button text.', 'wishsuite' ),
                    'type'        => 'text',
                    'default'     => __( 'Wishlist', 'wishsuite' ),
                    'placeholder' => __( 'Wishlist', 'wishsuite' ),
                ),

                array(
                    'name'        => 'added_button_text',
                    'label'       => __( 'Product added text', 'wishsuite' ),
                    'desc'        => __( 'Enter the product added text.', 'wishsuite' ),
                    'type'        => 'text',
                    'default'     => __( 'Product Added', 'wishsuite' ),
                    'placeholder' => __( 'Product Added', 'wishsuite' ),
                ),

                array(
                    'name'        => 'exist_button_text',
                    'label'       => __( 'Already exists in the wishlist text', 'wishsuite' ),
                    'desc'        => wp_kses_post( 'Enter the message for "<strong>already exists in the wishlist</strong>" text.' ),
                    'type'        => 'text',
                    'default'     => __( 'Product already added', 'wishsuite' ),
                    'placeholder' => __( 'Product already added', 'wishsuite' ),
                ),

            ),

            'wishsuite_table_settings_tabs' => array(

                array(
                    'name'    => 'wishlist_page',
                    'label'   => __( 'Wishlist page', 'wishsuite' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => wishsuite_get_post_list(),
                    'desc'    => wp_kses_post('Select a wishlist page for wishlist table. It should contain the shortcode <code>[wishsuite_table]</code>'),
                ),

                array(
                    'name'  => 'after_added_to_cart',
                    'label'  => __( 'Remove from the "Wishlist" after adding to the cart.', 'wishsuite' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name' => 'show_fields',
                    'label' => __('Show fields in table', 'wishsuite'),
                    'desc' => __('Choose which fields should be presented on the product compare page with table.', 'wishsuite'),
                    'type' => 'multicheckshort',
                    'options' => wishsuite_get_available_attributes(),
                    'default' => [
                        'remove'        => esc_html__( 'Remove', 'wishsuite' ),
                        'image'         => esc_html__( 'Image', 'wishsuite' ),
                        'title'         => esc_html__( 'Title', 'wishsuite' ),
                        'price'         => esc_html__( 'Price', 'wishsuite' ),
                        'quantity'      => esc_html__( 'Quantity', 'wishsuite' ),
                        'add_to_cart'   => esc_html__( 'Add To Cart', 'wishsuite' ),
                    ],
                ),

                array(
                    'name'    => 'table_heading',
                    'label'   => __( 'Table heading text', 'wishsuite' ),
                    'desc'    => __( 'You can change table heading text from here.', 'wishsuite' ),
                    'type'    => 'multitext',
                    'options' => wishsuite_table_heading()
                ),

                array(
                    'name' => 'empty_table_text',
                    'label' => __('Empty table text', 'wishsuite'),
                    'desc' => __('Text will be displayed if the user doesn\'t add any product to  the wishlist.', 'wishsuite'),
                    'type' => 'textarea'
                ),

                array(
                    'name'        => 'image_size',
                    'label'       => __( 'Image size', 'wishsuite' ),
                    'desc'        => __( 'Enter your required image size.', 'wishsuite' ),
                    'type'        => 'multitext',
                    'options'     =>[
                        'width'  => esc_html__( 'Width', 'wishsuite' ),
                        'height' => esc_html__( 'Height', 'wishsuite' ),
                    ],
                    'default' => [
                        'width'   => 80,
                        'height'  => 80,
                    ],
                ),

                array(
                    'name'  => 'hard_crop',
                    'label'  => __( 'Image Hard Crop', 'wishsuite' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name'    => 'social_share_button_area_title',
                    'headding'=> esc_html__( 'Social share button', 'wishsuite' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'element_section_title_area',
                ),

                array(
                    'name'  => 'enable_social_share',
                    'label'  => esc_html__( 'Enable social share button', 'wishsuite' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'desc'    => esc_html__( 'Enable social share button.', 'wishsuite' ),
                ),

                array(
                    'name'        => 'social_share_button_title',
                    'label'       => esc_html__( 'Social share button title', 'wishsuite' ),
                    'desc'        => esc_html__( 'Enter your social share button title.', 'wishsuite' ),
                    'type'        => 'text',
                    'default'     => esc_html__( 'Share:', 'wishsuite' ),
                    'placeholder' => esc_html__( 'Share', 'wishsuite' ),
                    'class' => 'depend_social_share_enable'
                ),

                array(
                    'name' => 'social_share_buttons',
                    'label' => esc_html__('Enable share buttons', 'wishsuite'),
                    'desc'    => esc_html__( 'You can manage your social share buttons.', 'wishsuite' ),
                    'type' => 'multicheckshort',
                    'options' => [
                        'facebook'      => esc_html__( 'Facebook', 'wishsuite' ),
                        'twitter'       => esc_html__( 'Twitter', 'wishsuite' ),
                        'pinterest'     => esc_html__( 'Pinterest', 'wishsuite' ),
                        'linkedin'      => esc_html__( 'Linkedin', 'wishsuite' ),
                        'email'         => esc_html__( 'Email', 'wishsuite' ),
                        'reddit'        => esc_html__( 'Reddit', 'wishsuite' ),
                        'telegram'      => esc_html__( 'Telegram', 'wishsuite' ),
                        'odnoklassniki' => esc_html__( 'Odnoklassniki', 'wishsuite' ),
                        'whatsapp'      => esc_html__( 'WhatsApp', 'wishsuite' ),
                        'vk'            => esc_html__( 'VK', 'wishsuite' ),
                    ],
                    'default' => [
                        'facebook'   => esc_html__( 'Facebook', 'wishsuite' ),
                        'twitter'    => esc_html__( 'Twitter', 'wishsuite' ),
                        'pinterest'  => esc_html__( 'Pinterest', 'wishsuite' ),
                        'linkedin'   => esc_html__( 'Linkedin', 'wishsuite' ),
                        'telegram'   => esc_html__( 'Telegram', 'wishsuite' ),
                    ],
                    'class' => 'depend_social_share_enable'
                ),

            ),

            'wishsuite_style_settings_tabs' => array(

                array(
                    'name'    => 'button_style',
                    'label'   => __( 'Button style', 'wishsuite' ),
                    'desc'    => __( 'Choose a style for the wishlist button from here.', 'wishsuite' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'default'     => esc_html__( 'Default style', 'wishsuite' ),
                        'themestyle'  => esc_html__( 'Theme style', 'wishsuite' ),
                        'custom'      => esc_html__( 'Custom style', 'wishsuite' ),
                    ]
                ),

                array(
                    'name'    => 'button_icon_type',
                    'label'   => __( 'Button icon type', 'wishsuite' ),
                    'desc'    => __( 'Choose an icon for the wishlist button from here.', 'wishsuite' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'none'     => esc_html__( 'None', 'wishsuite' ),
                        'default'  => esc_html__( 'Default icon', 'wishsuite' ),
                        'custom'   => esc_html__( 'Custom icon', 'wishsuite' ),
                    ]
                ),

                array(
                    'name'    => 'button_custom_icon',
                    'label'   => __( 'Button custom icon', 'wishsuite' ),
                    'type'    => 'image_upload',
                    'options' => [
                        'button_label' => esc_html__( 'Upload', 'wishsuite' ),   
                        'button_remove_label' => esc_html__( 'Remove', 'wishsuite' ),   
                    ],
                ),

                array(
                    'name'    => 'addedbutton_icon_type',
                    'label'   => __( 'Added Button icon type', 'wishsuite' ),
                    'desc'    => __( 'Choose an icon for the wishlist button from here.', 'wishsuite' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'none'     => esc_html__( 'None', 'wishsuite' ),
                        'default'  => esc_html__( 'Default icon', 'wishsuite' ),
                        'custom'   => esc_html__( 'Custom icon', 'wishsuite' ),
                    ]
                ),

                array(
                    'name'    => 'addedbutton_custom_icon',
                    'label'   => __( 'Added Button custom icon', 'wishsuite' ),
                    'type'    => 'image_upload',
                    'options' => [
                        'button_label' => esc_html__( 'Upload', 'wishsuite' ),   
                        'button_remove_label' => esc_html__( 'Remove', 'wishsuite' ),   
                    ],
                ),

                array(
                    'name'    => 'table_style',
                    'label'   => __( 'Table style', 'wishsuite' ),
                    'desc'    => __( 'Choose a style for the wishlist table here.', 'wishsuite' ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => [
                        'default' => esc_html__( 'Default style', 'wishsuite' ),
                        'custom'  => esc_html__( 'Custom style', 'wishsuite' ),
                    ]
                ),

                array(
                    'name'    => 'button_custom_style_title',
                    'headding'=> __( 'Button custom style', 'wishsuite' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'button_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'button_color',
                    'label' => esc_html__( 'Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the color of the button.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'  => 'button_hover_color',
                    'label' => esc_html__( 'Hover Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the hover color of the button.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'  => 'background_color',
                    'label' => esc_html__( 'Background Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the background color of the button.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'  => 'hover_background_color',
                    'label' => esc_html__( 'Hover Background Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the hover background color of the button.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_padding',
                    'label'   => __( 'Padding', 'wishsuite' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'wishsuite' ),   
                        'right' => esc_html__( 'Right', 'wishsuite' ),   
                        'bottom'=> esc_html__( 'Bottom', 'wishsuite' ),   
                        'left'  => esc_html__( 'Left', 'wishsuite' ),
                        'unit'  => esc_html__( 'Unit', 'wishsuite' ),
                    ],
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_margin',
                    'label'   => __( 'Margin', 'wishsuite' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'wishsuite' ),   
                        'right' => esc_html__( 'Right', 'wishsuite' ),   
                        'bottom'=> esc_html__( 'Bottom', 'wishsuite' ),   
                        'left'  => esc_html__( 'Left', 'wishsuite' ),
                        'unit'  => esc_html__( 'Unit', 'wishsuite' ),
                    ],
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'button_custom_border_radius',
                    'label'   => __( 'Border Radius', 'wishsuite' ),
                    'type'    => 'dimensions',
                    'options' => [
                        'top'   => esc_html__( 'Top', 'wishsuite' ),   
                        'right' => esc_html__( 'Right', 'wishsuite' ),   
                        'bottom'=> esc_html__( 'Bottom', 'wishsuite' ),   
                        'left'  => esc_html__( 'Left', 'wishsuite' ),
                        'unit'  => esc_html__( 'Unit', 'wishsuite' ),
                    ],
                    'class' => 'button_custom_style',
                ),

                array(
                    'name'    => 'table_custom_style_title',
                    'headding'=> __( 'Table custom style', 'wishsuite' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'table_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'table_heading_color',
                    'label' => esc_html__( 'Heading Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the heading color of the wishlist table.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

                array(
                    'name'  => 'table_heading_bg_color',
                    'label' => esc_html__( 'Heading Background Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the heading background color of the wishlist table.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_heading_border_color',
                    'label' => esc_html__( 'Heading Border Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the heading border color of the wishlist table.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

                array(
                    'name'  => 'table_border_color',
                    'label' => esc_html__( 'Border Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the border color of the wishlist table.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

                array(
                    'name'    => 'table_custom_style_add_to_cart',
                    'headding'=> __( 'Add To Cart Button style', 'wishsuite' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'table_custom_style element_section_title_area',
                ),

                array(
                    'name'  => 'table_cart_button_color',
                    'label' => esc_html__( 'Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button color of the wishlist table.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_cart_button_bg_color',
                    'label' => esc_html__( 'Background Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button background color of the wishlist table.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_cart_button_hover_color',
                    'label' => esc_html__( 'Hover Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button hover color of the wishlist table.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),
                array(
                    'name'  => 'table_cart_button_hover_bg_color',
                    'label' => esc_html__( 'Hover Background Color', 'wishsuite' ),
                    'desc'  => wp_kses_post( 'Set the add to cart button hover background color of the wishlist table.', 'wishsuite' ),
                    'type'  => 'color',
                    'class' => 'table_custom_style',
                ),

            ),

        );
        
        return $settings_fields;
    }

    public function plugin_page() {
        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'Wishlist Settings','wishsuite' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';
    }

    public function save_message() {
        if( isset( $_GET['settings-updated'] ) ) {
            ?>
                <div class="updated notice is-dismissible"> 
                    <p><strong><?php esc_html_e('Successfully Settings Saved.', 'wishsuite') ?></strong></p>
                </div>
            <?php
        }
    }

}