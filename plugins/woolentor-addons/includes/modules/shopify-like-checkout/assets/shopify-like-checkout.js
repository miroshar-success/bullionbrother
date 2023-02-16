;(function($){
    "use strict";
    
    // Show login form
    $(document).ready(function(){
        $('.woolentor-checkout__box .showlogin').on('click', function(e){
            e.preventDefault();
        });
    });
    
    // Remove coupon notice from form top
    $(document).on('removed_coupon_in_checkout', function(arg){
        $('.woolentor-checkout__left-sidebar .woocommerce-message').remove();
    });
    
    // Validate and address fields & go to the next step
    $('.woolentor-checkout__step-footer.woolentor-footer--1').on('click', '.woolentor-checkout__button', function(e){
        e.preventDefault();
        var step = $(this).data('step');
    
        validate_address( step );

    });
    
    // Navigate to the next/prev steps from footer nav
    $('.woolentor-checkout__step-footer').on('click', '.woolentor-checkout__button,.woolentor-checkout__text-link', function(e){
        if( $(this).is('#place_order') || ($(this).is('.woolentor-checkout__button') && $(this).closest('.woolentor-footer--1').length) || ($(this).is('.woolentor-checkout__text-link') &&$(this).closest('.woolentor-footer--1').length) ){
            return;
        }
    
        e.preventDefault();
        var step = $(this).data('step');
        var $checkout_box = $('woolentor-checkout__box');
    
        $checkout_box.update_step_class( step );
    });
    
    
    // Update wrapper step class
    $.fn.update_step_class = function( current_step ){
        $('.woocommerce-NoticeGroup-checkout').remove();

        localStorage.setItem("woolentorShopifyCheckoutStep", current_step );
    
        $('.woolentor-checkout__breadcrumb-item').removeClass('active');
        $('.woolentor-checkout__breadcrumb-item[data-step="'+ current_step +'"]').addClass('active');
    
        var current_step_class = 'woolentor-' + current_step;
        $('.woolentor-checkout__box').removeClass('woolentor-step--info woolentor-step--shipping woolentor-step--payment').addClass( current_step_class );
    }

    // Setup the tab
    $('.woolentor-checkout__breadcrumb li').on('click', function(e){
        var step = $(this).closest('.woolentor-checkout__breadcrumb-item').data('step'),
            $checkout_box = $('woolentor-checkout__box')
    
        if( step != 'step--info' ){
            validate_address(step);
        } else {
            $checkout_box.update_step_class( step );
        }
    });
    
    // Validate the address fields
    function validate_address( step ){
        var $checkout_box = $('woolentor-checkout__box'),
            $checkout_form = $('.checkout.woocommerce-checkout');

        $checkout_form.addClass( 'processing' ).block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        $.ajax({
            type : 'POST',
            url  : woolentor_slc_params.ajax_url,
            data : {
                'action': 'validate_1st_step',
                'fields': $('.woolentor-checkout__section.woolentor-step--info').find(':input').serialize(),
                'nonce' : woolentor_slc_params.nonce
            },
            dataType:   'json',
            success:    function( result ) {
                $checkout_form.removeClass( 'processing' ).unblock();
                if( result.data.messages ){
                    submit_error( result.data.messages );
                } else {
                    $checkout_box.update_step_class( step );
                }
            },
            error:  function( jqXHR, textStatus, errorThrown ) {
                console.log(errorThrown);
            }
        });
    }
    
    // Render the notice
    function submit_error( error_message ) {
        var $checkout_form = $('.checkout.woocommerce-checkout');
    
        $( '.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message' ).remove();
        $checkout_form.before( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + error_message + '</div>' ); // eslint-disable-line max-len
        $checkout_form.removeClass( 'processing' ).unblock();
        $checkout_form.find( '.input-text, select, input:checkbox' ).trigger( 'validate' ).trigger( 'blur' );
        scroll_to_notices();
        $( document.body ).trigger( 'checkout_error' , [ error_message ] );
    }
    
    // Scroll to the notice
    function scroll_to_notices() {
        var scrollElement = $( '.woocommerce-NoticeGroup-checkout' );
    
        if ( ! scrollElement.length ) {
            scrollElement = $( '.form.checkout' );
        }
        $.scroll_to_notices( scrollElement );
    }
    
})(jQuery);