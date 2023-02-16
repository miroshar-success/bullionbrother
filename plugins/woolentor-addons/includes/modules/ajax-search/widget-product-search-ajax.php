<?php
/**
*  Class Ajax Search Widgets
*/
class WooLentor_Product_Search_Ajax_Widget extends WP_Widget{
        
    /**
    * Default Constructor
    */
    public function __construct() {
        $widget_options = array(
            'description' => esc_html__('WooLentor Ajax Product Search Widget', 'woolentor')
        );
        parent::__construct( 'woolentor_widget_psa', __('WooLentor: Product Search Ajax', 'woolentor'), $widget_options );
    }

    /**
    * Output
    */
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', ( !empty( $instance[ 'title' ] ) ? $instance[ 'title' ] : '' ) );
        echo $args['before_widget'];
        if( !empty( $instance['title'] ) ){ echo $args['before_title'] . $title . $args['after_title']; }
        $shortcode_atts = [
            'limit'         => ( !empty( $instance[ 'limit' ] ) ? $instance[ 'limit' ] : '' ),
            'show_category' => ( !empty( $instance[ 'show_category' ] ) ? (bool) $instance[ 'show_category' ] : false ),
        ];
        echo woolentor_do_shortcode( 'woolentorsearch', $shortcode_atts );
        echo $args['after_widget'];
    }

    /**
    * Form
    */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $limit = ! empty( $instance['limit'] ) ? $instance['limit'] : '';
        $show_category = ! empty( $instance['show_category'] ) ? (bool) $instance['show_category'] : false;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'woolentor' ) ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php echo esc_html__( 'Show Number of Product:', 'woolentor' ) ?></label>
            <input type="number" class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo esc_attr( $limit ); ?>" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_category ); ?> id="<?php echo esc_attr($this->get_field_id( 'show_category' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_category' )); ?>" value="1" />
            <label for="<?php echo esc_attr($this->get_field_id( 'show_category' )); ?>"><?php echo esc_html__( 'Show Category Dropdown','woolentor' ); ?></label>
        </p>
        <?php
    }

    /**
    * Update
    */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'limit' ] = strip_tags( $new_instance[ 'limit' ] );
        $instance['show_category'] = ! empty( $new_instance['show_category'] ) ? (bool) $new_instance['show_category'] : false;
        return $instance;
    }

}