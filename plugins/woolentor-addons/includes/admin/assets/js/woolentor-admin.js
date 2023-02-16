;(function($){
"use strict";

    // Tab Menu
    function woolentor_admin_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('wlactive').parent().addClass('wlactive').siblings().removeClass('wlactive').children('a').removeClass('wlactive');
            $( $tabpane + $target ).addClass('wlactive').siblings().removeClass('wlactive');
        });
    }

    // Navigation tabs Nested tabs
    woolentor_admin_tabs( $(".woolentor-nested-tabs"), '.woolentor-admin-nested-tab-pane' );

    // Extension Tabs
    woolentor_admin_tabs( $(".woolentor-admin-tabs"), '.woolentor-admin-tab-pane' );

    // Navigation Tabs
    $('.woolentor-admin-main-nav').on('click', '.woolentor-admin-main-nav-btn', function(e) {
        e.preventDefault()
        const $this = $(this),
            $siblingsBtn = $this.closest('li').siblings().find('.woolentor-admin-main-nav-btn'),
            $target = $this.attr('href')
        localStorage.setItem("wlActiveTab", $target);
        if(!$this.hasClass('wlactive')) {
            $this.addClass('wlactive')
            $siblingsBtn.removeClass('wlactive')
            $($target).addClass('wlactive').show().siblings().removeClass('wlactive').hide()
        }
    })
    if (localStorage.wlActiveTab !== undefined && localStorage.wlActiveTab !== null ) {
        const $wlActiveTab = localStorage.getItem('wlActiveTab')
        $('.woolentor-admin-main-nav-btn').each(function() {
            const $this = $(this),
                $siblingsBtn = $this.closest('li').siblings().find('.woolentor-admin-main-nav-btn')
            if($this.attr('href') === $wlActiveTab) {
                $this.addClass('wlactive')
                $siblingsBtn.removeClass('wlactive')
            }
        })
        $($wlActiveTab).addClass('wlactive').show().siblings().removeClass('wlactive').hide()
    } else {
        var $defaultIndex = $('.woolentor-admin-main-nav-btn').length-1;
        const $firstTab = $('.woolentor-admin-main-nav-btn')[$defaultIndex],
            $target = $firstTab.hash
        $firstTab.classList.add('wlactive')
        $($target).addClass('wlactive').show().siblings().removeClass('wlactive').hide()
    }

    /* Number Input */
    $('.woolentor-admin-number-btn').on('click', function(e){
        e.preventDefault()
        const $this = $(this),
            $input = $this.parent('.woolentor-admin-number').find('input[type="number"]')[0]
        if($this.hasClass('increase')) {
            $input.value = Number($input.value) + 1
        } else if($this.hasClass('decrease') && Number($input.value) > 1) {
            $input.value = Number($input.value) - 1
        }
    });

    // Footer Sticky Save Button
    var $adminHeaderArea  = $('.woolentor-admin-main-nav'),
        $stickyFooterArea = $('.woolentor-admin-footer,.woolentor-sticky-condition');

    if ( $stickyFooterArea.length <= 0 || $adminHeaderArea.length <= 0 ) return;

    var totalOffset = $adminHeaderArea.offset().top + $adminHeaderArea.outerHeight();
    var footerSaveStickyToggler = function () {
        var windowScroll    = $(window).scrollTop(),
            windowHeight    = $(window).height(),
            documentHeight  = $(document).height();

        if (totalOffset < windowScroll && windowScroll + windowHeight != documentHeight) {
            $stickyFooterArea.addClass('woolentor-admin-sticky');
        } else if (windowScroll + windowHeight == documentHeight || totalOffset > windowScroll) {
            $stickyFooterArea.removeClass('woolentor-admin-sticky');
        }
    };
    footerSaveStickyToggler();
    $(window).scroll(footerSaveStickyToggler);

    /* Pro Popup */
    /* Open */
    $('[data-woolentor-pro="disabled"]').on('click', function(e){
        e.preventDefault()
        const $popup = $('#woolentor-admin-pro-popup')
        $popup.addClass('open')
    });
    /* Close */
    $('.woolentor-admin-popup-close').on('click', function(){
        const $this = $(this),
            $popup = $this.closest('.woolentor-admin-popup')
        $popup.removeClass('open')
    });
    /* Close on outside clicl */
    $(document).on('click', function(e){
        if(e.target.classList.contains('woolentor-admin-popup')) {
            e.target.classList.remove('open')
        }
    });
    
    /* Switch Enable/Disable Function */
    $('[data-switch-toggle]').on('click', function(e){
        e.preventDefault();

        const $this = $(this),
        $type = $this.data('switch-toggle'),
        $target = $this.data('switch-target'),
        $switches = $(`[data-switch-id="${$target}"`)

        $switches.each(function(){
            const $switch = $(this)
            if($switch.data('woolentor-pro') !== 'disabled') {
                const $input = $switch.find('input[type="checkbox"');
                var actionBtn = $switch.closest('.woolentor-admin-switch-block-actions').find('.woolentor-admin-switch-block-setting');
                if( $type === 'enable' && $input.is(":visible") ) {
                    $input[0].setAttribute("checked", "checked");
                    $input[0].checked = true;
                    if( actionBtn.hasClass('woolentor-visibility-none') ){
                        actionBtn.removeClass('woolentor-visibility-none');
                    }
                }
                if( $type === 'disable' && $input.is(":visible") ) {
                    $input[0].removeAttribute("checked");
                    $input[0].checked = false;
                    actionBtn.addClass('woolentor-visibility-none');
                }

            }
        });

    });

    /* Select 2 */
    $('.woolentor-admin-select select[multiple="multiple"]').each(function(){
        const $this = $(this),
            $parent = $this.parent();
        $this.select2({
            dropdownParent: $parent,
            placeholder: "Select template"
        });
    })

    /**
     * Admin Module additional setting button
     */
    $('.woolentor-admin-switch .checkbox').on('click',function(e){
        var actionBtn = $(this).closest('.woolentor-admin-switch-block-actions').find('.woolentor-admin-switch-block-setting');
        if( actionBtn.hasClass('woolentor-visibility-none') ){
            actionBtn.removeClass('woolentor-visibility-none');
        }else{
            actionBtn.addClass('woolentor-visibility-none');
        }
    });

    // Option data save
    $('.woolentor-admin-btn-save').on('click',function(event){
        event.preventDefault();

        var $option_form = $(this).closest('.woolentor-admin-main-tab-pane').find('form.woolentor-dashboard'),
            $savebtn     = $(this),
            $section     = $option_form.data('section'),
            $field_keys  = $option_form.data('fields');

        $.ajax( {
            url: WOOLENTOR_ADMIN.ajaxurl,
            type: 'POST',
            data: {
                nonce   : WOOLENTOR_ADMIN.nonce,
                section : $section,
                fileds  : $field_keys,
                action  : 'woolentor_save_opt_data',
                data    : $option_form.serializeJSON()
            },
            beforeSend: function(){
                $savebtn.text( WOOLENTOR_ADMIN.message.loading ).addClass('updating-message');
            },
            success: function( response ) {
                $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(WOOLENTOR_ADMIN.message.success);
            },
            complete: function( response ) {
                $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(WOOLENTOR_ADMIN.message.success);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }

        });

    });

    // Save Button Enable
    $('.woolentor-admin-main-tab-pane .woolentor-dashboard').on( 'click', 'input,select,textarea,.woolentor-admin-number-btn' , function() {
        $(this).closest('.woolentor-admin-main-tab-pane').find('.woolentor-admin-btn-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
    });

    $('.woolentor-admin-main-tab-pane .woolentor-dashboard').on( 'keyup', 'input' , function() {
        $(this).closest('.woolentor-admin-main-tab-pane').find('.woolentor-admin-btn-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
    });

    $('.woolentor-admin-header-actions .woolentor-admin-btn').on('click', function(){
        $(this).closest('.woolentor-admin-main-tab-pane').find('.woolentor-admin-btn-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
    });

    $('.woolentor-admin-main-tab-pane .woolentor-dashboard').on('change', 'select.woolentor-admin-select', function() {
        $(this).closest('.woolentor-admin-main-tab-pane').find('.woolentor-admin-btn-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
    });

    // Module additional settings
    $('.woolentor-admin-switch-block-setting').on('click',function(event){
        event.preventDefault();

        var $this     = $(this),
            $section  = $this.data('section'),
            $fields   = $this.data('fields'),
            $fieldname = $this.data('fieldname') ? $this.data('fieldname') : '',
            content = null,
            modulewrapper = wp.template( 'woolentormodule' );

        $.ajax( {
            url: WOOLENTOR_ADMIN.ajaxurl,
            type: 'POST',
            data: {
                nonce   : WOOLENTOR_ADMIN.nonce,
                section : $section,
                fileds  : $fields,
                fieldname : $fieldname,
                action  : 'woolentor_module_data',
                subaction : 'get_data',
            },
            beforeSend: function(){
                $this.addClass('module-setting-loading');
            },
            success: function( response ) {

                content = modulewrapper( {
                    section : $section,
                    fileds  : response.data.fields,
                    content : response.data.content
                } );
                $( 'body' ).append( content );

                woolentor_module_ajax_reactive();
                $( document ).trigger('module_setting_loaded');
                $this.removeClass('module-setting-loading');
                
            },
            complete: function( response ) {
                $this.removeClass('module-setting-loading');
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }

        });


    });

    // PopUp reactive JS
    function woolentor_module_ajax_reactive(){

        // Select 2 Multiple selection
        $('.woolentor-module-setting-popup').find('.woolentor-admin-option:not(.woolentor-repeater-field) .woolentor-admin-select select[multiple="multiple"]').each(function(){
            const $this = $(this),
                $parent = $this.parent();
            $this.select2({
                dropdownParent: $parent,
                placeholder: "Select Item"
            });
        });

        //Initiate Color Picker
        $('.woolentor-module-setting-popup').find('.woolentor-admin-option:not(.woolentor-repeater-field) .wp-color-picker-field').wpColorPicker({
            change: function (event, ui) {
                $(this).closest('.woolentor-module-setting-popup-content').find('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
            },
            clear: function (event) {
                $(this).closest('.woolentor-module-setting-popup-content').find('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
            }
        });

        // WPColor Picker Button disable.
        $('div[data-woolentor-pro="disabled"] .wp-picker-container button').each(function(){
            $(this).attr("disabled", true);
        });

        /* Number Input */
        $('.woolentor-admin-number-btn').on('click', function(e){
            e.preventDefault()
            const $this = $(this),
                $input = $this.parent('.woolentor-admin-number').find('input[type="number"]')[0]
            if($this.hasClass('increase')) {
                $input.value = Number($input.value) + 1
            } else if($this.hasClass('decrease') && Number($input.value) > 1) {
                $input.value = Number($input.value) - 1
            }
        });

        // Icon Picker
        $('.woolentor-module-setting-popup').find('.woolentor-admin-option:not(.woolentor-repeater-field).woolentor_icon_picker .regular-text').fontIconPicker({
            source: woolentor_fields.iconset,
            emptyIcon: true,
            hasSearch: true,
            theme: 'fip-bootstrap'
        }).on('change', function() {
            $(this).closest('.woolentor-module-setting-popup-content').find('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
        });

        // Media Uploader
        $('.woolentor-browse').on('click', function (event) {
            event.preventDefault();

            var self = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: self.data('uploader_title'),
                button: {
                    text: self.data('uploader_button_text'),
                },
                multiple: false
            });

            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();
                self.prev('.woolentor-url').val(attachment.url).change();
                self.siblings('.woolentor_display').html('<img src="'+attachment.url+'" alt="'+attachment.title+'" />');
            });

            // Finally, open the modal
            file_frame.open();
            
        });

        // Remove Media Button
        $('.woolentor-remove').on('click', function (event) {
            event.preventDefault();
            var self = $(this);
            self.siblings('.woolentor-url').val('').change();
            self.siblings('.woolentor_display').html('');
        });

        // Module additional setting save
        $('.woolentor-admin-module-save').on('click',function(event){
            event.preventDefault();

            var $option_form = $(this).closest('.woolentor-module-setting-popup-content').find('form.woolentor-module-setting-data'),
                $savebtn     = $(this),
                $section     = $option_form.data('section'),
                $field_keys  = $option_form.data('fields');

            $.ajax( {
                url: WOOLENTOR_ADMIN.ajaxurl,
                type: 'POST',
                data: {
                    nonce   : WOOLENTOR_ADMIN.nonce,
                    section : $section,
                    fileds  : $field_keys,
                    action  : 'woolentor_save_opt_data',
                    data    : $(this).closest('.woolentor-module-setting-popup-content').find('form.woolentor-module-setting-data :input').not('.woolentor-repeater-hidden :input').serializeJSON()
                },
                beforeSend: function(){
                    $savebtn.text( WOOLENTOR_ADMIN.message.loading ).addClass('updating-message');
                },
                success: function( response ) {
                    $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(WOOLENTOR_ADMIN.message.success);
                },
                complete: function( response ) {
                    $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text(WOOLENTOR_ADMIN.message.success);
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
    
            });

        });

        // Module Setting Reset
        $('.woolentor-admin-module-reset').on('click',function(event){
            event.preventDefault();

            var $option_form = $(this).closest('.woolentor-module-setting-popup-content').find('form.woolentor-module-setting-data'),
                $resetbtn    = $(this),
                $section     = $option_form.data('section');

            Swal.fire({
                title: WOOLENTOR_ADMIN.message.sure,
                text: 'It will reset all the settings to default, and all the changes you made will be deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: WOOLENTOR_ADMIN.message.yes,
                cancelButtonText: WOOLENTOR_ADMIN.message.cancel,
            }).then((result) => {
                if ( result.isConfirmed ) {

                    $.ajax( {
                        url: WOOLENTOR_ADMIN.ajaxurl,
                        type: 'POST',
                        data: {
                            nonce   : WOOLENTOR_ADMIN.nonce,
                            section : $section,
                            action  : 'woolentor_module_data',
                            subaction : 'reset_data',
                        },

                        beforeSend: function(){
                            $resetbtn.removeClass('disabled').addClass('updating-message').text( WOOLENTOR_ADMIN.message.reseting );
                        },

                        success: function( response ) {
                            $resetbtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text( WOOLENTOR_ADMIN.message.reseted );
                        },

                        complete: function( response ) {
                            $resetbtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text( WOOLENTOR_ADMIN.message.reseted );
                            window.location.reload();
                        },

                        error: function(errorThrown){
                            console.log(errorThrown);
                        }
            
                    });


                }
            })

        });

        // Save button active
        $('.woolentor-module-setting-popup-content .woolentor-module-setting-data').on( 'click', 'input,select,textarea,.woolentor-admin-number-btn' , function() {
            $(this).closest('.woolentor-module-setting-popup-content').find('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
        });

        $('.woolentor-module-setting-popup-content .woolentor-module-setting-data').on( 'keyup', 'input' , function() {
            $(this).closest('.woolentor-module-setting-popup-content').find('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
        });

        $('.woolentor-module-setting-popup-content .woolentor-module-setting-data').on('change', 'select', function() {
            $(this).closest('.woolentor-module-setting-popup-content').find('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
        });

        /* Close PopUp */
        $('.woolentor-admin-popup-close').on('click', function(){
            const $this = $(this),
                $popup = $this.closest('.woolentor-admin-popup')
            $popup.removeClass('open')
        });

        // Repeater Field
        woolentor_repeater_field();
        
        // Field Dependency
        $(document).ready(function() {
            $('.woolentor-module-setting-data').woolentor_conditions();
        });

    }

    /* Repeater Item control */
    $(document).on('repeater_field_added', function( e, hidden_repeater_elem ){

        $( hidden_repeater_elem ).find('.woolentor-admin-select select[multiple="multiple"]').each(function(){
            const $this = $(this),
                $parent = $this.parent();
            $this.select2({
                dropdownParent: $parent,
                placeholder: "Select template"
            });
        });

        $( hidden_repeater_elem ).find('.wp-color-picker-field').each(function(){
            $(this).wpColorPicker({
                change: function (event, ui) {
                    $(this).closest('.woolentor-module-setting-popup-content').find('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
                },
                clear: function (event) {
                    $(this).closest('.woolentor-module-setting-popup-content').find('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
                }
            });
        });

    });

    function woolentor_repeater_field(){
        
        /* Add field */
        $('.woolentor-repeater-item-add').on('click',function(e){
            e.preventDefault();

            var $this            = $(this),
                $hidden          =  $this.prev('.woolentor-repeater-hidden').clone(true),
                $insert_location =  $this.closest('.woolenor-reapeater-fields-area').find('div.woolentor-option-repeater-item:not(.woolentor-repeater-hidden):last'),
                $itemCount       =  $this.closest('.woolenor-reapeater-fields-area').find('.woolentor-option-repeater-item:not(.woolentor-repeater-hidden)').length;
            
            $hidden.attr('data-id', $itemCount );
            $('.woolentor-option-repeater-item-area .woolentor-option-repeater-item').removeClass('woolentor_active_repeater');
            $hidden.removeClass('woolentor-repeater-hidden').addClass('woolentor_active_repeater');
            $hidden.insertAfter( $insert_location );

            if( $insert_location.length == 0 ){
                $this.closest('.woolenor-reapeater-fields-area').find('.woolentor-option-repeater-item-area').html( $hidden );
            }

            $(document).trigger('repeater_field_added', [ $('.woolentor-module-setting-data .woolentor-option-repeater-item.woolentor_active_repeater') ] );
            $(document).trigger('repeater_field_item_added', [ $('.woolentor-module-setting-data .woolentor-option-repeater-item.woolentor_active_repeater') ] );

            // Field Dependency
            $('.woolentor-option-repeater-item-area').children('.woolentor-option-repeater-item').children('.woolentor-option-repeater-fields').woolentor_conditions();

            // Enable Button
            $('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );

            return false;

        });

        // Change Heading using title field value
        $('.woolentor-repeater-title-field :input').on('keyup change',function( event ){
            $(this).closest('.woolentor-option-repeater-fields').siblings('.woolentor-option-repeater-tools').find('.woolentor-option-repeater-item-title').html( $(this).val() );
        });

        // Hide Show Manage
        $('.woolentor-option-repeater-item').on('click', '.woolentor-option-repeater-tools', function(){
            const $this = $(this),
                $parentItem = $this.parent();
            if( $parentItem.hasClass('woolentor_active_repeater') ) {
                $parentItem.removeClass('woolentor_active_repeater');
            } else {
                $parentItem.addClass('woolentor_active_repeater').siblings().removeClass('woolentor_active_repeater');
                $(document).trigger('repeater_field_added', [ $('.woolentor-module-setting-data .woolentor-option-repeater-item.woolentor_active_repeater') ] );
                $(document).trigger('repeater_field_item_active', [ $parentItem ] );
            }
            $('.woolentor-option-repeater-item-area').children('.woolentor-option-repeater-item').children('.woolentor-option-repeater-fields').woolentor_conditions();
        });

        // Remove Element
        $( '.woolentor-option-repeater-item-remove' ).on('click', function( event ) {
            
            const $this = $(this),
                $parentItem = $this.parents('.woolentor-option-repeater-item'),
                $fieldsArea = $parentItem.parents('.woolenor-reapeater-fields-area');

            $parentItem.remove();
            
            // ID Re-Order
            $('.woolentor-option-repeater-item:not(.woolentor-repeater-hidden)').each( function( index ) {
                $(this).attr('data-id', index );
            });

            $(document).trigger('repeater_field_item_removed', [ $parentItem, $fieldsArea ] );

            // Enable Button
            $('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );

            return false;
        });

        // Initiate sortable Field
        if( $( ".woolentor-option-repeater-item-area" ).length > 0 ){
            $( ".woolentor-option-repeater-item-area" ).sortable({
                axis: 'y',
                connectWith: ".woolentor-option-repeater-item",
                handle: ".woolentor-option-repeater-tools",
                placeholder: "widget-placeholder",
                update: function( event, ui ) {
                    $('.woolentor-admin-module-save').removeClass('disabled').attr('disabled', false).text( WOOLENTOR_ADMIN.message.btntxt );
                    $('.woolentor-option-repeater-item-area').children('.woolentor-option-repeater-item').children('.woolentor-option-repeater-fields').woolentor_conditions();
                }
            });
        }

    }
    woolentor_repeater_field();

    // Extension Tabs
    // woolentor_admin_tabs( $(".woolentor-admin-tabs"), '.woolentor-admin-tab-pane' );

    // Field Dependency
    $(document).ready(function() {
        $('.woolentor-dashboard').children('.woolentor-admin-options').woolentor_conditions();
    });
        
})(jQuery);