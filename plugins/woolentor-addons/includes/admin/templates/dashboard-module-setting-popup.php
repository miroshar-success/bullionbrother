<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<script type="text/template" id="tmpl-woolentormodule">

    <div class="woolentor-module-setting-popup">
        <div id="woolentor-admin-pro-popup" class="woolentor-admin-popup open">
            <div class="woolentor-module-setting-popup-content woolentor-admin-popup-inner">
                <button class="woolentor-admin-popup-close">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor"></path>
                    </svg>
                </button>
                <form class="woolentor-module-setting-data" id="woolentor-module-setting-form" action="#" method="post" data-section="{{data.section}}" data-fields="{{data.fileds}}">
                    {{{ data.content }}}
                    <div class="woolentor-admin-footer {{ data.section !== 'woolentor_others_tabs' ? 'has-reset' : '' }}">
                        <# if( data.section != 'woolentor_others_tabs' ){ #>
                            <button class="woolentor-admin-module-reset woolentor-admin-btn woolentor-admin-btn-primary hover-effect-1"><?php echo esc_html__('Reset To Default','woolentor');?></button>
                        <# } #>
                        <button class="woolentor-admin-module-save woolentor-admin-btn woolentor-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('Save Changes','woolentor');?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</script>