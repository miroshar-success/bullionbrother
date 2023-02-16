/**
 * All of the code for notices on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function($){
		$( document ).on(
			'change',
			'#wps_wgm_email_template',
			function(){
				var template_ids = $( this ).val();

				jQuery( '#wps_wgm_loader' ).show();
				var data = {
					action:'wps_wgm_append_default_template',
					template_ids:template_ids,
					wps_nonce:wps_wgm.wps_wgm_nonce
				};
				$.ajax(
					{
						url: wps_wgm.ajaxurl,
						type: "POST",
						data: data,
						dataType :'json',
						success: function(response)
					{
							if (response.result == 'success') {
								var templateid = response.templateid;
								var option = '';
								for (key in templateid) {
									option += '<option value="' + key + '">' + templateid[key] + '</option>';
								}
								jQuery( "#wps_wgm_email_defualt_template" ).html( option );
								jQuery( "#wps_wgm_loader" ).hide();
							} else if (response.result == 'no_ids') {
								 var option = '';
								 option = '<option value="">' + wps_wgm.append_option_val + '</option>';
								 jQuery( "#wps_wgm_email_defualt_template" ).html( option );
								jQuery( "#wps_wgm_loader" ).hide();
							}
						}
					}
				);
			}
		);

	}
);
