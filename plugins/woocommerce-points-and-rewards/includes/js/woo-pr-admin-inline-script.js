"use strict";
jQuery(document).ready(function ($) {
	/* Script for users */
	if( jQuery("select[name='action']").length ){
		jQuery('<option>').val('reset_points').text(WOO_PR_Points_Admin_Inline.reset_points_option_text).appendTo("select[name='action']");
	}
	if( jQuery("select[name='action2']").length ){
    	jQuery('<option>').val('reset_points').text(WOO_PR_Points_Admin_Inline.reset_points_option_text).appendTo("select[name='action2']");
	}

	/* Script for products */
	if( jQuery("select#product-type").length ){
		var product_type = $( 'select#product-type' ).val();
		if(product_type == 'woo_pr_points') {
			$('ul.product_data_tabs li.general_options').show();
			$('div#general_product_data div.options_group.show_if_downloadable').hide();
			$('div#woo_pr_and_rewards').parent().hide();
		}
		$( document ).on( 'change', 'select#product-type', function(){

			var product_type = $( 'select#product-type' ).val();
			$('div#woo_pr_and_rewards').parent().show();
			if(product_type == 'woo_pr_points') {
    			$('ul.product_data_tabs li.general_options').show();
    			$('div#general_product_data div.options_group.show_if_downloadable').hide();
    			$('div#woo_pr_and_rewards').parent().hide();
    		}
		} );
	}
	if( jQuery("ul.product_data_tabs li.inventory_options").length ){
		jQuery('ul.product_data_tabs li.inventory_options').addClass('show_if_woo_pr_points');
	}
	if( jQuery("div#general_product_data div.options_group.pricing").length ){
		jQuery('div#general_product_data div.options_group.pricing').addClass('show_if_woo_pr_points');
	}
	if( jQuery("div#inventory_product_data p._manage_stock_field").length ){
		jQuery('div#inventory_product_data p._manage_stock_field').addClass('show_if_woo_pr_points');
	}
	if( jQuery("div#inventory_product_data div.stock_fields").length ){
		jQuery('div#inventory_product_data div.stock_fields').addClass('show_if_woo_pr_points');
	}

});

/* Script for product cat */
jQuery( document ).ajaxComplete( function( event, request, options ) {
	if( jQuery("#addtag #woo_pr_rewards_earn_point").length ){
	    if ( request && 4 === request.readyState && 200 === request.status
	        && options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

	        var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
	        if ( ! res || res.errors ) {
	            return;
	        }
	        // Clear Display type field on submit
	        jQuery( '#addtag #woo_pr_rewards_earn_point' ).val( '' );
	        jQuery( '#addtag #woo_pr_rewards_max_point_disc' ).val( '' );
	        return;
	    }
	}
} );
