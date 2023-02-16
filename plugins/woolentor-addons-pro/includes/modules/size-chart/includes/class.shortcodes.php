<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// return;
class Woolentor_Size_Chart_Shortcodes{

    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function get_instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){
        add_shortcode('woolentor_size_chart', [ $this, 'size_chart_sc' ] );
    }

    /**
     * Render shortcode
     */
    public function size_chart_sc( $atts, $content = '' ){
        $popup_button_text = woolentor_get_option( 'popup_button_text', 'woolentor_size_chart_settings' );
        $popup_button_text = $popup_button_text ? $popup_button_text : __('Size Chart', 'woolentor-pro');

        $button_icon_class = woolentor_get_option( 'button_icon', 'woolentor_size_chart_settings', 'sli sli-chart' );
        $button_icon = $button_icon_class ? sprintf('<i class="%s" aria-hidden="true"></i>', $button_icon_class) : '';

        // Auto detect the chart id to display the chart for product details page
        $chart_props = Woolentor_Size_Chart::get_assigned_chart( get_the_id() );
        $chart_id    = '';

        if($chart_props){
            $chart_id = $chart_props['chart_id'];
        }

        $default_atts = array(
            'id'    => $chart_id,
            'type'  => ''
        );
        $atts = shortcode_atts( $default_atts, $atts, $content );
         
        // If there is no chart id do not render the shortcode
        if( !$atts['id'] ){
            return;
        }

        ob_start();

        if( $atts['type'] == 'popup'){
            $popup_title      = apply_filters('woolentor_size_chart_popup_title', get_the_title($atts['id']), $chart_id );
            $hide_popup_title = woolentor_get_option( 'hide_popup_title', 'woolentor_size_chart_settings' );
            $hide_popup_title_class = $hide_popup_title ? '' :  'wl-has-popup-title';

            ?>
                <a href="#wl-size-chart-<?php echo esc_attr($atts['id']) ?>" class="wl-size-chart-button"><?php echo wp_kses_post($button_icon) .  esc_html($popup_button_text) ?></a>
                <div id="wl-size-chart-<?php echo esc_attr($atts['id']) ?>" class="wl-size-chart-popup wl-size-chart-popup-wrapper <?php echo esc_attr($hide_popup_title_class) ?>">
                    <div class="wl-size-chart-popup-inner">
                        <div class="wl-popup-main-content">
                            <span class="wl-size-chart-popup-close">
                                <svg width="15" height="15" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <div class="wl-size-chart-popup-content">
                                <?php 
                                    if( $popup_title && $hide_popup_title != 'on' ){
                                        echo '<h2 class="wl-title">'. wp_kses_post($popup_title) .'</h2>';
                                    }
                                ?>
                                <?php $this->render_chart($atts['id']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        } else {
            echo '<div id="wl-size-chart-'. esc_attr($atts['id']) . '">';
                $this->render_chart($atts['id']);
            echo '</div>';
        }

        // Enqueue the handler to generate the inline CSS
        wp_enqueue_style('woolentor-size-chart');
        wp_enqueue_style('woolentor-size-chart-style');

        // Prepare the inline CSS
        $chart_id   = $atts['id'];
        $custom_css = array();
        
        $button_margin = woolentor_dimensions_pro( 'button_margin', 'woolentor_size_chart_settings', 'margin' );
        if($button_margin){
            $custom_css[] = '.wl-size-chart-button{'. $button_margin .'}';
        }

        $custom_css[] = $this->add_inline_css(
            'background-color',
            'table_head_bg_color',
            array( 
                '#wl-size-chart-'. $chart_id .' thead tr'
            )
        );

        $custom_css[] = $this->add_inline_css(
            'color',
            'table_head_text_color',
            array( 
                '#wl-size-chart-'. $chart_id .' thead th'
            )
        );

        $custom_css[] = $this->add_inline_css(
            'background-color',
            'table_even_row_bg_color', 
            array(
                '#wl-size-chart-'. $chart_id .' tbody tr:nth-child(even)'
            )
        );

        $custom_css[] = $this->add_inline_css(
            'color', 
            'table_even_row_text_color',
            array(
                '#wl-size-chart-'. $chart_id .' tbody tr:nth-child(even) td'
            )
        );

        $custom_css[] = $this->add_inline_css(
            'background-color', 
            'table_odd_row_bg_color',
            array(
                '#wl-size-chart-'. $chart_id .' tbody tr:nth-child(odd)'
            )
        );

        $custom_css[] = $this->add_inline_css(
            'color', 
            'table_odd_row_text_color',
            array(
                '#wl-size-chart-'. $chart_id .' tbody tr:nth-child(odd) td'
            )
        );

        $custom_css[] = $this->add_inline_css(
            'border', 
            'table_border',
            array(
                '#wl-size-chart-'. $chart_id .' table',
                '#wl-size-chart-'. $chart_id .' th',
                '#wl-size-chart-'. $chart_id .' td',
            )
        );

        // Render the inline CSS into the footer
        wp_add_inline_style( 'woolentor-size-chart-style', implode('', $custom_css) );

        return ob_get_clean();
    }

    /**
     * Generate inline CSS
     */
    public function add_inline_css($property ='', $opt_name = '',  $selectors = array() ){
        $value = woolentor_get_option( $opt_name, 'woolentor_size_chart_settings' );

        if( $value ){
            $selectors = implode(',', $selectors);
            return "$selectors{
              $property: {$value};
            }";
        }

        return null;
    }

    /**
     * Render the size chart description and table of the given id
     */
    public function render_chart( $chart_id ){
        $contents_part_order = apply_filters('woolentor_size_chart_contents_order', array('thumbnail', 'desc', 'chart_table') );
        $chart_props = Woolentor_Size_Chart::get_chart_props($chart_id);

        $has_any_content = false;

        foreach( $contents_part_order as $content_part_name ){

            if( $content_part_name == 'thumbnail' ){

                if( has_post_thumbnail($chart_id) && $chart_props['hide_thumbnail'] != 'yes' ){
                    $this->render_thumbnail( $chart_id );
                    $has_any_content = true;
                }
                
            } elseif( $content_part_name == 'desc' ){
                if( get_the_content(null, false, $chart_id) && $chart_props['hide_desc'] != 'yes' ){
                    $this->render_desc( $chart_id );
                    $has_any_content = true;
                }
                
            } elseif( $content_part_name == 'chart_table'  ){

                if( $chart_props['chart_table'] && $chart_props['hide_chart_table'] != 'yes' ){
                    $this->render_chart_table( $chart_id );
                    $has_any_content = true;
                }

            }
        }

        if( !$has_any_content && current_user_can( 'manage_options' ) ){
            echo '<p>';
            printf( __('There are nothing to display. Please check the %s to see if it contains any content or if there are any elements that are hidden.', 'woolentor-pro'), '<a href="'. admin_url('post.php?post='. $chart_id .'&action=edit') .'" target="_blank"><b>'.esc_html__('Size Chart','woolentor-pro').'</b></a>' );
            echo '</p>';
            return;
        }
    }

    /**
     * Render thumbnail part
     */
    public function render_thumbnail( $chart_id ){
        echo '<div class="wl-size-chart-elem wl-size-chart-image">';
            $image_size = apply_filters('woolentor_size_chart_image_size', 'large');
            echo get_the_post_thumbnail( $chart_id, $image_size );
        echo '</div>';
    }

    /**
     * Render desc part
     */
    public function render_desc( $chart_id ){
        echo '<div class="wl-size-chart-elem wl-size-chart-desc">';
            echo get_the_content( null, false, $chart_id );
        echo '</div>';
    }

    /**
     * Render chart table
     */
    public function render_chart_table( $chart_id ){
        $chart_table     = get_post_meta($chart_id, '_chart_table', true);
        $chart_table_arr = (array) json_decode(get_post_meta($chart_id, '_chart_table', true));


        if( empty($chart_table_arr) || $chart_table == '[[""]]' ){
            return;
        }

        $th_arr = $chart_table_arr[0];
        ?>
        <table class="wl-size-chart-elem wl-size-chart-table">
            <thead>
                <tr>
                    <?php foreach($th_arr as $th){
                        echo '<th>'. $th .'</th>';
                    } ?>
                    
                </tr>
            </thead>
                <?php foreach( $chart_table_arr as $index => $table_row ){
                    if( $index != 0 && $index < count($chart_table_arr)){
                        echo '<tr>';
                            foreach($table_row as $td){
                                echo '<td>'. $td .'</td>';
                            }
                        echo '</tr>';
                    }
                } ?>
            </tbody>
        </table>
        <?php
    }

}