<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Quickview_Product_Image_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-quickview-product-thumbnail-image';
    }

    public function get_title() {
        return __( 'WL: Quickview Product Image', 'woolentor-pro' );
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
            'woolentor-widgets-scripts',
        ];
    }

    public function get_keywords(){
        return ['quickview','product quickview','popup'];
    }

    protected function register_controls() {

        // Product Main Image Style
        $this->start_controls_section(
            'product_main_image_style_section',
            [
                'label' => esc_html__( 'Main Image', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'main_margin',
                [
                    'label' => esc_html__( 'Main Image Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-quick-view-single' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'main_image_border',
                    'label' => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector' => '#htwlquick-viewmodal.woocommerce div.product {{WRAPPER}} .woocommerce-product-gallery__image img',
                ]
            );

        $this->end_controls_section();
        
        // Product Thumbnail Image Style
        $this->start_controls_section(
            'product_thumbnail_image_style_section',
            [
                'label' => esc_html__( 'Thumbnail Image', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'thumbnail_margin',
                [
                    'label' => esc_html__( 'Thumbnail Image Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-quick-thumb-single' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'thumbnail_image_border',
                    'label' => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-quick-view-thumbnails .slick-slide img',
                ]
            );

        $this->end_controls_section();


    }

    protected function render() {
        $settings  = $this->get_settings_for_display();

        if( Plugin::instance()->editor->is_edit_mode() ){
            $product = wc_get_product( woolentor_get_last_product_id() );
        } else{
            global $product;
            $product = wc_get_product();
        }

        if ( empty( $product ) ) { return; }

        $post_thumbnail_id = $product->get_image_id();
        if( ! $post_thumbnail_id ){
            $post_thumbnail_id = get_option( 'woocommerce_placeholder_image', 0 );
        }
        $attachment_ids = $product->get_gallery_image_ids();

        ?>
        <div class="ht-quick-view-learg-img">
            <?php if ( $post_thumbnail_id ): ?>
                <div class="ht-quick-view-single images">
                    <?php 
                        $html = wc_get_gallery_image_html( $post_thumbnail_id, true );
                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
                    ?>
                </div>
            <?php endif; 
                if ( $attachment_ids ) {
                    foreach ( $attachment_ids as $attachment_id ) {
                        ?>
                            <div class="ht-quick-view-single">
                                <?php 
                                    $html = wc_get_gallery_image_html( $attachment_id, true );
                                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
                                ?>
                            </div>
                        <?php
                    }
                }
            ?>
            
        </div>

        <div class="ht-quick-view-thumbnails">
            <?php if ( $product->get_image_id() ): ?>
                
                <div class="ht-quick-thumb-single">
                    <?php
                        $thumbnail_src = wp_get_attachment_image_src( $post_thumbnail_id, 'woocommerce_gallery_thumbnail' );
                        echo '<img src=" '.$thumbnail_src[0].' " alt="'.get_the_title().'">';
                    ?>
                </div>
                
            <?php endif; ?>
            <?php
                if ( $attachment_ids && $product->get_image_id() ) {
                    foreach ( $attachment_ids as $attachment_id ) {
                        ?>
                            <div class="ht-quick-thumb-single">
                                <?php
                                  $thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'woocommerce_gallery_thumbnail' );
                                  echo '<img src=" '.$thumbnail_src[0].' " alt="'.get_the_title().'">';
                                ?>
                            </div>
                        <?php
                    }
                }
            ?>
        </div>
        <?php if ( Plugin::instance()->editor->is_edit_mode() ) { ?>
            <script>
                ;jQuery(document).ready(function($) {
                    'use strict';
                    $('.ht-quick-view-learg-img').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        fade: true,
                        asNavFor: '.ht-quick-view-thumbnails'
                    });
                    $('.ht-quick-view-thumbnails').slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        asNavFor: '.ht-quick-view-learg-img',
                        dots: false,
                        arrows: true,
                        focusOnSelect: true,
                        prevArrow: '<button class="woolentor-slick-prev"><i class="sli sli-arrow-left"></i></button>',
                        nextArrow: '<button class="woolentor-slick-next"><i class="sli sli-arrow-right"></i></button>',
                    });
                });
            </script>
        <?php } ?>
        <?php

    }

}