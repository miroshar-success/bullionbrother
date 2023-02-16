<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<script type="text/template" id="tmpl-woolentor_template_import">
    <div id="htwpt-popup-area" class="woolentor-template-import-popup">
        <div class="woolentor-template-import-header">
            <span class="woolentor-template-title">{{{data.title}}}</span>
            <button class="woolentor-template-popup-close">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor"></path>
                </svg>
            </button>
        </div>
        <div class="httemplate-popupcontent">
            <div class='htwptspinner'></div>
            <div class="htwptmessage" style="display: none;">
                <p>{{{data.message}}}</p>
                <span class="htwpt-edit"></span>
            </div>
            <div class="htwptpopupcontent">
                <ul class="htwptemplata-requiredplugins">{{{data.requiredplugins}}}</ul>
                <p><?php esc_html_e( 'Import template to your Library', 'woolentor' ); ?></p>
                <span class="htwptimport-button-dynamic">{{{data.temImportButton}}}</span>
                <div class="htpageimportarea">
                    <p><?php esc_html_e( 'Create a new page from this template', 'woolentor' ); ?></p>
                    <input id="htwptpagetitle" type="text" name="htwptpagetitle" placeholder="<?php echo esc_attr_x( 'Enter a Page Name', 'placeholder', 'woolentor' ); ?>">
                    <span class="htwptimport-button-dynamic-page">{{{data.pageImportButton}}}</span>
                </div>

            </div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-woolentor_template_preview">
    <div id="woolentor-popup-preview" class="woolentor-popup-preview">
        <div class="woolentor-template-import-header">
            <span class="woolentor-template-title">{{{data.title}}}</span>
            <button class="woolentor-template-preview-close">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor"></path>
                </svg>
            </button>
        </div>
        <div class="woolentor-popup-preview-inner">
            <img src="{{data.thumbnail}}" alt="{{data.title}}" style="width:100%;"/>
        </div>
    </div>
</script>