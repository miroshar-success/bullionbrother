<?php

////////////////////////////
// API ENDPOINTS
////////////////////////////

//UNINSTALL ENDPOINT
function fca_ga_uninstall_ajax() {
	
	$msg = esc_textarea( $_REQUEST['msg'] );
	$nonce = $_REQUEST['nonce'];
	$nonceVerified = wp_verify_nonce( $nonce, 'fca_ga_uninstall_nonce') == 1;

	if ( $nonceVerified && !empty( $msg ) ) {
		
		$url =  "https://api.fatcatapps.com/api/feedback.php";
				
		$body = array(
			'product' => 'analyticscat',
			'msg' => $msg,		
		);
		$args = array(
			'timeout'     => 15,
			'redirection' => 15,
			'body' => json_encode( $body ),	
			'blocking'    => true,
			'sslverify'   => false
		);	
		
		$return = wp_remote_post( $url, $args );
		
		wp_send_json_success( $msg );

	}
	wp_send_json_error( $msg );

}
add_action( 'wp_ajax_fca_ga_uninstall', 'fca_ga_uninstall_ajax' );
