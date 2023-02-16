/**
 * Swatchly Frontend JS
 */
 ;( function ( $ ) {
	"use strict";

	if ( typeof swatchly_params === 'undefined' ) {
		return false;
	}

	/* Tooltip JS
	======================================================= */
	function addTooltip(){
		var image_src = $(this).data('tooltip_image'),
			text = $(this).data('tooltip_text');

		if(image_src && text){
			$(this).append(`<div class="swatchly-tooltip"><span class="swatchly-tooltip-text">${text}</span><img src="${image_src}" /></div>`);
		} else if(image_src){
			$(this).append(`<div class="swatchly-tooltip"><img src="${image_src}" /></div>`);
		} else if(text){
			$(this).append(`<div class="swatchly-tooltip"><span class="swatchly-tooltip-text">${text}</span></div>`);
		}
	}

	function removeTooltip(e){
		$('.swatchly-tooltip').remove();
	}

	$(document).ready(function() {
		$(document).on( 'mouseover', 'div.swatchly-swatch,a.swatchly-swatch', addTooltip );
		$(document).on('mouseleave', 'div.swatchly-swatch,a.swatchly-swatch', removeTooltip);
	});

	/* For both Single & Product loop
	======================================================= */
	$(document).ready(function(){
		$('.swatchly-hide-this-variation-row').closest('tr').addClass('swatchly_d_none');
	});

	/* Product Loop JS
	======================================================= */
	var product_loop = {
	    /* 
		 * Some thems make the full product loop item linked to the product details. 
		 * Preventing the user from clicking on the product loop item so the swatches will be clickable.
		 */
		prevent_click: function(){
			$('.swatchly_loop_variation_form').on( 'click', function(e){
				if( $(e.target).is('.swatchly-more-button') || $(e.target).closest('.swatchly-more-button').length ){
					return;
				}
				
				e.preventDefault();
				e.stopPropagation();
				e.stopImmediatePropagation();
			});
		},
		init_variation_form: function(){
			var enable_swatches = Boolean(Number(swatchly_params.enable_swatches));

	        if(enable_swatches){
	            $( '.swatchly_loop_variation_form' ).swatchly_loop_variation_form();
	        }
		},
		init_ajax_add_to_cart: function(){
			var hide_wc_forward_button = Boolean(Number(swatchly_params.hide_wc_forward_button)),
	            enable_cart_popup_notice = Boolean(Number(swatchly_params.enable_cart_popup_notice));
				
			// Ajax add to cart
	        $('body').on('click', '.swatchly_ajax_add_to_cart.swatchly_found_variation', function(e){
	            e.preventDefault();

	            var $thisbutton = $( this ),
	                productId    = $thisbutton.data( 'product_id' ),
	                variationId  = $thisbutton.attr( 'data-variation_id' ),
	                variation    = $thisbutton.attr( 'data-variation' );

	            productId = parseFloat( productId );
	            productId = productId.toFixed(0);
	            productId = Math.abs( productId );

	            variationId = parseFloat( variationId );
	            variationId = variationId.toFixed(0);
	            variationId = Math.abs( variationId );

	            if ( (isNaN( productId ) || productId === 0) || (isNaN( variationId ) || variationId === 0) ) {
	                return true;
	            }

	            if($thisbutton.is('.wc-variation-is-unavailable')){
	                return window.alert( wc_add_to_cart_variation_params.i18n_unavailable_text );
	            }

	            if ( '' !== variation ) {
	                variation = JSON.parse( variation );
	            }

	            var data = {
	                action: 'swatchly_ajax_add_to_cart',
	                product_id: productId,
	                variation_id: variationId,
	                variation: variation
	            };

	            $( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

	            $thisbutton.removeClass( 'added' );
	            $thisbutton.addClass( 'loading' );

	            // Ajax add to cart request
	            $.ajax({
	                type: 'POST',
	                url: woocommerce_params.ajax_url,
	                data: data,
	                dataType: 'json',
	                success: function ( response ) {
	                    if ( ! response ) {
	                        return;
	                    }

	                    // remove thickbox
	                    tb_remove();

	                    if ( response.error && response.product_url ) {
	                        window.location = response.product_url;
	                        return;
	                    }

	                    // Trigger event so themes can refresh other areas.
	                    $( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
	                    $( document.body ).trigger("update_checkout");

	                    // Redirect to cart option
	                    if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
	                        window.location = wc_add_to_cart_params.cart_url;
	                        return;
	                    } else {
	                        if(hide_wc_forward_button){
	                            $thisbutton.closest('.product').find('.added_to_cart.wc-forward').hide();
	                        }

	                        if(enable_cart_popup_notice){
	                            // Genrate Notice Popup
	                            $.ajax( {
	                                type: 'POST',
	                                url: woocommerce_params.ajax_url,
	                                data: {
	                                    action: 'swatchly_ajax_add_to_cart_notice',
	                                },
	                                success: function( response ) {
	                                    if ( ! response ) {
	                                        return;
	                                    }

	                                    tb_remove();

	                                    $( '#swatchly_notice_popup' ).html( response );

	                                    tb_show( '', '#TB_inline?&amp;width=600&amp;inlineId=swatchly_notice_popup' );
	                                },
	                                error: function(errorThrown) {
	                                    console.log(errorThrown);
	                                },
	                            } );
	                        }
	                    }

	                    $thisbutton.removeClass('loading');
	                },
	                error: function(errorThrown) {
	                    $thisbutton.removeClass('loading');
	                    console.log(errorThrown);
	                },
	            });
	        });
		}
	}

	$(document).ready(function(){
		$('.swatchly_loop_variation_form').addClass('swatchly_loaded_on_ready');
		product_loop.prevent_click();
	});

	// Reset the changes
	$.fn.reset = function (event){
		$(this).find('.swatchly_ajax_add_to_cart').removeClass('alt disabled wc-variation-is-unavailable swatchly_found_variation added');

		if(event === 'click'){
			var $button_text = $(this).find('.swatchly_ajax_add_to_cart').data('select_options_text')
			$(this).find('.swatchly_ajax_add_to_cart').text($button_text);
		}

		// remove veiw cart button
		$(this).find('.added_to_cart.wc-forward').remove();
		$(this).remove_out_of_stock();

		// hide reset button
		$(this).find('.reset_variations').attr('style', 'display: none !important');
	};

	// Reset to the initial price
	$.fn.reset_to_default_price = function(){
		$(this).find('.price').first().removeClass('swatchly_d_none');
		$(this).find('.swatchly_price').remove();
	};

	// Don't show the out of stock element
	$.fn.remove_out_of_stock = function (){
		if($(this).find('.swatchly_pl.swatchly_out_of_stock')){
			$(this).find('.swatchly_pl.swatchly_out_of_stock').remove();
		}
	}

	// Find the product image selector
	$.fn.get_product_image_selector = function(){
		var $product_thumbnail = '',
			product_thumbnail_selector = swatchly_params.product_thumbnail_selector;

		// custom selector priority first
		if(product_thumbnail_selector){
			$product_thumbnail = $(this).find(product_thumbnail_selector);

			return $product_thumbnail;
		}

		// look for default wc image selector
		$product_thumbnail = $(this).find('img.attachment-woocommerce_thumbnail');

		// ocean theme support
		if($product_thumbnail.length === 0){
			$product_thumbnail = $(this).find('img.woo-entry-image-main');

			// look for other default seletors
			if($product_thumbnail.length === 0){
				$product_thumbnail = $(this).find('img.attachment-woocommerce_thumbnail');

				if($product_thumbnail.length === 0){
					$product_thumbnail = $(this).find('img.wp-post-image');

					if($product_thumbnail.length === 0){
						$product_thumbnail = $(this).find('img').first();
					}
				}
			}
		}

		return $product_thumbnail;
	};

	// Backup product image properties
	// Needed when reset variation & show the default image
	$.fn.backup_product_image = function(){
		var $product_thumbnail = $(this).get_product_image_selector();

		// Clone & set default image's properties
		var backup_attributes = {
			"data-backup_alt": $product_thumbnail.attr('alt'),
			"data-backup_src": $product_thumbnail.attr('src'),
			"data-backup_width": $product_thumbnail.attr('width'),
			"data-backup_height": $product_thumbnail.attr('height')
		}

		if( $product_thumbnail.attr('srcset') ) {
		    backup_attributes["data-backup_srcset"] = $product_thumbnail.attr( 'srcset' );
		    backup_attributes["data-backup_sizes"] = $product_thumbnail.attr( 'sizes' );
		}

		$product_thumbnail.attr(backup_attributes);
	};

	// Change the product image when variation found
	$.fn.change_image = function(variation){
		// image selector
		var $product_thumbnail = $(this).get_product_image_selector();

		// if the variation does not have any image use the main image
		if( !variation.image_id ){
			$product_thumbnail.reset_to_default_image();
			return;
		}

		var attributes = {
		    alt: variation.image.alt,
		    src: variation.image.thumb_src,
		    width: variation.image.thumb_src_w,
		    height: variation.image.thumb_src_h
		};

		if( $product_thumbnail.attr('srcset') ) {
		    attributes.srcset = variation.image.srcset;
		    attributes.sizes = variation.image.sizes;
		}

		// Finally change/update image
		$product_thumbnail.attr(attributes);
	};

	// Reset to the default image when click on "reset" button
	$.fn.reset_to_default_image = function(){
		// Image selector
		var $product_thumbnail = $(this).get_product_image_selector();
		if( $(this).is('img') ){
			$product_thumbnail = $(this);
		}

		// Get backup attributes before reset
		var backup_attributes = {
			alt: $product_thumbnail.attr('data-backup_alt'),
			src: $product_thumbnail.attr('data-backup_src'),
			width: $product_thumbnail.attr('data-backup_width'),
			height: $product_thumbnail.attr('data-backup_height')
		}

		if( $product_thumbnail.attr('srcset') ) {
			backup_attributes["srcset"] = $product_thumbnail.attr( 'data-backup_srcset' );
			backup_attributes["sizes"]  = $product_thumbnail.attr( 'data-backup_sizes' );
		}

		// Finally reset the image
		$product_thumbnail.attr(backup_attributes);
	};

	// Change add to cart button text
	$.fn.change_add_to_cart_text = function(){
		var original_cart_text = $(this).text(),
			new_cart_text = $(this).data('add_to_cart_text');

		$(this).html($(this).html().replace(original_cart_text, new_cart_text));
	};

	// Function for each loop variation form
	$.fn.swatchly_loop_variation_form = function(){
		var $price_selector = '.price';

		return this.each( function(){
			var $el_variation_form = $( this ),
				$el_product = $el_variation_form.closest('.product'),
				$el_ajax_add_to_cart = $el_product.find('.swatchly_ajax_add_to_cart');

			$el_product.backup_product_image();

			// hide reset button by default
			$el_product.find('.reset_variations').attr('style', 'display: none !important');

			$el_variation_form.on('found_variation', function(e, variation){
				$el_product.reset();

				var availability_html = variation.availability_html,
					is_in_stock = variation.is_in_stock;

				// If out of stcok
				if(!is_in_stock){
					$el_ajax_add_to_cart.before(`<div class="swatchly_pl swatchly_out_of_stock">${availability_html}</div>`);
					$el_ajax_add_to_cart.addClass('alt disabled wc-variation-is-unavailable');
				} else {
					$el_product.remove_out_of_stock();
				}

				// show reset buton once a vaiation found
				$el_product.find('.reset_variations').attr('style', '');

				// Update price if catalog mode is not enabled
				if( !parseInt(swatchly_params.enable_catalog_mode ) ){
					if( !$el_product.find('.swatchly_price').length ){
						if($(variation.price_html).length){
							$el_product.find($price_selector).addClass('swatchly_d_none').after( $(variation.price_html).addClass('swatchly_price') );
						}
					} else {
						$el_product.find('.swatchly_price').remove();
						$el_product.find($price_selector).addClass('swatchly_d_none').after($(variation.price_html).addClass('swatchly_price'));
					}
				}

				// Update Image
				$el_product.change_image(variation);

				// For ajax add to cart
				// Manually generate selected variation attributes
				var selected_variation = {},
					variations = $(this).find( 'select[name^=attribute]' );
				if ( !variations.length) {
				    variations = $(this).find( '[name^=attribute]:checked' );
				}
				if ( !variations.length) {
				    variations = $(this).find( 'input[name^=attribute]' );
				}

				variations.each( function() {
				    var $this_item = $( this ),
				        attribute_name = $this_item.attr( 'name' ),
				        attribute_value = $this_item.val(),
				        index,
				        attribute_tax_name;
				        $this_item.removeClass( 'error' );
				    if ( attribute_value.length === 0 ) {
				        index = attribute_name.lastIndexOf( '_' );
				        attribute_tax_name = attribute_name.substring( index + 1 );
				        $this_item.addClass( 'required error' );
				    } else {
				        selected_variation[attribute_name] = attribute_value;
				    }
				});

				// Don't work on the cart button if catalog mode is enabled
				if( !parseInt(swatchly_params.enable_catalog_mode) ){
					// Cart button update class, text etc..
					if($el_ajax_add_to_cart.length){
						$el_ajax_add_to_cart.change_add_to_cart_text('found_variation');
						$el_ajax_add_to_cart.addClass('swatchly_found_variation');

						$el_ajax_add_to_cart.attr('data-variation_id', variation.variation_id);
						$el_ajax_add_to_cart.attr('data-variation', JSON.stringify(selected_variation));

						if($el_ajax_add_to_cart.hasClass('added')){
							$el_ajax_add_to_cart.removeClass('added');
						}
					}
				}
			}).on('click','.reset_variations', function(e){
				$el_product.reset('click');
				$el_product.remove_out_of_stock();
				$el_product.reset_to_default_price();
				$el_product.reset_to_default_image();

			});
		});
	};

	// Product loop init
	$(window).on('load', function(){
	    product_loop.init_variation_form();
		product_loop.init_ajax_add_to_cart();
	} );

	/*=====  End of Product Loop JS  ======*/
	

	/* Single product page JS
	======================================================= */
	var single_product = {
	    init: function(){
			var enable_swatches           = Boolean(Number(swatchly_params.enable_swatches)),
			is_product            		  = Boolean(Number(swatchly_params.is_product)),
			deselect_on_click             = Boolean(Number(swatchly_params.deselect_on_click)),
			show_selected_attribute_name  = Boolean(Number(swatchly_params.show_selected_attribute_name)),
			variation_label_separator     = swatchly_params.variation_label_separator;
	
			if( enable_swatches){
				$.fn.swatchly_variation_form = function(){
					return this.each( function(){
						var $el_variation_form = $( this );
		
						// Actions while select a swatch
						$el_variation_form.on( 'click', 'div.swatchly-swatch', function ( e ) {
							var $el_swatch = $( this ),
								$el_default_select = $el_swatch.closest( '.value' ).find( 'select' ),
								value   = $el_swatch.attr( 'data-attr_value' ),
		
								// single attribute preview image support
								enable_varation_image_preview_on_single_attribute_select = Boolean(Number(0)),
								image_for_clicked_swatch = {};
		
							if( enable_varation_image_preview_on_single_attribute_select ){
								var data_product_variations = $(this).closest('.variations_form').data('product_variations');
								
								// find out the first variation image if vairation has an image
								// using the selected attribute value
								if( typeof data_product_variations === 'object' ){
									for( let i in data_product_variations ){
										if( Object.values( data_product_variations[i].attributes ).includes(value) ){
											if( data_product_variations[i].image_id ){
												image_for_clicked_swatch = data_product_variations[i].image;
												break;
											}
										}
									}
								}
							}
		
								
							if( !deselect_on_click ){
		
								// Add selected class & remove siblings selected class
								$el_swatch.addClass('swatchly-selected').siblings('div.swatchly-swatch').removeClass('swatchly-selected');
		
								// Show selected variation name
								if( show_selected_attribute_name ){
									if($el_swatch.closest('tr').find('.swatchly_selected_variation_name').length){
										$el_swatch.closest('tr').find('.swatchly_selected_variation_name').text( variation_label_separator + $el_swatch.data('attr_label') );
									} else {
										$el_swatch.closest('tr').find('.label label').append('<span class="swatchly_selected_variation_name">'+ variation_label_separator + $el_swatch.data('attr_label') +'</span>');

									}
								}
		
							} else {
		
								if($el_swatch.hasClass('swatchly-selected')){
		
									// Remove selection
									$el_swatch.removeClass('swatchly-selected');
		
									// Change select field value to empty
									value = '';
		
									// Remove selected variation name
									$el_swatch.closest('tr').find('.swatchly_selected_variation_name').text( '' );
		
								} else {
		
									// Add selected class & remove siblings selected class
									$el_swatch.addClass('swatchly-selected').siblings('div.swatchly-swatch').removeClass('swatchly-selected');
		
									// Show selected variation name
									if( show_selected_attribute_name ){
										if($el_swatch.closest('tr').find('.swatchly_selected_variation_name').length){
											$el_swatch.closest('tr').find('.swatchly_selected_variation_name').text( variation_label_separator + $el_swatch.data('attr_label') );
										} else {
											$el_swatch.closest('tr').find('.label label').append('<span class="swatchly_selected_variation_name">'+ variation_label_separator + $el_swatch.data('attr_label') +'</span>');
										}
									}
		
								}
							}
		
							$el_default_select.val(value);
							$el_default_select.change();
							
						})
						.on( 'woocommerce_update_variation_values', function() {
							setTimeout( function() {
		
								// Loop through each variation row
								$el_variation_form.find( 'tbody tr' ).each( function() {
									var $tr = $(this),
									values = [];
		
									// Set default attribute label
									if( show_selected_attribute_name && !$tr.find('.swatchly_selected_variation_name').length ){
										var default_attr_label = $tr.find('.swatchly-type-wrap').attr('data-default_attr_value');
		
										if(default_attr_label){
											$tr.find('.label label').append('<span class="swatchly_selected_variation_name">'+ variation_label_separator + default_attr_label +'</span>');

										} else {
											$tr.find('.label label').append('<span class="swatchly_selected_variation_name"></span>');

										}
									}
		
									// List all attribute values
									$tr.find('select').find('option').each(function(index, option){
										values.push( option.value );
									});
		
									// Disable unavailable swatches
									$tr.find( 'div.swatchly-swatch' ).each( function() {
										var $el_swatch = $( this ),
											value = $el_swatch.attr( 'data-attr_value' );
		
										if( values.indexOf( value ) == -1 ){
											$el_swatch.addClass('swatchly-disabled');
										} else {
											$el_swatch.removeClass('swatchly-disabled');
										}
									});
		
								}); // tbody tr each
							}, 100 ); // timeout
		
							// Update price for product loop
							var $price_selector = '.price',
								$el_product = $el_variation_form.closest('.product');
							if(!swatchly_params.is_product){
								if($el_product.find('.swatchly_price').length){
									$el_product.find($price_selector).removeClass('swatchly_d_none');
									$el_product.find('.swatchly_price').remove();
								}
							}
						})
						.on( 'click', '.reset_variations', function () {
							$el_variation_form.find( '.swatchly-selected' ).removeClass( 'swatchly-selected' );
							$el_variation_form.find( '.swatchly-disabled' ).removeClass( 'swatchly-disabled' );
							$el_variation_form.find('.swatchly_selected_variation_name').text( '' );
						}); // on click div.swatchly-swatch
					});
				}
		
				// Do stuffs for each variations form
				$( function () {
					$( '.variations_form:not(.swatchly_variation_form)' ).addClass('swatchly_variation_form').swatchly_variation_form();
				} );
		
				// All major quick view plugin support
				$(document).ajaxComplete(function (event, request, settings) {
					$( '.variations_form:not(.swatchly_variation_form)' ).addClass('swatchly_variation_form').swatchly_variation_form();
				});
			}
		}
	}

	// Single product init
	single_product.init();

	/* Third party plugin/theme's compatibility
	======================================================= */

	/**
	 * 1. annasta Woocommerce Product Filters
	 */
	 $( document ).on( 'awf_after_ajax_products_update', function() {
		product_loop.prevent_click();
		product_loop.init_variation_form();
		single_product.init();
	});

	/**
	 * 2. Jet Smart Filters
	 */
	 $( document ).on( 'jet-filter-content-rendered', function() {
		product_loop.prevent_click();
		product_loop.init_variation_form();
		single_product.init();
	});

	/**
	 * 3. Woolentor Support
	 */
	 $( document ).on('woolentor_quick_view_rendered', function(){
        product_loop.prevent_click();
		product_loop.init_variation_form();
		single_product.init();
    });

	/**
	 * 4. infiniteScroll Support
	 * It's oly work under document.ready and on 'append.infiniteScroll'
	 */
	 $(document).ready(function(){
		$('.products').on('append.infiniteScroll', function(){
			product_loop.prevent_click();
			product_loop.init_variation_form();
			single_product.init();
		});
	});

	/**
	 * Themes compatibility
	 * 1. Airi theme
	 * It is intended to add compatibility for the airi theme's infinite scroll
	 */
	$( document ).on('ajaxComplete', function (event, jqxhr, settings) {
		$('.swatchly_loop_variation_form:not(.swatchly_loaded_on_ready)').each(function(){
			product_loop.prevent_click();
			product_loop.init_variation_form();
			single_product.init();
		});
	});
	
})(jQuery);