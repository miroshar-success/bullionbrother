<?php
/**
*  Manage WC Template
*/
class Woolentor_Manage_WC_Template{

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){
        add_action( 'init', [ $this, 'init' ] );
    }

    public function init(){

        // Body classes
        add_filter( 'body_class', [ $this, 'body_classes' ] );

        // Add Admin bar Menu
        add_action( 'admin_bar_menu', [ $this, 'add_menu_in_admin_bar' ], 300 );
        
        // Product details page
        add_filter( 'wc_get_template_part', [ $this, 'set_product_content_template' ], 99, 3 );
        add_filter( 'template_include', [ $this, 'set_product_template' ], 100 );
        add_action( 'woolentor_woocommerce_product_content', [ $this, 'set_product_builder_content' ], 5 );
        add_action( 'woolentor_woocommerce_product_content', [ $this, 'get_default_product_data' ], 10 );

        // Product Archive Page
        add_filter('template_include', [ $this, 'set_product_archive_template' ], 999 );
        add_action( 'woolentor_woocommerce_archive_product_content', [ $this, 'set_archive_product_builder_content' ] );

    }

    /**
     * [body_classes]
     * @param  [array] $classes
     * @return [array] 
     */
    public function body_classes( $classes ){

        $class_prefix = 'elementor-page-';

        if ( is_product() && false !== self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {

            $classes[] = $class_prefix.self::has_template( 'singleproductpage', '_selectproduct_layout' );

        }elseif( is_checkout() && false !== self::has_template( 'productcheckoutpage' ) ){

            $classes[] = $class_prefix.self::has_template( 'productcheckoutpage' );

        }elseif( is_shop() && false !== self::has_template( 'productarchivepage' ) ){

            $classes[] = $class_prefix.self::has_template( 'productarchivepage' );

        }elseif ( is_account_page() ) {
            if ( is_user_logged_in() && false !== self::has_template( 'productmyaccountpage' ) ) {
                $classes[] = $class_prefix.self::has_template( 'productmyaccountpage' );
            }else{
                if( false !== self::has_template( 'productmyaccountloginpage' ) ){
                    $classes[] = $class_prefix.self::has_template( 'productmyaccountloginpage' );
                }
            }
        }else{
            if ( is_cart() && ! WC()->cart->is_empty() && false !== self::has_template( 'productcartpage' ) ) {
                $classes[] = $class_prefix.self::has_template( 'productcartpage' );
            }else{
                if( false !== self::has_template( 'productemptycartpage' ) ){
                    $classes[] = $class_prefix.self::has_template( 'productemptycartpage' );
                }
                if( WC()->cart && WC()->cart->is_empty() ){
                    $classes[] = 'woolentor-empty-cart';
                }
            }
        }

        return $classes;

    }

    /**
     * [add_menu_in_admin_bar] Add Admin Bar Menu For Navigate Quick Edit builder template
     *
     * @param \WP_Admin_Bar $wp_admin_bar
     * @return void
     */
    public function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {

        if( function_exists('woolentorBlocks_get_ID') ){
            if( ! Woolentor_Template_Manager::instance()->edit_with_gutenberg( woolentorBlocks_get_ID() ) || is_admin()){
                return;
            }

            $icon = WOOLENTOR_ADDONS_PL_URL.'includes/admin/assets/images/icons/menu-bar_32x32.png';

            $wp_admin_bar->add_menu( [
                'id'     => 'woolentor_template_builder',
                'parent' => '',
                'title'  => sprintf( '<img src="%s" alt="%s">%s', $icon, __('WooLentor Admin Menu','woolentor'), esc_html__('WooLentor','woolentor') ),
            ] );

            $wp_admin_bar->add_menu( [
                'id'     => 'woolentor_template_' . woolentorBlocks_get_ID() . get_the_ID(),
                'parent' => 'woolentor_template_builder',
                'href'   => get_edit_post_link( woolentorBlocks_get_ID() ),
                'title'  => sprintf( '%s', get_the_title( woolentorBlocks_get_ID() ) ),
                'meta' => [],
            ] );
        }

    }

    /**
     * [has_template]
     * @param  [string]  $field_key
     * @return boolean | int
     */
    public static function has_template( $field_key = '', $meta_key = '' ){
        $template_id    = Woolentor_Template_Manager::instance()->get_template_id( $field_key );
        $wlindividualid = !empty( $meta_key ) && get_post_meta( get_the_ID(), $meta_key, true ) ? get_post_meta( get_the_ID(), $meta_key, true ) : '0';

        if( '0' !== $wlindividualid ){
            return $wlindividualid;
        }elseif( '0' !== $template_id ){
            return $template_id;
        }else{
            return false;
        }

    }

    /**
     * [get_template_id]
     * @param  [string]  $field_key
     * @param  [string]  $meta_key
     * @return boolean | int
     */
    public static function get_template_id( $field_key = '', $meta_key = '' ){
        $wltemplateid = Woolentor_Template_Manager::instance()->get_template_id( $field_key );
        $wlindividualid = !empty( $meta_key ) && get_post_meta( get_the_ID(), $meta_key, true ) ? get_post_meta( get_the_ID(), $meta_key, true ) : '0';

        if( $wlindividualid != '0' ){ 
            $wltemplateid = $wlindividualid; 
        }
        return $wltemplateid;
    }

    /**
     * [render_build_content]
     * @param  [int]  $id
     * @return string
     */
    public static function render_build_content( $id ){

        $output = '';
        $document = woolentor_is_elementor_editor() ? Elementor\Plugin::instance()->documents->get( $id ) : false;

        if( $document && $document->is_built_with_elementor() ){
            $output = Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
        }else{
            $content = get_the_content( null, false, $id );

            if ( has_blocks( $content ) ) {
                $blocks = parse_blocks( $content );
                foreach ( $blocks as $block ) {
                    $output .= do_shortcode( render_block( $block ) );
                }
            }else{
                $content = apply_filters( 'the_content', $content );
                $content = str_replace(']]>', ']]&gt;', $content );
                return $content;
            }

        }

        return $output;

    }

    /*
    * Manage Product Page
    */
    // Change Product page content template
    public function set_product_content_template( $template, $slug, $name ) {
        if ( 'content' === $slug && 'single-product' === $name ) {
            if ( self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {
                $template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/single-product.php';
            }
        }
        return $template;
    }

    // Set Product page template
    public function set_product_template( $template ) {
        if ( is_embed() ) {
            return $template;
        }
        if ( is_singular( 'product' ) ) {
            if ( self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {
                $templateid = get_page_template_slug( self::get_template_id( 'singleproductpage', '_selectproduct_layout' ) );
                if ( ( 'elementor_header_footer' === $templateid ) || ( 'woolentor_fullwidth' === $templateid ) ) {
                    $template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/single-product-fullwidth.php';
                } elseif ( ( 'elementor_canvas' === $templateid ) || ( 'woolentor_canvas' === $templateid ) ) {
                    $template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/single-product-canvas.php';
                }
            }
        }
        return $template;
    }

    // Single product default content
    public function get_default_product_data() {
        WC()->structured_data->generate_product_data();
    }

    // Set Builder content
    public static function set_product_builder_content() {
        if ( self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {
            $wltemplateid = self::get_template_id( 'singleproductpage', '_selectproduct_layout' );
            echo self::render_build_content( $wltemplateid );
        }
    }

    /*
    * Manage product archive page
    */
    public function archive_template_id(){
        $template_id = 0;

        if ( defined('WOOCOMMERCE_VERSION') ) {

            $termobj            = get_queried_object();
            $get_all_taxonomies = woolentor_get_taxonomies();

            if ( is_shop() || ( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() ) || ( isset( $termobj->taxonomy ) && is_tax( $termobj->taxonomy ) && array_key_exists( $termobj->taxonomy, $get_all_taxonomies ) ) ) {
                
                $product_shop_custom_page_id = self::get_template_id( 'productarchivepage' );

                // Archive Layout Control
                $wltermlayoutid = 0;
                if(( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() )){

                    $product_archive_custom_page_id = self::get_template_id( 'productallarchivepage' );

                    // Get Meta Value
                    $wltermlayoutid = get_term_meta( $termobj->term_id, 'wooletor_selectcategory_layout', true ) ? get_term_meta( $termobj->term_id, 'wooletor_selectcategory_layout', true ) : '0';

                    if( !empty( $product_archive_custom_page_id ) && $wltermlayoutid == '0' ){
                        $wltermlayoutid = $product_archive_custom_page_id;
                    }

                }
                if( $wltermlayoutid != '0' ){ 
                    $template_id = $wltermlayoutid;
                }else{
                    if ( !empty( $product_shop_custom_page_id ) ) {
                        $template_id = $product_shop_custom_page_id;
                    }
                }
                // return $template_id;
            }

            return $template_id;
        }

    }

    public function set_product_archive_template( $template ){
        $archive_template_id = $this->archive_template_id();
        $templatefile   = array();
        $templatefile[] = 'wl-woo-templates/archive-product.php';
        if( $archive_template_id != '0' ){
            $template = locate_template( $templatefile );
            if ( ! $template || ( ! empty( $status_options['template_debug_mode'] ) && current_user_can( 'manage_options' ) ) ){
                $template = WOOLENTOR_ADDONS_PL_PATH . '/wl-woo-templates/archive-product.php';
            }
            $page_template_slug = get_page_template_slug( $archive_template_id );
            if ( ( 'elementor_header_footer' === $page_template_slug ) || ( 'woolentor_fullwidth' === $page_template_slug ) ) {
                $template = WOOLENTOR_ADDONS_PL_PATH . '/wl-woo-templates/archive-product-fullwidth.php';
            } elseif ( ( 'elementor_canvas' === $page_template_slug ) || ( 'woolentor_canvas' === $page_template_slug ) ) {
                $template = WOOLENTOR_ADDONS_PL_PATH . '/wl-woo-templates/archive-product-canvas.php';
            }
        }
        return $template;
    }

    // Set Builder Content
    public function set_archive_product_builder_content(){
        $archive_template_id = $this->archive_template_id();
        if( $archive_template_id != '0' ){
            echo self::render_build_content( $archive_template_id );
        }
    }

    // Get Template width
    public static function get_template_width( $template_id ){
        $get_width = get_post_meta( $template_id, '_woolentor_container_width', true );
		return $get_width ? $get_width : '';
    }

    // Get Builder Template ID
    public function get_builder_template_id(){

        if ( is_singular( 'product' ) ) {
            if ( self::has_template( 'singleproductpage', '_selectproduct_layout' ) ) {
                $template_id = self::get_template_id( 'singleproductpage', '_selectproduct_layout' );
            }else{
                $template_id = '';
            }
        }else{
            $archive_template_id = $this->archive_template_id();
            $template_id         = $archive_template_id != '0' ? $archive_template_id : '';
        }

        return apply_filters( 'woolentor_builder_template_id', $template_id );

    }

}

Woolentor_Manage_WC_Template::instance();