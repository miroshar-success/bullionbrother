/**
 * All of the code for thankyou order on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

(function( $ ) {
	'use strict';

	$( document ).ready(
		function() {
			$( document ).find( '#thankyou_box' ).prev().remove();
			if ($( '#wps_wgm_thankyouorder_enable' ).prop( "checked" ) == true) {
				$( '.wps_uwgc_thankyouorder_row' ).show();
			}
			if ($( '.wps_uwgc_thankyouorder_tbody > tr' ).length == 2) {
				$( '.wps_uwgc_remove_thankyouorder_content' ).each(
					function() {
						$( this ).hide();
					}
				);
			}

			$( document ).on(
				'click',
				'#wps_uwgc_save_thankyou_order',
				function(event)
				{
					event.preventDefault();
					var response = check_validation_setting();
					if ( response != undefined) {
						$( document ).find( '#mainform' ).append( '<input type="hidden" name="wps_uwgc_save_thankyou_order_js" id="wps_uwgc_save_thankyou_order_js">' );
						$( document ).find( '#mainform' ).submit();
					}
				}
			);

			$( document ).on(
				'click',
				'.wps_uwgc_remove_thankyouorder',
				function()
				{

					if ($( '#wps_wgm_thankyouorder_enable' ).prop( "checked" ) == true) {
						$( this ).closest( 'tr' ).remove();
						var tbody_length = $( '.wps_uwgc_thankyouorder_tbody > tr' ).length;

						if ( tbody_length == 2 ) {
							$( '.wps_uwgc_remove_thankyouorder_content' ).each(
								function() {
									$( this ).hide();
								}
							);
						}
					}
				}
			);

			$( document ).on(
				'change',
				'#wps_wgm_thankyouorder_enable',
				function()
				{
					if ($( this ).prop( "checked" ) == true) {
						$( '.wps_uwgc_thankyouorder_row' ).show();
					} else {
						$( '.wps_uwgc_thankyouorder_row' ).hide();
					}
				}
			);

			$( document ).on(
				'click',
				'#wps_uwgc_add_more',
				function()
				{
					if ($( '#wps_wgm_thankyouorder_enable' ).prop( "checked" ) == true) {
						var response = check_validation_setting();
						if ( response == true) {
							var tbody_length = $( '.wps_uwgc_thankyouorder_tbody > tr' ).length;
							var new_row = '<tr valign="top"><td class="forminp forminp-text"><label for="wps_wgm_thankyouorder_minimum"><input type="text" name="wps_wgm_thankyouorder_minimum[]" class="wps_wgm_thankyouorder_minimum input-text wc_input_price" required=""></label></td><td class="forminp forminp-text"><label for="wps_wgm_thankyouorder_maximum"><input type="text" name="wps_wgm_thankyouorder_maximum[]" class="wps_wgm_thankyouorder_maximum input-text wc_input_price" required=""></label></td><td class="forminp forminp-text"><label for="wps_wgm_thankyouorder_current_type"><input type="text" name="wps_wgm_thankyouorder_current_type[]" class="wps_wgm_thankyouorder_current_type input-text wc_input_price" required=""></label></td><td class="wps_uwgc_remove_thankyouorder_content forminp forminp-text"><input type="button" value="Remove" class="wps_uwgc_remove_thankyouorder button" ></td></tr>';

							if ( tbody_length == 2 ) {
								$( '.wps_uwgc_remove_thankyouorder_content' ).each(
									function() {
										$( this ).show();
									}
								);
							}
							$( '.wps_uwgc_thankyouorder_tbody' ).append( new_row );
						}
					}
				}
			);
		}
	);
	var check_validation_setting = function(){
		if ($( '#wps_wgm_thankyouorder_enable' ).prop( "checked" ) == true) {
			var tbody_length = $( '.wps_uwgc_thankyouorder_tbody > tr' ).length;
			var i = 1;
			var min_arr = []; var max_arr = [];
			var empty_warning = false;
			var is_lesser = false;
			var num_valid = false;
			$( '.wps_wgm_thankyouorder_minimum' ).each(
				function(){
					min_arr.push( $( this ).val() );
				}
			);
			var order_number = $( '#wps_wgm_thankyouorder_number' ).val();
			if (order_number.length > 0) {
				if (jQuery.isNumeric( order_number )) {
					if (order_number < 1) {
						is_lesser = true;
					}
				} else {
					num_valid = true;
				}
			}
			if (is_lesser) {
				$( '.notice.notice-error.is-dismissible' ).each(
					function(){
						$( this ).remove();
					}
				);
				$( '.notice.notice-success.is-dismissible' ).each(
					function(){
						$( this ).remove();
					}
				);

				$( 'html, body' ).animate(
					{
						scrollTop: $( ".woocommerce_page_wps-wgc-setting-lite" ).offset().top
					},
					800
				);
				var num_message = '<div class="notice notice-error is-dismissible"><p><strong>Number Of Orders should be greater than 1!</strong></p></div>';
				$( num_message ).insertBefore( jQuery( 'h3.wps_wgm_overview_heading' ) );
				return;
			}
			if (num_valid) {
				$( '.notice.notice-error.is-dismissible' ).each(
					function(){
						$( this ).remove();
					}
				);
				$( '.notice.notice-success.is-dismissible' ).each(
					function(){
						$( this ).remove();
					}
				);

				$( 'html, body' ).animate(
					{
						scrollTop: $( ".woocommerce_page_wps-wgc-setting-lite" ).offset().top
					},
					800
				);
				var num_message = '<div class="notice notice-error is-dismissible"><p><strong>Number Of Orders should be in numbers !</strong></p></div>';
				 $( num_message ).insertBefore( jQuery( 'h3.wps_wgm_overview_heading' ) );
				return;
			}
			var i = 1;

			$( '.wps_wgm_thankyouorder_maximum' ).each(
				function(){
					max_arr.push( $( this ).val() );
					i++;
				}
			);
			var i = 1;
			var thankyouorder_arr = [];
			$( '.wps_wgm_thankyouorder_current_type' ).each(
				function(){
					thankyouorder_arr.push( $( this ).val() );

					if ( ! $( this ).val()) {
						$( '.wps_uwgc_thankyouorder_tbody > tr:nth-child(' + (i + 1) + ') .wps_wgm_thankyouorder_current_type' ).css( "border-color", "red" );
						empty_warning = true;
					} else {
						$( '.wps_uwgc_thankyouorder_tbody > tr:nth-child(' + (i + 1) + ') .wps_wgm_thankyouorder_current_type' ).css( "border-color", "" );
					}
					i++;
				}
			);
			if (empty_warning) {
				$( '.notice.notice-error.is-dismissible' ).each(
					function(){
						$( this ).remove();
					}
				);
				$( '.notice.notice-success.is-dismissible' ).each(
					function(){
						$( this ).remove();
					}
				);
				$( 'html, body' ).animate(
					{
						scrollTop: $( ".woocommerce_page_wps-wgc-setting-lite" ).offset().top
					},
					800
				);
				var empty_message = '<div class="notice notice-error is-dismissible"><p><strong>Some Fields are empty!</strong></p></div>';
				$( empty_message ).insertBefore( jQuery( 'h3.wps_wgm_overview_heading' ) );
				return;
			}
			var minmaxcheck = false;
			if (max_arr.length > 0 && min_arr.length > 0) {
				if ( min_arr.length == max_arr.length && max_arr.length == thankyouorder_arr.length) {
					var min_arr_length = min_arr.length;
					for (var j = 0; j < min_arr_length; j++) {

						if (parseInt( min_arr[j] ) > parseInt( max_arr[j] )) {
							minmaxcheck = true;
							$( '.wps_uwgc_thankyouorder_tbody > tr:nth-child(' + (j + 2) + ') .wps_wgm_thankyouorder_minimum' ).css( "border-color", "red" );
						} else {
							$( '.wps_uwgc_thankyouorder_tbody > tr:nth-child(' + (j + 2) + ') .wps_wgm_thankyouorder_minimum' ).css( "border-color", "" );
						}
					}
				} else {
					$( '.notice.notice-error.is-dismissible' ).each(
						function(){
							$( this ).remove();
						}
					);
					$( '.notice.notice-success.is-dismissible' ).each(
						function(){
							$( this ).remove();
						}
					);

					$( 'html, body' ).animate(
						{
							scrollTop: $( ".woocommerce_page_wps-wgc-setting-lite" ).offset().top
						},
						800
					);
					var empty_message = '<div class="notice notice-error is-dismissible"><p><strong>Some Fields are empty!</strong></p></div>';
					$( empty_message ).insertBefore( jQuery( 'h3.wps_wgm_overview_heading' ) );
					return;
				}
			}
			if (minmaxcheck) {
				$( '.notice.notice-error.is-dismissible' ).each(
					function(){
						$( this ).remove();
					}
				);
				$( '.notice.notice-success.is-dismissible' ).each(
					function(){
						$( this ).remove();
					}
				);

				$( 'html, body' ).animate(
					{
						scrollTop: $( ".woocommerce_page_wps-wgc-setting-lite" ).offset().top
					},
					800
				);
				var empty_message = '<div class="notice notice-error is-dismissible"><p><strong>Minimum value cannot have value grater than Maximim value.</strong></p></div>';
				$( empty_message ).insertBefore( jQuery( 'h3.wps_wgm_overview_heading' ) );
				return;
			}
			return true;
		} else {
			return false;
		}
	};

})( jQuery );
