<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Cart_Table_List_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-cart-table-list';
    }
    
    public function get_title() {
        return __( 'WL: Cart Table (List Style)', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-product-breadcrumbs';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets-pro',
        ];
    }

    public function get_script_depends(){
        return [
            'woolentor-widgets-scripts-pro',
        ];
    }

    public function get_keywords(){
        return ['cart','table','list','woocommerce cart table','woocommerce cart','customize cart table'];
    }

    protected function register_controls() {
        // General
        $this->start_controls_section(
            'cart_general',
            [
                'label' => esc_html__( 'General', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'heading_general_product',
                [
                    'label' => __( 'Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_control(
                'style',
                [
                    'label'   => __( 'Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                    ]
                ]
            );

            $this->add_responsive_control(
                'vertical_alignment',
                [
                    'label'   => __( 'Vertical Alignment', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'flex-start',
                    'options' => [
                        'flex-start'    => __( 'Top', 'woolentor-pro' ),
                        'center'        => __( 'Middle', 'woolentor-pro' ),
                        'flex-end'      => __( 'Bottom', 'woolentor-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product' => 'align-items: {{VALUE}};',
                    ],
                    'condition' => [
                        'style!' => '',
                    ]
                ]
            );

            $this->add_control(
                'remove_product_link',
                [
                    'label'         => esc_html__( 'Remove Product link', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                ]
            );

            $this->add_control(
                'line_through_old_price',
                [
                    'label'         => __( 'Line Through Old Price', 'woolentor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Yes', 'woolentor' ),
                    'label_off'     => __( 'No', 'woolentor' ),
                    'description'   => __( 'Display the old price as a line-through. (if the product has any discount)', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'condition' => [
                        'style!' => '',
                    ]
                ]
            );

            $this->add_control(
                'heading_general_quantity',
                    [
                    'label'     => __( 'Quantity Input Field', 'woolentor-pro' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'disable_user_adj_qtn',
                [
                    'label'         => esc_html__( 'Disable users adjusting quantity', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                ]
            );

            $this->add_control(
                'qty_input_placement',
                [
                    'label' => esc_html__( 'Quantity Placement', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left'                => esc_html__( 'Left', 'woolentor-pro' ),
                        'after_title'         => esc_html__( 'After Title', 'woolentor-pro' ),
                    ],
                    'condition' => [
                        'style' => '1',
                    ]
                ]
            );

            $this->add_control(
                'heading_general_action_buttons',
                [
                    'label' => __( 'Product Action Buttons', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'style' => '1',
                        'dependent-control-name' => [ 'value-1', 'value-2' ],
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'show_details_action',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                            [
                                'name' => 'show_compare_action',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                            [
                                'name' => 'show_wishlist_action',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                            [
                                'name' => 'show_remove_action',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                        ],
                    ],
                ]
            );
            $this->add_control(
                'action_button_layout',
                [
                    'label' => esc_html__( 'Action Buttons Layout', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1' => esc_html__( 'Style 1', 'woolentor-pro' ),
                        '2' => esc_html__( 'Style 2', 'woolentor-pro' ),
                        '3' => esc_html__( 'Style 3', 'woolentor-pro' ),
                    ],
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'style',
                                'operator' => '===',
                                'value' => '1',
                            ],
                            [
                                'relation' => 'or',
                                'terms' => [
                                    [
                                        'name' => 'show_details_action',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ],
                                    [
                                        'name' => 'show_compare_action',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ],
                                    [
                                        'name' => 'show_wishlist_action',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ],
                                    [
                                        'name' => 'show_remove_action',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ],
                                ],
                            ]
                        ]
                    ],
                ]
            );

        $this->end_controls_section();

        // Visibility
        $this->start_controls_section(
            'visibility',
            array(
                'label' => __( 'Visibility', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );
            $this->add_control(
                'heading_visibility_contents',
                    [
                    'label' => __( 'Product Contents', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'show_thumbnail_remove_icon',
                [
                    'label'         => __( 'Remove Icon', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'return_value'  => 'yes',
                    'label_on'      => esc_html__( 'Show', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'Show', 'woolentor-pro' ),
                    'default'       => 'yes',
                ]
            );

            $this->add_control(
                'show_product_stock',
                [
                    'label'         => esc_html__( 'Product Stock', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Show', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'Hide', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                ]
            );

            $this->add_control(
                'stock_availability_placement',
                [
                    'label'   => esc_html__( 'Stock Placement', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        'left'    => esc_html__( 'Left', 'woolentor-pro' ),
                        'right'   => esc_html__( 'Right', 'woolentor-pro' ),
                    ],
                    'label_block' => false,
                    'default'     => 'left',
                    'condition' => [
                        'style' => '1',
                    ]
                ]
            );

            $this->add_control(
                'show_sku',
                [
                    'label'         => __( 'SKU', 'woolentor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'woolentor' ),
                    'label_off'     => __( 'Hide', 'woolentor' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'condition' => [
                        'style!' => '',
                    ]
                ]
            );

            $this->add_control(
                'show_meta_data',
                [
                    'label'         => __( 'Meta Data', 'woolentor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'woolentor' ),
                    'label_off'     => __( 'Hide', 'woolentor' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'condition' => [
                        'style!' => '',
                    ]
                ]
            );

            $this->add_control(
                'show_discount_percent',
                [
                    'label'         => __( 'Discounted Amount (%)', 'woolentor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'woolentor' ),
                    'label_off'     => __( 'Hide', 'woolentor' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'condition' => [
                        'style!' => '',
                    ]
                ]
            );

            $this->add_control(
                'discount_percent_placement',
                [
                    'label'   => esc_html__( 'Discount Placement', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        'with_price'    => esc_html__( 'With Price', 'woolentor-pro' ),
                        'separate_line' => esc_html__( 'Separate Line', 'woolentor-pro' ),
                    ],
                    'label_block' => false,
                    'default'     => 'with_price',
                    'condition'   => [
                        'style'                 => '1',
                        'show_discount_percent' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_visibility_action_buttons',
                [
                    'label' => __( 'Product Action Buttons', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'show_details_action',
                [
                    'label'         => __( 'Product Details', 'woolentor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'woolentor' ),
                    'label_off'     => __( 'Hide', 'woolentor' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'condition' => [
                        'style' => '1',
                    ]
                ]
            );
            
            $this->add_control(
                'show_compare_action',
                [
                    'label'         => __( 'Compare', 'woolentor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'woolentor' ),
                    'label_off'     => __( 'Hide', 'woolentor' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

            $this->add_control(
                'compare_icon_popover',
                [
                    'label' => esc_html__( 'Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => esc_html__( 'Default', 'woolentor-pro' ),
                    'label_on' => esc_html__( 'Custom', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'style' => '2',
                        'show_compare_action' => 'yes',
                    ]
                ]
            );

            $this->start_popover();
                $this->add_control(
                    'compare_icon',
                    [
                        'label' => esc_html__( 'Compare Icon', 'woolentor-pro' ),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'default' => [
                            'value' => 'fas fa-balance-scale',
                            'library' => 'fa-solid',
                        ],
                        'recommended' => [
                            'fa-solid' => [
                                'balance-scale',
                                'balance-scale-left',
                                'balance-scale-right',
                            ],
                        ],
                        'separator' => 'after',
                    ]
                );
            $this->end_popover();
            
            $this->add_control(
                'show_wishlist_action',
                [
                    'label'         => __( 'Wishlist', 'woolentor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'woolentor' ),
                    'label_off'     => __( 'Hide', 'woolentor' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'wishlist_icon_popover',
                [
                    'label' => esc_html__( 'Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => esc_html__( 'Default', 'woolentor-pro' ),
                    'label_on' => esc_html__( 'Custom', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'style' => '2',
                        'show_wishlist_action' => 'yes',
                    ]
                ]
            );
            $this->start_popover();
                $this->add_control(
                    'wishlist_icon',
                    [
                        'label' => esc_html__( 'Wishlist Icon', 'woolentor-pro' ),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'default' => [
                            'value' => 'far fa-heart',
                            'library' => 'fa-regular',
                        ],
                        'recommended' => [
                            'fa-solid' => [
                                'heart',
                            ],
                            'fa-regular' => [
                                'heart',
                            ],
                        ],
                    ]
                );

                $this->add_control(
                    'wishlist_icon_added',
                    [
                        'label' => esc_html__( 'Wishlist Icon - Added', 'woolentor-pro' ),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'default' => [
                            'value' => 'fas fa-heart',
                            'library' => 'fa-solid',
                        ],
                        'recommended' => [
                            'fa-solid' => [
                                'heart',
                            ],
                            'fa-regular' => [
                                'heart',
                            ],
                        ],
                        'separator' => 'after',
                    ]
                );
            $this->end_popover();

            $this->add_control(
                'show_remove_action',
                [
                    'label'         => __( 'Delete', 'woolentor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Show', 'woolentor' ),
                    'label_off'     => __( 'Hide', 'woolentor' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

            $this->add_control(
                'remove_icon_popover',
                [
                    'label' => esc_html__( 'Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => esc_html__( 'Default', 'woolentor-pro' ),
                    'label_on' => esc_html__( 'Custom', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'style' => '2',
                        'show_remove_action' => 'yes',
                    ]
                ]
            );
            $this->start_popover();
                $this->add_control(
                    'delete_action_icon',
                    [
                        'label' => esc_html__( 'Delete Icon', 'woolentor-pro' ),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'default' => [
                            'value' => 'fas fa-trash-alt',
                            'library' => 'fa-solid',
                        ],
                        'recommended' => [
                            'fa-solid' => [
                                'times',
                                'times-circle',
                                'trash',
                                'trash-alt',
                                'trash-restore',
                                'trash-restore-alt',
                            ],
                            'fa-regular' => [
                                'times',
                                'times-circle',
                                'trash-alt'
                            ],
                        ],
                    ]
                );
            $this->end_popover();

            $this->add_control(
                'heading_visibility_action_buttons_cart',
                [
                    'label' => __( 'Cart Action Buttons', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'show_continue_button',
                [
                    'label'         => esc_html__( 'Continue Shopping', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Show', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'Hide', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                ]
            );
            
            $this->add_control(
                'show_coupon_form',
                [
                    'label'         => esc_html__( 'Coupon Form', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Show', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'Hide', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

            $this->add_control(
                'show_update_button',
                [
                    'label'         => esc_html__( 'Update Cart', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Show', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'Hide', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'elements_ordering',
            array(
                'label' => __( 'Ordering', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'style!' => '',
                ]
            )
        );
            
            $this->add_control(
                'heading_order_contents',
                    [
                    'label' => __( 'Product Contents', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'order_price',
                [
                    'label'       => __( 'Price', 'woolentor-pro' ),
                    'type'        => Controls_Manager::NUMBER,
                    'label_block' => false,
                    'default'     => '1',
                    'placeholder' => '1',
                    'condition' => [
                        'style' => '2',
                    ]
                ]
            );

            $this->add_control(
                'order_qty',
                [
                    'label'       => __( 'Quantity', 'woolentor-pro' ),
                    'type'        => Controls_Manager::NUMBER,
                    'label_block' => false,
                    'default'     => '1',
                    'placeholder' => '1',
                    'condition' => [
                        'style' => '1',
                    ],
                ]
            );

            $this->add_control(
                'order_sku',
                [
                    'label'       => __( 'SKU', 'woolentor-pro' ),
                    'type'        => Controls_Manager::NUMBER,
                    'label_block' => false,
                    'default'     => '2',
                    'placeholder' => '2',
                    'condition' => [
                        'show_sku' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'order_meta_data',
                [
                    'label'       => __( 'Meta Data', 'woolentor-pro' ),
                    'type'        => Controls_Manager::NUMBER,
                    'label_block' => false,
                    'default'     => '3',
                    'placeholder' => '3',
                    'condition' => [
                        'show_sku' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'order_stock_availability',
                [
                    'label'       => __( ' Stock Availablity', 'woolentor-pro' ),
                    'type'        => Controls_Manager::NUMBER,
                    'label_block' => false,
                    'default'     => '4',
                    'placeholder' => '4',
                    'condition' => [
                        'show_product_stock' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_order_action_buttons',
                    [
                    'label' => __( 'Product Action Buttons', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'order_compare_action',
                [
                    'label'       => __( 'Compare', 'woolentor-pro' ),
                    'type'        => Controls_Manager::NUMBER,
                    'label_block' => false,
                    'default'     => '1',
                    'condition' => [
                        'style!' => '',
                        'show_compare_action' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'order_wishlist_action',
                [
                    'label'       => __( 'Wishlist', 'woolentor-pro' ),
                    'type'        => Controls_Manager::NUMBER,
                    'label_block' => false,
                    'default'     => '2',
                    'condition' => [
                        'style!' => '',
                        'show_wishlist_action' => 'yes',
                    ]
                ]
            );
            
            $this->add_control(
                'order_remove_action',
                [
                    'label'       => __( 'Remove', 'woolentor-pro' ),
                    'type'        => Controls_Manager::NUMBER,
                    'label_block' => false,
                    'default'     => '3',
                    'condition' => [
                        'style!' => '',
                        'show_remove_action' => 'yes',
                    ]
                ]
            );
        $this->end_controls_section();

        // Pricing Discount Roles
        $this->start_controls_section(
            'discount_rules',
            [
                'label' => esc_html__( 'Pricing Discount Rules', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'custom_price_discount_rule',
                [
                    'label'         => __( 'Enable Discount Rules', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

            $roles_fileds = new Repeater();

            $roles_fileds->add_control(
                'discount_qtn',
                [
                    'label' => esc_html__( 'Quantity', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                ]
            );

            $roles_fileds->add_control(
                'discount_amount',
                [
                    'label' => esc_html__( 'Percent / Amount', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                ]
            );

            $roles_fileds->add_control(
                'discount_type',
                [
                    'label' => esc_html__( 'Type', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'percent',
                    'options' => [
                        'percent' => esc_html__( 'Percent Of', 'woolentor-pro' ),
                        'amount'  => esc_html__( 'Amount Of', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'discount_rule_list',
                [
                    'label' => __( 'Discount Rules', 'woolentor-pro' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $roles_fileds->get_controls(),
                    'title_field' => 'Quantity: {{{ discount_qtn }}} Amount: {{{ discount_amount }}}',
                    'prevent_empty' => false,
                    'condition' => [
                        'custom_price_discount_rule' => 'yes'
                    ]
                ]
            );

        $this->end_controls_section();

        // Product Gift Roles
        $this->start_controls_section(
            'product_gift_rules',
            [
                'label' => esc_html__( 'Buy One Get One Rule', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'brougth_pro_id',
                [
                    'label' => __( 'Select Bought Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => woolentor_post_name( 'product' ),
                ]
            );
            
            $this->add_control(
                'gifted_pro_id',
                [
                    'label' => __( 'Select Gifted Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => woolentor_post_name( 'product' ),
                    'description'=>esc_html__( 'This product must be set price = 0', 'woolentor-pro' )
                ]
            );

        $this->end_controls_section();

        // Custom Labels
        $this->start_controls_section(
            'custom_labels',
            array(
                'label' => __( 'Custom Labels', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );
            $this->add_control(
                'heading_custom_labels_product',
                [
                    'label' => __( 'Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'label_qty',
                [
                    'label'       => __( 'Quantity', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Qty:', 'woolentor-pro' ),
                    'label_block' => true,
                    'condition'   => [
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'discount_percent_label',
                [
                    'label'       => __( 'Discount Amount', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'description' => __( 'Use <b><code>{discounted_amount}</code></b> placeholder to show the amount.', 'woolentor-pro' ),
                    'label_block' => true,
                    'default'     => __( '{discounted_amount}% off', 'woolentor-pro' ),
                    'placeholder' => __( 'Sale {discounted_amount}% off', 'woolentor-pro' ),
                    'condition' => [
                        'style!' => '',
                        'show_discount_percent' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'heading_custom_labels_product_actions',
                [
                    'label' => __( 'Product - Actions', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'style' => '1',
                    ]
                ]
            );
            $this->add_control(
                'label_show_product_details',
                [
                    'label'       => __( 'Show Product Details', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Show product details', 'woolentor-pro' ),
                    'label_block' => false,
                    'condition'   => [
                        'style' => '1',
                        'show_details_action' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'label_add_to_wishlist',
                [
                    'label'       => __( 'Add to Wishlist', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Add to Wishlist', 'woolentor-pro' ),
                    'label_block' => false,
                    'separator'   => 'before',
                    'condition'   => [  
                        'style' => '1',
                        'show_wishlist_action' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'label_added_to_wishlist',
                [
                    'label'       => __( 'Added to Wishlist', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Added to Wishlist', 'woolentor-pro' ),
                    'label_block' => false,
                    'condition'   => [
                        'style' => '1',
                        'show_wishlist_action' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'label_already_added_to_wishlist',
                [
                    'label'       => __( 'Already in Wishlist', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Product already added', 'woolentor-pro' ),
                    'label_block' => false,
                    'condition'   => [
                        'style' => '1',
                        'show_wishlist_action' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'label_add_to_compare',
                [
                    'label'       => __( 'Add to Compare', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Add to Compare', 'woolentor-pro' ),
                    'label_block' => false,
                    'separator'   => 'before',
                    'condition'   => [
                        'style' => '1',
                        'show_compare_action' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'label_added_to_compare',
                [
                    'label'       => __( 'Added to Compare', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Product Added', 'woolentor-pro' ),
                    'label_block' => false,
                    'separator'   => 'after',
                    'condition'   => [
                        'style' => '1',
                        'show_compare_action' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'label_delete',
                [
                    'label'       => __( 'Delete', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Delete', 'woolentor-pro' ),
                    'label_block' => false,
                    'condition'   => [
                        'style' => '1',
                        'show_remove_action' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'heading_custom_labels_cart_actions',
                [
                    'label' => __( 'Cart - Actions', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );
            $this->add_control(
                'coupon_form_button_txt',
                [
                    'label' => __( 'Apply Coupon', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Apply coupon', 'woolentor-pro' ),
                    'placeholder' => __( 'Apply coupon button text', 'woolentor-pro' ),
                    'condition'=>[
                        'show_coupon_form'=>'yes',
                    ],
                    'label_block'=>false,
                ]
            );

            $this->add_control(
                'coupon_form_pl_txt',
                [
                    'label' => __( 'Coupon Placeholder', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Coupon code', 'woolentor-pro' ),
                    'placeholder' => __( 'Coupon code', 'woolentor-pro' ),
                    'condition'=>[
                        'show_coupon_form'=>'yes',
                    ],
                    'label_block'=>false,
                ]
            );

            $this->add_control(
                'continue_button_txt',
                [
                    'label' => __( 'Continue Shopping', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Continue Shopping', 'woolentor-pro' ),
                    'placeholder' => __( 'Continue Shopping button text', 'woolentor-pro' ),
                    'condition'=>[
                        'show_continue_button'=>'yes',
                    ],
                    'label_block'=>false,
                ]
            );

            $this->add_control(
                'update_cart_button_txt',
                [
                    'label' => __( 'Update cart', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Update cart', 'woolentor-pro' ),
                    'placeholder' => __( 'Update cart button text', 'woolentor-pro' ),
                    'condition'=>[
                        'show_update_button'=>'yes',
                    ],
                    'label_block'=>false,
                ]
            );

        $this->end_controls_section();

        // Cart Table
        $this->start_controls_section(
            'cart_table_style_section',
            [
                'label' => __( 'Table', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_table_border',
                    'label' => __( 'Table Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart',
                ]
            );

            $this->add_responsive_control(
                'cart_table_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-collapse:separate;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_table_padding',
                [
                    'label' => __( 'Table Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Cart Table Content
        $this->start_controls_section(
            'cart_content_style_section',
            [
                'label' => __( 'Product Wrapper', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,   
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'table_cell_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-cart-product',
                ]
            );

            $this->add_responsive_control(
                'table_cell_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'cart_table_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-cart-product',
                ]
            );

            $this->add_responsive_control(
                'table_cell_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'table_cell_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Remove Button Style
        $this->start_controls_section(
            'cart_product_remove_style',
            array(
                'label' => __( 'Remove Icon', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style!' => '',
                    'show_thumbnail_remove_icon' => 'yes',
                ]
            )
        );

            $this->start_controls_tabs( 'cart_remove_style_tabs' );

                // Normal Style
                $this->start_controls_tab( 
                    'product_remove_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_remove_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item :is(td.product-remove,div.product-remove) a.remove' => 'color: {{VALUE}} !important',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_remove_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove' => 'background: {{VALUE}} !important',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td a.remove' => 'background: {{VALUE}} !important'
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_product_remove_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove,{{WRAPPER}} .shop_table.cart tr.cart_item td a.remove',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_product_remove_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td a.remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover Style
                $this->start_controls_tab( 
                    'product_remove_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_remove_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove:hover' => 'color: {{VALUE}} !important',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td a.remove:hover' => 'color: {{VALUE}} !important'
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_remove_background_hover_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove:hover' => 'background: {{VALUE}} !important',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td a.remove:hover' => 'background: {{VALUE}} !important'
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_product_remove_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove:hover,{{WRAPPER}} .shop_table.cart tr.cart_item td a.remove:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Product Image
        $this->start_controls_section(
            'cart_product_image_style',
            array(
                'label' => __( 'Product Image', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'product_image_width',
                [
                    'label' => __( 'Image Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 400,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 170,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-thumbnail img' => 'width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_image_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-thumbnail img',
                ]
            );

            $this->add_responsive_control(
                'product_image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_image_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_image_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );  

        $this->end_controls_section();

        // Product Title
        $this->start_controls_section(
            'cart_product_title_style',
            array(
                'label' => __( 'Product Title', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs( 'cart_item_style_tabs' );

                // Product Title Normal Style
                $this->start_controls_tab( 
                    'product_title_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_title_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item .product-name' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'cart_product_title_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .shop_table.cart tr.cart_item .product-name',
                        )
                    );

                    $this->add_responsive_control(
                        'cart_product_title_margin',
                        [
                            'label' => __( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .product-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_product_title_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .product-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Product Title Hover Style
                $this->start_controls_tab( 
                    'product_title_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_title_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item .product-name a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Product Price
        $this->start_controls_section(
            'cart_product_price_style',
            array(
                'label' => __( 'Product Price', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'heading_cart_product_price_wrapper',
                    [
                    'label' => __( 'Wrapper', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_responsive_control(
                'cart_product_price_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after'
                ]
            );

            $this->add_control(
                'heading_cart_product_price',
                    [
                    'label' => __( 'Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_product_price_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-price,{{WRAPPER}} .shop_table.cart tr.cart_item div.product-price .amount',
                )
            );

            $this->add_control(
                'cart_product_price_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item .product-price' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .shop_table.cart tr.cart_item .product-price .amount' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'after'
                ]
            );

            $this->add_responsive_control(
                'cart_product_price_new_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-price-new' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'heading_cart_product_price_old',
                    [
                    'label' => __( 'Price Old', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_product_price_old_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-product-price-old,{{WRAPPER}} .woolentor-product-price-old .amount ',
                )
            );
            
            $this->add_control(
                'cart_product_price_old_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-price-old,{{WRAPPER}} .woolentor-product-price-old .amount ' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_responsive_control(
                'cart_product_price_old_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-price-old' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

         // Product Quantity Field
        $this->start_controls_section(
            'cart_product_quantity_field_style',
            array(
                'label' => __( 'Quantity Field', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'heading_quantity_field_label',
                    [
                    'label' => __( 'Label', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );
            
            $this->add_control(
                'cart_quantity_field_label_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-quantity span' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'heading_quantity_field_input',
                    [
                    'label' => __( 'Input', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'cart_quantity_field_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-quantity input[type=number]' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_quantity_field_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-quantity input[type=number]' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_quantity_field_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-quantity input[type=number]',
                ]
            );

            $this->add_control(
                'heading_quantity_margin',
                    [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );
            $this->add_responsive_control(
                'cart_quantity_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item div.product-quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        $this->end_controls_section();

        // Meta data
        $this->start_controls_section(
            'cart_product_meta_data',
            array(
                'label' => __( 'Meta Data', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_meta_data' => 'yes',
                ]
            )
        );

            $this->add_control(
                'heading_cart_product_meta_data_label',
                    [
                    'label' => __( 'Label', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_cart_product_meta_data_label_typo',
                    'selector' => '{{WRAPPER}} .wl-variation-key',
                ]
            );

            $this->add_control(
                'cart_product_meta_data_label_color',
                [
                    'label'=> __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl-variation-key' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'heading_cart_product_meta_data_value',
                    [
                    'label' => __( 'Value', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_cart_product_meta_data_value_typo',
                    'selector' => '{{WRAPPER}} .woolentor-cart-product-meta > div span.wl-variation-value',
                ]
            );

            $this->add_control(
                'cart_product_meta_data_value_color',
                [
                    'label'=> __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-meta > div span.wl-variation-value' => 'color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Meta data - sku
        $this->start_controls_section(
            'cart_product_meta_data_sku',
            array(
                'label' => __( 'Meta Data - SKU  ', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_responsive_control(
                'cart_product_meta_data_sku_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-meta.wl-sku' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_product_meta_data_sku_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-meta.wl-sku' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        $this->end_controls_section();

        // Meta data - variations
        $this->start_controls_section(
            'cart_product_meta_data_variations',
            array(
                'label' => __( 'Meta Data - Variations  ', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_responsive_control(
                'cart_product_meta_data_variations_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-meta.wl-variations' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_product_meta_data_variations_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-meta.wl-variations' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        $this->end_controls_section();

        // Stock Status
        $this->start_controls_section(
            'cart_product_stock_availability',
            array(
                'label' => __( 'Stock Availablity', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_product_stock' => 'yes',
                ]
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cart_product_stock_availability_typo',
                    'selector' => '{{WRAPPER}} .woolentor-cart-product-stock',
                ]
            );

            $this->add_control(
                'cart_product_stock_availability_color',
                [
                    'label'=> __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-stock' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_product_stock_availability_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-stock' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Discount Label
        $this->start_controls_section(
            'cart_product_discount_label',
            array(
                'label' => __( 'Discount Label  ', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_discount_percent' => 'yes',
                ]
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cart_product_discount_label_typo',
                    'selector' => '{{WRAPPER}} .woolentor-cart-product-sale',
                ]
            );

            $this->add_control(
                'cart_product_discount_label_color',
                [
                    'label'=> __( 'Text Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-sale' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_product_discount_label_bg_color',
                [
                    'label'=> __( 'BG Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-sale' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_product_discount_label_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-cart-product-sale',
                ]
            );

            $this->add_responsive_control(
                'cart_product_discount_label_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-sale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_product_discount_label_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-sale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Action Buttons
        $this->start_controls_section(
            'cart_product_action_buttons',
            array(
                'label' => __( 'Action Buttons - Product', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style!' => '',
                ]
            )
        );

            $this->add_control(
                'heading_cart_product_action_buttons_wrapper',
                    [
                    'label' => __( 'Wrapper', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_product_action_wrapper_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-cart-product-actions',
                ]
            );

            $this->add_responsive_control(
                'cart_product_action_wrapper_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-actions' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_product_action_buttons_spacing',
                [
                    'label' => __( 'Spacing', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-actions' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_product_action_wrapper_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-actions' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'heading_cart_product_action_button',
                    [
                    'label'     => __( 'Button', 'woolentor-pro' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cart_product_action_buttons_typo',
                    'selector' => '{{WRAPPER}} .woolentor-cart-product-actions-btn,{{WRAPPER}} .woolentor-cart-product-actions-btn a',
                ]
            );

            $this->add_control(
                'cart_product_action_buttons_bg_color',
                [
                    'label'=> __( 'BG Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-actions > *' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_product_action_buttons_text_color',
                [
                    'label'=> __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-actions-btn,{{WRAPPER}} .woolentor-cart-product-actions-btn a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_product_action_buttons_text_hover_color',
                [
                    'label'=> __( 'Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-actions-btn,{{WRAPPER}} .woolentor-cart-product-actions-btn a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_product_action_buttons_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-cart-product-actions > *',
                ]
            );

            $this->add_responsive_control(
                'cart_product_action_buttons_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-actions > *' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_product_action_buttons_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-cart-product-actions > *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Cart Table Content
        $this->start_controls_section(
            'action_buttons_cart',
            [
                'label' => __( 'Actions Button - Cart', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_action_table_cell_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} tr:last-child td',
                ]
            );

            $this->add_responsive_control(
                'cart_action_table_cell_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} tr:last-child td' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'cart_action_cart_table_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} tr:last-child td',
                ]
            );

            $this->add_responsive_control(
                'cart_action_table_cell_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} tr:last-child td' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_action_table_cell_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} tr:last-child td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Update cart
        $this->start_controls_section(
            'cart_update_button_style',
            array(
                'label' => __( 'Update Cart Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_update_button'=>'yes',
                ],
            )
        );

            $this->start_controls_tabs( 'cart_update_style_tabs' );

                // Product Title Normal Style
                $this->start_controls_tab( 
                    'cart_update_button_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_update_button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_update_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'cart_update_button_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_update_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_update_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_update_button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Update cart button hover style
                $this->start_controls_tab( 
                    'cart_update_button_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_update_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_update_button_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_update_button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_update_button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Continue Button Style
        $this->start_controls_section(
            'cart_continue_button_style',
            array(
                'label' => __( 'Continue Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_continue_button'=>'yes',
                ],
            )
        );

            $this->start_controls_tabs( 'cart_continue_style_tabs' );

                // Continue Button Normal Style
                $this->start_controls_tab( 
                    'cart_continue_button_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_continue_button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_continue_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'cart_continue_button_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_continue_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_continue_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_continue_button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_continue_button_margin',
                        [
                            'label' => __( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Cart continue Button hover style
                $this->start_controls_tab( 
                    'cart_continue_button_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_continue_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_continue_button_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_continue_button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_continue_button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Apply coupon
        $this->start_controls_section(
            'cart_coupon_style',
            array(
                'label' => __( 'Apply coupon', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_coupon_form'=>'yes',
                ],
            )
        );

            $this->add_control(
                'cart_coupon_button_heading',
                [
                    'label' => __( 'Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_coupon_button_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart td.actions .coupon .button',
                )
            );

            $this->add_control(
                'cart_coupon_button_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_coupon_button_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button' => 'background-color: {{VALUE}}; transition:0.4s',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_coupon_button_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart td.actions .coupon .button',
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_button_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_button_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '.theme-astra #content {{WRAPPER}} table.cart .button[name="apply_coupon"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'heading_button_hover',
                    [
                    'label' => __( 'Button - Hover', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'cart_coupon_button_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_coupon_button_hover_bg_color',
                [
                    'label' => __( 'Hover Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_coupon_hover_button_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart td.actions .coupon .button:hover',
                ]
            );

            $this->add_control(
                'cart_coupon_inputbox_heading',
                [
                    'label' => __( 'Input Box', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'cart_coupon_inputbox_color',
                [
                    'label' => __( 'Input Box Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_coupon_inputbox_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_coupon_inputbox_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text',
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_inputbox_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_inputbox_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_inputbox_width',
                [
                    'label' => __( 'Input Box Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 100,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text' => 'width: {{SIZE}}{{UNIT}} !important;',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'global_font_typography_section',
            [
                'label' => __('Global Font Family', 'woolentor-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'global_font_typography',
                [
                    'label'       => __( 'Font Family', 'woolentor-pro' ),
                    'description' => __('Set a specific font family for this widget.', 'woolentor-pro'),
                    'type'        => Controls_Manager::FONT,
                    'default'     => '',
                    'selectors' => [
                        '{{WRAPPER}} *:not(i)' => 'font-family: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section();

    }

    protected function render() {
        $settings  = $this->get_settings_for_display();

        $table_items = ( isset( $settings['table_item_list'] ) ? $settings['table_item_list'] : array() );

        // Cart Option
        $cart_table_opt = array(
            'update_cart_button' => array(
                'enable'    => $settings['show_update_button'],
                'button_txt'=> $settings['update_cart_button_txt'],
            ),
            'continue_shop_button'=> array(
                'enable'    => $settings['show_continue_button'],
                'button_txt'=> $settings['continue_button_txt'],
            ),
            'coupon_form' => array(
                'enable'        => $settings['show_coupon_form'],
                'button_txt'    => $settings['coupon_form_button_txt'],
                'placeholder'   => $settings['coupon_form_pl_txt'],
            ),
            'extra_options' => array(
                'disable_qtn'   => $settings['disable_user_adj_qtn'],
                'remove_link'   => $settings['remove_product_link'],
                'show_stock'    => $settings['show_product_stock'],
            ),
        );

        $by_one_get_one = [
            'bought_id' => !empty( $settings['brougth_pro_id'] ) ? $settings['brougth_pro_id'] : '',
            'gifted_id' => !empty( $settings['gifted_pro_id'] ) ? $settings['gifted_pro_id'] : ''
        ];

        if( class_exists('\WC_Shortcode_Cart') ){
            WooLentor_Shortcode_Cart_List::byOneGetone( $by_one_get_one );
            if( $settings['custom_price_discount_rule'] === 'yes' ){
                add_action( 'woocommerce_before_calculate_totals', [ $this, 'quantity_based_pricing' ], 9999 );
            }
            WooLentor_Shortcode_Cart_List::output( $settings, $atts = array(), $table_items, $cart_table_opt );
        }
        
    }

    public function quantity_based_pricing( $cart ) {

        if ( is_admin() && !defined( 'DOING_AJAX' ) ) return;

        $discount_rule_list = $this->get_settings_for_display('discount_rule_list');

        if( isset( $discount_rule_list ) && is_array( $discount_rule_list ) && count( $discount_rule_list ) > 0 ){
            foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
                $cart_item['data']->set_price( $this->apply_discount( $discount_rule_list, $cart_item ) );
            }
        }
        
    }

    /**
     * [apply_discount]
     * @param  [array] $threshold Discount data array
     * @param  [array] $cart_item cart data
     * @return [int]   price
     */
    public function apply_discount( $threshold, $cart_item ){

        $pre_price = $price = $cart_item['data']->get_price();
        foreach ( $threshold as $key => $data ) {
            if( $cart_item['quantity'] >= $data['discount_qtn'] && !empty( $data['discount_amount'] ) ){
                if( $data['discount_type'] === 'amount' ){
                    $price = $pre_price - ( $data['discount_amount'] / $cart_item['quantity'] );
                }else{
                    $price = round( $pre_price * ( 1 - ( $data['discount_amount'] / 100 ) ), 2 );
                }
            }
        }
        return $price;

    }


}


/**
 * Cart Shortcode
 *
 * Used on the cart page, the cart shortcode displays the cart contents and interface for coupon codes and other cart bits and pieces.
 *
 * @package WooCommerce/Shortcodes/Cart
 * @version 2.3.0
 */
if( class_exists('\WC_Shortcode_Cart') ){
    class WooLentor_Shortcode_Cart_List extends \WC_Shortcode_Cart{
        /**
         * Output the cart shortcode.
         */
        public static function output( $config = array(), $atts = '', $cartitem = [], $cartopt = [] ) {
            // Constants.
            wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

            $atts        = shortcode_atts( array(), $atts, 'woocommerce_cart' );
            $nonce_value = wc_get_var( $_REQUEST['woocommerce-shipping-calculator-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

            // Update Shipping. Nonce check uses new value and old value (woocommerce-cart). @todo remove in 4.0.
            if ( ! empty( $_POST['calc_shipping'] ) && ( wp_verify_nonce( $nonce_value, 'woocommerce-shipping-calculator' ) || wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) ) ) { // WPCS: input var ok.
                
                //self::calculate_shipping();

                // Also calc totals before we check items so subtotals etc are up to date.
                \WC()->cart->calculate_totals();
            }

            // Check cart items are valid.
            do_action( 'woocommerce_check_cart_items' );

            // Calc totals.
            \WC()->cart->calculate_totals();

            if ( \WC()->cart->is_empty() ) {
                wc_get_template( 'cart/cart-empty.php');
            } else {

                if( $config['style'] == '1' && file_exists( WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart/cart-table-list.php' ) ){
                    include WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart/cart-table-list.php';
                }elseif( $config['style'] == '2' && file_exists( WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart/cart-table-list-2.php') ){
                    include WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart/cart-table-list-2.php';
                }
            }

        }

        // By One get one apply
        public static function byOneGetone( $by_one_get_one ){

            $product_bought_id = !empty( $by_one_get_one['bought_id'] ) ? $by_one_get_one['bought_id'] : '';
            $product_gifted_id = !empty( $by_one_get_one['gifted_id'] ) ? $by_one_get_one['gifted_id'] : '';

            if( !empty( $product_gifted_id ) ){

                // see if product id in cart
                $product_bought_cart_id = \WC()->cart->generate_cart_id( $product_bought_id );
                $product_bought_in_cart = \WC()->cart->find_product_in_cart( $product_bought_cart_id );

                // see if gift id in cart
                $product_gifted_cart_id = \WC()->cart->generate_cart_id( $product_gifted_id );
                $product_gifted_in_cart = \WC()->cart->find_product_in_cart( $product_gifted_cart_id );


                // if not in cart remove gift, else add gift
                if ( ! $product_bought_in_cart ) {
                    if ( $product_gifted_in_cart ) \WC()->cart->remove_cart_item( $product_gifted_in_cart );
                } else {
                    if ( ! $product_gifted_in_cart ) \WC()->cart->add_to_cart( $product_gifted_id );
                }

            }

        }


    }
}