/**
 * All of the code for admin giftcard JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function(){
		jQuery( '#wps_uwgc_pdf_deprecated' ).on(
			'click',
			function(){
				var html = '';
				jQuery( "#wps_wgm_loader" ).show();
				jQuery.ajax(
					{
						url:wps_wgm.ajaxurl,
						type:"POST",
						dataType :'json',
						data:{
							action:'wps_uwgc_new_way_for_generating_pdfs',
							'wps_uwgc_new_way_for_pdf':'yes',
						},success : function(response){
							jQuery( "#wps_wgm_loader" ).hide();
							if (response.result == true) {
								html = '<div><input type="button" name="wps_uwgc_pdf_deprecated_next_step" class="wps_uwgc_pdf_deprecated_next_step" id="wps_uwgc_pdf_deprecated_next_step" value="Next Step"></div>';
								jQuery( ".wps_uwgc_pdf_deprecated_row" ).html( html );
							} else if (response.result == false) {
								var message = response.message;
								message = + '<b style="color:red;">' + message + '</b>';
								jQuery( ".wps_uwgc_pdf_deprecated_row" ).html( message );
							}
						}
					}
				);
			}
		);

		jQuery( document ).on(
			'click',
			'#wps_uwgc_pdf_deprecated_next_step',
			function(){
				jQuery( "#wps_wgm_loader" ).show();
				jQuery.ajax(
					{
						url:wps_wgm.ajaxurl,
						type:"POST",
						dataType :'json',
						data:{
							action:'wps_uwgc_next_step_for_generating_pdfs',
							'wps_uwgc_next_step_for_pdf':'yes',
						},success : function(response){
							jQuery( "#wps_wgm_loader" ).hide();
							if (response.result == true) {
								  var message = response.message;
								  var append_message = '<b style="color:green;">' + message + '</b>';
								  jQuery( ".wps_uwgc_pdf_deprecated_row" ).html( append_message );
							} else if (response.result == false) {
								var message = response.message;
								var append_message = '<b style="color:red;">' + message + '</b>';
								jQuery( ".wps_uwgc_pdf_deprecated_row" ).html( append_message );
							}
						}
					}
				);
			}
		);
	}
);
