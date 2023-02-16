<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Single_Product_Sale_Schedule_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-sale-schedule';
    }
    
    public function get_title() {
        return __( 'WL: Product Sale Schedule', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-countdown';
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets-pro',
        ];
    }
    
    public function get_script_depends() {
        return [
            'countdown-min',
            'woolentor-widgets-scripts-pro',
        ];
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_keywords(){
        return ['schedule','product schedule','sale schedule','coundown'];
    }

    protected function register_controls() {

         // Sale Schedule
        $this->start_controls_section(
            'wl-products-sale-schedule-setting',
            [
                'label' => esc_html__( 'Sale Schedule', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'customlabel_days',
                [
                    'label'       => __( 'Days', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Days', 'woolentor-pro' ),
                ]
            );

            $this->add_control(
                'customlabel_hours',
                [
                    'label'       => __( 'Hours', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Hours', 'woolentor-pro' ),
                ]
            );

            $this->add_control(
                'customlabel_minutes',
                [
                    'label'       => __( 'Minutes', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Minutes', 'woolentor-pro' ),
                ]
            );

            $this->add_control(
                'customlabel_seconds',
                [
                    'label'       => __( 'Seconds', 'woolentor-pro' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Seconds', 'woolentor-pro' ),
                ]
            );

        $this->end_controls_section();

        // Style Countdown tab section
        $this->start_controls_section(
            'sale_schedule_counter_style_section',
            [
                'label' => __( 'Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'sale_schedule_counter_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-countdown-wrap .ht-product-countdown .cd-single .cd-single-inner h3' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ht-product-countdown-wrap .ht-product-countdown .cd-single .cd-single-inner p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'sale_schedule_counter_background_color',
                    'label' => __( 'Counter Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-product-countdown-wrap .ht-product-countdown .cd-single .cd-single-inner',
                ]
            );

            $this->add_responsive_control(
                'sale_schedule_counter_space_between',
                [
                    'label' => __( 'Space', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-countdown-wrap .ht-product-countdown .cd-single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

    }

    protected function render() {

        $settings    = $this->get_settings_for_display();

        // Countdown Custom Label
        $data_customlavel = [];
        $data_customlavel['daytxt'] = ! empty( $settings['customlabel_days'] ) ? $settings['customlabel_days'] : 'Days';
        $data_customlavel['hourtxt'] = ! empty( $settings['customlabel_hours'] ) ? $settings['customlabel_hours'] : 'Hours';
        $data_customlavel['minutestxt'] = ! empty( $settings['customlabel_minutes'] ) ? $settings['customlabel_minutes'] : 'Min';
        $data_customlavel['secondstxt'] = ! empty( $settings['customlabel_seconds'] ) ? $settings['customlabel_seconds'] : 'Sec';

        // Sale Schedule
        if( woolentor_is_preview_mode() ){
            $product_id = woolentor_get_last_product_id();
        } else{
            $product_id = get_the_ID();
        }

        $offer_start_date_timestamp = get_post_meta( $product_id, '_sale_price_dates_from', true );
        $offer_start_date = $offer_start_date_timestamp ? date_i18n( 'Y/m/d', $offer_start_date_timestamp ) : '';
        $offer_end_date_timestamp = get_post_meta( $product_id, '_sale_price_dates_to', true );
        $offer_end_date = $offer_end_date_timestamp ? date_i18n( 'Y/m/d', $offer_end_date_timestamp ) : '';

        if ( $offer_end_date == '' ) {
            echo '<div class="ht-single-product-countdown">'.__( 'Do not set sale schedule time', 'woolentor-pro' ).'</div>';
        }else{
            if( $offer_end_date != '' ):
                if( $offer_start_date_timestamp && $offer_end_date_timestamp && current_time( 'timestamp' ) > $offer_start_date_timestamp && current_time( 'timestamp' ) < $offer_end_date_timestamp
                ): 
            ?>
                <div class="ht-single-product-countdown ht-product-countdown-wrap">
                    <div class="ht-product-countdown" data-countdown="<?php echo esc_attr( $offer_end_date ); ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                </div>
            <?php endif; endif;
        }

    }

}