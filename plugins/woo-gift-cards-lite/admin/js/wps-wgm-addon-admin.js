jQuery(document).ready( function($) {

	const ajaxUrl  		 = localised.ajaxurl;
	const nonce    		 = localised.nonce;
	const action         = localised.callback;
	const pending_orders = localised.pending_orders;
	const pending_pages = localised.pending_pages;
	const pending_pages_count  = 'undefined' != typeof pending_pages ? pending_pages.length : 0;
	const pending_count  = 'undefined' != typeof pending_orders ? pending_orders.length : 0;

	/* Close Button Click */
	jQuery( document ).on( 'click','.treat-button',function(e){
		e.preventDefault();
		Swal.fire({
			icon: 'warning',
			title: 'We Have got ' + pending_count + ' Orders ready to go!',
			text: 'Click to start import',
			footer: 'Please do not reload/close this page until prompted',
			showCloseButton: true,
			showCancelButton: true,
			focusConfirm: false,
			confirmButtonText:
			  '<i class="fa fa-thumbs-up"></i> Start',
			confirmButtonAriaLabel: 'Thumbs up',
			cancelButtonText:
			  '<i class="fa fa-thumbs-down"></i> Cancel',
			cancelButtonAriaLabel: 'Thumbs down'
		}).then((result) => {
			if (result.isConfirmed) {

				Swal.fire({
					title   : 'Meta keys are being imported!',
					html    : 'Do not reload/close this tab.',
					footer  : '<span class="order-progress-report">' + pending_count + ' are left to import',
					didOpen: () => {
						Swal.showLoading()
					}
				});
			
				wgmstartImport( pending_orders );
			} else if (result.isDismissed) {
			  Swal.fire('Import Stopped', '', 'info');
			}
		})
	});

	const wgmstartImport = ( orders ) => {
		var event   = 'wps_wgm_import_single_post_meta_table';
		var request = { action, event, nonce, orders };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			orders = JSON.parse( response );
		}).then(
		function( orders ) {
			orders = JSON.parse( orders ).orders;
			if( ! jQuery.isEmptyObject(orders) ) {
				count = Object.keys(orders).length;
				jQuery('.order-progress-report').text( count + ' are left to import' );
				wgmstartImport(orders);
			} else {
				// All post_meta imported!

				Swal.fire({
					icon   : 'success',
					title   : 'Settings are been imported',
					html    : 'Do not reload/close this tab.',
				});
		
				wgmstartOptionsImport();
			}
		}, function(error) {
			console.error(error);
		});
	}

	const wgmstartOptionsImport = () => {
		var event   = 'wps_wgm_import_options_table';
		var request = { action, event, nonce };
		jQuery.post( ajaxUrl , request ).done(function( response ){
		}).then(
		function() {
			// All options imported!
			Swal.fire({
				title   : 'Hold On Pages are been imported',
				html    : 'Do not reload/close this tab.',
				footer  : '<span class="order-progress-report">' + pending_pages_count + ' are left to import',
				didOpen: () => {
					Swal.showLoading()
				}
			});
			wgmstartShortcodesImport( pending_pages );
		}, function(error) {
			console.error(error);
		});
	}

	const wgmstartShortcodesImport = ( pages ) => {
		var event   = 'wps_wgm_import_shortcodes';
		var request = { action, event, nonce, pages };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			pages = JSON.parse( response );
		}).then(
		function( pages ) {
			pages = JSON.parse( pages );
			console.log( jQuery.isEmptyObject(pages) );
			if( ! jQuery.isEmptyObject(pages) ) {
				count = Object.keys(pages).length;
				jQuery('.order-progress-report').text( count + ' are left to import' );
				wgmstartShortcodesImport(pages);
			} else {
				Swal.fire({
					title   : 'Hold On Terms are been imported',
					html    : 'Do not reload/close this tab.',
					didOpen: () => {
						Swal.showLoading()
					}
				});
				wgmstartTermsImport();
			}
		}, function(error) {
			console.error(error);
		});
	}
	
	const wgmstartTermsImport = () => {
		var event   = 'wps_wgm_import_terms';
		var request = { action, event, nonce };
		jQuery.post( ajaxUrl , request ).done(function( response ){
		}).then(
		function() {
			// All options imported!
			Swal.fire(' All of the Data are Migrated Successfully !', 'If You are using Premium Version of Giftcard then please update Pro plugin from plugin page.', 'success').then(() => {
				window.location.reload();
			});
		}, function(error) {
			console.error(error);
		});
	}

	// End of scripts.
});
