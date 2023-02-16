<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Archive_Title_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-archive-title-addons';
    }
    
    public function get_title() {
        return __( 'WL: Archive Title', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-archive-title';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
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
        return ['archive','title','category title','search title'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'archive-title-conent',
            [
                'label' => __( 'Archive Title', 'woolentor' ),
            ]
        );
        
            $this->add_control(
                'title_html_tag',
                [
                    'label'   => __( 'Title HTML Tag', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => woolentor_html_tag_lists(),
                    'default' => 'h2',
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'show_title',
                [
                    'label' => __( 'Show Title', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __( 'Hide', 'woolentor' ),
                    'label_on' => __( 'Show', 'woolentor' ),
                    'default' => 'yes',
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'show_description',
                [
                    'label' => __( 'Show Description', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __( 'Hide', 'woolentor' ),
                    'label_on' => __( 'Show', 'woolentor' ),
                    'default' => 'yes',
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'show_image',
                [
                    'label' => __( 'Show Image', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __( 'Hide', 'woolentor' ),
                    'label_on' => __( 'Show', 'woolentor' ),
                    'default' => 'no',
                    'return_value' => 'yes',
                ]
            );

        $this->end_controls_section();

        // Slider Button stle
        $this->start_controls_section(
            'archive-title-style-section',
            [
                'label' => esc_html__( 'Style', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'archive_title_align',
                [
                    'label'        => __( 'Alignment', 'woolentor' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'prefix_class' => 'elementor-align-%s',
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-archive-data-area' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'heading_title',
                [
                    'label' => esc_html__( 'Title', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => esc_html__( 'Title Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-archive-data-area .woolentor-archive-title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .woolentor-archive-data-area .woolentor-archive-title',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-archive-data-area .woolentor-archive-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'heading_description',
                [
                    'label' => esc_html__( 'Description', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'description_color',
                [
                    'label' => esc_html__( 'Description Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-archive-data-area .woolentor-archive-desc' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'description_typography',
                    'selector' => '{{WRAPPER}} .woolentor-archive-data-area .woolentor-archive-desc',
                ]
            );

            $this->add_responsive_control(
                'description_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-archive-data-area .woolentor-archive-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Tab option end

    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();

        $data       = woolentor_get_archive_data();
        $title_tag  = woolentor_validate_html_tag( $settings['title_html_tag'] );

        if( woolentor_is_preview_mode() ){
            $data['title'] = esc_html__('Archive Title','woolentor');
            $data['image_url']  = '';
            $data['desc']       = esc_html__('Archive Description','woolentor');
        }

        $title          = ( $settings['show_title'] == 'yes' && !empty( $data['title'] ) ) ? sprintf( "<%s class='woolentor-archive-title'>%s</%s>", $title_tag, esc_html( $data['title'] ), $title_tag  ) : '';
        $description    = ( $settings['show_description'] == 'yes' && !empty( $data['desc'] ) ) ? sprintf( "<div class='woolentor-archive-desc'>%s</div>", esc_html( $data['desc'] )  ) : '';
        $image          = ( $settings['show_image'] == 'yes' && !empty( $data['image_url'] ) ) ? sprintf( "<div class='woolentor-archive-image'><img src='%s' alt='%s'></div>", esc_url( $data['image_url'] ), esc_attr( $data['title'] )  ) : '';
        
        ?>
            <div class="woolentor-archive-data-area">
                <?php
                    echo sprintf( '%s %s %s', $image, $title, $description );
                ?>
            </div>
        <?php
    }

}
