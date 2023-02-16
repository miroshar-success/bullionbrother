<?php
namespace WooLentorBlocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage Blocks
 */
class Blocks_init {
    
	/**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;
    public static $blocksList = [];

    /**
     * [instance] Initializes a singleton instance
     * @return [Blocks_init]
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
		$this->register_blocks();
	}

    /**
     * Prepare Block Data
     *
     * @param [array] $block
     * @return void
     */
    public function prepare_block_data( $block ){

        $block = apply_filters( 'woolentor/register_block_args', $block );

        if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

        if( !isset( $block['server_side_render'] ) ){
            return;
        }

        $block_dir = ( isset($block['is_pro']) && $block['is_pro'] == true && defined( "WOOLENTOR_ADDONS_PL_PATH_PRO" ) ) ? WOOLENTOR_ADDONS_PL_PATH_PRO . 'blocks/' : WOOLENTOR_BLOCK_PATH . '/src/blocks/';
        $block_name  = str_replace('woolentor/', '', trim(preg_replace('/\(.+\)/', '', $block['name'])));
        $block_file  = $block_dir . $block_name . '/index.php';

        // Get Block JSON
        ob_start();
        if( file_exists( $block_dir . $block_name .'/block.json' ) ){
		    include $block_dir . $block_name .'/block.json';
        }
		$metadata = json_decode( ob_get_clean(), true );
		if( ! is_array( $metadata ) ){
			return $block;
		}

        // Add default attribute.
        $block = wp_parse_args( $block, [
            'block_name'	    => $block_name,
            'name'				=> $metadata['name'],
            'title'				=> $metadata['title'],
            'attributes'	    => $metadata['attributes'],
            'render_callback'	=> false,
            'enqueue_style'		=> false,
            'enqueue_script'	=> false,
            'enqueue_assets'	=> false,
            'is_editor'         => ( isset( $_GET['is_editor_mode'] ) && $_GET['is_editor_mode'] == 'yes' )
        ] );

        $block['render_callback'] = function ( $settings, $content ) use ( $block, $block_file ) {
            $this->enqueue_block_assets( $block );
            ob_start();
            if( file_exists( $block_file ) ){
                include ( $block_file );
            }
            return ob_get_clean();
        };

        // Register block.
        register_block_type( $block['name'], $block );

	    return $block;

    }

    /**
     * Manage Block Assets
     *
     * @param [array] $block
     * @return void
     */
	public function enqueue_block_assets( $block ){

        // Assest handle name.
        $handle = 'woolentor-' . $block['block_name'];
        
        // Enqueue style.
        if( $block['enqueue_style'] ) {
            wp_enqueue_style( $handle, $block['enqueue_style'], [], false, 'all' );
        }
        
        // Enqueue script.
        if( $block['enqueue_script'] ) {
            wp_enqueue_script( $handle, $block['enqueue_script'], [], false, true );
        }
        
        // Enqueue assets callback.
        if( $block['enqueue_assets'] && is_callable( $block['enqueue_assets'] ) ) {
            call_user_func( $block['enqueue_assets'], $block );
        }
    }

    /**
     * Prepare Block for register
     *
     * @param [array] $block
     * @return array
     */
    public function register_block( $block ) {
        return $this->prepare_block_data( $block );
    }

    /**
     * Register Block
     *
     * @return void
     */
    public function register_blocks(){

        parse_str( $_SERVER['QUERY_STRING'], $query_arr );
        $post_add_new_screen = basename( $_SERVER['PHP_SELF'] ) === 'post-new.php' ? true : false;
        if( $post_add_new_screen === false ){
            if( is_admin() && empty( $query_arr['action'] ) ){
                return;
            }
        }

        $block_list = Blocks_List::get_block_list();

        foreach ( $block_list as $block_key => $block ) {
            if( is_array( $block ) ){
                $block_name = str_replace('woolentor/', '', trim(preg_replace('/\(.+\)/', '', $block['name'])));
                if( $block['active'] === true && woolentorBlocks_get_option( $block_key, 'woolentor_gutenberg_tabs', 'on' ) === 'on' ){
                    $this->register_block( $block );
                    self::$blocksList[$block['type']][] = $block_name;
                }
            }
        }

    }

    /**
     * Manage Template type
     *
     * @param [string] $type
     * @return String
     */
    public function get_template_type( $type ){

        switch ( $type ) {

            case 'single':
            case 'quickview':
                $template_type = 'single';
                break;

            case 'shop':
            case 'archive':
                $template_type = 'shop';
                break;

            case 'cart':
            case 'emptycart':
                $template_type = 'cart';
                break;

            case 'minicart':
                $template_type = 'minicart';
                break;

            case 'checkout':
            case 'checkouttop':
                $template_type = 'checkout';
                break;

            case 'myaccount':
            case 'myaccountlogin':
            case 'dashboard':
            case 'orders':
            case 'downloads':
            case 'edit-address':
            case 'edit-account':
                $template_type = 'myaccount';
                break;

            case 'lost-password':
            case 'reset-password':
                    $template_type = 'lostpassword';
                    break;

            case 'thankyou':
                $template_type = 'thankyou';
                break;

            default:
                $template_type = '';

        }

        return $template_type;

    }


}
