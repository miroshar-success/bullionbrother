// Deactivation Form
jQuery(document).ready(function () {

    jQuery(document).on("click", function(e) {
        let popup = document.getElementById('awcfe-survey-form');
        let overlay = document.getElementById('awcfe-survey-form-wrap');
        let openButton = document.getElementById('deactivate-checkout-field-editor-and-manager-for-woocommerce');
        if(e.target.id == 'awcfe-survey-form-wrap'){
            awcfeClose();
        }
        if(e.target === openButton){ 
            e.preventDefault();
            popup.style.display = 'block';
            overlay.style.display = 'block';
        }
        if(e.target.id == 'awcfe_skip'){
            e.preventDefault();
            let urlRedirect = document.querySelector('a#deactivate-checkout-field-editor-and-manager-for-woocommerce').getAttribute('href');
            window.location = urlRedirect;
        }
        if(e.target.id == 'awcfe_cancel'){
            e.preventDefault();
            awcfeClose();
        }
    });

	function awcfeClose() {
		let popup = document.getElementById('awcfe-survey-form');
        let overlay = document.getElementById('awcfe-survey-form-wrap');
		popup.style.display = 'none';
		overlay.style.display = 'none';
		jQuery('#awcfe-survey-form form')[0].reset();
		jQuery("#awcfe-survey-form form .awcfe-comments").hide();
		jQuery('#awcfe-error').html('');
	}

    jQuery("#awcfe-survey-form form").on('submit', function(e) {
        e.preventDefault();
        let valid = awcfeValidate();
		if (valid) {
            let urlRedirect = document.querySelector('a#deactivate-checkout-field-editor-and-manager-for-woocommerce').getAttribute('href');
            let form = jQuery(this);
            let serializeArray = form.serializeArray();
            let actionUrl = 'https://feedback.acowebs.com/plugin.php';
            jQuery.ajax({
                type: "post",
                url: actionUrl,
                data: serializeArray,
                contentType: "application/javascript",
                dataType: 'jsonp',
                beforeSend: function () {
					jQuery('#awcfe_deactivate').prop( 'disabled', 'disabled' );
				},
                success: function(data)
                {
                    window.location = urlRedirect;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    window.location = urlRedirect;
                }
            });
        }
    });

    jQuery('#awcfe-survey-form .awcfe-comments textarea').on('keyup', function () {
		awcfeValidate();
	});

    jQuery("#awcfe-survey-form form input[type='radio']").on('change', function(){
        awcfeValidate();
        let val = jQuery(this).val();
        if ( val == 'I found a bug' || val == 'Plugin suddenly stopped working' || val == 'Plugin broke my site' || val == 'Other' || val == 'Plugin doesn\'t meets my requirement' ) {
            jQuery("#awcfe-survey-form form .awcfe-comments").show();
        } else {
            jQuery("#awcfe-survey-form form .awcfe-comments").hide();
        }
    });

    function awcfeValidate() {
		let error = '';
		let reason = jQuery("#awcfe-survey-form form input[name='Reason']:checked").val();
		if ( !reason ) {
			error += 'Please select your reason for deactivation';
		}
		if ( error === '' && ( reason == 'I found a bug' || reason == 'Plugin suddenly stopped working' || reason == 'Plugin broke my site' || reason == 'Other' || reason == 'Plugin doesn\'t meets my requirement' ) ) {
			let comments = jQuery('#awcfe-survey-form .awcfe-comments textarea').val();
			if (comments.length <= 0) {
				error += 'Please specify';
			}
		}
		if ( error !== '' ) {
			jQuery('#awcfe-error').html(error);
			return false;
		}
		jQuery('#awcfe-error').html('');
		return true;
	}

});
