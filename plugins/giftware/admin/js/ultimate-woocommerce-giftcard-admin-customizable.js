/**
 * All of the code for customizable giftcard JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function(){
		jQuery( document ).on(
			"click",
			"#wps_wgm_custom_giftcard",
			function() {
				var data = { action:'wps_uwgc_show_customizable_dialog' };
				jQuery( "#wps_uwgc_loader" ).show();
				jQuery.ajax(
					{
						url:ajax_object.ajaxurl,
						data: data,
						type: "POST",
						dataType :'json',
						success: function(response) {
							jQuery( "#wps_uwgc_loader" ).hide();
							if (response.result == true) {
								window.location = response.redirect_url;
							}
						}
					}
				);
			}
		);

		/*default giftcard image*/
		var imageurl = jQuery( "#wps_wgm_customize_default_giftcard" ).val();
		if (imageurl != null && imageurl != "") {
			jQuery( "#wps_wgm_custamize_upload_giftcard_image" ).attr( "src",imageurl );
			jQuery( "#wps_wgm_customize_remove_giftcard_para" ).show();

		}

		/*Upload Default Gift Card image*/
		jQuery( '.wps_wgm_customize_default_giftcard' ).click(
			function(){
				var imageurl = jQuery( "#wps_wgm_customize_default_giftcard" ).val();
				tb_show( '', 'media-upload.php?TB_iframe=true' );
				window.send_to_editor = function(html)
			{
							var imageurl = jQuery( html ).attr( 'href' );
					if (typeof imageurl == 'undefined') {
						imageurl = jQuery( html ).attr( 'src' );
					}
						var last_index = imageurl.lastIndexOf( '/' );
						var url_last_part = imageurl.substr( last_index + 1 );
					if ( url_last_part == '' ) {

						imageurl = jQuery( html ).children( "img" ).attr( "src" );
					}
						jQuery( "#wps_wgm_customize_default_giftcard" ).val( imageurl );
						jQuery( "#wps_wgm_custamize_upload_giftcard_image" ).attr( "src",imageurl );
						jQuery( "#wps_wgm_customize_remove_giftcard_para" ).show();
						tb_remove();
				};
				return false;
			}
		);

		/*Hide the image on X button*/
		jQuery( ".wps_wgm_customize_remove_giftcard_span" ).click(
			function(){
				jQuery( "#wps_wgm_customize_remove_giftcard_para" ).hide();
				jQuery( "#wps_wgm_customize_default_giftcard" ).val( "" );
			}
		);

		/*Customized Email template image for upload multiple images*/
		jQuery( document ).on(
			"click",
			".wps_wgm_customize_email_template_image",
			function(){
				var text_url_id = jQuery( this ).prev( 'input' ).attr( "id" );
				tb_show( '', 'media-upload.php?TB_iframe=true' );
				window.send_to_editor = function(html)
			{
						var imageurl = jQuery( html ).attr( 'href' );

					if (typeof imageurl == 'undefined') {
						imageurl = jQuery( html ).attr( 'src' );
					}
						var last_index = imageurl.lastIndexOf( '/' );
						var url_last_part = imageurl.substr( last_index + 1 );
					if ( url_last_part == '' ) {

						imageurl = jQuery( html ).children( "img" ).attr( "src" );
					}
						jQuery( "#" + text_url_id ).val( imageurl );
						jQuery( "#" + text_url_id ).next().next().find( 'img' ).attr( "src",imageurl );
						jQuery( ".wps_uwgc_customize_remove_email_template_image_para" ).show();
						tb_remove();
				};
			}
		);

		/*Hide the image on X button for upload multiple images*/
		jQuery( document ).on(
			"click",
			".wps_uwgc_customize_remove_email_template_image_span",
			function(){
				var image_id = jQuery( this ).closest( 'div' ).find( "input" ).attr( "id" );
				jQuery( "#" + image_id ).val( "" );
				jQuery( this ).closest( "div" ).remove();
			}
		);

		/*Add button functionality to append the upload button with text*/
		jQuery( document ).on(
			"click",
			".wps_uwgc_add_more_image",
			function(){
				var count = jQuery( this ).data( 'count' );
				var prev_count = count;
				count++;
				jQuery( "#wps_uwgc_add_more_image_" + prev_count ).hide();
				var browse_html = '<div class="wps_upload_email_template_div">';
				browse_html += '<input type="text" id="wps_upload_url_' + count + '" readonly class="wps_uwgc_custamize_upload_giftcard_template_image" data-count=' + count + ' name="wps_wgm_customize_email_template_image[]"/>';
				browse_html += '<input type="button" class="wps_wgm_customize_email_template_image button" value="Upload"/>';
				browse_html += '<p class="wps_uwgc_customize_remove_email_template_image_para">';
				browse_html += '<span class="wps_uwgc_customize_remove_email_template_image">';
				browse_html += '<img src="" width="150px" height="150px">';
				browse_html += '<span class="wps_uwgc_customize_remove_email_template_image_span" data-value=' + count + '>X</span>';
				browse_html += '</span></p></div>';
				browse_html += '<input class="button wps_uwgc_add_more_image" id="wps_uwgc_add_more_image_' + count + '" type="button" value="Add more" data-count="' + count + '" <input type="hidden" value=' + count + '>';
				jQuery( "#browse_img_section" ).append( browse_html );
			}
		);
	}
);
