;(function($){
"use strict";

    var woolentorCheckout = function woolentorCheckout( $scope, $ ) {

        var woolentor_msc_checkout = $scope.find(".woolentor-msc-checkout").eq(0);
        var require_message = woolentor_msc_checkout.data('message');

        if ( woolentor_msc_checkout.length > 0 ) {

            $( "form.woocommerce-checkout .validate-required :input").attr("required", "required");
            $( "form.woocommerce-checkout .validate-email .input-text").addClass("email");

             // Back to the cart page
            $( '#woolentor-msc-back-to-cart' ).on( 'click', function() {
                window.location.href = $( this ).data( 'url' ); 
            });

            var button_prev = $('#woolentor-msc-prev'),
                button_next = $('#woolentor-msc-next,#woolentor-msc-skip-login'),
                first_step = getItemId( $('.woolentor-msc-tab-item.first') ),
                last_step = getItemId( $('.woolentor-msc-tab-item.last') ),
                active_step = 1,
                tabs = $('.woolentor-msc-tabs-menu ul'),
                tabs_content = $('.woolentor-msc-steps-wrapper'),
                coupon_form = $( '#checkout_coupon' );

            function getItemId( $item ) {
                var id = $item.attr( 'id' );
                return id.replace( 'woolentor-step-', '' );
            }

            function scrollToTop(){
                var different = $( '.woolentor-msc-tabs-menu' ).offset().top - $( window ).scrollTop();
                var scroll_offset = 70;
                if ( typeof 120 !== 'undefined' ) {
                    scroll_offset = 120;
                }
                if ( different < -40 ) {
                    $( 'html, body' ).animate({
                        scrollTop: $( '.woolentor-msc-tabs-menu' ).offset().top - scroll_offset, 
                    }, 1000 );
                }
            }

            switchTab( active_step, false );

            button_prev.on( 'click', function () {
                var step_number = active_step - 1;
                if ( step_number >= first_step ) {
                    switchTab( step_number, false );
                    scrollToTop();
                }
            });

            button_next.on( 'click', function () {
                var step_number = active_step + 1;
                if ( step_number <= last_step ) {
                    var valid_form = validateCheckoutForm();
                    if( valid_form === true ){
                        switchTab( step_number, false );
                        scrollToTop();
                    }
                }
            });

            function switchTab( step_number, step ) {

                if ( !step ) {
                    step = tabs.find('#woolentor-step-' + step_number);
                }
                tabs.find('li').removeClass('current');

                button_prev.addClass('current');
                button_next.addClass('current');

                coupon_form.hide();

                // Hide the skip login button
                if ( 1 < step_number ) {
                    $( '#woolentor-msc-skip-login').removeClass( 'current' );
                }
                if ( 1 == step_number && last_step == 5 ) {
                    $( '#woolentor-msc-next').removeClass( 'current' );
                }

                tabs_content.find('div.woolentor-msc-step-item').removeClass('current');
                $( '#woolentor-msc-step-item-' + step_number ).addClass('current');

                if ( !step.hasClass("current") ) {
                    step.addClass("current");
                }
                active_step = step_number;

                if ( active_step == first_step ) {
                    button_prev.removeClass('current');
                }if ( active_step == last_step ) {
                    button_next.removeClass('current');
                }

                // Show the Coupon form
                if ( $( '.woolentor-msc-step-review.current' ).length > 0 ) {
                    coupon_form.show();
                }

            }

            function validateCheckoutForm(){

                var form_valid = true;
                var found_element = false;

                $('.woolentor-msc-steps-wrapper .woolentor-msc-step-item.current :input').not('input.select2-search__field').each(function () {
                    if (found_element === true)
                        return false;

                    if ( $(this).attr("required") && $(this).is(":visible") ) {
                        if ($.trim($(this).val()) === '') {
                            found_element = true;
                            form_valid = false;
                            var scrool_to_element = $(this).attr("id");

                            if( !$("#"+scrool_to_element).closest('.form-row').hasClass("woolentor-error") ){
                                $("#"+scrool_to_element).closest('.form-row').addClass("woolentor-error");
                                $("#"+scrool_to_element).closest('p').append('<span class="woolentor-error-class">' + require_message + '</span>');
                            }

                            $('html, body').animate({
                                scrollTop: $("#" + scrool_to_element).offset().top - 40
                            }, 1000, function () {
                                $("#" + scrool_to_element).focus();
                            });

                            return false;
                        }
                    }

                });
                return form_valid;
            }

            /**
             * Select2 Activation
             */
            $("select.woolentor-enhanced-select").selectWoo({
                allowClear:!0,
                placeholder:$(this).data("placeholder")
            }).addClass("enhanced");

        }
    }

    // Multi checkout 2
    function validateCheckoutForm2($clk_element, $scope){
        var woolentor_msc2_checkout = $scope.find(".woolentor-msc2-checkout").eq(0),
            require_message         = woolentor_msc2_checkout.data('required-message'),
            form_valid              = true,
            found_element           = false,
            $inputs_to_validate     = $clk_element.closest('.woolentor-msc2-step').find('.woocommerce-billing-fields__field-wrapper :input').not('input.select2-search__field');

            if(
                ($clk_element.parent().parent().attr('id') == 'shipping_method_step' || $clk_element.parent().parent().attr('id') == 'shipping_address_step') && 
                $('.woolentor-msc2-step #ship-to-different-address-checkbox').is(':checked')
            ){
                $inputs_to_validate = $clk_element.closest('.woolentor-msc2-step').find('.woocommerce-shipping-fields__field-wrapper :input').not('input.select2-search__field');
            }

            $inputs_to_validate.each(function () {
            if (found_element === true)
                return false;

            if ( $(this).attr("required") && $(this).is(":visible") ) {
                if ($.trim($(this).val()) === '') {
                    found_element = true;
                    form_valid = false;
                    var scrool_to_element = $(this).attr("id");

                    if( !$("#"+scrool_to_element).closest('.form-row').hasClass("woolentor-error") ){
                        $("#"+scrool_to_element).closest('.form-row').addClass("woolentor-error");
                    }

                    $('html, body').animate({
                        scrollTop: $("#" + scrool_to_element).offset().top - 40
                    }, 1000, function () {
                        // $("#" + scrool_to_element).focus();
                        $("#" + scrool_to_element).closest('.form-row').addClass('focused woocommerce-invalid');
                    });

                    return false;
                }
            }

        });

        return form_valid;
    }

    var woolentorCheckout2 = function woolentorCheckout( $scope, $ ) {
        // Add focus class
        var $input_field = '.woolentor-fields-1 .woocommerce-input-wrapper input,.woolentor-fields-1 .woocommerce-form-login .form-row-first input,.woolentor-fields-1 .woocommerce-form-login .form-row-last input';
        
        $( ".woolentor-msc2-checkout .woocommerce-billing-fields__field-wrapper .validate-required :input").attr("required", "required");
        $( ".woolentor-msc2-checkout .woocommerce-shipping-fields__field-wrapper .validate-required :input").attr("required", "required");

        $scope.find($input_field).focus(function(){
            $(this).closest('.form-row').addClass("focused");
        }).blur(function(){
            $(this).closest('.form-row').removeClass("focused");
        });

        $scope.find('.form-row').each(function (){
            if( $(this).find(':input').attr('type') == 'checkbox' ){
                return;
            }

            if( $(this).find(':input').val() && $(this).find(':input').val().length > 0 ){
                $(this).addClass("has-value");
            }
        });

        $scope.find($input_field).on('keyup', function(e){
            if( this.value ){
                $(this).closest('.form-row').addClass("has-value");
            } else {
                $(this).closest('.form-row').removeClass("has-value");
            }
        });

        /* Field Active State */
        const fieldsActiveState = function() {
            const files = $('.woolentor-field input:not([type="checkbox"]):not([type="radio"]), .woolentor-field select, .woolentor-field textarea')
            files.each(function() {
                const $this = $(this);
                $this[0].value !== '' ? $this.parent().addClass('woolentor-active') : $this.parent().removeClass('woolentor-active');
                $this.on('input', function() {
                    $this[0].value !== '' ? $this.parent().addClass('woolentor-active') : $this.parent().removeClass('woolentor-active');
                })
            })
        }
        fieldsActiveState()
    
        /* Slide Toggle Button */
        $('[data-toggle="woolentor-slide-toggle"]').on('click', function(e) {
            e.preventDefault()
            const $this = $(this);
            $this.toggleClass('woolentor-active')
            $($this[0].hash).slideToggle()
        })
    
        /* Billing Address Toggle */
        const billingToggle = $('.woolentor-field input[data-billingToggle]'),
            billingToggleFunction = function(elem) {
                const $target = elem.data('billingtoggle')
                if(elem.is(':checked')) {
                    $($target).slideDown()
                } else {
                    $($target).slideUp()
                }
            }
        billingToggleFunction(billingToggle)
        billingToggle.on('change', function(e) {
            const $this = $(this)
            billingToggleFunction($this)
        })
        
        woolentorSteps($scope);
    }

    const woolentorSteps = function($scope) {
        const $stepItem = $('.woolentor-step:not([disabled])'),
            $stepNav = $('.woolentor-step-nav'),
            $stepFooter = $('.woolentor-step-footer'),
            $stepNavLi = $stepNav.find('li:not([disabled])'),
            $stepNavLiLength = $stepNavLi.length,
            $stepNavLiSingleLength = 100/$stepNavLiLength,
            $stepNavLiActiveNumber = $stepNav.find('.woolentor-active').data('step-number'),
            $stepNavBarActive = $('.woolentor-step-nav-bar-active'),
            $stepClasses = {
                active: 'woolentor-active',
                complete: 'woolentor-complete'
            },
        stepToggleFunction = function(targetId, type = undefined) {
            const $stepItem = $(targetId),
                {navCurrent, navCurrentIndex} = {
                    navCurrent: $stepNav.find(`ul [data-step-target="${targetId}"]`),
                    navCurrentIndex: $stepNav.find(`ul [data-step-target="${targetId}"]`).data('step-number')
                },
                {active, complete} = $stepClasses,
                classToggleFunction = function(target) {
                    target.addClass(active).removeClass(complete).removeAttr('disabled')
                    target.prevAll().addClass(complete).removeClass(active)
                    target.nextAll().removeClass(active +' '+ complete)
                }
            /* Add/Remove classes form nav item */
            if(navCurrent.length){classToggleFunction(navCurrent)}
            /* Add/Remove classes form step item */
            classToggleFunction($stepItem)
            /* Step active bar width */ 
            $stepNavBarActive.css({
                'width': `${navCurrentIndex === $stepNavLiLength ? 100 : ($stepNavLiSingleLength*navCurrentIndex) - ($stepNavLiSingleLength/2)}%`,
            })
        }
        /* Step active bar width */ 
        // Fix for elementor edit mode
        const info = {};
        info.active_number = '';
        info.li_count = $scope.find('.woolentor-step-nav li').length;
        info.li_single_width = 100 / info.li_count;
        $stepNavBarActive.css({
            'width': `${$stepNavLiActiveNumber === info.li_count ? 100 : (info.li_single_width*$stepNavLiActiveNumber) - (info.li_single_width/2)}%`,
        })

        /* Nav Function */
        $stepNavLi.on('click', function() {
            if( $(this).is('.woolentor-complete') ){
                stepToggleFunction($(this).data('step-target'))
            }
        })
        /* Button Function */
        $stepFooter.on('click', '.woolentor-btn:not([disabled])', function() {
            var x = validateCheckoutForm2($(this), $scope);
            if(x === true){
                $('.woolentor-msc2-checkout .woolentor-error-class').remove();
                stepToggleFunction($(this).data('step-target'))
            }
            
        })
        /* Step Item Function */
        $stepItem.on('click', function(e) {
            const $this = $(this),
                $toggle = $this.data('step-toggle')
            if($toggle === 'slide' & ($(e.target).hasClass('woolentor-step') || $(e.target).hasClass('woolentor-block-heading-title'))) {
                if(!$this.attr('disabled')) {
                    stepToggleFunction(`#${$this.attr('id')}`, $toggle)
                }
            }
        })
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-checkout-multi-step-form.default', woolentorCheckout );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-checkout-multi-step-form-style-2.default', woolentorCheckout2 );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-checkout-multi-step-form-style-2-nav.default', woolentorCheckout2 );
    });

})(jQuery);