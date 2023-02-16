<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Product_Video_Gallery_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-video-gallery';
    }

    public function get_title() {
        return __( 'WL: Product Video Gallery', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-video-camera';
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

    public function get_script_depends() {
        return [
            'woolentor-widgets-scripts',
        ];
    }

    public function get_keywords(){
        return ['video','gallery','product video gallery'];
    }

    protected function register_controls() {

         $this->start_controls_section(
            'product_thumbnails_content',
            array(
                'label' => __( 'Video Thumbnails', 'woolentor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );
            
            $this->add_control(
                'tab_thumbnails_position',
                [
                    'label'   => __( 'Thumbnails Position', 'woolentor' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                        'top' => [
                            'title' => __( 'Top', 'woolentor' ),
                            'icon'  => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'woolentor' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default'     => 'bottom',
                    'toggle'      => false,
                    'label_block' => true,
                ]
            );

        $this->end_controls_section();
        
        // Product Main Image Style
        $this->start_controls_section(
            'product_image_style_section',
            [
                'label' => __( 'Main Video Area', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'main_video_height',
                [
                    'label' => __( 'Height', 'woolentor' ),
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
                        'size' => 550,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .embed-responsive' => 'height: {{SIZE}}{{UNIT}};overflow:hidden;',
                        '{{WRAPPER}} .embed-responsive iframe' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_image_border',
                    'label' => __( 'Product image border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-product-gallery-video',
                ]
            );

            $this->add_responsive_control(
                'product_image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-gallery-video img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '{{WRAPPER}} .woolentor-product-gallery-video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '{{WRAPPER}} .embed-responsive' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-gallery-video' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Thumbnails Image Style
        $this->start_controls_section(
            'product_image_thumbnails_style_section',
            [
                'label' => __( 'Thumbnails', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_thumbnais_image_border',
                    'label' => __( 'Product image border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-product-video-tabs li a',
                ]
            );

            $this->add_responsive_control(
                'product_thumbnais_image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-video-tabs li a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '{{WRAPPER}} .woolentor-product-video-tabs li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_product_thumbnais_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-video-tabs li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render() {
        $settings  = $this->get_settings_for_display();

        $this->add_render_attribute( 'wl_product_thumbnails_attr', 'class', 'wlpro-product-videothumbnails thumbnails-tab-position-'.$settings['tab_thumbnails_position'] );

        global $post;
        if( woolentor_is_preview_mode() ){
            $product = wc_get_product( woolentor_get_last_product_id() );
        } else{
            global $product;
        }

        if ( empty( $product ) ) { return; }
        $gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
        if ( $product->get_image_id() ){
            array_unshift( $gallery_images_ids, $product->get_image_id() );
        }

        ?>

        <div <?php echo $this->get_render_attribute_string( 'wl_product_thumbnails_attr' ); ?>>
            <div class="wl-thumbnails-image-area">

                    <?php if( $settings['tab_thumbnails_position'] == 'left' || $settings['tab_thumbnails_position'] == 'top' ): ?>
                        <ul class="woolentor-product-video-tabs">
                            <?php
                                $j=0;
                                foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
                                    $j++;
                                    if( $j == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
                                    $video_url = get_post_meta( $gallery_attachment_id, 'woolentor_video_url', true );
                                    ?>
                                    <li class="<?php if( !empty( $video_url ) ){ echo 'wlvideothumb'; }?>">
                                        <a class="<?php echo $tabactive; ?>" href="#wlvideo-<?php echo $j; ?>">
                                            <?php
                                                if( !empty( $video_url ) ){
                                                    echo '<span class="wlvideo-button"><i class="sli sli-control-play"></i></span>';
                                                    echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' );
                                                }else{
                                                    echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' );
                                                }
                                            ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            ?>
                        </ul>
                    <?php endif; ?>

                    <div class="woolentor-product-gallery-video">
                        <?php
                            if( woolentor_is_preview_mode() ){
                                if ( $product->is_on_sale() ) { 
                                    echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woolentor-pro' ) . '</span>', $post, $product ); 
                                }
                            }else{
                                woolentor_show_product_sale_flash();
                            }

                            if(function_exists('woolentor_custom_product_badge')){
                                woolentor_custom_product_badge();
                            }

                            $i = 0;
                            foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
                                $i++;
                                if( $i == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
                                $video_url = get_post_meta( $gallery_attachment_id, 'woolentor_video_url', true );
                                ?>
                                <div class="video-cus-tab-pane <?php echo $tabactive; ?>" id="wlvideo-<?php echo $i; ?>">
                                    <?php
                                        if( !empty( $video_url ) ){
                                            ?>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <?php echo wp_oembed_get( $video_url ); ?>
                                                </div>
                                            <?php
                                        }else{
                                            echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single' );
                                        }
                                    ?>
                                </div>
                                <?php
                            }
                        ?>
                    </div>

                    <?php if( $settings['tab_thumbnails_position'] == 'right' || $settings['tab_thumbnails_position'] == 'bottom' ): ?>

                        <ul class="woolentor-product-video-tabs">
                            <?php
                                $j=0;
                                foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
                                    $j++;
                                    if( $j == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
                                    $video_url = get_post_meta( $gallery_attachment_id, 'woolentor_video_url', true );
                                    ?>
                                    <li class="<?php if( !empty( $video_url ) ){ echo 'wlvideothumb'; }?>">
                                        <a class="<?php echo $tabactive; ?>" href="#wlvideo-<?php echo $j; ?>">
                                            <?php
                                                if( !empty( $video_url ) ){
                                                    echo '<span class="wlvideo-button"><i class="sli sli-control-play"></i></span>';
                                                    echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' );
                                                }else{
                                                    echo wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' );
                                                }
                                            ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            ?>
                        </ul>

                    <?php endif; ?>
                    
            </div>
        </div>

         <script>
            ;jQuery(document).ready(function($) {
                'use strict';

                var $default_data = {
                    src:'',
                    srcfull:'',
                    srcset:'',
                    sizes:'',
                    width:'',
                    height:'',
                };
                $( '.single_variation_wrap' ).on( 'show_variation', function ( event, variation ) {

                    // Get First image data
                    if( $default_data.src.length === 0 ){
                        $default_data.srcfull = $('.woolentor-product-gallery-video').find('.video-cus-tab-pane.htactive img').attr('src');
                        $default_data.src = $('.woolentor-product-gallery-video').find('.video-cus-tab-pane.htactive img').attr('src');
                        $default_data.srcset = $('.woolentor-product-gallery-video').find('.video-cus-tab-pane.htactive img').attr('srcset');
                    }

                    $('.woolentor-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('src',variation.image.full_src);
                    $('.woolentor-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('srcset',variation.image.srcset);
                    $('.woolentor-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('src',variation.image.src);

                    $('.variations').find('.reset_variations').on('click', function(e){
                        $('.woolentor-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('src', $default_data.srcfull );
                        $('.woolentor-product-gallery-video').find('.video-cus-tab-pane.htactive img').wc_set_variation_attr('srcset', $default_data.srcset );
                    });

                });
            });
        </script>

        <?php
    }

}