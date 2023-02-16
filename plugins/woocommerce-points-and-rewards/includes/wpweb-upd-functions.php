<?php

/**
 * Load installer for the WPWeb Updater.
 * @return $api Object
 */
if ( ! class_exists( 'Wpweb_Upd_Admin' ) && ! function_exists( 'wpweb_updater_install' ) ) {
	
	function wpweb_updater_install( $api, $action, $args ) {
		
		$download_url = 'https://s3.amazonaws.com/wpweb-plugins/Plugins/WPWUPD/wpweb-updater.zip';
		
		if ( 'plugin_information' != $action ||
			false !== $api ||
			! isset( $args->slug ) ||
			'wpweb-updater' != $args->slug
		) return $api;
		
		$api				= new stdClass();
		$api->name			= 'WPWeb Updater';
		$api->version		= '1.0.0';
		$api->download_link	= esc_url( $download_url );
		
		return $api;
	}
	
	add_filter( 'plugins_api', 'wpweb_updater_install', 10, 3 );
}

/**
 * WPWeb Updater Installation Prompts
 */
if ( ! class_exists( 'Wpweb_Upd_Admin' ) && ! function_exists( 'wpweb_updater_notice' ) ) {

	/**
	 * Display a notice if the "WPWeb Updater" plugin hasn't been installed.
	 * @return void
	 */
	function wpweb_updater_notice() {
		
		$active_plugins	= apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		
		if( in_array( 'wpweb-updater/wpweb-updater.php', $active_plugins ) ) return;
		
		$slug			= 'wpweb-updater';
		$install_url	= wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug );
		$activate_url	= 'plugins.php?action=activate&plugin=' . urlencode( 'wpweb-updater/wpweb-updater.php' ) . '&plugin_status=all&paged=1&s&_wpnonce=' . urlencode( wp_create_nonce( 'activate-plugin_wpweb-updater/wpweb-updater.php' ) );
		
		//initilize variables
		$message = $dismiss_url = $dismiss_link = '';
		
		$dismiss_notice	= get_site_option( 'dismiss_install_wpweb_notice' );
		
		if( !$dismiss_notice ) { //if notice dismissed
			
			$message		= '<a href="' . esc_url( $install_url ) . '">Install the WPWeb Updater plugin</a> to get updates for your WPWeb plugins.';
			$dismiss_url	= add_query_arg( 'action', 'install-wpweb-dismiss', add_query_arg( 'nonce', wp_create_nonce( 'install-wpweb-dismiss' ) ) );
			$dismiss_link	= '<p class="alignright"><a href="' . esc_url( $dismiss_url ) . '">' . 'Dismiss' . '</a></p>';
		}
		
		$is_downloaded	= false;
		$plugins		= array_keys( get_plugins() );
		
		foreach ( $plugins as $plugin ) {
			if ( strpos( $plugin, 'wpweb-updater.php' ) !== false ) {
				$is_downloaded	= true;
				$message		= '<a href="' . esc_url( network_admin_url( $activate_url ) ) . '">Activate the WPWeb Updater plugin</a> to get updates for your WPWeb plugins.';
				$dismiss_link	= '';
			}
		}
		
		if( !empty( $message ) ) {//If message is not empty
			echo '<div class="updated fade"><p  class="alignleft">' . $message . '</p>'.$dismiss_link.'<div class="clear"></div></div>' . "\n";
		}
	}
	
	add_action( 'admin_notices', 'wpweb_updater_notice' );
	
	/**
	 * Dismiss Install Wpweb plugin notification
	 * 
	 */
	function wpweb_updater_dismiss_install_wpweb_notification() {
		
		if ( isset( $_GET['action'] ) && ( 'install-wpweb-dismiss' == $_GET['action'] ) && isset( $_GET['nonce'] ) && check_admin_referer( 'install-wpweb-dismiss', 'nonce' ) ) {
			
			update_site_option( 'dismiss_install_wpweb_notice', true );
			$redirect_url = remove_query_arg( 'action', remove_query_arg( 'nonce', $_SERVER['REQUEST_URI'] ) );
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}
	add_action( 'admin_init', 'wpweb_updater_dismiss_install_wpweb_notification' );
}