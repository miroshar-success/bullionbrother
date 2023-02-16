jQuery(document).ready(function($){

	//Form reset
	$('.xoo-as-form-reset').click(function(e){
		if( !confirm( 'Are you sure?' ) )
			e.preventDefault();
	})

	//Toggle pro
	$('.xoo-as-pro-toggle').click(function(e){
		$('.xoo-settings-container').toggleClass('xoo-as-disable-pro');
	})

	$('.xoo-settings-container').addClass('xoo-as-disable-pro');

	//Switch Tabs
	$('ul.xoo-sc-tabs li').click(function(){
		$('ul.xoo-sc-tabs li, .xoo-sc-tab-content').removeClass('xoo-sct-active');
		$(this).addClass('xoo-sct-active');
		$(this).parents('.xoo-settings-container').attr('active-tab',$(this).data('tab'));
		$('.xoo-sc-tab-content[data-tab="'+$(this).data('tab')+'"]').addClass('xoo-sct-active');
	})

	$('ul.xoo-sc-tabs li:nth-child(1)').trigger('click');

	$('.xoo-as-form').on( 'submit', function(e){

		e.preventDefault();

		$button = $(this).find('.xoo-as-form-save');
		$button.text( 'Saving....' );

		var data = {
			'form': $(this).serialize(),
			'action': 'xoo_admin_settings_save',
			'xoo_ff_nonce': xoo_admin_params.nonce
		}

		$.ajax({
			url: xoo_admin_params.adminurl,
			type: 'POST',
			data: data,
			success: function(response){
				$button.text('Settings Saved');
				setTimeout(function(){
					$button.text( 'Save' )
				},5000)
			}
		});

	})



	//Media

	function renderMediaUploader(upload_btn) {
	 
	    var file_frame, image_data;
	 
	    /**
	     * If an instance of file_frame already exists, then we can open it
	     * rather than creating a new instance.
	     */
	    if ( undefined !== file_frame ) {
	 
	        file_frame.open();
	        return;
	 
	    }
	 
	    /**
	     * If we're this far, then an instance does not exist, so we need to
	     * create our own.
	     *
	     * Here, use the wp.media library to define the settings of the Media
	     * Uploader. We're opting to use the 'post' frame which is a template
	     * defined in WordPress core and are initializing the file frame
	     * with the 'insert' state.
	     *
	     * We're also not allowing the user to select more than one image.
	     */
	    file_frame = wp.media.frames.file_frame = wp.media({
	        frame:    'post',
	        state:    'insert',
	        multiple: false
	    });
	 
	    /**
	     * Setup an event handler for what to do when an image has been
	     * selected.
	     *
	     * Since we're using the 'view' state when initializing
	     * the file_frame, we need to make sure that the handler is attached
	     * to the insert event.
	     */
	    file_frame.on( 'insert', function() {
	 	
	        // Read the JSON data returned from the Media Uploader
   		 	var json = file_frame.state().get( 'selection' ).first().toJSON();

   		 	upload_btn.siblings('.xoo-upload-url').val(json.url);
   		 	upload_btn.siblings('.xoo-upload-title').html(json.filename);
   		
	 
	    });
	 
	    // Now display the actual file_frame
	    file_frame.open();
 
	}





	
    $( '.xoo-upload-icon' ).on( 'click', function( evt ) {

        // Stop the anchor's default behavior
        evt.preventDefault();

        // Display the media uploader
        renderMediaUploader($(this));

    });
 
   


    //Get media uploaded name
	$('.xoo-upload-url').each(function(){
		var media_url = $(this).val();
		if(!media_url) return true; // Skip to next if no value is set

		var index = media_url.lastIndexOf('/') + 1;
		var media_name = media_url.substr(index);

		$(this).siblings('.xoo-upload-title').html(media_name);
	})


	//Remove uploaded file
	$('.xoo-remove-media').on('click',function(){
		$(this).siblings('.xoo-upload-url').val('');
		$(this).siblings('.xoo-upload-title').html('');
	})


	//Initialize color picker
	$('.xoo-as-color-input').wpColorPicker();

	//initialize sortable
	$('.xoo-as-sortable-list').each( function( index, sortEl ){
		var $sortEl = $(sortEl),
			sortData = $sortEl.data('sort');
		$sortEl.sortable( sortData );
	} );


})