/* jshint asi: true */
jQuery(document).ready(function($){
	
	var $deactivateButton = $('#the-list tr.active').filter( function() { return $(this).data('plugin') === 'fca-ga/fca-ga.php' } ).find('.deactivate a')
	$deactivateButton.click(function(e){
		e.preventDefault()
		$deactivateButton.unbind('click')
		$('body').append(fca_ga.html)
		fca_ga_uninstall_button_handlers( $deactivateButton.attr('href') )
		
	})
}) 

function fca_ga_uninstall_button_handlers( url ) {
	var $ = jQuery

	$('#fca-deactivate-skip').click(function(){
		$(this).prop( 'disabled', true )
		window.location.href = url
	})
	$('#fca-deactivate-send').click(function(){
		$(this).prop( 'disabled', true )
		$(this).html('...')
		$('#fca-deactivate-skip').hide()
		$.ajax({
			url: fca_ga.ajaxurl,
			type: 'POST',
			data: {
				"action": "fca_ga_uninstall",
				"nonce": fca_ga.nonce,
				"msg": $('#fca-deactivate-textarea').val()
			}
		}).done( function( response ) {
			window.location.href = url			
		})	
	})
	
}