<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Qr_Code_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-qrcode-addons';
    }
    
    public function get_title() {
        return __( 'WL: QR Code', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-barcode';
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
        return ['qrcode','qrcode generate','product qr code','qr code for product'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'qrcode-conent',
            [
                'label' => __( 'QR Code', 'woolentor' ),
            ]
        );
            
            $this->add_control(
                'size',
                [
                    'label' => __( 'Size', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                    'default' => 150,
                ]
            );

            $this->add_control(
                'add_cart_url',
                [
                    'label' => __( 'Enable Add to Cart URL', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'quantity',
                [
                    'label' => __( 'Quantity', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 1000,
                    'step' => 1,
                    'default' => 1,
                    'condition'=>[
                        'add_cart_url'=>'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'code_align',
                [
                    'label' => esc_html__( 'Alignment', 'woolentor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'woolentor' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'woolentor' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'woolentor' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-qrcode' => 'text-align: {{VALUE}};',
                    ],
                    'separator'=>'before',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute( 'area_attr', 'class', 'woolentor-qrcode' );

        if( woolentor_is_preview_mode() ){
            $product_id = woolentor_get_last_product_id();
        } else{
            $product_id = get_the_ID();
        }

        $quantity = ( !empty( $settings['quantity'] ) ? $settings['quantity'] : 1 );
        if( $settings['add_cart_url'] == 'yes' ){
            $url = get_the_permalink( $product_id ).sprintf('?add-to-cart=%s&quantity=%s',$product_id, $quantity );
        }else{
            $url = get_the_permalink( $product_id );
        }

        $title = get_the_title( $product_id );
        $product_url   = urlencode( $url );

        $size    = ( !empty( $settings['size'] ) ? $settings['size'] : 120 );
        $dimension = $size.'x'.$size;

        $image_src = sprintf( 'https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $dimension, $product_url );
       
        ?>
        <div <?php echo $this->get_render_attribute_string( 'area_attr' ); ?> >
            <?php
                echo sprintf('<img src="%1$s" alt="%2$s">', $image_src, $title );
            ?>
        </div>
        <?php
    }

}

