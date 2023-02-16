<?php
/**
 * Template CTP
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<script type="text/template" id="tmpl-woolentorctppopup">
    

    <div class="woolentor-template-edit-popup-area">
        <div class="woolentor-body-overlay"></div>
        <div class="woolentor-template-edit-popup">

            <div class="woolentor-template-edit-header">
                <h3 class="woolentor-template-edit-setting-title">
                    <span class="woolentor-template-edit-setting-image dashicons dashicons-admin-generic"></span>
                    {{{data.heading.head}}}
                </h3>
                <span class="woolentor-template-edit-cross">
                    <svg version="1.1" width="18" height="28" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 496.096 496.096" style="enable-background:new 0 0 496.096 496.096;" xml:space="preserve">
                    <path d="M259.41,247.998L493.754,13.654c3.123-3.124,3.123-8.188,0-11.312c-3.124-3.123-8.188-3.123-11.312,0L248.098,236.686 L13.754,2.342C10.576-0.727,5.512-0.639,2.442,2.539c-2.994,3.1-2.994,8.015,0,11.115l234.344,234.344L2.442,482.342 c-3.178,3.07-3.266,8.134-0.196,11.312s8.134,3.266,11.312,0.196c0.067-0.064,0.132-0.13,0.196-0.196L248.098,259.31 l234.344,234.344c3.178,3.07,8.242,2.982,11.312-0.196c2.995-3.1,2.995-8.016,0-11.116L259.41,247.998z" fill="#ddd" data-original="#000000"/></svg>
                </span>
            </div>

            <div class="woolentor-template-edit-body">
                
                <div class="woolentor-template-edit-field">
                    <label class="woolentor-template-edit-label">{{{ data.heading.fields.name.title }}}</label>
                    <input class="woolentor-template-edit-input" id="woolentor-template-title" type="text" name="woolentor-template-title" placeholder="{{ data.heading.fields.name.placeholder }}">
                </div>

                <div class="woolentor-template-edit-field">
                    <label class="woolentor-template-edit-label">{{{data.heading.fields.type}}}</label>
                    <select class="woolentor-template-edit-input" name="woolentor-template-type" id="woolentor-template-type">
                        <# 
                            _.each( data.templatetype, function( item, key ) {

                                #><option value="{{ key }}">{{{ item.label }}}</option><#

                            } );
                        #>
                    </select>
                </div>

                <# if( data.haselementor === 'yes' ){ #>
                    <div class="woolentor-template-edit-field woolentor-template-editor-field">
                        <label class="woolentor-template-edit-label">{{{data.heading.fields.editor}}}</label>
                        <select class="woolentor-template-edit-input" name="woolentor-template-editor" id="woolentor-template-editor">
                            <# 
                                _.each( data.editor, function( item, key ) {

                                    #><option value="{{ key }}">{{{ item }}}</option><#

                                } );
                            #>
                        </select>
                    </div>
                <# } #>

                <div class="woolentor-template-edit-bottom-box">

                    <div class="woolentor-template-edit-set-default-field woolentor-template-edit-set-checkbox">
                        <input class="woolentor-template-edit-set-checkbox-input" type="checkbox" name="woolentor-template-default" id="woolentor-template-default">
                        <label class="woolentor-template-edit-set-checkbox-lable" for="woolentor-template-default">
                            {{{data.heading.fields.setdefault}}}
                            <span class="woolenor-help-tip">
                                <span class="woolentor-help-tip-trigger"><i class="dashicons dashicons-editor-help"></i></span>
                                <span class="woolenor-help-text">It will override the WooCommerce default template with the template type you selected above.</span>
                            </span>
                        </label>
                    </div>

                    <# if( data.haselementor === 'yes' ){ #>
                    <div class="woolentor-template-edit-set-default-field woolentor-template-edit-set-design">
                        <label>{{{data.heading.sampledata.visibility}}}</label>
                        <span class="woolentor-template-edit-eye-icon dashicons dashicons-visibility"></span>
                    </div>
                    <# } #>

                </div>

                <div class="woolentor-template-edit-demo-design-show-wrap">

                    <# _.each( data.templatelist, function( itemgroup, groupkey ) { #>
                        <div class="woolentor-template-edit-demo-design-show woolentor-template-edit-demo-design-slider demo-{{groupkey}}">
                            <#
                                _.each( itemgroup, function( item, itemkey ) {
                                    var protmp = item.isPro === 1 ? 'tmp-pro' : '';
                                    #>
                                    <label class="woolentor-template-edit-demo-plan woolentor-{{ protmp }}" for="woolentor-template-edit-demo-plan-{{groupkey}}-{{item.id}}">
                                        <# if( item.isPro !== 1 ){ #>
                                        <input type="radio" data-builder="elementor" name="woolentor-template-edit-demo-plan" id="woolentor-template-edit-demo-plan-{{groupkey}}-{{item.id}}" value="{{item.id}}" />
                                        <# } #>
                                        <span class="woolentor-template-edit-demo-content">
                                            <span class="woolentor-template-edit-demo-image">
                                                <img src="{{item.thumbnail}}" alt="{{ item.title }}">
                                            </span>
                                            <span class="woolentor-template-edit-demo-name">{{{data.heading.sampledata.elementor}}}</span>
                                            <# if( item.isPro === 1 ){ #>
                                            <span class="woolentor-template-edit-demo-name tmp-pro">{{{data.heading.sampledata.pro}}}</span>
                                            <# } #>
                                            <!-- <a class="woolentor-template-edit-demo-eye" href="{{ item.url }}" target="_blank"><span class="dashicons dashicons-admin-links"></span></a> -->
                                            <a class="woolentor-template-edit-demo-eye thickbox" href="{{ item.thumbnail }}"><span class="dashicons dashicons-visibility"></span></a>
                                        </span>
                                    </label>
                                    <#
                                });
                                
                            #>
                        </div>
                    <# } ); #>

                </div>

            </div>

            <div class="woolentor-template-edit-footer">

                <div class="woolentor-template-button-group">
                    <div class="woolentor-template-button-item woolentor-editor-elementor {{ data.haselementor === 'yes' ? 'button-show' : '' }}">
                        <button class="woolentor-tmp-elementor">{{{ data.heading.buttons.elementor.label }}}</button>
                    </div>
                    <div class="woolentor-template-button-item woolentor-editor-gutenberg {{ data.haselementor === 'no' ? 'button-show' : '' }}">
                        <button class="woolentor-tmp-gutenberg">{{{ data.heading.buttons.gutenberg.label }}}</button>
                    </div>
                    <div class="woolentor-template-button-item">
                        <button class="woolentor-tmp-save button button-primary disabled" disabled="disabled">{{{ data.heading.buttons.save.label }}}</button>
                    </div>
                </div>

            </div>

        </div>
    </div>

</script>