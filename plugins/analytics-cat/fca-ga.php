<?php
/*
	Plugin Name: Analytics Cat Free
	Plugin URI: https://fatcatapps.com/analytics-cat
	Description: Add Your Google Analytics / Universal Analytics Tracking Code To Your Site With Ease.
	Text Domain: fca-ga
	Domain Path: /languages
	Author: Fatcat Apps
	Author URI: https://fatcatapps.com/
	License: GPLv2
	Version: 1.1.1
*/


// BASIC SECURITY
defined( 'ABSPATH' ) or die( 'Unauthorized Access!' );



if ( !defined('FCA_GA_PLUGIN_DIR') ) {
	
	//DEFINE SOME USEFUL CONSTANTS
	define( 'FCA_GA_PLUGIN_VER', '1.1.1' );
	define( 'FCA_GA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'FCA_GA_PLUGINS_URL', plugins_url( '', __FILE__ ) );
	define( 'FCA_GA_PLUGINS_BASENAME', plugin_basename(__FILE__) );
	define( 'FCA_GA_PLUGIN_FILE', __FILE__ );
	define( 'FCA_GA_PLUGIN_PACKAGE', 'Free' ); //DONT CHANGE THIS - BREAKS AUTO UPDATER
	
	//LOAD CORE
	include_once( FCA_GA_PLUGIN_DIR . '/includes/functions.php' );
	include_once( FCA_GA_PLUGIN_DIR . '/includes/api.php' );
	
	//LOAD MODULES
	include_once( FCA_GA_PLUGIN_DIR . '/includes/notices/notices.php' );
	include_once( FCA_GA_PLUGIN_DIR . '/includes/editor/editor.php' );

	//INSERT SCRIPT
	function fca_ga_maybe_add_script() {

		$roles = wp_get_current_user()->roles;
		
		$options = get_option( 'fca_ga', true );
		$id = empty ( $options['id'] ) ? '' : esc_attr( $options['id'] );
		$atts = apply_filters( 'fca_ga_attributes', '' );
		$exclude = empty ( $options['exclude'] ) ? array() : $options['exclude'];
		$do_script = count( array_intersect( array_map( 'strtolower', $roles), array_map( 'strtolower', $exclude ) ) ) == 0;
				
		if ( $id && $do_script ) {
			
			ob_start(); ?>

			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $id . $atts ?>"></script>
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
				gtag( 'config', '<?php echo $id ?>' );
			</script>

			<?php
			echo ob_get_clean();
		}
	}
	add_action('wp_head', 'fca_ga_maybe_add_script');
		
	function fca_ga_add_plugin_action_links( $links ) {
		
		$url = admin_url( 'options-general.php?page=fca_ga_settings_page' );
		
		$new_links = array(
			'configure' => "<a href='$url' >" . __( 'Configure Google Analytics', 'fca-ga' ) . '</a>'
		);
		
		$links = array_merge( $new_links, $links );
	
		return $links;
		
	}
	add_filter( 'plugin_action_links_' . FCA_GA_PLUGINS_BASENAME, 'fca_ga_add_plugin_action_links' );
	
	function fca_ga_load_localization() {
		
		load_plugin_textdomain( 'fca-ga', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	add_action( 'init', 'fca_ga_load_localization' );
}