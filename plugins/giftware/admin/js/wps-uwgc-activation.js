/**
 * All of the code for activation on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function($){

		jQuery( '#wps-uwgc-install-lite' ).click(
			function(e){
				e.preventDefault();
				jQuery( '#wps_notice_loader' ).css('display','inline-block');
				var data = {
					action:'wps_uwgc_activate_lite_plugin',
				};
				$.ajax(
					{
						url:wps_uwgc_activation.ajax_url,
						type:'POST',
						data:data,
						success:function(response){
							jQuery( '#wps_notice_loader' ).css('display','none');
							if (response == 'success') {
								window.location.reload();
							}
						}
					}
				);
			}
		);

		jQuery( '#wps-pro-notice-dismiss' ).click(
			function(e){
				e.preventDefault();
				var data = {
					action:'wps_uwgc_dismiss_plugin_notice',
					wps_nonce:wps_uwgc_activation.wps_uwgc_nonce
				};
				$.ajax(
					{
						url:wps_uwgc_activation.ajax_url,
						type:'POST',
						data:data,
						success:function(response){
							if (response == 'success') {
								window.location.reload();
							}
						}
					}
				);

			}
		);
	}
);
