/**
 * Admin JS
 */
 ;( function ( $ ) {
    'use strict';

    /**
     * Ajax request on product variation changes
     */
     $('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {
     	var data = {
     		action: 'swatchly_ajax_reload_metabox_panel',
     		product_id: swatchly_params.product_id,
     		nonce: swatchly_params.nonce,
     	};

     	$.ajax({
     		type: 'POST',
     		url:  woocommerce_admin.ajax_url,
     		data: data,
     		beforeSend: function(){},
     		success: function ( response ) {
     			$('#swatchly_swatches_product_data .wc-metaboxes').html(response.data);

     			// Reinitialize color picker
     			if($('.swatchly_color_picker').length){
     				$( ".swatchly_color_picker" ).wpColorPicker();
     			}
     		},
     		error: function(errorThrown) {
     		    console.log(errorThrown);
     		},
     	});
     });

    /**
     * Ajax request on product metabox save swatches
     */
    $('.swatchly_save_swatches').on('click', function(e){
    	e.preventDefault();

    	var $message = $('.swatchly.wc-metaboxes-wrapper .woocommerce-message'),
    	    $product_data = $('#woocommerce-product-data'),
            data = {
                action: 'swatchly_ajax_save_product_meta',
                product_id: swatchly_params.product_id,
                input_fields: $('#swatchly_swatches_product_data').find(':input').serializeJSON({checkboxUncheckedValue: "0"}),
                nonce: swatchly_params.nonce
            };


    	$product_data.block({
    		message: null,
    		overlayCSS: {
    			background: '#fff',
    			opacity: 0.6
    		}
    	});

		$.ajax({
			type: 'POST',
			url:  woocommerce_admin.ajax_url,
			data: data,
			beforeSend: function(){
			    $message.html(`<p>${ swatchly_params.i18n.saving }</p>`);
			},
			success: function ( response ) {
				$message.addClass('updated').html(`<p>${ response.data.message }</p>`).css('display', 'block');
				$product_data.unblock();
			},
			error: function(errorThrown) {
			    console.log(errorThrown);
			},
		});
    });

    /**
     * Reset to default
     */
    $('.swatchly_reset_to_default').on('click', function(e){
        e.preventDefault();

        var $message = $('.swatchly.wc-metaboxes-wrapper .woocommerce-message'),
            $product_data = $('#woocommerce-product-data'),
            data = {
                action: 'swatchly_ajax_reset_product_meta',
                product_id: swatchly_params.product_id,
                nonce: swatchly_params.nonce
            };

        $product_data.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        if( confirm("Are you sure??") ){
            $.ajax({
                type: 'POST',
                url:  woocommerce_admin.ajax_url,
                data: data,
                beforeSend: function(){
                    $message.html(`<p>${ swatchly_params.i18n.saving }</p>`);
                },
                success: function ( response ) {
                    $message.addClass('updated').html(`<p>${ response.data.message }</p>`).css('display', 'block');
                    $product_data.unblock();
                    $product_data.trigger('woocommerce_variations_loaded');
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                },
            });
        }
    });

    /**
     * Media uploader field
     */
    $( document ).ready( function () {
    	// Only show the "remove image" button when needed
        $( '.swatchly_media_field .swatchly_input' ).each(function(){
            if( !$(this).val() ){
                $(this).siblings('.swatchly_remove_image').hide();
            }
        });

    	$( document ).on( 'click', '.button.swatchly_upload_image', function( event ) {
    		event.preventDefault();

    		var file_frame;
    		var $this = $(this);

    		// If the media frame already exists, reopen it.
    		if ( file_frame ) {
    			file_frame.open();
    			return;
    		}

    		// Create the media frame.
    		file_frame = wp.media.frames.downloadable_file = wp.media({
    			title: swatchly_params.i18n.choose_an_image,
    			button: {
    				text: swatchly_params.i18n.use_image
    			},
    			multiple: false
    		});

    		// When an image is selected, run a callback.
    		file_frame.on( 'select', function() {
    			var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
    			var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

    			$this.closest('.swatchly_media_field').find( '.swatchly_input' ).val( attachment.id );
    			$this.closest('.swatchly_media_field').find( '.swatchly_media_preview' ).html('<img width="60px" height="60x" src="'+ attachment_thumbnail.url +'" alt="" />');
    			$this.closest('.swatchly_media_field').find( '.button.swatchly_remove_image' ).show();
    		});

    		// Finally, open the modal.
    		file_frame.open();
    	});

    	$( document ).on( 'click', '.button.swatchly_remove_image', function() {
    		var $this = $(this);

    		$this.closest('.swatchly_media_field').find( '.swatchly_input' ).val( '' );
    		$this.closest('.swatchly_media_field').find( '.swatchly_media_preview' ).html('');
    		$this.closest('.swatchly_media_field').find( '.button.swatchly_remove_image' ).hide();

    		return false;
    	});
    });

    /**
     * Color picker field
     */
    if($('.swatchly_color_picker').length){
    	$( ".swatchly_color_picker" ).wpColorPicker();
    }

    /** 
     * Media field (2)
     */
    $('.swatchly-cs-field-media').on('click', '.button', function (event) {
        event.preventDefault();

        var $this = $(this);

        // Create the media frame.
        var file_frame = wp.media.frames.file_frame = wp.media({
            title: $this.data('popup_title'),
            button: {
                text: $this.data('upload_button_text'),
            },
            multiple: false
        });

        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON(),
                thumbnail;

            $this.siblings('.swatchly-cs--url').attr('value', attachment.url).change();
            $this.siblings('.swatchly-cs--preview').html(`
                <div class="swatchly-cs-image-preview">
                    <a href="#" class="swatchly-cs--remove fas fa-times">x</a>
                    <img src="`+ attachment.url +`" class="swatchly-cs--src">
                    </div>
            `);
                
            if ( typeof attachment.sizes !== 'undefined' && typeof attachment.sizes.thumbnail !== 'undefined' ) {
                thumbnail = attachment.sizes.thumbnail.url;
            } else if ( typeof attachment.sizes !== 'undefined' && typeof attachment.sizes.full !== 'undefined' ) {
                thumbnail = attachment.sizes.full.url;
            } else if ( attachment.url.split('.').pop().toLowerCase() === 'svg' ) {
                thumbnail = attachment.url;
            } else {
                thumbnail = attachment.icon;
            }

            $this.siblings('.swatchly-cs--thumbnail').attr('value', thumbnail);
            $this.siblings('.swatchly-cs--id').val( attachment.id );
            $this.siblings('.swatchly-cs--width').val( attachment.width );
            $this.siblings('.swatchly-cs--height').val( attachment.height );
            $this.siblings('.swatchly-cs--alt').val( attachment.alt );
            $this.siblings('.swatchly-cs--title').val( attachment.title );
            $this.siblings('.swatchly-cs--description').val( attachment.description );
        });

        // Finally, open the modal
        file_frame.open();
    });             

    $('.swatchly-cs-field-media').on('click', '.swatchly-cs--remove', function(e){
        e.preventDefault();

        $(this).closest('.swatchly-cs--preview').siblings('input[type="hidden"]').each(function(){
            $(this).attr('value', '');
        });

        $('.swatchly-cs--preview .swatchly-cs-image-preview').remove();
    });    

    /**
     * Auto change swatch types (when click parent)
     */
    $(document).on('change', '.swatchly_swatch_type.swatchly_2', function(e){    
    	var $inner_swatch_types = $(this).closest('.wc-metabox').find('.swatchly_swatch_type.swatchly_1'),
            $wc_metabox1 = $(this).closest('.wc-metabox').find('.wc-metabox.swatchly_1'),
            $wc_metabox2 = $(this).closest('.wc-metabox.swatchly_2');

    	if(this.value == 'label' || this.value == 'color' || this.value == 'image'){
    		// Remove class
    		 $wc_metabox2.removeClass( 'swatchly_type_ swatchly_type_select swatchly_type_label swatchly_type_color swatchly_type_image' );
    		 $wc_metabox1.removeClass( 'swatchly_type_ swatchly_type_select swatchly_type_label swatchly_type_color swatchly_type_image' );

    		// Set curret swatch type class
    		$wc_metabox2.addClass( 'swatchly_type_'+ this.value );
    		$wc_metabox1.addClass( 'swatchly_type_'+ this.value );

    		// Auto select swatch types
    		$inner_swatch_types.val(this.value).change();
    	} else {
    		// Remove current swatch type class
    		 $wc_metabox2.removeClass( 'swatchly_type_label swatchly_type_color swatchly_type_image' );
    		 $wc_metabox1.removeClass( 'swatchly_type_label swatchly_type_color swatchly_type_image' );

    		// Set current swatch type class
    		$wc_metabox2.addClass( 'swatchly_type_'+ this.value );
    		$wc_metabox1.addClass( 'swatchly_type_'+ this.value );

    		// Auto select swatch types
            if( this.value == 'select' ){
                $inner_swatch_types.val(this.value).change();
            } else {
                $inner_swatch_types.val(this.value).change('');
            }

    		$inner_swatch_types.attr('selected', 'selected');
    		$wc_metabox2.find('.wc-metabox-content').css('display', 'none');
    	}
    });
 
    /**
     * Disable toggle while swatch type = select
     */
    $(document).on('click','#wpcontent .wc-metabox.swatchly_2.swatchly_type_select > h3,#wpcontent .wc-metabox.swatchly_2.swatchly_type_ > h3', function(e){
    	$(this).closest('.wc-metabox.swatchly_2').find('.wc-metabox-content').css('display', 'none');
    });

    /**
     * Tooltip fields conditon
     */
    $(document).on('change', '.swatchly_tooltip', function(){
    	var $wc_metabox = $(this).closest('.wc-metabox');

		$wc_metabox.removeClass('swatchly_tooltip_ swatchly_tooltip_disable swatchly_tooltip_text swatchly_tooltip_image');
		$wc_metabox.addClass( 'swatchly_tooltip_'+ this.value );
    });

    /**
     * Enable multi color field condition
     */
    $(document).on('change', '.wc-metabox.swatchly_1 .enable_multi_color', function(){
    	var $wc_metabox1 = $(this).closest('.wc-metabox.swatchly_1');
    	var $val = this.checked == true ? '1' : '';

		$wc_metabox1.removeClass('swatchly_enable_multi_color_ swatchly_enable_multi_color_1' );
		$wc_metabox1.addClass( 'swatchly_enable_multi_color_'+ $val );
    });

    /**
     * Metabox Auto convert dropdown to image condition
     */
    $(document).on('change', '#swatchly_auto_convert_dropdowns_image', function(){
        var $toolbar_top = $(this).closest('.toolbar-top');
        var $val = this.checked == true ? '1' : '';

        $toolbar_top.removeClass('swatchly_auto_convert_dropdowns_to_image_ swatchly_auto_convert_dropdowns_to_image_1' );
        $toolbar_top.addClass( 'swatchly_auto_convert_dropdowns_to_image_'+ $val );
    });

    /**
     * Dependency
     */
    $(document).ready(function() {
        $('.swatchly-cs-taxonomy').woolentor_conditions();
    });

} )( jQuery );