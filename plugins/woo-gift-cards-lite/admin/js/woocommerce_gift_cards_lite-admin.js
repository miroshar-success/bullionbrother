/**
 * All of the code for notices on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package          woo-gift-cards-lite
 */

(function( $ ) {
	'use strict';

	jQuery( document ).ready(
		function(){

			jQuery(document).on('keyup','.wps_wgm_variation_price', function() {
				this.value = this.value.replace(/[^0-9,.]/g, '');
			});

			jQuery(document).on('click','.wps_add_more_price',function(e){
				e.preventDefault();
				var empty_warning = false;
				jQuery( '.wps_wgm_variation_text' ).each( function() {
					if(!jQuery(this).val()){				
						jQuery(this).css("border-color", "red");
						empty_warning = true;
					}
					else{				
						jQuery(this).css("border-color", "");
					}
				});
				jQuery('.wps_wgm_variation_price').each(function(){
					console.log(jQuery(this).val());
					if(!jQuery(this).val()){				
						jQuery(this).css("border-color", "red");
						empty_warning = true;
					}
					else{				
						jQuery(this).css("border-color", "");
					}		
				});
				if (empty_warning == false) {
					var shtml = '<div class="wps_wgm_variation_giftcard">\
					<input type="text" class="wps_wgm_variation_text" name="wps_wgm_variation_text[]" value="" placeholder="Enter Description">\
					<input type="text" class="wps_wgm_variation_price wc_input_price" name="wps_wgm_variation_price[]" value="" placeholder="Enter Price">\
					<a class="wps_remove_more_price button" href="javascript:void(0)">Remove</a>\
					</div>\
					<a href="#" class="wps_add_more_price button">Add</a>';
					jQuery('#wps_variable_gift').append(shtml);
					jQuery(this).remove();
				}
			});

			jQuery(document).on('click','.wps_remove_more_price',function(e){
				e.preventDefault();
				jQuery(this).parent().remove();
		
			});

			$('.wps_wgm_gc_price_range').keyup( function() {
				var minspend = parseInt( $('#wps_wgm_general_setting_giftcard_minspend').val() );
				var maxspend = parseInt( $('#wps_wgm_general_setting_giftcard_maxspend').val() );
				if (minspend > maxspend) {
					$('#wps_wgm_save_general').css('display','none');
					$('#wps_wgm_general_setting_giftcard_minspend').css('border-color','red');
					$('#wps_wgm_general_setting_giftcard_maxspend').css('border-color','red');
				} else {
					$('#wps_wgm_general_setting_giftcard_minspend').css('border-color','');
					$('#wps_wgm_general_setting_giftcard_maxspend').css('border-color','');
					$('#wps_wgm_save_general').css('display','block');
				}
			});

			$( document ).find( '.wc-pbc-show-if-not-supported' ).remove();
			$( "#wps_wgm_product_setting_exclude_category" ).select2();
			$( "#wps_wgm_email_template" ).select2();
			wps_wgc_show_and_hide_panels();
			var pricing_option = $( '#wps_wgm_pricing' ).val();
			wps_wgc_show_and_hide_pricing_option( pricing_option );
			$( '#wps_wgm_pricing' ).change(
				function() {
					var pricing_option = $( this ).val();
					wps_wgc_show_and_hide_pricing_option( pricing_option );
				}
			);
			var imageurl = $( "#wps_wgm_mail_setting_upload_logo" ).val();
			if (imageurl != null && imageurl != "") {
				$( "#wps_wgm_mail_setting_upload_image" ).attr( "src",imageurl );
				$( "#wps_wgm_mail_setting_remove_logo" ).show();

			}
			jQuery( ".wps_wgm_mail_setting_remove_logo_span" ).on ( 
				'click',
				function(){
					jQuery( "#wps_wgm_mail_setting_remove_logo" ).hide();
					jQuery( "#wps_wgm_mail_setting_upload_logo" ).val( "" );
				}
			);
			var imageurl = $( "#wps_wgm_mail_setting_upload_logo" ).val();
			if (imageurl != null && imageurl != "") {
				$( "#wps_wgm_mail_setting_upload_image" ).attr( "src",imageurl );
				$( "#wps_wgm_mail_setting_remove_logo" ).show();

			}
			jQuery( "#wps_wgm_mail_setting" ).on( 
				'click',
				function(){
					jQuery( "#wps_wgm_mail_setting_wrapper" ).slideToggle();
				}
			);

			jQuery( '.wps_wgm_mail_setting_upload_logo' ).on( 
				'click',
				function(){
					var imageurl = $( "#wps_wgm_mail_setting_upload_logo" ).val();
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
							$( "#wps_wgm_mail_setting_upload_logo" ).val( imageurl );
							$( "#wps_wgm_mail_setting_upload_image" ).attr( "src",imageurl );
							$( "#wps_wgm_mail_setting_remove_logo" ).show();
							tb_remove();
					};
					return false;
				}
			);

			jQuery( '.wps_wgm_mail_setting_background_logo' ).on( 
				'click',
				function()
				{
					var imageurl = $( "#wps_mail_other_setting_background_logo_value" ).val();
					tb_show( '', 'media-upload.php?TB_iframe=true' );
					 window.send_to_editor = function(html)
					{
						var imageurl = jQuery( html ).attr( 'href' );
						if (typeof imageurl == 'undefined') {
							imageurl = jQuery( html ).attr( 'src' );
						}
						$( "#wps_wgm_mail_setting_background_logo_value" ).val( imageurl );
						$( "#wps_wgm_mail_setting_background_logo_image" ).attr( "src",imageurl );
						$( "#wps_wgm_mail_setting_remove_background" ).show();
						tb_remove();
					 };
					return false;
				}
			);

			jQuery( ".wps_wgm_mail_setting_remove_background_span" ).on( 
				'click',
				function(){
					jQuery( "#wps_wgm_mail_setting_remove_background" ).hide();
					jQuery( "#wps_wgm_mail_setting_background_logo_value" ).val( "" );
				}
			);
			var imageurl = $( "#wps_wgm_mail_setting_background_logo_value" ).val();
			if (imageurl != null && imageurl != "") {
				$( "#wps_wgm_mail_setting_background_logo_image" ).attr( "src",imageurl );
				$( "#wps_wgm_mail_setting_remove_background" ).show();

			}
			function wps_wgc_show_and_hide_panels() {
				var product_type    = $( 'select#product-type' ).val();
				var is_wps_wgm_gift = false;
				var is_tax_enable_for_gift = wps_wgc.is_tax_enable_for_gift;
				if (product_type == "wgm_gift_card") {
					is_wps_wgm_gift = true;
				}
				if (is_wps_wgm_gift) {
					// Hide/Show all with rules.
					var hide_classes = '.hide_if_wps_wgm_gift, .hide_if_wps_wgm_gift';
					var show_classes = '.show_if_wps_wgm_gift, .show_if_wps_wgm_gift';
					$.each(
						woocommerce_admin_meta_boxes.product_types,
						function( index, value ) {
							hide_classes = hide_classes + ', .hide_if_' + value;
							show_classes = show_classes + ', .show_if_' + value;
						}
					);
					$( hide_classes ).show();
					$( show_classes ).hide();
					// Shows rules.
					if ( is_wps_wgm_gift ) {
						$( '.show_if_wps_wgm_gift' ).show();
					}
					$( '.show_if_' + product_type ).show();
					// Hide rules.
					if ( ! is_wps_wgm_gift ) {
						$( '.show_if_wps_wgm_gift' ).hide();
					}
					$( '.hide_if_' + product_type ).hide();
					$( 'input#_manage_stock' ).change();
					// Hide empty panels/tabs after display.
					$( '.woocommerce_options_panel' ).each(
						function() {
							var $children = $( this ).children( '.options_group' );
							if ( 0 === $children.length ) {
								return;
							}
							var $invisble = $children.filter(
								function() {
									return 'none' === $( this ).css( 'display' );
								}
							);
							// Hide panel.
							if ( $invisble.length === $children.length ) {
								var $id = $( this ).prop( 'id' );
								$( '.product_data_tabs' ).find( 'li a[href="#' + $id + '"]' ).parent().hide();
							}
						}
					);
					$( "#general_product_data .show_if_simple.show_if_external.show_if_variabled" ).attr( "style", "display:block !important;" );
					if (is_tax_enable_for_gift == 'on') {
						$( document ).find( "#general_product_data .options_group.show_if_simple.show_if_external.show_if_variable" ).attr( "style", "display:block !important;" );
					}
				}
			}

			function wps_wgc_show_and_hide_pricing_option(pricing_option){
				$( '.wps_wgm_from_price_field' ).show();
				$( '.wps_wgm_to_price_field' ).show();
				$( '.wps_wgm_selected_price_field' ).show();
				$( '.wps_wgm_default_price_field' ).show();
				$( '.wps_wgm_user_price_field' ).show();
				$( '#wps_variable_gift' ).hide();

				if (pricing_option == 'wps_wgm_selected_price') {
					$( '.wps_wgm_from_price_field' ).hide();
					$( '.wps_wgm_to_price_field' ).hide();
					$( '.wps_wgm_default_price_field' ).hide();
					$( '.wps_wgm_user_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().hide();
					$( '#wps_variable_gift' ).hide();
					$( '.wps_wgm_min_user_price_field' ).hide(); 
				}
				if (pricing_option == 'wps_wgm_range_price') {
					$( '.wps_wgm_selected_price_field' ).hide();
					$( '.wps_wgm_default_price_field' ).hide();
					$( '.wps_wgm_user_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().show();
					$( '#wps_variable_gift' ).hide();
					$( '.wps_wgm_min_user_price_field').hide();
				}
				if (pricing_option == 'wps_wgm_default_price') {
					$( '.wps_wgm_from_price_field' ).hide();
					$( '.wps_wgm_to_price_field' ).hide();
					$( '.wps_wgm_selected_price_field' ).hide();
					$( '.wps_wgm_user_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().show();
					$( '#wps_variable_gift' ).hide();
					$( '.wps_wgm_min_user_price_field').hide();
				}
				if (pricing_option == 'wps_wgm_user_price') {
					$( '.wps_wgm_from_price_field' ).hide();
					$( '.wps_wgm_to_price_field' ).hide();
					$( '.wps_wgm_default_price_field' ).hide();
					$( '.wps_wgm_selected_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().show();
					$( '#wps_variable_gift' ).hide();
					$( '.wps_wgm_min_user_price_field').show();
				}
				if (pricing_option == 'wps_wgm_variable_price') {
					$( '.wps_wgm_from_price_field' ).hide(); 
					$( '.wps_wgm_to_price_field' ).hide();  
					$( '.wps_wgm_default_price_field' ).hide(); 
					$( '.wps_wgm_selected_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().hide();
					$( '.wps_wgm_user_price_field' ).hide();
					$( '#wps_variable_gift' ).show();
					$( '.wps_wgm_min_user_price_field').hide();
				}
			}

			$( '.notice-dismiss' ).click(
				function(){
					$( ".notice-success" ).remove();
				}
			);
			
			// Hide-show the instruction box.
			$( '.wps_wgm_instructions_reminder' ).on( 
				'click',
				function(){
					$( '#wps-modal-main-wrapper' ).css( 'display','block' );
				}
			);
			$( '.wps_no_thanks_general' ).on( 
				'click',
				function(){
					$( '#wps-modal-main-wrapper' ).css( 'display','none' );
				}
			);

			// Email Selection from Backend.
			var radio_on_load = $( "input[name='wps_wgm_select_email_format']:checked" ).val();
			if (radio_on_load == 'normal') {
				$( '#wps_wgm_normal_card' ).css( 'border','3px solid #808080' );
			} else if (radio_on_load == 'mom') {
				$( '#wps_wgm_mom_card' ).css( 'border','3px solid #808080' );
			}
			// On change selection for radio button border: 3px solid #808080;!

			$( '.wps_wgm_select_email' ).change(
				function(){
					var radioVal = $( this ).val();
					if (radioVal == 'normal') {
						$( '#wps_wgm_normal_card' ).css( 'border','3px solid #808080' );
						$( '#wps_wgm_mom_card' ).css( 'border','none' );
					} else if (radioVal == 'mom') {
						$( '#wps_wgm_mom_card' ).css( 'border','3px solid #808080' );
						$( '#wps_wgm_normal_card' ).css( 'border','none' );
					}
				}
			);
			jQuery( '.wps_wgm_mobile_nav .dashicons' ).on(
				'click',
				function(e) {
					e.preventDefault();
					jQuery( '.wps_wgm_navigator_template' ).toggle( 'slow' );
				}
			);

			$( document ).on(
				'click',
				'.generate_link',
				function(){
					$( '.wps_redeem_registraion_div' ).show();
				}
			);

			$( document ).on(
				'click',
				'.wps-redeem-pop-close',
				function(){
					$( '.wps_redeem_registraion_div' ).hide();
				}
			);

			$( document ).on(
				'click',
				'.remove_giftcard_redeem_details' ,
				function (e) {

					var res = confirm( "Are you sure ! want to delete the account details  " );
					if (res == true) {
						$( document ).find( '#mainform' ).submit();
					} else {
						return false;
					}
				}
			);

			var clipboard1 = new Clipboard( '.wps_link_copy' );
			var clipboard2 = new Clipboard( '.wps_embeded_copy' );
			$( document ).on(
				'click',
				'.wps_redeem_copy',
				function(event) {
					event.preventDefault();

				}
			);
			/*======================================
			=            Sticky-Sidebar            =
			======================================*/
			setTimeout(
				function()
				  {
					if ( jQuery( window ).width() >= 900 ) {
						jQuery( '.wps_wgm_navigator_template' ).stickySidebar(
							{
								topSpacing: 30,
								bottomSpacing: 10
								}
						);
					}
				},
				500
			);

			/*=====  End of Sticky-Sidebar  ======*/
		}
	);
})( jQuery );
