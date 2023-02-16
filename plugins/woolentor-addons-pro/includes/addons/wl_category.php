<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Category_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-category-list';
    }

    public function get_title() {
        return __( 'WL: Category List', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-product-categories';
    }

    public function get_categories() {
        return array( 'woolentor-addons' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets',
        ];
    }

    public function get_keywords(){
        return ['category','product category','category list','categorise'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Category List', 'woolentor-pro' ),
            )
        );

            $this->add_control(
                'layout',
                [
                    'label' => esc_html__( 'Select Layout', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'horizontal',
                    'options' => [
                        'vertical' => esc_html__('Vertical','woolentor-pro'),
                        'horizontal' => esc_html__('Horizontal','woolentor-pro'),
                    ],
                    'label_block' => true,
                ]
            );

            $this->add_responsive_control(
                'category_grid_column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '8',
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
                    'condition'=>[
                        'layout'=>'horizontal',
                    ],
                    'label_block' => true,
                    'prefix_class' => 'wl-columns%s-',
                ]
            );

            $this->add_control(
                'category_display_type',
                [
                    'label' => esc_html__( 'Category Display Type', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'all_cat',
                    'options' => [
                        'single_cat' => esc_html__('Single Category','woolentor-pro'),
                        'multiple_cat'=> esc_html__('Multiple Categories','woolentor-pro'),
                        'all_cat'=> esc_html__('All Categories','woolentor-pro'),
                    ],
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'product_categories',
                [
                    'label' => esc_html__( 'Select categories', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => woolentor_taxonomy_list(),
                    'condition' => [
                        'category_display_type' => 'single_cat',
                    ]
                ]
            );

            $this->add_control(
                'multi_categories',
                [
                    'label' => esc_html__( 'Select categories', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => woolentor_taxonomy_list(),
                    'condition' => [
                        'category_display_type' => 'multiple_cat',
                    ]
                ]
            );

            $this->add_control(
                'catorder',
                [
                    'label' => esc_html__( 'Order', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'ASC',
                    'options' => [
                        'ASC'   => esc_html__('Ascending','woolentor-pro'),
                        'DESC'  => esc_html__('Descending','woolentor-pro'),
                    ],
                    'condition' => [
                        'category_display_type!' => 'single_cat',
                    ]
                ]
            );

            $this->add_control(
                'catorderby',
                [
                    'label' => esc_html__( 'Orderby', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'name',
                    'options' => [
                        'ID'    => esc_html__('ID','woolentor'),
                        'name'  => esc_html__('Name','woolentor'),
                        'slug'  => esc_html__('Slug','woolentor'),
                        'parent' => esc_html__('Parent','woolentor'),
                        'menu_order' => esc_html__('Menu Order','woolentor'),
                    ],
                    'condition' => [
                        'category_display_type!' => 'single_cat',
                    ]
                ]
            );

            $this->add_control(
                'limitcount',
                [
                    'label' => esc_html__( 'Show items', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'default' => 5,
                    'condition' => [
                        'category_display_type' => 'all_cat',
                    ]
                ]
            );

            $this->add_control(
                'custom_title',
                [
                    'label' => esc_html__( 'Custom Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'category_display_type' => 'single_cat',
                    ]
                ]
            );

            $this->add_control(
                'catcustitle',
                [
                    'label' => __( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'rows' => 10,
                    'placeholder' => __( 'Type your title here', 'woolentor-pro' ),
                    'condition' => [
                        'custom_title' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'custom_image',
                [
                    'label' => esc_html__( 'Custom Image', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'category_display_type' => 'single_cat',
                    ]
                ]
            );

            $this->add_control(
                'catcusimage',
                [
                    'label' => __( 'Image', 'woolentor-pro' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'condition' => [
                        'custom_image' => 'yes',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'thumbnailsize',
                    'default' => 'thumbnail',
                    'separator' => 'none',
                ]
            );

        $this->end_controls_section();

        // Extra Option
        $this->start_controls_section(
            'section_extra_option',
            array(
                'label' => esc_html__( 'Extra Option', 'woolentor-pro' ),
            )
        );
            
            $this->add_control(
                'show_image',
                [
                    'label' => __( 'Show Image / Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'show_product_counter',
                [
                    'label' => esc_html__( 'Show Product Counter', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4 sup' => 'display: block !important;',
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4' => 'padding-right: 25px;',
                    ],
                ]
            );

            $this->add_control(
                'show_description',
                [
                    'label' => esc_html__( 'Show Category Description', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content p' => 'display: block !important;',
                    ],
                ]
            );

            $this->add_control(
                'length',
                [
                    'label' => esc_html__( 'Description length', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10000,
                    'step' => 1,
                    'default' => 15,
                    'condition'=>[
                        'show_description'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'show_custom_icon',
                [
                    'label' => esc_html__( 'Custom Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'select_category',
                [
                    'label' => esc_html__( 'Select Category', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => woolentor_taxonomy_list(),
                ]
            );

            $repeater->add_control(
                'icon',
                [
                    'label' => esc_html__( 'Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-star',
                        'library' => 'solid',
                    ],
                ]
            );

            $this->add_control(
                'category_icon_options',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [
                        [
                            'select_category' => esc_html__( 'Select Category', 'woolentor-pro' ),
                            'icon' => 'fas fa-star',
                        ]
                    ],
                    'title_field' => '{{{ select_category }}}',
                    'condition' => [
                        'show_custom_icon' => 'yes',
                    ],
                ]
            );

        $this->end_controls_section();

        // Area Style Section
        $this->start_controls_section(
            'category_style_section',
            [
                'label' => esc_html__( 'Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
                    'default' => 'yes',
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
                        'size' => 10,
                    ],
                    'condition'=>[
                        'no_gutters!'=>'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'padding: 0  {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'item_border',
                    'label' => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wlsingle-categorie',
                ]
            );

            $this->add_responsive_control(
                'contentalign',
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
                    'condition'=>[
                        'layout'=>'horizontal',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Title Style Section
        $this->start_controls_section(
            'category_title_style',
            [
                'label' => esc_html__( 'Title', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4 a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'title_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4 a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Counter Style Section
        $this->start_controls_section(
            'category_counter_style',
            [
                'label' => esc_html__( 'Counter', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_product_counter'=>'yes',
                ]
            ]
        );
            
            $this->add_control(
                'counter_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4 sup' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'counter_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4:hover sup' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content h4 sup',
                ]
            );

        $this->end_controls_section();

        // Description Style Section
        $this->start_controls_section(
            'category_description_style',
            [
                'label' => esc_html__( 'Description', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_description'=>'yes',
                ]
            ]
        );
            
            $this->add_control(
                'description_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'description_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wlsingle-categorie .wlcategorie-content p',
                ]
            );

        $this->end_controls_section();

        // Description Style Section
        $this->start_controls_section(
            'category_custom_icon_style',
            [
                'label' => esc_html__( 'Icon', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_custom_icon'=>'yes',
                ]
            ]
        );
            
            $this->add_control(
                'icon_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlsingle-categorie-img a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'icon_hover_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlsingle-categorie .wlsingle-categorie-img a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'icon_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wlsingle-categorie .wlsingle-categorie-img a',
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $display_type = $this->get_settings_for_display('category_display_type');
        $order = ! empty( $settings['catorder'] ) ? $settings['catorder'] : '';
        $orderby = ! empty( $settings['catorderby'] ) ? $settings['catorderby'] : '';

        $custom_title   = $this->get_settings_for_display('custom_title');
        $custom_image   = $this->get_settings_for_display('custom_image');
        $catcustitle    = $this->get_settings_for_display('catcustitle');
        $column         = $this->get_settings_for_display('category_grid_column');
        $layout         = $this->get_settings_for_display('layout');

        $collumval = 'wl-col-8';
        if( $column !='' ){
            $collumval = 'wl-col-'.$column;
        }

        $catargs = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => true,
        );

        if( $display_type == 'single_cat' ){
            $product_categories = $settings['product_categories'];
            $product_cats = str_replace( ' ', '', $product_categories );
            $catargs['slug'] = $product_cats;
        }
        elseif( $display_type == 'multiple_cat' ){
            $product_categories = $settings['multi_categories'];
            $product_cats = str_replace(' ', '', $product_categories);
            $catargs['slug'] = $product_cats;
        }else{
            $catargs['slug'] = '';
        }
        $prod_categories = get_terms( 'product_cat', $catargs );

        if( $display_type == 'all_cat' ){
            $limitcount = $settings['limitcount'];
        }else{
            $limitcount = -1;
        }

        $size = $settings['thumbnailsize_size'];
        $image_size = Null;
        if( $size === 'custom' ){
            $image_size = [
                $settings['thumbnailsize_custom_dimension']['width'],
                $settings['thumbnailsize_custom_dimension']['height']
            ];
        }else{
            $image_size = $size;
        }

        $counter = 0;
        $thumbnails = '';

        $icon_opt = $settings['category_icon_options'];

        echo '<div class="wl-row '.( $settings['no_gutters'] === 'yes' ? 'wlno-gutters' : '' ).' wl-layout-'.$settings['layout'].'">';
        foreach ( $prod_categories as $key => $prod_cat ):
            $counter++;

            $cat_thumb_id = get_term_meta( $prod_cat->term_id, 'thumbnail_id', true );

            $cat_thumb = wp_get_attachment_image( $cat_thumb_id, $image_size );

            // Multiple Custom Icon
            if( is_array( $icon_opt ) && $settings['show_custom_icon'] == 'yes' ){
                foreach ( $icon_opt as $icon ) {
                    if( $prod_cat->slug === $icon['select_category'] ){
                        $cat_thumb = woolentor_render_icon( $icon, 'icon' );
                        break;
                    }
                }
            }

            $term_link = get_term_link( $prod_cat, 'product_cat' );

            if( ( $custom_title == 'yes' ) && ( !empty($catcustitle) ) ){
                $prod_cat->name = $catcustitle;
            }

            if( ( $custom_image == 'yes' ) && (!empty($settings['catcusimage']['url'])) ){
                $thumbnails = '<img src="'.esc_url( $settings['catcusimage']['url'] ).'" alt="'.esc_attr__( $prod_cat->name, 'woolentor-pro' ).'">';
            }else{
                $thumbnails = $cat_thumb;
            }

        ?>
        <div class="<?php echo esc_attr( esc_attr( $collumval ) ); ?>">
            <div class="wlsingle-categorie">
                <?php if( !empty($thumbnails) && $settings['show_image'] === 'yes' ):?>
                    <div class="wlsingle-categorie-img">
                        <a href="<?php echo esc_url( $term_link ); ?>">
                            <?php echo $thumbnails; ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="wlcategorie-content">
                    <h4><a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html__( $prod_cat->name, 'woolentor-pro' ); ?></a><sup>(<?php echo esc_html__( $prod_cat->count, 'woolentor-pro' ); ?>)</sup></h4>
                    <p><?php echo wp_trim_words( $prod_cat->description, $settings['length'] ); ?></p>
                </div>
            </div>
        </div>
        <?php
        if( $counter == $limitcount ) { break; }
        endforeach;
        echo '</div>';
    }

}