/* jshint asi: true */
jQuery(document).ready(function($) {
	
	$('.fca_ga_multiselect').select2()
	$('#fca_ga_main_form').show()

	if ( $('.fca-ga-id').val() !== '' ) {
		$('#fca-ga-setup-notice').hide()
	}
	
})
