/**
 * All of the code for javascript on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function(){

		jQuery( '#DiscountBox' ).prev().remove();
		if (jQuery( '#wps_wgm_discount_enable' ).prop( "checked" ) == true) {
			jQuery( '.wps_wgm_discount_row' ).show();
		}
		if (jQuery( '.wps_wgm_discount_tbody > tr' ).length == 2) {
			jQuery( '.wps_wgm_remove_discount_content' ).each(
				function() {
					jQuery( this ).hide();
				}
			);
		}
		jQuery( document ).on(
			'change',
			'#wps_wgm_discount_enable',
			function()
			{
				if (jQuery( this ).prop( "checked" ) == true) {
					jQuery( '.wps_wgm_discount_row' ).show();
				} else {
					jQuery( '.wps_wgm_discount_row' ).hide();
				}
			}
		);
		jQuery( document ).on(
			'click',
			'#wps_uwgc_save_discount',
			function(e){
				e.preventDefault();
				var response = check_validation_setting();
				if ( response != undefined) {
					jQuery( document ).find( '#mainform' ).append( '<input type="hidden" name="wps_uwgc_save_discount_js" id="wps_uwgc_save_discount_js">' );
					jQuery( document ).find( '#mainform' ).submit();
				}
			}
		);
		jQuery( document ).on(
			'click',
			'.wps_wgm_remove_discount',
			function()
			{
				if (jQuery( '#wps_wgm_discount_enable' ).prop( "checked" ) == true) {
					jQuery( this ).closest( 'tr' ).remove();
					var tbody_length = jQuery( '.wps_wgm_discount_tbody > tr' ).length;

					if ( tbody_length == 2 ) {
						jQuery( '.wps_wgm_remove_discount_content' ).each(
							function() {
								jQuery( this ).hide();
							}
						);
					}
				}
			}
		);
		jQuery( document ).on(
			'click',
			'#wps_wgm_add_more',
			function()
			{
				if (jQuery( '#wps_wgm_discount_enable' ).prop( "checked" ) == true) {
					var response = check_validation_setting();
					if ( response == true) {
						var tbody_length = jQuery( '.wps_wgm_discount_tbody > tr' ).length;
						var new_row = '<tr valign="top"><td class="forminp forminp-text"><label for="wps_wgm_discount_minimum"><input type="text" name="wps_wgm_discount_minimum[]" class="wps_wgm_discount_minimum input-text wc_input_price wps_price_range" required=""></label></td><td class="forminp forminp-text"><label for="wps_wgm_discount_maximum"><input type="text" name="wps_wgm_discount_maximum[]" class="wps_wgm_discount_maximum input-text wc_input_price wps_price_range" required=""></label></td><td class="forminp forminp-text"><label for="wps_wgm_discount_current_type"><input type="text" name="wps_wgm_discount_current_type[]" class="wps_wgm_discount_current_type input-text wc_input_price wps_price_range" required=""></label></td><td class="wps_wgm_remove_discount_content forminp forminp-text"><input type="button" value="Remove" class="wps_wgm_remove_discount button" ></td></tr>';

						if ( tbody_length == 2 ) {
							jQuery( '.wps_wgm_remove_discount_content' ).each(
								function() {
									jQuery( this ).show();
								}
							);
						}
						jQuery( '.wps_wgm_discount_tbody' ).append( new_row );
					}
				}
			}
		);
		jQuery( document ).on(
			'keyup',
			'.wps_price_range',
			function() {
				this.value = this.value.replace(/[^0-9]/g, '');
			}
		);
	}
);
var check_validation_setting = function(){

	if (jQuery( '#wps_wgm_discount_enable' ).prop( "checked" ) == true) {
		var tbody_length = jQuery( '.wps_wgm_discount_tbody > tr' ).length;
		var i = 1;
		var min_arr = [];
		var empty_warning = false;
		jQuery( '.wps_wgm_discount_minimum' ).each(
			function(){
				min_arr.push( jQuery( this ).val() );

				if ( ! jQuery( this ).val()) {
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (i + 1) + ') .wps_wgm_discount_minimum' ).css( "border-color", "red" );
					empty_warning = true;
				} else {
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (i + 1) + ') .wps_wgm_discount_minimum' ).css( "border-color", "" );
				}
				i++;
			}
		);

		var i = 1;
		var max_arr = [];
		jQuery( '.wps_wgm_discount_maximum' ).each(
			function(){
				max_arr.push( jQuery( this ).val() );

				if ( ! jQuery( this ).val()) {
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (i + 1) + ') .wps_wgm_discount_maximum' ).css( "border-color", "red" );
					empty_warning = true;
				} else {
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (i + 1) + ') .wps_wgm_discount_maximum' ).css( "border-color", "" );
				}
				i++;
			}
		);
		var i = 1;
		var discount_arr = [];
		jQuery( '.wps_wgm_discount_current_type' ).each(
			function(){
				discount_arr.push( jQuery( this ).val() );

				if ( ! jQuery( this ).val()) {
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (i + 1) + ') .wps_wgm_discount_current_type' ).css( "border-color", "red" );
					empty_warning = true;
				} else {
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (i + 1) + ') .wps_wgm_discount_current_type' ).css( "border-color", "" );
				}
				i++;
			}
		);
		if (empty_warning) {
			jQuery( '.notice.notice-error.is-dismissible' ).each(
				function(){
					jQuery( this ).remove();
				}
			);
			jQuery( '.notice.notice-success.is-dismissible' ).each(
				function(){
					jQuery( this ).remove();
				}
			);

			jQuery( 'html, body' ).animate(
				{
					scrollTop: jQuery( ".woocommerce_page_wps-wgc-setting-lite" ).offset().top
				},
				800
			);
			var empty_message = '<div class="notice notice-error is-dismissible"><p><strong>Some Fields are empty!</strong></p></div>';
			jQuery( empty_message ).insertBefore( jQuery( 'h3.wps_wgm_overview_heading' ) );
			return;
		}
		var minmaxcheck = false;
		if ( min_arr.length == max_arr.length && max_arr.length == discount_arr.length) {
			min_arr_length = min_arr.length;

			for (var j = 0; j < min_arr_length; j++) {

				if (parseInt( min_arr[j] ) > parseInt( max_arr[j] )) {
					minmaxcheck = true;
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (j + 2) + ') .wps_wgm_discount_minimum' ).css( "border-color", "red" );
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (j + 2) + ') .wps_wgm_discount_minimum' ).css( "border-color", "red" );
				} else {
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (j + 2) + ') .wps_wgm_discount_minimum' ).css( "border-color", "" );
					jQuery( '.wps_wgm_discount_tbody > tr:nth-child(' + (j + 2) + ') .wps_wgm_discount_minimum' ).css( "border-color", "" );
				}
			}
		} else {
			jQuery( '.notice.notice-error.is-dismissible' ).each(
				function(){
					jQuery( this ).remove();
				}
			);
			jQuery( '.notice.notice-success.is-dismissible' ).each(
				function(){
					jQuery( this ).remove();
				}
			);

			jQuery( 'html, body' ).animate(
				{
					scrollTop: jQuery( ".woocommerce_page_wps-wgc-setting-lite" ).offset().top
				},
				800
			);
			var empty_message = '<div class="notice notice-error is-dismissible"><p><strong>Some Fields are empty!</strong></p></div>';
			jQuery( empty_message ).insertBefore( jQuery( 'h3.wps_wgm_overview_heading' ) );
			return;
		}
		if (minmaxcheck) {
			jQuery( '.notice.notice-error.is-dismissible' ).each(
				function(){
					jQuery( this ).remove();
				}
			);
			jQuery( '.notice.notice-success.is-dismissible' ).each(
				function(){
					jQuery( this ).remove();
				}
			);

			jQuery( 'html, body' ).animate(
				{
					scrollTop: jQuery( ".woocommerce_page_wps-wgc-setting-lite" ).offset().top
				},
				800
			);
			var empty_message = '<div class="notice notice-error is-dismissible"><p><strong>Minimum value cannot have value grater than Maximim value.</strong></p></div>';
			jQuery( empty_message ).insertBefore( jQuery( 'h3.wps_wgm_overview_heading' ) );
			return;
		}
		return true;
	} else {
		return false;
	}
};
