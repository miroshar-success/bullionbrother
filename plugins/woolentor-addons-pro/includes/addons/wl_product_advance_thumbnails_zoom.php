<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Product_Advance_Thumbnails_Zoom_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-thumbnails-zoom-image';
    }

    public function get_title() {
        return __( 'WL: Product Image With Zoom', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-product-images';
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
    
    public function get_script_depends() {
        return [
            'slick',
             'woolentor-easyzoom',
            'woolentor-widgets-scripts-pro',
        ];
    }

    public function get_keywords(){
        return ['image','product image','thumbnail','custom thumbnail layout','zoom'];
    }

    protected function register_controls() {

         $this->start_controls_section(
            'product_thumbnails_content',
            [
                'label' => __( 'Product Image', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
            $this->add_control(
                'tabslitems',
                [
                    'label' => __( 'Thumbnails Slider Items', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 20,
                    'step' => 1,
                    'default' => 4
                ]
            );

        $this->end_controls_section();

        // Tab With Slider Arrow style
        $this->start_controls_section(
            'tabwithslider_arrow_style',
            [
                'label' => __( 'Thumbnails', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                
            ]
        );
            
            $this->add_control(
                'tabslider_arrow_color',
                [
                    'label' => __( 'Arrow Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl-product-details-thumbs .slick-arrow, .wl-product-details-images .slick-arrow' => 'color: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_control(
                'tabslider_active_color',
                [
                    'label' => __( 'Active Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl-product-details-thumbs .slick-current img' => 'border-color: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'tabslider_arrow_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wl-product-details-thumbs img',
                ]
            );

            $this->add_control(
                'tabslider_arrow_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-product-details-thumbs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        

    }

    protected function render() {
        $settings  = $this->get_settings_for_display();
     
        if( woolentor_is_preview_mode() ){
            $product = wc_get_product( woolentor_get_last_product_id() );
        } else{
            global $product, $post;
        }
        if ( empty( $product ) ) { return; }

        $gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
        if ( $product->get_image_id() ){
            $gallery_images_ids = array( 'wlthumbnails_id' => $product->get_image_id() ) + $gallery_images_ids;
        }

        // Placeholder image set
        if( empty( $gallery_images_ids ) ){
            $gallery_images_ids = array( 'wlthumbnails_id' => get_option( 'woocommerce_placeholder_image', 0 ) );
        }

        ?>

        <?php if( woolentor_is_preview_mode() ){ echo '<div class="product">'; } ?>
            <div class="wl-thumbnails-image-area">
            
                <?php 
                    echo '<div class="wl-product-details-images">';
                        $i = 0;
                        foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                            $i++;
                            if( $i == 1 ){
                                echo '<div class="slider-for__item wl_zoom woolentor_image_change" data-src="'.wp_get_attachment_image_url( $gallery_attachment_id, 'full' ).'"><img src="'.wp_get_attachment_image_url( $gallery_attachment_id, 'woocommerce_single' ).'"></div>';
                            }else{
                                echo '<div class="slider-for__item wl_zoom" data-src="'.wp_get_attachment_image_url( $gallery_attachment_id, 'full' ).'"><img src="'.wp_get_attachment_image_url( $gallery_attachment_id, 'woocommerce_single' ).'"></div>';
                            }
                        }
                    echo '</div><div class="wl-product-details-thumbs">';
                        foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                            echo '<div class="sm-image">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</div>';
                        }
                    echo '</div>';
                ?>

            </div>
        <?php if( woolentor_is_preview_mode() ){ echo '</div>'; } ?>

            <script>
                ;jQuery(document).ready(function($) {
                    'use strict';
                    $('.wl-product-details-images').each(function(){
                        var $this = $(this);
                        var $thumb = $this.siblings('.wl-product-details-thumbs');
                        $this.slick({
                            arrows: true,
                            slidesToShow: 1,
                            autoplay: false,
                            autoplaySpeed: 5000,
                            dots: false,
                            infinite: true,
                            centerMode: false,
                            prevArrow:'<span class="arrow-prv"><i class="fa fa-angle-left"></i></span>',
                            nextArrow:'<span class="arrow-next"><i class="fa fa-angle-right"></i></span>',
                            centerPadding: 0,
                            asNavFor: $thumb,
                        });
                    });
                    $('.wl-product-details-thumbs').each(function(){
                        var $this = $(this);
                        var $details = $this.siblings('.wl-product-details-images');
                        $this.slick({
                            arrows: true,
                            slidesToShow: <?php echo $settings['tabslitems']; ?>,
                            slidesToScroll: 1,
                            autoplay: false,
                            autoplaySpeed: 5000,
                            vertical:false,
                            verticalSwiping:true,
                            dots: false,
                            infinite: true,
                            focusOnSelect: true,
                            centerMode: false,
                            centerPadding: 0,
                            prevArrow:'<span class="arrow-prv"><i class="fa fa-angle-left"></i></span>',
                            nextArrow:'<span class="arrow-next"><i class="fa fa-angle-right"></i></span>',
                            asNavFor: $details,
                        });
                    }); 
                    $('.wl_zoom').zoom();

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
                            $default_data.srcfull = $('.wl-thumbnails-image-area').find('.woolentor_image_change').attr('data-src');
                            $default_data.src = $('.wl-thumbnails-image-area').find('.woolentor_image_change img').attr('src');
                        }

                        $('.wl-thumbnails-image-area').find('.woolentor_image_change').wc_set_variation_attr('data-src',variation.image.full_src);
                        $('.wl-thumbnails-image-area').find('.woolentor_image_change .zoomImg').wc_set_variation_attr('src',variation.image.src);

                        $('.wl-thumbnails-image-area').find('.woolentor_image_change img').wc_set_variation_attr('src',variation.image.src);

                        $('.wl-thumbnails-image-area').find('.wl-product-details-images').slick('slickGoTo', 0);

                        // Reset data
                        $('.variations').find('.reset_variations').on('click', function(e){
                            $('.wl-thumbnails-image-area').find('.woolentor_image_change').wc_set_variation_attr('data-src', $default_data.srcfull );
                            $('.wl-thumbnails-image-area').find('.woolentor_image_change .zoomImg').wc_set_variation_attr('src',$default_data.src);
                            $('.wl-thumbnails-image-area').find('.woolentor_image_change img').wc_set_variation_attr('src', $default_data.src );

                            $('.wl_zoom').zoom();

                        });

                        $('.wl_zoom').zoom();

                    });

                });
            </script>
        

        <?php
    }

}