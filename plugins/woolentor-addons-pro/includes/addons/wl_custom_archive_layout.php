<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Custom_Archive_Layout_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-custom-product-archive';
    }
    
    public function get_title() {
        return __( 'WL: Product Archive Layout (Custom)', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-products';
    }
    
    public function get_categories() {
        return [ 'woolentor-addons-pro' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','woolentor-widgets-pro'];
    }

    public function get_script_depends() {
        return [
            'slick',
            'countdown-min',
            'woolentor-widgets-scripts',
            'woolentor-widgets-scripts-pro',
            'woolentor-quick-cart',
        ];
    }

    public function get_keywords(){
        return ['shop page','product page','custom product page','custom shop page','custom layout'];
    }

    protected function register_controls() {

        // Product Content
        $this->start_controls_section(
            'woolentor-products-layout-setting',
            [
                'label' => __( 'Layout Settings', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'product_layout',
                [
                    'label'   => esc_html__('Product Layout', 'woolentor-pro'),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'universal',
                    'options' => [
                        'content'   => esc_html__('Current Theme Style', 'woolentor-pro'),
                        'universal' => esc_html__('Universal Style', 'woolentor-pro'),
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'woolentor_product_view_mode',
                [
                    'label' => __( 'View Mode', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'grid',
                    'options' => [
                        'grid' => __( 'Grid', 'woolentor-pro' ),
                        'list' => __( 'List', 'woolentor-pro' ),
                    ],
                    'condition'=>[
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'tab_menu',
                [
                    'label' => __( 'Tab Menu', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'condition'=>[
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'filterable',
                [
                    'label' => __( 'Filterable', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
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

            $this->add_control(
                'paginate',
                [
                    'label' => esc_html__( 'Pagination', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'separator'=>'before',
                    'default'=>'yes'
                ]
            );

            $this->add_control(
                'allow_order',
                [
                    'label' => esc_html__( 'Allow Order', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
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
                    'default' => 'yes',
                    'condition' => [
                        'paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'query_post_type',
                [
                    'type' => 'hidden',
                    'default' => 'current_query',
                ]
            );

        $this->end_controls_section();

        // Column
        $this->start_controls_section(
            'section_column_option',
            [
                'label' => __( 'Columns', 'woolentor-pro' ),
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
                        'product_layout!'=>'content'
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'padding: 0  {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wl-row' => 'margin: 0  -{{SIZE}}{{UNIT}};',
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
                        'product_layout!'=>'content'
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Content
        $this->start_controls_section(
            'woolentor-products-content-setting',
            [
                'label' => __( 'Content Settings', 'woolentor-pro' ),
                'condition'=>[
                    'product_layout!'=>'content'
                ],
            ]
        );
            $this->add_control(
                'product_content_style',
                [
                    'label'   => __( 'Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'  => __( 'Style One', 'woolentor-pro' ),
                        '2'  => __( 'Style Two', 'woolentor-pro' ),
                        '3'  => __( 'Style Three', 'woolentor-pro' ),
                        '4'  => __( 'Style Four', 'woolentor-pro' ),
                    ]
                ]
            );

            $this->add_control(
                'hide_product_title',
                [
                    'label'     => __( 'Hide Title', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-title' => 'display: none !important;',
                        '{{WRAPPER}} .wlshop-list-content h3' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_price',
                [
                    'label'     => __( 'Hide Price', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-price' => 'display: none !important;',
                        '{{WRAPPER}} .wlshop-list-content .ht-product-list-price' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_category',
                [
                    'label'     => __( 'Hide Category', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-categories:not(.ht-product-brand)' => 'display: none !important;',
                        '{{WRAPPER}} .wlshop-list-content .ht-product-categories:not(.ht-product-brand)' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_category_before_border',
                [
                    'label'     => __( 'Hide category before border', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-categories:not(.ht-product-brand)::before' => 'display: none !important;',
                        '{{WRAPPER}} .ht-product-inner .ht-product-categories:not(.ht-product-brand)' => 'padding-left: 0 !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_ratting',
                [
                    'label'     => __( 'Hide Rating', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-ratting-wrap' => 'display: none !important;',
                        '{{WRAPPER}} .wlshop-list-content .ht-product-list-ratting' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_gird_content',
                [
                    'label'     => __( 'Grid Description ', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                ]
            );
            $this->add_control(
                'product_excerpt_allow_html',
                [
                    'label'     => __( 'Description Allow HTML Tag', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                ]
            );
            $this->add_control(
              'woolentor_product_grid_desription_count',
                [
                    'label'   => __( 'Description Limit', 'woolentor-pro' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 15,
                    'step'    => 1,
                    'condition' => [
                    	'hide_product_gird_content' => 'yes',
                    	'product_excerpt_allow_html!' => 'yes'
                    ]
                ]
            );
            $this->add_control(
                'hide_product_list_content',
                [
                    'label'     => __( 'Hide List Description', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-content .woocommerce-product-details__short-description' => 'display: none !important;',
                    ],
                ]
            );
            $this->add_control(
              'woolentor_list_desription_count',
                [
                    'label'   => __( 'Description Limit', 'woolentor-pro' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 150,
                    'step'    => 1,
                    'condition' => [
                    	'hide_product_list_content!' => 'yes',
                        'product_excerpt_allow_html!' => 'yes'
                    ]
                ]
            );

            $this->add_control(
                'hide_product_sale_badge',
                [
                    'label'     => __( 'Hide Sale Badge', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-image-wrap .ht-product-label' => 'display: none !important;',
                    ],
                ]
            );
            $this->add_control(
                'product_sale_badge_type',
                [
                    'label'     => __( 'Sale Badge Type', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'default',
                    'options'   => [
                        'default'       => __( 'Default', 'woolentor-pro' ),
                        'custom'        => __( 'Custom', 'woolentor-pro' ),
                        'dis_percent'   => __( 'Percentage', 'woolentor-pro' ),
                        'dis_price'     => __( 'Discount Ammount', 'woolentor-pro' ),
                    ],                    
                    'condition' => [
                    	'hide_product_sale_badge!' => 'yes'
                    ]
                ]
            );
            $this->add_control(
                'product_sale_badge_custom',
                [
                    'label'     => __( 'Custom Badge Text', 'woolentor-pro' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => 'Sale!',
                    'condition' => [
                        'product_sale_badge_type' =>'custom'
                    ]
                ]
            );
            $this->add_control(
                'product_sale_percent_position',
                [
                    'label'     => __( 'Additional Text Position', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'before',
                    'options'   => [
                        'after' => __( 'After', 'woolentor-pro' ),
                        'before'=> __( 'Before', 'woolentor-pro' ),
                    ],
                    'condition' => [
                        'product_sale_badge_type' => array('dis_percent','dis_price'),
                    ]
                ]
            );
            $this->add_control(
                'product_after_badge_percent',
                [
                    'label'     => __( 'After Text', 'woolentor-pro' ),
                    'type'      => Controls_Manager::TEXT,
                    'condition' => [
                        'product_sale_percent_position' =>'after',
                        'product_sale_badge_type' => array('dis_percent','dis_price'),
                    ]
                ]
            );
            $this->add_control(
                'product_before_badge_percent',
                [
                    'label'     => __( 'Before Text', 'woolentor-pro' ),
                    'type'      => Controls_Manager::TEXT,
                    'condition' => [
                        'product_sale_percent_position' =>'before',
                        'product_sale_badge_type' => array('dis_percent','dis_price'),
                    ]
                ]
            );

            $this->add_control(
                'title_length',
                [
                    'label'     => __( 'Title Length', 'woolentor-pro' ),
                    'type'      => Controls_Manager::NUMBER,
                    'min'       => -1,
                    'max'       => 1000,
                    'step'      => 1,
                    'default'   => 3
                ]
            );

            $this->add_control(
                'stock_progress_bar',
                [
                    'label'     => __( 'Show Product Stock Progress Bar', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                ]
            );

            $this->add_control(
                'show_product_brand',
                [
                    'label'     => __( 'Show Product Brand', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                ]
            );

            $this->add_control(
                'product_brand_taxonomy',
                [
                    'label' => __( 'Product brand', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => woolentor_pro_get_taxonomies( 'product', true ),
                    'condition'=>[
                        'show_product_brand'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'hide_brand_before_border',
                [
                    'label'     => __( 'Hide brand before border', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-brand::before' => 'display: none !important;',
                        '{{WRAPPER}} .ht-product-inner .ht-product-brand' => 'padding-left: 0 !important;',
                    ],
                    'condition'=>[
                        'show_product_brand'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'product_title_tag_heading',
                [
                    'label' => __( 'Product Title tag', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'title_html_tag_grid_view',
                [
                    'label'   => __( 'Title HTML Tag (Grid View)', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => woolentor_html_tag_lists(),
                    'default' => 'h4',
                ]
            );
            $this->add_control(
                'title_html_tag_list_view',
                [
                    'label'   => __( 'Title HTML Tag (List View)', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => woolentor_html_tag_lists(),
                    'default' => 'h3',
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_stock_progressbar',
            [
                'label' => __( 'Stock Progressbar', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition'=>[
                    'stock_progress_bar'=>'yes',
                    'product_layout!'=>'content'
                ],
            ]
        );
            
            $this->add_control(
                'hide_order_counter',
                [
                    'label'     => __( 'Hide Order Counter', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wltotal-sold' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_available_counter',
                [
                    'label'     => __( 'Hide Available Counter', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wlcurrent-stock' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'order_custom_text',
                [
                    'label' => __( 'Ordered Custom Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Ordered', 'woolentor-pro' ),
                    'condition' => [
                        'hide_order_counter!' => 'yes',
                    ],
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'available_custom_text',
                [
                    'label' => __( 'Available Custom Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Items available', 'woolentor-pro' ),
                    'condition' => [
                        'hide_available_counter!' => 'yes',
                    ],
                    'label_block' => true,
                ]
            );

        $this->end_controls_section();

        // Product Action Button
        $this->start_controls_section(
            'woolentor-products-action-button',
            [
                'label' => __( 'Action Button Settings', 'woolentor-pro' ),
                'condition'=>[
                    'product_layout!'=>'content'
                ],
            ]
        );
            
            $this->add_control(
                'show_action_button',
                [
                    'label' => __( 'Action Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'woolentor-pro' ),
                    'label_off' => __( 'Hide', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_quickview_button',
                [
                    'label' => __( 'Hide Quick View Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition'=>[
                        'show_action_button'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'show_wishlist_button',
                [
                    'label' => __( 'Hide Wishlist Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition'=>[
                        'show_action_button'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'show_compare_button',
                [
                    'label' => __( 'Hide Compare Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition'=>[
                        'show_action_button'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'show_addtocart_button',
                [
                    'label' => __( 'Hide Shopping Cart Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition'=>[
                        'show_action_button'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'action_button_style',
                [
                    'label'   => __( 'Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'woolentor-pro' ),
                        '2'   => __( 'Style Two', 'woolentor-pro' ),
                        '3'   => __( 'Style Three', 'woolentor-pro' ),
                    ],
                    'condition'=>[
                        'show_action_button'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'action_button_show_on',
                [
                    'label'   => __( 'Show On', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'normal',
                    'options' => [
                        'hover'   => __( 'Hover', 'woolentor-pro' ),
                        'normal'  => __( 'Normal', 'woolentor-pro' ),
                    ],
                    'condition'=>[
                        'show_action_button'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'action_button_position',
                [
                    'label'   => __( 'Position', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                        'middle' => [
                            'title' => __( 'Middle', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-middle',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                        'contentbottom' => [
                            'title' => __( 'Content Bottom', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default'     => is_rtl() ? 'left' : 'right',
                    'toggle'      => false,
                ]
            );

            $this->add_control(
                'variation_quick_addtocart',
                [
                    'label' => __( 'Variation product quick cart', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

        $this->end_controls_section();

        // Product Image Setting
        $this->start_controls_section(
            'woolentor-products-thumbnails-setting',
            [
                'label' => __( 'Image Settings', 'woolentor-pro' ),
                'condition'=>[
                    'product_layout!'=>'content'
                ],
            ]
        );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'thumbnailsize',
                    'default' => 'large',
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'thumbnails_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );

            $this->add_control(
                'thumbnails_style',
                [
                    'label'   => __( 'Thumbnails Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'  => __( 'Single Image', 'woolentor-pro' ),
                        '2'  => __( 'Image Slider', 'woolentor-pro' ),
                        '3'  => __( 'Gallery Tab', 'woolentor-pro' ),
                    ]
                ]
            );

            $this->add_control(
                'image_navigation_bg_color',
                [
                    'label' => __( 'Arrows Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#444444',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-image-slider .slick-arrow' => 'color: {{VALUE}} !important;',
                    ],
                    'condition'=>[
                        'thumbnails_style'=>'2',
                    ]
                ]
            );

            $this->add_control(
                'image_dots_normal_bg_color',
                [
                    'label' => __( 'Dots Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#cccccc',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-image-slider .slick-dots li button' => 'background-color: {{VALUE}} !important;',
                    ],
                    'condition'=>[
                        'thumbnails_style'=>'2',
                    ]
                ]
            );

            $this->add_control(
                'image_dots_hover_bg_color',
                [
                    'label' => __( 'Dots Active Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'condition'=>[
                        'thumbnails_style'=>'2',
                    ],
                    'default' =>'#666666',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-image-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'image_tab_menu_border_color',
                [
                    'label' => __( 'Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#737373',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-cus-tab-links li a' => 'border-color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'thumbnails_style'=>'3',
                    ]
                ]
            );

            $this->add_control(
                'image_tab_menu_active_border_color',
                [
                    'label' => __( 'Active Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#ECC87B',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-cus-tab-links li a.htactive' => 'border-color: {{VALUE}} !important;',
                    ],
                    'condition'=>[
                        'thumbnails_style'=>'3',
                    ]
                ]
            );

        $this->end_controls_section();

        // Product countdown
        $this->start_controls_section(
            'woolentor-products-countdown-setting',
            [
                'label' => __( 'Offer Price Counter Settings', 'woolentor-pro' ),
                'condition'=>[
                    'product_layout!'=>'content'
                ],
            ]
        );
            
            $this->add_control(
                'show_countdown',
                [
                    'label' => __( 'Show Countdown Timer', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'woolentor-pro' ),
                    'label_off' => __( 'Hide', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_countdown_gutter',
                [
                    'label' => __( 'Gutter', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'woolentor-pro' ),
                    'label_off' => __( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' =>[
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'product_countdown_position',
                [
                    'label'   => __( 'Position', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                        'middle' => [
                            'title' => __( 'Middle', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-middle',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                        'contentbottom' => [
                            'title' => __( 'Content Bottom', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default'     => 'bottom',
                    'toggle'      => false,
                    'label_block' => true,
                    'condition' =>[
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'custom_labels',
                [
                    'label'        => __( 'Custom Label', 'woolentor-pro' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'condition'   => [
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_days',
                [
                    'label'       => __( 'Days', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Days', 'woolentor-pro' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_hours',
                [
                    'label'       => __( 'Hours', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Hours', 'woolentor-pro' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_minutes',
                [
                    'label'       => __( 'Minutes', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Minutes', 'woolentor-pro' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_seconds',
                [
                    'label'       => __( 'Seconds', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Seconds', 'woolentor-pro' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();

        // Style Default tab section
        $this->start_controls_section(
            'universal_product_style_section',
            [
                'label' => __( 'Grid View Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'product_layout!'=>'content'
                ],
            ]
        );

            $this->add_responsive_control(
                'product_inner_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce div.product.mb-30' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_inner_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce div.product.mb-30' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_inner_border_area',
                    'label' => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner',
                ]
            );

            $this->add_responsive_control(
                'product_inner_border_radius_area',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_inner_border_color',
                [
                    'label' => __( 'Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#f1f1f1',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner' => 'border-color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'product_inner_border_area_border' => ''
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'product_inner_box_shadow',
                    'label' => __( 'Hover Box Shadow', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner:hover',
                ]
            );

            $this->add_control(
                'product_content_area_heading',
                [
                    'label' => __( 'Content area', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'product_content_area_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_content_area_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'content_area_bg','woolentor_style_tabs', '#ffffff' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_content_area_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content',
                ]
            );

            $this->add_control(
                'product_badge_heading',
                [
                    'label' => __( 'Product Badge', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_badge_color',
                [
                    'label' => __( 'Badge Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'badge_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_outofstock_badge_color',
                [
                    'label' => __( 'Out of Stock Badge Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-label.ht-stockout' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'product_badge_bg_color',
                [
                    'label' => __( 'Badge Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-label' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_badge_typography',
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-label',
                ]
            );

            $this->add_responsive_control(
                'product_badge_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Category
            $this->add_control(
                'product_category_heading',
                [
                    'label' => __( 'Product Category', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_category_typography',
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories a',
                ]
            );

            $this->add_control(
                'product_category_color',
                [
                    'label' => __( 'Category Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'category_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories a' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories::before' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_category_hover_color',
                [
                    'label' => __( 'Category Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'category_hover_color','woolentor_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_category_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Brand
            $this->add_control(
                'product_brand_heading',
                [
                    'label' => __( 'Product Brand', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'description'=>__( 'Base on WooCommerce product brand','woolentor-pro' ),
                    'condition'=>[
                        'show_product_brand'=>'yes',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_brand_typography',
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-brand a',
                    'condition'=>[
                        'show_product_brand'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'product_brand_color',
                [
                    'label' => __( 'Brand Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'category_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-brand a' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-brand::before' => 'background-color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'show_product_brand'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'product_brand_hover_color',
                [
                    'label' => __( 'Brand Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'category_hover_color','woolentor_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-brand a:hover' => 'color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'show_product_brand'=>'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_brand_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-brand' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'show_product_brand'=>'yes',
                    ],
                ]
            );

            // Product Title
            $this->add_control(
                'product_title_heading',
                [
                    'label' => __( 'Product Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_title_typography',
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-title a',
                ]
            );

            $this->add_control(
                'product_title_color',
                [
                    'label' => __( 'Title Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,                    
                    'default' => woolentor_get_option_pro( 'title_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-title a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_title_hover_color',
                [
                    'label' => __( 'Title Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'title_hover_color','woolentor_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-title a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_title_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Price
            $this->add_control(
                'product_price_heading',
                [
                    'label' => __( 'Product Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_sale_price_color',
                [
                    'label' => __( 'Sale Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'sale_price_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_sale_price_typography',
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span',
                ]
            );

            $this->add_control(
                'product_regular_price_color',
                [
                    'label' => __( 'Regular Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'default' => woolentor_get_option_pro( 'regular_price_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span del span,{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span del' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_regular_price_typography',
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span del span',
                ]
            );

            $this->add_responsive_control(
                'product_price_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Rating
            $this->add_control(
                'product_rating_heading',
                [
                    'label' => __( 'Product Rating', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_rating_color',
                [
                    'label' => __( 'Empty Rating Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'empty_rating_color','woolentor_style_tabs', '#aaaaaa' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-ratting-wrap .ht-product-ratting .ht-product-user-ratting i.empty' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_rating_give_color',
                [
                    'label' => __( 'Rating Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'rating_color','woolentor_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-ratting-wrap .ht-product-ratting .ht-product-user-ratting i' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_rating_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-ratting-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

             // Product Description
            $this->add_control(
                'product_grid_description_heading',
                [
                    'label' => __( 'Product Description', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_grid_description_typography',
                    'selector' => '{{WRAPPER}} .ht-product-content .woocommerce-product-details__short-description p',
                ]
            );

            $this->add_control(
                'product_grid_description_color',
                [
                    'label' => __( 'Description Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'desc_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-content .woocommerce-product-details__short-description p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_grid_description_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-content .woocommerce-product-details__short-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Style Default End

        // Style Action Button tab section
        $this->start_controls_section(
            'universal_product_action_button_style_section',
            [
                'label' => __( 'Action Button Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'product_layout!'=>'content'
                ],
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'product_action_button_background_color',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'product_action_button_box_shadow',
                    'label' => __( 'Box Shadow', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul',
                ]
            );

            $this->add_control(
                'product_tooltip_heading',
                [
                    'label' => __( 'Tooltip', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

                $this->add_control(
                    'product_tooltip_color',
                    [
                        'label' => __( 'Tool Tip Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => woolentor_get_option_pro( 'tooltip_color','woolentor_style_tabs', '#ffffff' ),
                        'selectors' => [
                            '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a .ht-product-action-tooltip,{{WRAPPER}} span.woolentor-tip' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'product_action_button_tooltip_background_color',
                        'label' => __( 'Background', 'woolentor-pro' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a .ht-product-action-tooltip,{{WRAPPER}} span.woolentor-tip',
                    ]
                );

            $this->start_controls_tabs('product_action_button_style_tabs');

                // Normal
                $this->start_controls_tab(
                    'product_action_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_action_button_normal_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => woolentor_get_option_pro( 'btn_color','woolentor_style_tabs', '#000000' ),
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_font_size',
                        [
                            'label' => __( 'Font Size', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 20,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a i' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .woolentor-compare.compare::before,{{WRAPPER}} .ht-product-action ul li.woolentor-cart a::before' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_line_height',
                        [
                            'label' => __( 'Line Height', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
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
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a i' => 'line-height: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .woolentor-compare.compare::before,{{WRAPPER}} .ht-product-action ul li.woolentor-cart a::before' => 'line-height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'product_action_button_normal_background_color',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li',
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_normal_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_normal_margin',
                        [
                            'label' => __( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'product_action_button_normal_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li',
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_width',
                        [
                            'label' => __( 'Width', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
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
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_height',
                        [
                            'label' => __( 'Height', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
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
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover
                $this->start_controls_tab(
                    'product_action_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_action_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => woolentor_get_option_pro( 'btn_hover_color','woolentor_style_tabs', '#dc9a0e' ),
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li:hover a' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ht-product-action .yith-wcwl-wishlistaddedbrowse a, .ht-product-action .yith-wcwl-wishlistexistsbrowse a' => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'product_action_button_hover_background_color',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'product_action_button_hover_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Countdown tab section
        $this->start_controls_section(
            'universal_product_counter_style_section',
            [
                'label' => __( 'Offer Price Counter', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_countdown'=>'yes',
                    'product_layout!'=>'content'
                ]
            ]
        );

            $this->add_control(
                'product_counter_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'counter_color','woolentor_style_tabs', '#ffffff' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown .cd-single .cd-single-inner h3' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown .cd-single .cd-single-inner p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'product_counter_background_color',
                    'label' => __( 'Counter Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown .cd-single .cd-single-inner,{{WRAPPER}} .ht-products .ht-product.ht-product-countdown-fill .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown',
                ]
            );

            $this->add_responsive_control(
                'product_counter_space_between',
                [
                    'label' => __( 'Space', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown .cd-single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Shorting and result count Style Section
        $this->start_controls_section(
            'product-sorting-result-count-section',
            [
                'label' => __( 'Sorting & Result Count', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'result_count_typography',
                    'label' => __( 'Result Count Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive .woocommerce-result-count',
                ]
            );

            $this->add_control(
                'result_count_text_color',
                [
                    'label' => __( 'Result Count Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive .woocommerce-result-count' => 'color: {{VALUE}}',
                    ],
                    'separator'=>'after',
                ]
            );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'sorting_typography',
                    'label' => __( 'Sorting Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive .woocommerce-ordering select',
                ]
            );

            $this->add_control(
                'sorting_text_color',
                [
                    'label' => __( 'Sorting Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive .woocommerce-ordering select' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'sorting_border_color',
                [
                    'label' => __( 'Sorting Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive .woocommerce-ordering select' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Pagination Style Section
        $this->start_controls_section(
            'product-pagination-section',
            [
                'label' => __( 'Pagination', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

            $this->add_responsive_control(
                'product_pagination_position',
                [
                    'label'   => __( 'Alignment', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left'    => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'default'     => is_rtl() ? 'right' : 'left',
                    'toggle'      => false,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination'   => 'text-align: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'pagination_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li a,{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li span',
                ]
            );

            $this->start_controls_tabs('product_pagination_style_tabs');

                // Pagination normal style
                $this->start_controls_tab(
                    'product_pagination_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_pagination_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul' => 'border-color: {{VALUE}} !important',
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li' => 'border-right-color: {{VALUE}} !important; border-left-color: {{VALUE}} !important',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_pagination_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li a, {{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_pagination_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li a, {{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_pagination_link_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li a' => 'color: {{VALUE}} !important',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_pagination_link_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Pagination Active style
                $this->start_controls_tab(
                    'product_pagination_style_active_tab',
                    [
                        'label' => __( 'Active', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_pagination_link_color_hover',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}} !important',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_pagination_link_bg_color_hover',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-custom-product-archive nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}} !important',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // List View Style section
        $this->start_controls_section(
            'universal_product_list_style_section',
            [
                'label' => __( 'List View Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'product_layout!'=>'content'
                ]
            ]
        );

            // Product Description
            $this->add_control(
                'product_list_area_heading',
                [
                    'label' => __( 'Area', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_list_border_area',
                    'label' => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wlshop-list-wrap',
                ]
            );

            $this->add_responsive_control(
                'product_list_border_radius_area',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            // Product Description
            $this->add_control(
                'product_list_viewmode_heading',
                [
                    'label' => __( 'Viewing Mode Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_list_viewmode_button_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#000000',
                    'selectors' => [
                        '{{WRAPPER}} .wl-shop-tab-links li a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_viewmode_active_color',
                [
                    'label' => __( 'Active Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#f05b64',
                    'selectors' => [
                        '{{WRAPPER}} .wl-shop-tab-links li a:hover' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .wl-shop-tab-links li a.htactive' => 'color: {{VALUE}};',
                    ],
                ]
            );

            // Product Description
            $this->add_control(
                'product_list_description_heading',
                [
                    'label' => __( 'Product Description', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_list_description_typography',
                    'selector' => '{{WRAPPER}} .wlshop-list-content .woocommerce-product-details__short-description p',
                ]
            );

            $this->add_control(
                'product_list_description_color',
                [
                    'label' => __( 'Description Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'desc_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-content .woocommerce-product-details__short-description p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            // Product Category
            $this->add_control(
                'product_list_category_heading',
                [
                    'label' => __( 'Product Category', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_list_category_typography',
                    'selector' => '{{WRAPPER}} .wlshop-list-content .ht-product-categories a',
                ]
            );

            $this->add_control(
                'product_list_category_color',
                [
                    'label' => __( 'Category Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'category_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-content .ht-product-categories a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_category_hover_color',
                [
                    'label' => __( 'Category Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'category_hover_color','woolentor_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-content .ht-product-categories a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            // Product Title
            $this->add_control(
                'product_list_title_heading',
                [
                    'label' => __( 'Product Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_list_title_typography',
                    'selector' => '{{WRAPPER}} .wlshop-list-content .ht-list-product-title',
                ]
            );

            $this->add_control(
                'product_list_title_color',
                [
                    'label' => __( 'Title Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'title_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-content .ht-list-product-title a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_title_hover_color',
                [
                    'label' => __( 'Title Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'title_hover_color','woolentor_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-content .ht-list-product-title a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            // Product Price
            $this->add_control(
                'product_list_price_heading',
                [
                    'label' => __( 'Product Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_list_sale_price_color',
                [
                    'label' => __( 'Sale Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'sale_price_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-wrap .wlshop-list-content .ht-product-list-price span.price' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_list_sale_price_typography',
                    'selector' => '{{WRAPPER}} .wlshop-list-wrap .wlshop-list-content .ht-product-list-price span.price',
                ]
            );

            $this->add_control(
                'product_list_regular_price_color',
                [
                    'label' => __( 'Regular Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'default' => woolentor_get_option_pro( 'regular_price_color','woolentor_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-wrap .wlshop-list-content .ht-product-list-price span.price del span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_list_regular_price_typography',
                    'selector' => '{{WRAPPER}} .wlshop-list-wrap .wlshop-list-content .ht-product-list-price span.price del span',
                ]
            );

            // Product Rating
            $this->add_control(
                'product_list_rating_heading',
                [
                    'label' => __( 'Product Rating', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_list_rating_color',
                [
                    'label' => __( 'Empty Rating Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'empty_rating_color','woolentor_style_tabs', '#aaaaaa' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .wlshop-list-wrap .wlshop-list-content .ht-product-list-ratting .ht-product-ratting-wrap .ht-product-ratting .ht-product-user-ratting i.empty' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_rating_give_color',
                [
                    'label' => __( 'Rating Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'rating_color','woolentor_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .wlshop-list-wrap .wlshop-list-content .ht-product-list-ratting .ht-product-ratting-wrap .ht-product-ratting .ht-product-user-ratting i' => 'color: {{VALUE}};',
                    ],
                ]
            );

            // List view cart button
            $this->add_control(
                'product_list_cart_button_heading',
                [
                    'label' => __( 'Add to Cart Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_list_cart_button_typography',
                    'selector' => '{{WRAPPER}} .woocommerce .ht-product-list-action ul li a',
                ]
            );

            $this->add_control(
                'product_list_cart_button_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_color','woolentor_style_tabs', '#000000' ),
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .ht-product-list-action ul li a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_cart_button_border_color',
                [
                    'label' => __( 'Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_bg_color','woolentor_style_tabs', '#000000' ),
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .ht-product-list-action ul li a' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_cart_button_background_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_bg_color','woolentor_style_tabs', '#ffffff' ),
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .ht-product-list-action ul li a' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_cart_button_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_hover_color','woolentor_style_tabs', '#ffffff' ),
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .ht-product-list-action ul li a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_cart_button_hover_border_color',
                [
                    'label' => __( 'Hover Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_hover_bg_color','woolentor_style_tabs', '#ff3535' ),
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .ht-product-list-action ul li a:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_cart_button_hover_background_color',
                [
                    'label' => __( 'Hover Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_hover_bg_color','woolentor_style_tabs', '#ff3535' ),
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce .ht-product-list-action ul li a:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            // List view quickview button
            $this->add_control(
                'product_list_quickview_button_heading',
                [
                    'label' => __( 'Quickview Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_list_quickview_button_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_color','woolentor_style_tabs', '#000000' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-wrap .wlproduct-list-img .product-quickview a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_quickview_button_background_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_bg_color','woolentor_style_tabs', '#ffffff' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-wrap .wlproduct-list-img .product-quickview a' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_quickview_button_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_hover_color','woolentor_style_tabs', '#ffffff' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-wrap .wlproduct-list-img .product-quickview a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_list_quickview_button_hover_background_color',
                [
                    'label' => __( 'Hover Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => woolentor_get_option_pro( 'list_btn_hover_bg_color','woolentor_style_tabs', '#ff3535' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlshop-list-wrap .wlproduct-list-img .product-quickview a:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Progressbar Style
        $this->start_controls_section(
            'section_stock_progressbar_style',
            [
                'label' => __( 'Stock Progressbar', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'stock_progress_bar'=>'yes',
                ],
            ]
        );

            $this->add_control(
                'progressbar_heading',
                [
                    'label' => __( 'Progressbar', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'progressbar_height',
                [
                    'label' => __( 'Height', 'woolentor-pro' ),
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
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlprogress-area' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlprogress-area' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_active_bg_color',
                [
                    'label' => __( 'Sell Progress Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlprogress-bar' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_area',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_order_heading',
                [
                    'label' => __( 'Order & Ability Counter', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'order_ability_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-stock-progress-bar .wlstock-info',
                ]
            );

            $this->add_control(
                'order_ability_color',
                [
                    'label' => __( 'Label Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlstock-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'counter_number_color',
                [
                    'label' => __( 'Counter Number Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlstock-info span' => 'color: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Quick Cart Style
        $this->start_controls_section(
            'section_variation_quick_cart_style',
            [
                'label' => __( 'Variation product quick cart', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'variation_quick_addtocart'=>'yes',
                ],
            ]
        );
            
            $this->add_control(
                'cart_button_heading',
                [
                    'label' => __( 'Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->start_controls_tabs('quick_cart_button_style_tabs');

                $this->start_controls_tab(
                    'quick_cart_button_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'quick_cart_button_color',
                        [
                            'label' => __( 'Button Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce div.product .woolentor-quick-cart-form form .single_add_to_cart_button' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .ht-products.woocommerce .woolentor-quick-cart-area a.added_to_cart' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'quick_cart_button_typography',
                            'label' => __( 'Typography', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woocommerce div.product .woolentor-quick-cart-form form .single_add_to_cart_button,{{WRAPPER}} .ht-products.woocommerce .woolentor-quick-cart-area a.added_to_cart',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'quick_cart_button_background',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .woocommerce div.product .woolentor-quick-cart-form form .single_add_to_cart_button,{{WRAPPER}} .ht-products.woocommerce .woolentor-quick-cart-area a.added_to_cart',
                            'exclude' => ['image'],
                        ]
                    );

                    $this->add_responsive_control(
                        'quick_cart_button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce div.product .woolentor-quick-cart-form form .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .ht-products.woocommerce .woolentor-quick-cart-area a.added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'quick_cart_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woocommerce div.product .woolentor-quick-cart-form form .single_add_to_cart_button,{{WRAPPER}} .ht-products.woocommerce .woolentor-quick-cart-area a.added_to_cart',
                        ]
                    );

                    $this->add_responsive_control(
                        'quick_cart_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce div.product .woolentor-quick-cart-form form .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .ht-products.woocommerce .woolentor-quick-cart-area a.added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'quick_cart_button_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'quick_cart_button_hover_color',
                        [
                            'label' => __( 'Button Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce div.product .woolentor-quick-cart-form form .single_add_to_cart_button:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .ht-products.woocommerce .woolentor-quick-cart-area a.added_to_cart:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'quick_cart_button_hover_background',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .woocommerce div.product .woolentor-quick-cart-form form .single_add_to_cart_button:hover,{{WRAPPER}} .ht-products.woocommerce .woolentor-quick-cart-area a.added_to_cart:hover',
                            'exclude' => ['image'],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'quick_cart_button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woocommerce div.product .woolentor-quick-cart-form form .single_add_to_cart_button:hover,{{WRAPPER}} .ht-products.woocommerce .woolentor-quick-cart-area a.added_to_cart:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


    }


    protected function render( $instance = [] ) {

        $settings  = $this->get_settings_for_display();
        $tabuniqid = $this->get_id();
        $settings['tabuniqid'] = $tabuniqid;

        $type = 'recent_products';
        $type = $this->parse_product_type( $settings['product_type'] );

        $filterable = ( 'yes' === $settings['filterable'] ? rest_sanitize_boolean( $settings['filterable'] ) : false );

        $shortcode = new \WooLentor_WC_Shortcode_Products( $settings, $type, $filterable );
        $content = $shortcode->get_content( $settings['product_layout'] );

        $not_found_content = woolentor_pro_products_not_found_content();
        $wrap_class = 'ht-products';
        $wrap_attributes = '';

        $content_class = 'wl-shop-tab-area';
        $content_class .= ( ( 'grid' === $settings['woolentor_product_view_mode'] ) ? ' grid_view' : ' list_view' );

        if ( true === $filterable ) {
            $wrap_class .= ' wl-filterable-products-wrap';
            $wrap_attributes .= 'data-wl-widget-name="woolentor-custom-product-archive"';
            $wrap_attributes .= ' data-wl-widget-settings="' . esc_attr( htmlspecialchars( wp_json_encode( $settings ) ) ) . '"';

            $content_class .= ' wl-filterable-products-content';
        }

        ?>
            <div class="<?php echo esc_attr( $wrap_class ); ?>"<?php echo $wrap_attributes; ?>>
                <div id="wl-shop-tab-area-<?php echo $this->get_id(); ?>" class="<?php echo esc_attr( $content_class ); ?>">
                    <?php
                        if ( strip_tags( trim( $content ) ) ) {
                            echo $content;
                        } else{
                            echo $not_found_content;
                        }
                    ?>
                </div>
            </div>

            <script type="text/javascript">
                ;jQuery(document).ready(function($) {
                    var uniqid = '<?php echo $this->get_id(); ?>';
                    function woolentor_tabs_pro( $tabmenus, $tabpane ){
                        $tabmenus.on('click', 'a', function(e){
                            e.preventDefault();
                            var $this = $(this),
                                $target = $this.attr('href'),
                                $data = $this.data('tabvalue');
                            $this.addClass('htactive').parent().siblings().children('a').removeClass('htactive');
                            $( $tabpane ).removeClass('grid_view list_view');
                            $( $tabpane ).addClass( $data );

                            // refresh slick
                            $id = $this.attr('href');
                            $('.wl-shop-tab-area').find('.slick-slider').slick('refresh');
                        });
                    }
                    woolentor_tabs_pro( $( "#wl-shop-tab-links-"+uniqid ), '#wl-shop-tab-area-'+uniqid );
                });
            </script>

            <?php if ( Plugin::instance()->editor->is_edit_mode() ) { ?>
                <script type="text/javascript">
                    ;jQuery(document).ready(function($) {
                        'use strict';
                        $(".ht-product-image-thumbnaisl-<?php echo $tabuniqid; ?>").slick({
                            dots: true,
                            arrows: true,
                            prevArrow: '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
                            nextArrow: '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
                        });
                    });
                </script>
            <?php } ?>

            <?php

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

            case 'mixed_order':
                $product_type = 'random';
                break;

            default:
                $product_type = 'products';
                break;
        }
        return $product_type;
    }

}