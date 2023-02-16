<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Product_Grid_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-grid';
    }

    public function get_title() {
        return __( 'WL: Product Grid', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','woolentor-product-grid','woolentor-widgets'];
    }

    public function get_script_depends() {
        return ['slick','woolentor-widgets-scripts'];
    }

    public function get_keywords(){
        return ['woolentor','product','grid','Custom product','WooCommerce'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'product_grid_content',
            [
                'label' => __( 'Product Grid', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'product_layout',
                [
                    'label'   => esc_html__('Product Layout', 'woolentor-pro'),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'grid',
                    'options' => [
                        'content'   => esc_html__('Current Theme Style', 'woolentor-pro'),
                        'grid'      => esc_html__('Grid Style', 'woolentor-pro'),
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'grid_style',
                [
                    'label'   => esc_html__('Grid Style', 'woolentor-pro'),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => esc_html__('Grid Style One', 'woolentor-pro'),
                        '2'   => esc_html__('Grid Style Two', 'woolentor-pro'),
                        '3'   => esc_html__('Grid Style Three', 'woolentor-pro'),
                        '4'   => esc_html__('Grid Style Four', 'woolentor-pro'),
                        '5'   => esc_html__('Grid Style Five', 'woolentor-pro'),
                    ],
                    'condition' => [
                        'product_layout'=>'grid'
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'filterable',
                [
                    'label' => __( 'Filterable', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Product Query Settings
        $this->start_controls_section(
            'woolentor-products',
            [
                'label' => esc_html__( 'Query Settings', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'product_type',
                [
                    'label' => esc_html__( 'Product Type', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'recent',
                    'options' => [
                        'recent' => esc_html__( 'Recent Products', 'woolentor-pro' ),
                        'featured' => esc_html__( 'Featured Products', 'woolentor-pro' ),
                        'best_selling' => esc_html__( 'Best Selling Products', 'woolentor-pro' ),
                        'sale' => esc_html__( 'Sale Products', 'woolentor-pro' ),
                        'top_rated' => esc_html__( 'Top Rated Products', 'woolentor-pro' ),
                        'mixed_order' => esc_html__( 'Random Products', 'woolentor-pro' ),
                        'show_byid' => esc_html__( 'Show By ID', 'woolentor-pro' ),
                        'show_byid_manually' => esc_html__( 'Add ID Manually', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'product_id',
                [
                    'label' => esc_html__( 'Select Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => woolentor_post_name( 'product' ),
                    'condition' => [
                        'product_type' => 'show_byid',
                    ]
                ]
            );

            $this->add_control(
                'product_ids_manually',
                [
                    'label' => esc_html__( 'Product IDs', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'condition' => [
                        'product_type' => 'show_byid_manually',
                    ]
                ]
            );

            $this->add_control(
              'product_limit',
                [
                    'label'   => esc_html__( 'Product Limit', 'woolentor-pro' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 3,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'categories',
                [
                    'label' => esc_html__( 'Product Categories', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => woolentor_taxonomy_list(),
                    'condition' => [
                        'product_type!' => 'show_byid',
                    ]
                ]
            );

            $this->add_control(
                'cat_operator',
                [
                    'label'     => esc_html__('Category Operator', 'woolentor-pro'),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'IN',
                    'options'   => [
                        'AND'    => esc_html__('AND', 'woolentor-pro'),
                        'IN'     => esc_html__('IN', 'woolentor-pro'),
                        'NOT IN' => esc_html__('NOT IN', 'woolentor-pro'),
                    ],
                    'condition' => [
                        'categories!' => ''
                    ],
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label' => esc_html__( 'Order by', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'none'          => esc_html__('None','woolentor-pro'),
                        'ID'            => esc_html__('ID','woolentor-pro'),
                        'date'          => esc_html__('Date','woolentor-pro'),
                        'name'          => esc_html__('Name','woolentor-pro'),
                        'title'         => esc_html__('Title','woolentor-pro'),
                        'menu_order'    => esc_html__('Menu Order', 'woolentor-pro'),
                        'comment_count' => esc_html__('Comment count','woolentor-pro'),
                        'rand'          => esc_html__('Random','woolentor-pro'),
                    ],
                ]
            );

            $this->add_control(
                'order',
                [
                    'label' => esc_html__( 'Order', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','woolentor-pro'),
                        'ASC'   => esc_html__('Ascending','woolentor-pro'),
                    ],
                ]
            );

        $this->end_controls_section();

        // Additional Options
        $this->start_controls_section(
            'section_additional_option',
            [
                'label' => esc_html__( 'Additional Options', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'slider_on',
                [
                    'label' => esc_html__( 'Slider On', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'=>'before',
                    'condition' => [
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'paginate',
                [
                    'label' => esc_html__( 'Pagination', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

            $this->add_control(
                'allow_order',
                [
                    'label' => esc_html__( 'Allow Order', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'condition' => [
                        'paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'show_result_count',
                [
                    'label' => esc_html__( 'Show Result Count', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'condition' => [
                        'paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'content_add_to_cart_settings',
                [
                    'label' => esc_html__( 'Cart Button Settings', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'add_to_cart_text',
                [
                    'label' => esc_html__( 'Add to Cart Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Add To Cart', 'woolentor-pro' ),
                    'placeholder' => esc_html__( 'Type your cart button text', 'woolentor-pro' ),
                    'label_block' => true,
                    'condition'=>[
                        'grid_style'=>['1','2'],
                        'product_layout!'=>'content'
                    ]
                ]
            );

            $this->add_control(
                'button_icon',
                [
                    'label'       => esc_html__( 'Add to Cart Button Icon', 'woolentor-pro' ),
                    'type'        => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'buttonicon',
                    'default'=>[
                        'value'  => 'fas fa-plus',
                        'library'=> 'solid',
                    ],
                    'condition' => [
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'button_icon_align',
                [
                    'label'   => esc_html__( 'Add to Cart Icon Position', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left'   => esc_html__( 'Left', 'woolentor-pro' ),
                        'right'  => esc_html__( 'Right', 'woolentor-pro' ),
                    ],
                    'condition' => [
                        'button_icon[value]!' => '',
                        'grid_style'=>['1','2'],
                        'product_layout!'=>'content'
                    ],
                    'label_block' => true,
                ]
            );

            $this->add_responsive_control(
                'icon_specing',
                [
                    'label' => esc_html__( 'Icon Spacing', 'woolentor-pro' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 150,
                        ],
                    ],
                    'default' => [
                        'size' => 5,
                    ],
                    'condition' => [
                        'button_icon[value]!' => '',
                        'grid_style'=>['1','2'],
                        'product_layout!'=>'content'
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 .ht-product-content-2 .ht-price-addtocart-wrap .ht-addtocart a.woolentor-button-icon-right i'  => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .ht-product-2 .ht-product-content-2 .ht-price-addtocart-wrap .ht-addtocart a.woolentor-button-icon-left i'   => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'image_settings',
                [
                    'label' => esc_html__( 'Image Settings', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'image_layout_type',
                [
                    'label'   => esc_html__( 'Image Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'zoom',
                    'options' => [
                        'zoom'           => esc_html__( 'Zoom Image', 'woolentor-pro' ),
                        'secondary_img'  => esc_html__( 'Secondary Image', 'woolentor-pro' ),
                    ],
                    'label_block' => true,
                    'condition' => [
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'content_display_settings',
                [
                    'label' => esc_html__( 'Content Settings', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'hide_category',
                [
                    'label' => esc_html__( 'Hide Category', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'hide_rating',
                [
                    'label' => esc_html__( 'Hide Rating', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'product_layout!'=>'content'
                    ],
                ]
            );

        $this->end_controls_section();

        // Column
        $this->start_controls_section(
            'section_column_option',
            [
                'label' => __( 'Columns', 'woolentor-pro' ),
                'condition'=>[
                    'slider_on!'=>'yes',
                ]
            ]
        );

            $this->add_control(
                'default_column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'options' => [
                        '1' => esc_html__( 'One', 'woolentor-pro' ),
                        '2' => esc_html__( 'Two', 'woolentor-pro' ),
                        '3' => esc_html__( 'Three', 'woolentor-pro' ),
                        '4' => esc_html__( 'Four', 'woolentor-pro' ),
                        '5' => esc_html__( 'Five', 'woolentor-pro' ),
                        '6' => esc_html__( 'Six', 'woolentor-pro' ),
                    ],
                    'label_block' => true,
                    'condition'=>[
                        'product_layout'=>'content'
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'options' => [
                        '1' => esc_html__( 'One', 'woolentor-pro' ),
                        '2' => esc_html__( 'Two', 'woolentor-pro' ),
                        '3' => esc_html__( 'Three', 'woolentor-pro' ),
                        '4' => esc_html__( 'Four', 'woolentor-pro' ),
                        '5' => esc_html__( 'Five', 'woolentor-pro' ),
                        '6' => esc_html__( 'Six', 'woolentor-pro' ),
                        '7' => esc_html__( 'Seven', 'woolentor-pro' ),
                        '8' => esc_html__( 'Eight', 'woolentor-pro' ),
                        '9' => esc_html__( 'Nine', 'woolentor-pro' ),
                        '10'=> esc_html__( 'Ten', 'woolentor-pro' ),
                    ],
                    'label_block' => true,
                    'prefix_class' => 'wl-columns%s-',
                    'condition'=>[
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'no_gutters',
                [
                    'label' => esc_html__( 'No Gutters', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition'=>[
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_space',
                [
                    'label' => esc_html__( 'Space', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 15,
                    ],
                    'condition'=>[
                        'no_gutters!'=>'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'padding: 0  {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wl-row' => 'margin: 0  -{{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_bottom_space',
                [
                    'label' => esc_html__( 'Bottom Space', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 30,
                    ],
                    'condition'=>[
                        'no_gutters!'=>'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'product_layout!'=>'content'
                    ],
                ]
            );

        $this->end_controls_section();

        // Slider Option
        $this->start_controls_section(
            'section_slider_option',
            [
                'label' => esc_html__( 'Slider Option', 'woolentor-pro' ),
                'condition'=>[
                    'slider_on'=>'yes',
                    'product_layout!' => 'content',
                ]
            ]
        );
            
            $this->add_control(
                'slitems',
                [
                    'label' => esc_html__( 'Slider Items', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => esc_html__( 'Slider Arrow', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => esc_html__( 'Slider dots', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'woolentor-pro'),
                    'label_on' => __('Yes', 'woolentor-pro'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => esc_html__( 'Slider autoplay', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3000,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );


            $this->add_control(
                'slanimation_speed',
                [
                    'label' => __('Autoplay animation speed', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label' => __('Tablet Resolution', 'woolentor-pro'),
                    'description' => __('The resolution to the tablet.', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label' => __('Mobile Resolution', 'woolentor-pro'),
                    'description' => __('The resolution to mobile.', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                ]
            );

        $this->end_controls_section();

        // Item Style tab section
        $this->start_controls_section(
            'item_style_section',
            [
                'label' => esc_html__( 'Item Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'item_border',
                    'label' => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-product-2',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'label' => esc_html__( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'exclude'=>['image'],
                    'selector' => '{{WRAPPER}} .ht-product-2',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-product-2',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'item_hover_border',
                    'label' => esc_html__( 'Hover Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-product-2:hover',
                    'fields_options'=>[
                        'border'=>[
                            'label' => esc_html__( 'Hover Border', 'woolentor-pro' )
                        ]
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'item_hover_background',
                    'label' => esc_html__( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'exclude'=>['image'],
                    'selector' => '{{WRAPPER}} .ht-product-2:hover',
                    'fields_options'=>[
                        'background'=>[
                            'label' => esc_html__( 'Hover Background', 'woolentor-pro' )
                        ]
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_hover_box_shadow',
                    'label' => esc_html__( 'Hover Box Shadow', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-product-2:hover',
                ]
            );

        $this->end_controls_section();

        // Content Style tab section
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__( 'Content Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'content_title_heading',
                [
                    'label' => esc_html__( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-title-2 a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-title-2 a',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-title-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'content_category_heading',
                [
                    'label' => esc_html__( 'Category', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'category_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-categories-2 a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'category_hover_color',
                [
                    'label' => esc_html__( 'Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-categories-2 a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'category_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-categories-2 a',
                ]
            );

            $this->add_responsive_control(
                'category_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-categories-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'content_price_heading',
                [
                    'label' => esc_html__( 'Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'price_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-price-2 span' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-price-2' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'price_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-price-2 span',
                ]
            );

            $this->add_responsive_control(
                'price_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-price-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'content_rating_heading',
                [
                    'label' => esc_html__( 'Rating', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'rating_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-ratting-2 i' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'rating_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-product-ratting-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Action Button Style tab section
        $this->start_controls_section(
            'action_btn_style_section',
            [
                'label' => esc_html__( 'Action Button Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'action_btn_size',
                [
                    'label' => esc_html__( 'Size', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 .ht-product-image-wrap-2 [class*="ht-product-action"] ul li a' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .ht-product-2 .ht-product-content-2-wrap [class*="ht-product-action"] ul li a' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'action_btn_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 .ht-product-image-wrap-2 [class*="ht-product-action"] ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .ht-product-2 .ht-product-content-2-wrap [class*="ht-product-action"] ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('action_btn_style_tabs');
                
                // Button Normal
                $this->start_controls_tab(
                    'action_btn_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'action_btn_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ht-product-2 .ht-product-image-wrap-2 [class*="ht-product-action"] ul li a' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .ht-product-2 .ht-product-content-2-wrap [class*="ht-product-action"] ul li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'action_btn_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .ht-product-2 .ht-product-image-wrap-2 [class*="ht-product-action"] ul li a,{{WRAPPER}} .ht-product-2 .ht-product-content-2-wrap [class*="ht-product-action"] ul li a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'action_btn_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .ht-product-2 .ht-product-image-wrap-2 [class*="ht-product-action"] ul li a,{{WRAPPER}} .ht-product-2 .ht-product-content-2-wrap [class*="ht-product-action"] ul li a',
                            'exclude'=>['image'],
                        ]
                    );

                $this->end_controls_tab();

                // Button Hover
                $this->start_controls_tab(
                    'action_btn_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'action_btn_hover_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ht-product-2 .ht-product-image-wrap-2 [class*="ht-product-action"] ul li a:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .ht-product-2 .ht-product-content-2-wrap [class*="ht-product-action"] ul li a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'action_btn_hover_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .ht-product-2 .ht-product-image-wrap-2 [class*="ht-product-action"] ul li a:hover,{{WRAPPER}} .ht-product-2 .ht-product-content-2-wrap [class*="ht-product-action"] ul li a:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'action_btn_hover_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .ht-product-2 .ht-product-image-wrap-2 [class*="ht-product-action"] ul li a:hover,{{WRAPPER}} .ht-product-2 .ht-product-content-2-wrap [class*="ht-product-action"] ul li a:hover',
                            'exclude'=>['image'],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            // Add to Cart Button Style
            $this->add_control(
                'cart_button_heading',
                [
                    'label' => esc_html__( 'Add To Cart Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'=>[
                        'grid_style'=>['1','3']
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cart_btn_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-price-addtocart-wrap [class*="ht-addtocart"] a',
                    'condition'=>[
                        'grid_style'=>['1','3']
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_btn_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-price-addtocart-wrap [class*="ht-addtocart"] a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'grid_style'=>['1','3']
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_btn_icon_size',
                [
                    'label' => esc_html__( 'Icon Size', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-price-addtocart-wrap [class*="ht-addtocart"] a i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'grid_style'=>['1','3']
                    ],
                ]
            );

            $this->start_controls_tabs(
                'cart_btn_style_tabs',
                [
                    'condition'=>[
                        'grid_style'=>['1','3']
                    ],
                ]
            );
                
                // Cart Button Normal
                $this->start_controls_tab(
                    'cart_btn_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_btn_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-price-addtocart-wrap [class*="ht-addtocart"] a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_btn_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-price-addtocart-wrap [class*="ht-addtocart"] a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'cart_btn_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-price-addtocart-wrap [class*="ht-addtocart"] a',
                            'exclude'=>['image'],
                        ]
                    );

                $this->end_controls_tab();
                
                // Cart Button Hover
                $this->start_controls_tab(
                    'cart_btn_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_btn_hover_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-price-addtocart-wrap [class*="ht-addtocart"] a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_btn_hover_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-price-addtocart-wrap [class*="ht-addtocart"] a:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'cart_btn_hover_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .ht-product-2 [class*="ht-product-content"] .ht-price-addtocart-wrap [class*="ht-addtocart"] a:hover',
                            'exclude'=>['image'],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Slider Button style
        $this->start_controls_section(
            'products-slider-controller-style',
            [
                'label' => esc_html__( 'Slider Controller Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

            $this->start_controls_tabs('product_sliderbtn_style_tabs');

                // Slider Button style Normal
                $this->start_controls_tab(
                    'product_sliderbtn_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'button_style_heading',
                        [
                            'label' => __( 'Navigation Arrow', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_responsive_control(
                        'nvigation_position',
                        [
                            'label' => __( 'Position', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'top: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_style_dots_heading',
                        [
                            'label' => __( 'Navigation Dots', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_responsive_control(
                        'dots_position',
                        [
                            'label' => __( 'Position', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-dots' => 'left: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'dots_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-dots li button' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'dots_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-dots li button',
                        ]
                    );

                    $this->add_responsive_control(
                        'dots_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();// Normal button style end

                // Button style Hover
                $this->start_controls_tab(
                    'product_sliderbtn_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'button_style_arrow_heading',
                        [
                            'label' => __( 'Navigation', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_control(
                        'button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_hover_bg_color',
                        [
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );


                    $this->add_control(
                        'button_style_dotshov_heading',
                        [
                            'label' => __( 'Navigation Dots', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_control(
                            'dots_hover_bg_color',
                            [
                                'label' => __( 'Background Color', 'woolentor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'background-color: {{VALUE}} !important;',
                                    '{{WRAPPER}} .product-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border_hover',
                                'label' => __( 'Border', 'woolentor-pro' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-dots li button:hover',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius_hover',
                            [
                                'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Hover button style end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Tab option end

    }

    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();

        $type = 'recent_products';
        $type = $this->parse_product_type( $settings['product_type'] );

        $filterable = ( 'yes' === $settings['filterable'] ? rest_sanitize_boolean( $settings['filterable'] ) : false );

        $shortcode = new \WooLentor_WC_Shortcode_Products( $settings, $type, $filterable );
        $content = $shortcode->get_content( $settings['product_layout'] );
        $not_found_content = woolentor_pro_products_not_found_content();

        if ( true === $filterable ) {
            $wrap_class = 'wl-filterable-products-wrap';
            $content_class = 'wl-filterable-products-content';
            $wrap_attributes = 'data-wl-widget-name="wl-product-grid"';
            $wrap_attributes .= ' data-wl-widget-settings="' . esc_attr( htmlspecialchars( wp_json_encode( $settings ) ) ) . '"';
            ?>
            <div class="<?php echo esc_attr( $wrap_class ); ?>"<?php echo $wrap_attributes; ?>>
                <div class="<?php echo esc_attr( $content_class ); ?>">
                    <?php
                    if ( strip_tags( trim( $content ) ) ) {
                        echo $content;
                    } else{
                        echo $not_found_content;
                    }
                    ?>
                </div>
            </div>
            <?php
        } else {
            if ( strip_tags( trim( $content ) ) ) {
                echo $content;
            } else{
                echo $not_found_content;
            }
        }

    }

    protected function parse_product_type( $type ) {
        switch ( $type ) {

            case 'recent':
                $product_type = 'recent_products';
                break;

            case 'sale':
                $product_type = 'sale_products';
                break;

            case 'best_selling':
                $product_type = 'best_selling_products';
                break;

            case 'top_rated':
                $product_type = 'top_rated_products';
                break;

            case 'featured':
                $product_type = 'featured';
                break;

            default:
                $product_type = 'products';
                break;
        }
        return $product_type;
    }


}