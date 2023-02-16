/**
 * All of the code for notices on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package           woo-gift-cards-lite
 */

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			'#dismiss_notice',
			function(e){
				e.preventDefault();
				var data = {
					action:'wps_wgm_dismiss_notice',
					wps_nonce:wps_wgm_notice.wps_wgm_nonce
				};
				$.ajax(
					{
						url: wps_wgm_notice.ajaxurl,
						type: "POST",
						data: data,
						success: function(response)
						{
							window.location.reload();
						}
					}
				);
			}
		);
	}
);
