<?php
    /**
    *  Product Video Gallery
    */
    class Wl_Product_Video_Gallery{
        
        private static $_instance = null;
        public static function instance(){
            if( is_null( self::$_instance ) ){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        function __construct(){

            // Meta data
            add_filter( 'attachment_fields_to_edit', array( $this, 'woolentor_attachment_field_video' ), 10, 2 );
            add_filter( 'attachment_fields_to_save', array( $this, 'woolentor_attachment_field_video_save'), 10, 2 );

        }

        // Add Custom Meta Field
        function woolentor_attachment_field_video( $form_fields, $post ) {

            $form_fields['woolentor-product-video-url'] = array(
                'label' => esc_html__( 'Video', 'woolentor' ),
                'input' => 'text',
                'value' => get_post_meta( $post->ID, 'woolentor_video_url', true ),
                'helps' => esc_html__( 'Add Youtube / Vimeo URL', 'woolentor' )
            );
            return $form_fields;

        }

        // Save Custom Meta Field data
        function woolentor_attachment_field_video_save( $post, $attachment ) {
            if ( isset( $attachment['woolentor-product-video-url'] ) ) {
                update_post_meta( $post['ID'], 'woolentor_video_url', esc_url( $attachment['woolentor-product-video-url'] ) );
            }else{
                delete_post_meta( $post['ID'], 'woolentor_video_url' );
            }
            return $post;
        }

    }

    Wl_Product_Video_Gallery::instance();