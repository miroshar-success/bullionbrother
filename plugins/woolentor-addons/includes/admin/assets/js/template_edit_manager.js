;(function($){
    "use strict";
        
    var woolentorTemplateAdmin = {

        instance: [],
        templateId: 0,
        templateType:[
            'shop',
            'archive',
            'single',
            'cart',
            'emptycart',
            'checkout',
            'myaccount',
            'myaccountlogin',
            'dashboard',
            'orders',
            'downloads',
            'edit-address',
            'edit-account',
            'lost-password',
            'reset-password',
            'thankyou'
        ],

        init: function() {
            this.renderPopup();
            this.slickSlider();

            $('#woolentor-template-type').on('change', function() {
                var selectedEditor = $('#woolentor-template-editor').val() ? $('#woolentor-template-editor').val() : 'gutenberg';
                if( woolentorTemplateAdmin.templateType.indexOf(this.value) == -1 ){
                    $('#woolentor-template-editor').find('option[value="gutenberg"]').attr('disabled',true);
                    $('#woolentor-template-editor').find('option[value="elementor"]').prop("selected",true);
                    selectedEditor = 'elementor';
                    woolentorTemplateAdmin.manageEditButton( selectedEditor );
                }else{
                    woolentorTemplateAdmin.manageEditButton( selectedEditor );
                    $('#woolentor-template-editor').find('option[value="gutenberg"]').removeAttr('disabled');
                }
                woolentorTemplateAdmin.showSampleDemoTypeWise( this.value, selectedEditor );
            });

            $('#woolentor-template-editor').on('change', function() {
                var selectedType = $('#woolentor-template-type').val();
                woolentorTemplateAdmin.manageEditButton( this.value );
                woolentorTemplateAdmin.showSampleDemoTypeWise( selectedType, this.value );
            });

            $( 'body.post-type-woolentor-template #wpcontent' )
                .on( 'click.woolentorTemplateAdmin', '.page-title-action, .row-title, .row-actions .edit > a', this.openPopup );
            
            $( document )
                .on( 'click.woolentorTemplateAdmin', '.woolentor-body-overlay,.woolentor-template-edit-cross', this.closePopup )
                .on( 'click.woolentorTemplateAdmin', ".woolentor-tmp-save:not('.disabled')", this.dataStore )
                .on( 'click.woolentorTemplateAdmin', '.woolentor-tmp-gutenberg,.woolentor-tmp-elementor', this.redirectEditPage )
                .on( 'click.woolentorTemplateAdmin', '.woolentor-template-edit-set-design', this.showSampleDemo )
                .on( 'click.woolentorTemplateAdmin', '.woolentor-template-edit-body input,.woolentor-template-edit-body select', this.activeSaveButton )
                .on( 'keyup.woolentorTemplateAdmin', '.woolentor-template-edit-body input', this.activeSaveButton )
                .on( 'click.woolentorTemplateAdmin', '.woolentor-default-tmp-status-switch input', this.setDefaultTmpStatus )
                .on( 'click.woolentorTemplateAdmin', '.woolentor-template-importer button', this.templateImporter );
        },

        // Render Popup HTML
        renderPopup: function( event ){
            var popupTmp = wp.template( 'woolentorctppopup' ),
                content = null;

            content = popupTmp( {
                haselementor: WLTMCPT.haselementor,
                templatetype: WLTMCPT.templatetype,
                editor:       WLTMCPT.editor,
                heading:      WLTMCPT.labels,
                templatelist: WLTMCPT.templatelist,
            } );

            $( 'body' ).append( content );

        },

        // Slick Slider activation
        slickSlider: function(){
            $('.woolentor-template-edit-demo-design-slider').slick({
                infinite: false,
                slidesToShow: 3,
                slidesToScroll: 3,
                prevArrow: '<button type="button" class="woolentor-template-edit-demo-prev"> <i class="dashicons dashicons-arrow-left-alt2"></i> </button>',
                nextArrow: '<button type="button" class="woolentor-template-edit-demo-next"><i class=" dashicons dashicons-arrow-right-alt2"></i></button>',
            });
        },

        // Hide/Show Sample demo
        showSampleDemo: function( event ){
            $(".woolentor-template-edit-set-design").toggleClass("active");
            $(".woolentor-template-edit-demo-design-show-wrap").slideToggle().find('.slick-slider').slick('refresh');
        },

        // Show Demo template
        showSampleDemoTypeWise: function( tmpType = 'single', tmpEditor = 'elementor' ){

            // Sample design showing button hide/show
            if( $('.woolentor-template-edit-demo-design-show-wrap').find('.woolentor-template-edit-demo-design-slider.demo-'+tmpType).length == 0 ){
                $('.woolentor-template-edit-set-design').hide();
            }else{
                $('.woolentor-template-edit-set-design').show();
            }

            if( tmpEditor === 'gutenberg'){
                $('.woolentor-template-edit-set-design').hide();
                $('.woolentor-template-edit-demo-design-slider.demo-'+tmpType).removeClass('demo-show').slick('refresh');
            }else{
                $('.woolentor-template-edit-demo-design-slider').removeClass('demo-show');
                $('.woolentor-template-edit-demo-design-slider.demo-'+tmpType).addClass('demo-show').slick('refresh');
            }
            
        },

        // Manage Edit button based on Editor
        manageEditButton : function ( editor ){
            $('.woolentor-template-button-item[class*="woolentor-editor-"]').removeClass('button-show');
            $('.woolentor-template-button-item.woolentor-editor-'+editor).addClass('button-show');
        },

        // Active Save Button
        activeSaveButton: function( event ){
            $(".woolentor-tmp-save").removeClass("disabled");
            $(".woolentor-tmp-save").removeAttr("disabled");
            $(".woolentor-tmp-save").text(WLTMCPT.labels.buttons.save.label);
        },

        // Enable/Disable Editor Buttons
        enableDisableEditorButton: function( enable = 'no' ){
            if( enable === 'yes' ){
                $('.woolentor-template-edit-popup').find('.woolentor-tmp-gutenberg').removeClass( 'button disabled' ).removeAttr('disabled');
                $('.woolentor-template-edit-popup').find('.woolentor-tmp-elementor').removeClass( 'button disabled').removeAttr('disabled');
            }else{
                $('.woolentor-template-edit-popup').find('.woolentor-tmp-gutenberg').addClass( 'button disabled' ).attr('disabled','disabled');
                $('.woolentor-template-edit-popup').find('.woolentor-tmp-elementor').addClass( 'button disabled').attr('disabled','disabled');
            }
        },

        // Redirect Edit Page
        redirectEditPage: function( event ){
            event.preventDefault();

            var $this   = $( this ),
                link = $this.data( 'link' ) ? $this.data( 'link' ) : '',
                tmpId = $this.data('tmpid') ? $this.data('tmpid') : '';
            
            if( tmpId != '' && !$('body.post-type-woolentor-template').hasClass('woolentor-tmp-new-add') ){ woolentorTemplateAdmin.dataStore( event ); }

            window.location.replace( WLTMCPT.adminURL + link );
            
        },

        // Edit PopUp
        openPopup: function( event ) {
            event.preventDefault();

            var rowId = $(this).closest('tr').attr('id'),
                tmpId = null,
                editLink = null,
                elementorEditlink = null;

            if ( rowId ) {
                tmpId = rowId.replace( 'post-', '' );
                editLink = 'post.php?post='+tmpId+'&action=edit';
                elementorEditlink = 'post.php?post='+tmpId+'&action=elementor';
                // Hide Editor Selector Field
                $('.woolentor-template-editor-field').hide();
                woolentorTemplateAdmin.enableDisableEditorButton();
            }
            $('.woolentor-tmp-save').attr( 'data-tmpid', tmpId );
            $('.woolentor-tmp-gutenberg').attr( { 'data-link': editLink, 'data-tmpid': tmpId } );
            $('.woolentor-tmp-elementor').attr( { 'data-link': elementorEditlink, 'data-tmpid': tmpId } );

            if( tmpId ){
                $.ajax({
                    url: WLTMCPT.ajaxurl,
                    data: {
                        'action': 'woolentor_get_template',
                        'nonce' : WLTMCPT.nonce,
                        'tmpId' : tmpId,
                    },
                    type: 'POST',

                    beforeSend: function(){
                        $('.woolentor-template-edit-body').addClass('woolentor-template-loading');
                    },

                    success:function( response ) {

                        if( document.querySelector("#woolentor-template-type option[value='"+response.data.tmpType+"']") ){
                            document.querySelector("#woolentor-template-type option[value='"+response.data.tmpType+"']").selected = "true";
                        }
                        $("#woolentor-template-type").attr("disabled","true");

                        $('#woolentor-template-title').attr( 'value', response.data.tmpTitle );
                        if( tmpId == response.data.setDefault ){
                            $('#woolentor-template-default').prop('checked', true);
                        }else{
                            $('#woolentor-template-default').prop('checked', false);
                        }

                        let tmpBuilder = 'gutenberg';
                        if( WLTMCPT.haselementor === 'yes' ){
                            tmpBuilder = response.data.tmpEditor;
                        }

                        // Enable edit button
                        woolentorTemplateAdmin.manageEditButton( tmpBuilder );
                        woolentorTemplateAdmin.enableDisableEditorButton( 'yes' );

                        // Show Demo Design
                        woolentorTemplateAdmin.showSampleDemoTypeWise( response.data.tmpType, tmpBuilder );

                    },

                    complete:function( response ){
                        $('.woolentor-template-edit-body').removeClass('woolentor-template-loading');
                    },

                    error: function( errorThrown ){
                        console.log( errorThrown );
                    }

                });

                // Remove class if template eidit mode
                $('body.post-type-woolentor-template').removeClass('woolentor-tmp-new-add');

            }else{
                $('#woolentor-template-title').attr( 'value', '' );
                $("#woolentor-template-type").removeAttr('disabled');
                document.querySelector("#woolentor-template-type option[value='single']").selected = "true";
                $('#woolentor-template-default').prop('checked', false);

                // Disabled Button
                woolentorTemplateAdmin.enableDisableEditorButton();

                // Show Demo Design
                woolentorTemplateAdmin.showSampleDemoTypeWise('single');

                // Show Editor Selector Field
                $('.woolentor-template-editor-field').show();

            }

            $('body.post-type-woolentor-template').addClass('open-editor');

        },

        // Close Popup
        closePopup: function( event ) {
            $('body.post-type-woolentor-template').removeClass('open-editor');

            // Page refresh for new add
            if( $('body.post-type-woolentor-template').hasClass('woolentor-tmp-new-add') ){
                window.location.reload();
            }

        },

        // Data Store
        dataStore: function( event ) {
            var $this = $( this ),
                tmpId = event.target.dataset.tmpid ? event.target.dataset.tmpid : '',
                title = $('#woolentor-template-title').val(),
                setdefault = $('#woolentor-template-default').is(":checked") ? 'yes' : 'no',
                tmpType = $('#woolentor-template-type').val(),
                tmpEditor = $('#woolentor-template-editor').val(),
                sampleDemoId = '',
                sampleDemoBuilder = '';
            
            if( $('.woolentor-template-edit-demo-plan input[name="woolentor-template-edit-demo-plan"]:visible').is(":checked") ){
                sampleDemoId = $('.woolentor-template-edit-demo-plan input[name="woolentor-template-edit-demo-plan"]:checked').val();
                sampleDemoBuilder = $('.woolentor-template-edit-demo-plan input[name="woolentor-template-edit-demo-plan"]:checked').data('builder');
            }

            $.ajax({
                url: WLTMCPT.ajaxurl,
                data: {
                    'action': 'woolentor_template_store',
                    'nonce' : WLTMCPT.nonce,
                    'tmpId' : tmpId,
                    'title' : title,
                    'tmpType' : tmpType,
                    'tmpEditor' : tmpEditor,
                    'setDefault' : setdefault,
                    'sampleTmpID' : sampleDemoId,
                    'sampleTmpBuilder' : sampleDemoBuilder,
                },
                type: 'POST',

                beforeSend: function(){
                    $('body.post-type-woolentor-template').addClass('wlloading');
                    $this.text(WLTMCPT.labels.buttons.save.saving);
                    $this.addClass('updating-message');
                },

                success: function(data) {
                    if( tmpId == '' ){
                        
                        if ( data.data.id ) {
                            var editLink = 'post.php?post='+data.data.id+'&action=edit',
                                elementorEditlink = 'post.php?post='+data.data.id+'&action=elementor';
                        }
                        $('.woolentor-tmp-save').attr( 'data-tmpid', data.data.id );
                        $('.woolentor-tmp-gutenberg').attr( { 'data-link': editLink, 'data-tmpid': data.data.id } );
                        $('.woolentor-tmp-elementor').attr( { 'data-link': elementorEditlink, 'data-tmpid': data.data.id } );
                        
                        // Enable edit Button
                        woolentorTemplateAdmin.enableDisableEditorButton('yes');

                        // If insert new then add class to body
                        $('body.post-type-woolentor-template').addClass('woolentor-tmp-new-add');

                    }else{
                        $( '#post-'+tmpId ).find('.row-title').text( title );
                        $( '#post-'+tmpId ).find('.column-tmptype').text( WLTMCPT.templatetype[tmpType].label );

                        var $set_status = $( '#post-'+tmpId+' .woolentor-default-tmp-status-switch' ).find('.woolentor-status-'+tmpType);

                        if( setdefault == 'yes' ){
                            $('.type-woolentor-template:not(#post-'+tmpId+') .column-setdefault').find( '.woolentor-status-'+tmpType ).prop('checked',false);
                            $set_status.prop("checked", true);
                        }else{
                            $set_status.prop("checked", false);
                        }

                    }
                },

                complete: function(data){
                    $('body.post-type-woolentor-template').removeClass('wlloading');
                    $this.removeClass('updating-message');
                    $this.addClass('disabled');
                    $this.attr('disabled','disabled');
                    $this.text(WLTMCPT.labels.buttons.save.saved);
                },

                error: function( errorThrown ){
                    console.log( errorThrown );
                }

            });

        },

        // Set Default Template From Switcher
        setDefaultTmpStatus: function( event ){
            
            var $this         = $(this),
                tmpId         = $this.is(":checked") ? $this.val(): '0',
                checkboxClass = $this.attr('class'),
                tmpType       = checkboxClass.replace( 'woolentor-status-', '' );

            $.ajax({
                url: WLTMCPT.ajaxurl,
                type: 'POST',
                data: {
                    'action'  : 'woolentor_manage_default_template',
                    'nonce'   : WLTMCPT.nonce,
                    'tmpId'   : tmpId,
                    'tmpType' : tmpType,
                },

                beforeSend: function(){
                    $this.closest('label').addClass('woolentor-loading');
                },

                success:function( response ) {
                    var $set_status = $( '#post-'+tmpId+' .woolentor-default-tmp-status-switch' ).find('.woolentor-status-'+tmpType);
                    if( response.data.id != '0' ){
                        $('.type-woolentor-template:not(#post-'+tmpId+') .column-setdefault').find( '.woolentor-status-'+tmpType ).prop('checked',false);
                        $set_status.prop("checked", true);
                    }else{
                        $set_status.prop("checked", false);
                    }
                },

                complete:function( response ){
                    $this.closest('label').removeClass('woolentor-loading');
                },

                error: function( errorThrown ){
                    console.log( errorThrown );
                }

            });

        },

        // Template Importer
        templateImporter : function( event ){
            event.preventDefault();

            var $importBtn = $(this),
                $button_text = $importBtn.find('.woolentor-template-importer-btn-text');

            Swal.fire({
                title: WLTMCPT.labels.importer.message.title,
                text: WLTMCPT.labels.importer.message.message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: WLTMCPT.labels.importer.message.yesbtn,
                cancelButtonText: WLTMCPT.labels.importer.message.cancelbtn,
            }).then((result) => {
                if ( result.isConfirmed ) {
                    
                    $.ajax( {
                        url: WLTMCPT.ajaxurl,
                        type: 'POST',
                        data: {
                            nonce   : WLTMCPT.nonce,
                            action  : 'woolentor_import_template',
                        },

                        beforeSend: function(){
                            $importBtn.removeClass('update-comlete').addClass('updating-message');
                            $button_text.text( WLTMCPT.labels.importer.button.importing );
                        },

                        success: function( response ) {
                            $importBtn.removeClass('updating-message').addClass('update-comlete');
                            $button_text.text( WLTMCPT.labels.importer.button.imported );
                        },

                        complete: function( response ) {
                            $importBtn.removeClass('updating-message').addClass('update-comlete');
                            $button_text.text( WLTMCPT.labels.importer.button.imported );
                            window.location.reload();
                        },

                        error: function(errorThrown){
                            console.log(errorThrown);
                        }
            
                    });


                }
            })

        }


    };

    woolentorTemplateAdmin.init();
        
            
})(jQuery);