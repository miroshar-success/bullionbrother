<?php
	
////////////////////////////
// SETTINGS PAGE 
////////////////////////////

function fca_ga_plugin_menu() {
	add_options_page( 
		__( 'Google Analytics Manager', 'fca-ga' ),
		__( 'Google Analytics Manager', 'fca-ga' ),
		'manage_options',
		'fca_ga_settings_page',
		'fca_ga_settings_page'
	);
}
add_action( 'admin_menu', 'fca_ga_plugin_menu' );

//ENQUEUE ANY SCRIPTS OR CSS FOR OUR ADMIN PAGE EDITOR
function fca_ga_admin_enqueue() {

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'fca_ga_select2', FCA_GA_PLUGINS_URL . '/includes/select2/select2.min.js', array(), FCA_GA_PLUGIN_VER, true );
	wp_enqueue_style( 'fca_ga_select2', FCA_GA_PLUGINS_URL . '/includes/select2/select2.min.css', array(), FCA_GA_PLUGIN_VER );
	
	wp_enqueue_style( 'fca_ga_tooltipster_stylesheet', FCA_GA_PLUGINS_URL . '/includes/tooltipster/tooltipster.bundle.min.css', array(), FCA_GA_PLUGIN_VER );
	wp_enqueue_style( 'fca_ga_tooltipster_borderless_css', FCA_GA_PLUGINS_URL . '/includes/tooltipster/tooltipster-borderless.min.css', array(), FCA_GA_PLUGIN_VER );
	wp_enqueue_script( 'fca_ga_tooltipster_js',FCA_GA_PLUGINS_URL . '/includes/tooltipster/tooltipster.bundle.min.js', array('jquery'), FCA_GA_PLUGIN_VER, true );
				
	wp_enqueue_script('fca_ga_admin_js', FCA_GA_PLUGINS_URL . '/includes/editor/admin.min.js', array( 'jquery', 'fca_ga_select2' ), FCA_GA_PLUGIN_VER, true );		
	wp_enqueue_style( 'fca_ga_admin_stylesheet', FCA_GA_PLUGINS_URL . '/includes/editor/admin.min.css', array(), FCA_GA_PLUGIN_VER );
	
	$admin_data = array (
		'ajaxurl' => admin_url ( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'fca_ga_admin_nonce' ),
	);
	
	wp_localize_script( 'fca_ga_admin_js', 'adminData', $admin_data );
	
}

function fca_ga_settings_page() {
	
	fca_ga_admin_enqueue();
	
	if ( isSet( $_POST['fca_ga_save'] ) ) {

		$nonce = sanitize_text_field( $_POST['fca_ga']['nonce'] );

		if( wp_verify_nonce( $nonce, 'fca_ga_admin_nonce' ) === false ){
			wp_die( 'Unauthorized, please log in and try again.' );
		}

		fca_ga_settings_save();
	}
	
	$options = get_option( 'fca_ga', true );
	$role_options = array();
	
	$id = empty( $options['id'] ) ? '' : $options['id'];
	$exclude = empty( $options['exclude'] ) ? array() : $options['exclude'];
	
	$roles = get_editable_roles();
	forEach ( $roles as $role ) {
		$role_options[] = $role['name'];
	}
	?>
	<form style="display: none" action="" method="post" id="fca_ga_main_form">

		<?php echo wp_nonce_field( 'fca_ga_admin_nonce', 'fca_ga[nonce]' ) ?>
		
		 <h1><?php esc_html_e('Google Analytics by Fatcat Apps', 'fca-ga' ) ?></h1>
		 <p><?php esc_html_e( 'Need help?', 'fca-ga' ) ?> <a href="https://fatcatapps.com/analytics-cat-quick-start/" target="_blank"><?php esc_html_e( 'Read our quick-start guide.', 'fca-ga' ) ?></a></p>
				
		 <table class="fca_ga_setting_table" >
			 <tr>
				<th><?php esc_html_e('Google Analytics Tracking ID', 'fca-ga' ) ?></th>
				<td>
					<?php echo fca_ga_input( 'id', 'e.g. UA-12345678-1', $id, 'text' ) ?>
					<a class="fca_ga_hint" href="https://fatcatapps.com/analytics-cat-quick-start#what-is-my-google-analytics-tracking-id" target="_blank">
					<?php esc_html_e( 'What is my Google Analytics Tracking ID?', 'fca-ga' ) ?></a>
				</td>
			 </tr>
			 <tr>
				<th><?php esc_html_e( 'Exclude Roles', 'fca-ga' )?></th>
				<td>
					<?php echo fca_ga_select_multiple( 'exclude', $exclude, $role_options ) ?>
					<p class="fca_ga_hint"><?php esc_html_e( 'Logged in users selected above will not trigger analytics tracking.', 'fca-ga' ) ?></p>
				</td>
			 </tr>
		 </table>
		
		 <button type="submit" name="fca_ga_save" class="button button-primary"><?php esc_html_e( 'Save', 'fca-ga' ) ?></button>
	
	 </form>	
	<?php
}

function fca_ga_settings_save() {
	
	$result = update_option( 'fca_ga', array(	
		'id' => empty( $_POST['fca_ga']['id'] ) ? '' : sanitize_text_field( $_POST['fca_ga']['id'] ),		
		'exclude' => empty( $_POST['fca_ga']['exclude'] ) ?  array() : fca_ga_sanitize_text_array( $_POST['fca_ga']['exclude'] ),
	));
	
	if( $result ) {
		echo '<div id="fca-ga-notice-save" style="padding-bottom: 10px;" class="notice notice-success is-dismissible">';
			echo '<p><strong>' . __( "Settings updated.", 'fca-ga' ) . '</strong></p>';
		echo '</div>';		
	}
	
}
