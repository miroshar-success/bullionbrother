;(function($){
    "use strict";
    
    // Shortcode copy
    var shortcode_copy = {
        init: function() {
            $( '.post-type-woolentor-size-chart .column-shortcode' )
                .on( 'click', 'input', this.copy_link )
                .on( 'aftercopy', 'input', this.copy_success )
                .on( 'aftercopyfailure', 'input', this.copy_fail );
        },

        /**
         * Copy Shortcode
         *
         * @param {Object} evt Copy event.
         */
        copy_link: function( evt ) {

            $( this ).focus();
            $( this ).select();

            wcClearClipboard();
            wcSetClipboard( $( this ).val(), $( this ) );

            evt.preventDefault();
        },

        /**
         * Display a "Copied!" tip when success copying
         */
        copy_success: function() {
            $( this ).tipTip({
                'attribute':  'data-tip',
                'activation': 'focus',
                'fadeIn':     50,
                'fadeOut':    50,
                'delay':      0
            }).trigger( 'focus' );
        },

        /**
         * Displays the copy error message when failure copying.
         */
        copy_fail: function() {
            $( this ).tipTip({
                'attribute':  'data-tip-failed',
                'activation': 'focus',
                'fadeIn':     50,
                'fadeOut':    50,
                'delay':      0
            }).trigger( 'focus' );
        }
    };

    // Edittable
    var edit_table = {
        init: function() {
            // Initialize data table
            $('.post-type-woolentor-size-chart #_chart_table').editTable();
        }
    }

    // Conditional fields
    var conditional_fields = {
        init: function(){
            var _this = this;
            if( $('.post-type-woolentor-size-chart #_apply_on_all_products').is(':checked') ){
                this.hide_fields('.post-type-woolentor-size-chart #_apply_on_all_products');
            }

            $('.post-type-woolentor-size-chart #_apply_on_all_products').on('change', function(){
                if( this.checked ){
                    _this.hide_fields(this);
                } else {
                    _this.show_fields(this);
                }
            });
        },
        hide_fields: function(element){
            $(element).parent().siblings('.wl-categories, .wl-products').hide();
        },
        show_fields: function(element){
            $(element).parent().siblings('.wl-categories, .wl-products').show();
        }
    }

    // Auto check checkboxes
    var auto_check_subcategories = {
        init: function(){
            $('.post-type-woolentor-size-chart #product_catchecklist li input').on('change', function(){
                $(this).closest('li').find('.children input').prop('checked', $(this).prop('checked') );
            });
        }
    }

    $(document).ready(function(){

        edit_table.init();
        shortcode_copy.init();
        conditional_fields.init();
        auto_check_subcategories.init();

    });
    
})(jQuery);