<?php
namespace WooLentorBlocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blocks Assets Manage
 */
class Scripts {

	/**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Scripts]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	/**
	 * The Constructor.
	 */
	public function __construct() {
		add_action( 'enqueue_block_assets', [ $this, 'block_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );
	}

	/**
	 * Block assets.
	 */
	public function block_assets() {

		wp_enqueue_script(
		    'woolentor-block-main',
		    WOOLENTOR_BLOCK_URL . '/src/assets/js/script.js',
		    array(),
		    WOOLENTOR_VERSION,
		    true
		);

		wp_enqueue_style(
		    'woolentor-block-common',
		    WOOLENTOR_BLOCK_URL . '/src/assets/css/common-style.css',
		    array(),
		    WOOLENTOR_VERSION
		);

		wp_enqueue_style(
		    'woolentor-block-default',
		    WOOLENTOR_BLOCK_URL . '/src/assets/css/style-index.css',
		    array(),
		    WOOLENTOR_VERSION
		);

		if ( woolentorBlocks_Has_Blocks( woolentorBlocks_get_ID() ) || woolentorBlocks_is_gutenberg_page() ){
			$this->load_css();
		}

	}

	/**
	 * Load CSS File
	 */
	public function load_css(){
		wp_enqueue_style( 'woolentor-block-style', WOOLENTOR_BLOCK_URL . '/src/assets/css/blocks.style.build.css', array(), WOOLENTOR_VERSION );
	}

	/**
	 * Block editor assets.
	 */
	public function block_editor_assets() {

        wp_enqueue_style( 'font-awesome-four' );
		wp_enqueue_style( 'htflexboxgrid' );
		wp_enqueue_style( 'simple-line-icons-wl' );
		wp_enqueue_style( 'slick' );
		wp_enqueue_style( 'woolentor-widgets' );

		// Third-Party Scripts
		$this->load_extra_scripts();

		wp_enqueue_style( 'woolentor-block-editor-style', WOOLENTOR_BLOCK_URL . '/src/assets/css/editor-style.css', false, WOOLENTOR_VERSION, 'all' );

		$dependencies = require_once( WOOLENTOR_BLOCK_PATH . '/build/blocks-woolentor.asset.php' );
		wp_enqueue_script(
		    'woolentor-blocks',
		    WOOLENTOR_BLOCK_URL . '/build/blocks-woolentor.js',
		    $dependencies['dependencies'],
		    WOOLENTOR_VERSION,
		    true
		);

		/**
		 * Localize data
		 */
		$editor_localize_data = array(
			'url' 		=> WOOLENTOR_BLOCK_URL,
			'ajax' 		=> admin_url('admin-ajax.php'),
			'security' 	=> wp_create_nonce('woolentorblock-nonce'),
			'locale' 	=> get_locale(),
			'options'	=> $this->get_block_list()['block_list'],
			'templateType'=> $this->get_block_list()['template_type'],
			'sampledata'=> is_admin() ? Sample_Data::instance()->get_sample_data( false, 'sampledata/product' ) : array(),
		);

		// My Account MenuList
		if( get_post_type() === 'woolentor-template' ){
			$editor_localize_data['myaccountmenu'] = function_exists('wc_get_account_menu_items') ? ( wc_get_account_menu_items() + ['customadd' => esc_html__( 'Custom', 'woolentor' )] ) : [];
		}

		wp_localize_script( 'woolentor-blocks', 'woolentorData', $editor_localize_data );

	}

	/**
	 * Load Third Party Scripts
	 *
	 * @return void
	 */
	public function load_extra_scripts(){
		if( function_exists('WC') ){
			wp_enqueue_style('woocommerce-layout', \WC()->plugin_url() . '/assets/css/woocommerce-layout.css', false, \Automattic\Jetpack\Constants::get_constant('WC_VERSION'), 'all' );
			if ( ! wp_script_is( 'wc-add-to-cart-variation', 'enqueued' ) ) {
				wp_enqueue_script('wc-add-to-cart-variation', \WC()->plugin_url() . '/assets/js/frontend/add-to-cart-variation.js', array( 'jquery', 'wp-util', 'jquery-blockui' ), \Automattic\Jetpack\Constants::get_constant('WC_VERSION'), 'all' );
			}
		}
		wp_enqueue_style('wishsuite-frontend');
		wp_enqueue_style('evercompare-frontend');

	}

	/**
	 * Manage block based on template type
	 */
	public function get_block_list(){

		$blocks_list 	= Blocks_init::$blocksList;
		$generate_list 	= [];
		
		if( get_post_type() === 'woolentor-template' ){
            $tmpType = Blocks_init::instance()->get_template_type( get_post_meta( get_the_ID(), 'woolentor_template_meta_type', true ) );
        }else{
            $tmpType = '';
        }

		$is_builder = true;

		$common_block  	= array_key_exists( 'common', $blocks_list ) ? $blocks_list['common'] : [];
        $builder_common = ( $is_builder == true && array_key_exists( 'builder_common', $blocks_list ) ) ? $blocks_list['builder_common'] : [];
        $template_wise  = ( $is_builder == true && $tmpType !== '' && array_key_exists( $tmpType, $blocks_list ) ) ? $blocks_list[$tmpType] : [];

		if( $tmpType === '' ){
			$generate_list = $common_block;  
        }else{
            $generate_list = array_merge( $template_wise, $common_block, $builder_common );
        }

		return array(
			'block_list' 	=> $generate_list,
			'template_type' => $tmpType
		);

	}
	
	
}