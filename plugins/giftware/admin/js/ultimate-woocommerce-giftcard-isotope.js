/**
 * All of the code for customizable giftcard JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( window ).on("load", function( $ ) {
		// external js: isotope.pkgd.js
		// init Isotope.
		var $grid = jQuery( '.grid' ).isotope(
			{
				itemSelector: '.element-item',
				layoutMode: 'fitRows',
			}
		);

		// bind filter button click.
		jQuery( '#filters' ).on(
			'click',
			'button',
			function() {
				var filterValue = jQuery( this ).attr( 'data-filter' );
				$grid.isotope( { filter: filterValue } );
			}
		);

		// bind filter button click on mobile.
		jQuery( '#filters_on_mobile' ).on(
			'change',
			'select',
			function() {
				var filterValue = jQuery( this ).val( );
				$grid.isotope( { filter: filterValue } );
			}
		);

		// change is-checked class on buttons.
		jQuery( '.button-group' ).each(
			function( i, buttonGroup ) {
				var $buttonGroup = jQuery( buttonGroup );
				$buttonGroup.on(
					'click',
					'button',
					function() {
						$buttonGroup.find( '.is-checked' ).removeClass( 'is-checked' );
						jQuery( this ).addClass( 'is-checked' );
					}
				);
			}
		);

		jQuery( '.wps_download_template' ).on(
			'click',
			function(e) {
				e.preventDefault();
				jQuery( "#wps_wgm_loader" ).show();
				var temp_id = jQuery( this ).data( "id" );
				var data = {
					action:'wps_uwgc_import_selected_template',
					temp_id:temp_id,
					wps_nonce:wps_import_gc.wps_import_temp_nonce
				};
				jQuery.ajax(
					{
						url: wps_import_gc.ajaxurl,
						type: "POST",
						data: data,
						dataType: 'json',
						success: function(response)
					{
						   jQuery( "#wps_wgm_loader" ).hide();
						   var notice = "";
							if ( response ) {
								notice = "Template Imported Successfully !";
								setTimeout(function(){
									location.reload(); 
								}, 1000);
							} else {
								notice = "Template Already Exist !";
							}
							  	jQuery( document ).find( '#wps_import_notice' ).html( notice );
							  	jQuery( document ).find( '.wps_notice_temp' ).show();
							 	 jQuery( 'html' ).animate( { scrollTop: 0 }, 'slow' );
							if ( jQuery( document ).find( '.wps_notice_temp' ).length > 0) {
								setTimeout(
									function(){
										jQuery( document ).find( '.wps_notice_temp' ).hide();
									},
									2000
								);
							};
						}
					}
				);
			}
		);

		jQuery( '.wps_import_all_giftcard_templates' ).on(
			'click',
			function(e) {
				e.preventDefault();
				jQuery( "#wps_wgm_loader" ).show();
				var data = {
					action:'wps_uwgc_import_all_templates_at_once',
					wps_nonce:wps_import_gc.wps_import_temp_nonce
				};
				jQuery.ajax(
					{
						url: wps_import_gc.ajaxurl,
						type: "POST",
						data: data,
						success: function(response)
						{
						   jQuery( "#wps_wgm_loader" ).hide();
						   var notice = "";
							if ( response ) {
								notice = "All Giftcard Templates Imported Successfully !";
								setTimeout(function(){
									location.reload(); 
								}, 1000);
							} else {
								notice = "Templates Already Exists !";
							}
						    jQuery( document ).find( '#wps_import_notice' ).html( notice );
						    jQuery( document ).find( '.wps_notice_temp' ).show();
							if ( jQuery( document ).find( '.wps_notice_temp' ).length > 0) {
								setTimeout(
									function(){
										jQuery( document ).find( '.wps_notice_temp' ).hide();
										jQuery( document ).find( '.wps_import_all_giftcard_templates' ).hide();

									},
									2000
								);
							};
						}
					}
				);
			}
		);
		jQuery( '.cancel_notice' ).on(
			'click',
			function() {
				jQuery( this ).parent().hide();
			}
		);
		jQuery( document ).on(
			'click',
			'.wps_preview_links a',
			function( e ) {
				e.preventDefault();
				jQuery( this ).parent().parent( ".wps_event_template_preview" ).siblings( '.wps-popup-wrapper' ).fadeIn( "slow" );
			}
		);
		jQuery( document ).on(
			'click',
			'.wps-popup-img span',
			function( e ) {
				e.preventDefault();
				jQuery( this ).parent().parent().parent( '.wps-popup-wrapper' ).fadeOut( "slow" );
			}
		)


		jQuery( '.wps_update_template' ).on(
			'click',
			function(e) {

				e.preventDefault();
				jQuery( "#wps_wgm_loader" ).show();
				var temp_id = jQuery( this ).data( "id" );
				var data = {
					action:'wps_uwgc_update_selected_template',
					temp_id:temp_id,
					wps_nonce:wps_import_gc.wps_import_temp_nonce
				};
				jQuery.ajax(
					{
						url: wps_import_gc.ajaxurl,
						type: "POST",
						data: data,
						dataType: 'json',
						success: function(response)
						{
						    jQuery( "#wps_wgm_loader" ).hide();
						    var notice = "";
							if ( jQuery.type(response) == 'number' ) {
								notice = "Template Updated Successfully !";
							} else {
								notice = "Problem in updating the template.";
							}
							jQuery( document ).find( '#wps_import_notice' ).html( notice );
							jQuery( document ).find( '.wps_notice_temp' ).show();
							jQuery( 'html' ).animate( { scrollTop: 0 }, 'slow' );
							if ( jQuery( document ).find( '.wps_notice_temp' ).length > 0) {
								setTimeout(
									function(){
										jQuery( document ).find( '.wps_notice_temp' ).hide();
									},
									2000
								);
							};
						}
					}
				);
			}
		);


		/*======================================
		=            Sticky-Sidebar            =
		======================================*/
		setTimeout(
			function()
			  {
				if ( jQuery( window ).width() >= 900 ) {
					jQuery( '.wps_template_filter' ).stickySidebar(
						{
							topSpacing: 30,
							bottomSpacing: 5
							}
					);
				}
			},
			500
		);

		/*=====  End of Sticky-Sidebar  ======*/
	}
);
