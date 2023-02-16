<?php
//ADD NAG IF NO GA TRACKING CODE IS SET
function fca_ga_admin_notice() {
	$options = get_option( 'fca_ga', true );
		
	if ( empty( $options['id'] ) ) {
		$setup_url = admin_url( 'options-general.php?page=fca_ga_settings_page' );
	
		echo '<div id="fca-ga-setup-notice" class="notice notice-success is-dismissible" style="padding-bottom: 8px; padding-top: 8px;">';
			echo '<img style="float:left; margin-right: 16px;" height="120" width="120" src="' . FCA_GA_PLUGINS_URL . '/assets/googlecat_icon128_128_360.png' . '">';
			echo '<p><strong>' . __( "Thank you for installing Analytics Cat.", 'fca-ga' ) . '</strong></p>';
			echo '<p>' . __( "Ready to get started?", 'fca-ga' ) . '</p>';
			echo "<a href='$setup_url' type='button' class='button button-primary' style='margin-top: 25px;'>" . __( 'Set up Google Analytics', 'fca-ga' ) . "</a> ";
			echo '<br style="clear:both">';
		echo '</div>';
	}

}
add_action( 'admin_notices', 'fca_ga_admin_notice' );


function fca_ga_admin_review_notice() {
	
	$action = empty( $_GET['fca_ga_review_notice'] ) ? false : sanitize_text_field( $_GET['fca_ga_review_notice'] );
	
	if( $action ) {
		
		$nonce = empty( $_GET['fca_ga_nonce'] ) ? false : sanitize_text_field( $_GET['fca_ga_nonce'] );
		$nonceVerified = wp_verify_nonce( $nonce, 'fca_ga_leave_review' );
		if( $nonceVerified == false ) {
			wp_die( "Unauthorized. Please try logging in again." );
		}
		
		update_option( 'fca_ga_show_review_notice', false );
		if( $action == 'review' ) {
			echo "<script>document.location='https://wordpress.org/support/plugin/analytics-cat/reviews/?filter=5'</script>";
		}
				
		if( $action == 'later' ) {
			//MAYBE MAKE SURE ITS NOT ALREADY SET
			if( wp_next_scheduled( 'fca_ga_schedule_review_notice' ) == false ) {
				wp_schedule_single_event( time() + 30 * DAY_IN_SECONDS, 'fca_ga_schedule_review_notice' );
			}
		}
		
		if( $action == 'dismiss' ) {
			//DO NOTHING
		}		
	}	
	
	$show_review_option = get_option( 'fca_ga_show_review_notice', null );
	if ( $show_review_option === null  ) {
	
		//MAYBE MAKE SURE ITS NOT ALREADY SET
		if( wp_next_scheduled( 'fca_ga_schedule_review_notice' ) == false ) {
			wp_schedule_single_event( time() + 30 * DAY_IN_SECONDS, 'fca_ga_schedule_review_notice' );
			update_option( 'fca_ga_show_review_notice', false );
		}
	}
	
	if( $show_review_option  ) {

		$nonce = wp_create_nonce( 'fca_ga_leave_review' );
		$review_url = add_query_arg( array( 'fca_ga_review_notice' => 'review', 'fca_ga_nonce' => $nonce ) );
		$postpone_url = add_query_arg( array( 'fca_ga_review_notice' => 'later', 'fca_ga_nonce' => $nonce ) );
		$forever_dismiss_url = add_query_arg( array( 'fca_ga_review_notice' => 'dismiss', 'fca_ga_nonce' => $nonce ) );

		echo '<div id="fca-ga-review-notice" class="notice notice-success is-dismissible" style="padding-bottom: 8px; padding-top: 8px;">';
		
			echo '<img style="float:left; margin-right: 16px;" height="120" width="120" src="' . FCA_GA_PLUGINS_URL . '/assets/googlecat_icon128_128_360.png' . '">';
			echo '<p><strong>' . __( "Thank you for using Analytics Cat.", 'fca-ga' ) . '</strong></p>';
			echo '<p>' . __( "You've been using Analytics Cat for a while now, so who better to ask for a review than you?", 'fca-ga' ) . '<br>';
			echo __( "Would you please mind leaving us one? It really helps us a lot!", 'fca-ga' ) . '</p>';
			echo "<a href='$review_url' class='button button-primary' style='margin-top: 2px;'>" . __( 'Leave review', 'fca-ga' ) . "</a> ";
			echo "<a style='position: relative; top: 10px; left: 7px;' href='$postpone_url' >" . __( 'Maybe later', 'fca-ga' ) . "</a> ";
			echo "<a style='position: relative; top: 10px; left: 16px;' href='$forever_dismiss_url' >" . __( 'No thank you', 'fca-ga' ) . "</a> ";
			echo '<br style="clear:both">';
			
		echo '</div>';
	}

}
add_action( 'admin_notices', 'fca_ga_admin_review_notice' );

function fca_ga_enable_review_notice(){
	update_option( 'fca_ga_show_review_notice', true );
	wp_clear_scheduled_hook( 'fca_ga_schedule_review_notice' );
}
add_action ( 'fca_ga_schedule_review_notice', 'fca_ga_enable_review_notice' );

//DEACTIVATION SURVEY
function fca_ga_admin_deactivation_survey( $hook ) {
	if ( $hook === 'plugins.php' ) {
		
		ob_start(); ?>
		
		<div id="fca-deactivate" style="position: fixed; left: 232px; top: 191px; border: 1px solid #979797; background-color: white; z-index: 9999; padding: 12px; max-width: 669px;">
			<h3 style="font-size: 14px; border-bottom: 1px solid #979797; padding-bottom: 8px; margin-top: 0;"><?php _e( 'Sorry to see you go', 'fca-ga' ) ?></h3>
			<p><?php _e( 'Hi, this is David, the creator of Google Analytics by Fatcat Apps. Thanks so much for giving my plugin a try. I’m sorry that you didn’t love it.', 'fca-ga' ) ?>
			</p>
			<p><?php _e( 'I have a quick question that I hope you’ll answer to help us make Google Analytics by Fatcat Apps better: what made you deactivate?', 'fca-ga' ) ?>
			</p>
			<p><?php _e( 'You can leave me a message below. I’d really appreciate it.', 'fca-ga' ) ?>
			</p>
			<p><b><?php _e( 'If you\'re upgrading to Analytics Cat Premium and have questions or need help, click <a href=' . 'https://fatcatapps.com/article-categories/gen-getting-started/' . ' target="_blank">here</a></b>', 'fca-ga' ) ?>
			</p>

			<p><textarea style='width: 100%;' id='fca-deactivate-textarea' placeholder='<?php esc_attr_e( 'What made you deactivate?', 'fca-ga' ) ?>'></textarea></p>
			
			<div style='float: right;' id='fca-deactivate-nav'>
				<button style='margin-right: 5px;' type='button' class='button button-secondary' id='fca-deactivate-skip'><?php _e( 'Skip', 'fca-ga' ) ?></button>
				<button type='button' class='button button-primary' id='fca-deactivate-send'><?php _e( 'Send Feedback', 'fca-ga' ) ?></button>
			</div>
		
		</div>
		
		<?php
			
		$html = ob_get_clean();
		
		$data = array(
			'html' => $html,
			'nonce' => wp_create_nonce( 'fca_ga_uninstall_nonce' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);
		
		wp_enqueue_script( 'fca_ga_deactivation_js', FCA_GA_PLUGINS_URL . '/includes/notices/deactivation.min.js', false, FCA_GA_PLUGIN_VER, true );
		wp_localize_script( 'fca_ga_deactivation_js', "fca_ga", $data );
	}
	
	
}	
add_action( 'admin_enqueue_scripts', 'fca_ga_admin_deactivation_survey' );
	