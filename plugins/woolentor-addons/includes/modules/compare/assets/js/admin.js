;(function($){
"use strict";

	var EverCompareAdmin = {

		/**
		 * [init]
		 * @return @return {[void]} Initial Function
		 */
		init: function(){
			this.MenuActive();

			/**
             * For save value
             */
            this.HideShowField( '.depend_shop_btn_position_use_shortcode', evercompare.option_data['shop_btn_position'], 'use_shortcode' );
            this.HideShowField( '.depend_shop_btn_position_custom_hook', evercompare.option_data['shop_btn_position'], 'custom_position' );

            this.HideShowField( '.depend_product_btn_position_use_shortcode', evercompare.option_data['product_btn_position'], 'use_shortcode' );
            this.HideShowField( '.depend_product_btn_position_custom_hook', evercompare.option_data['product_btn_position'], 'custom_position' );

            this.HideShowField( '.depend_button_icon_type_custom', evercompare.option_data['button_icon_type'], 'custom' );
            this.HideShowField( '.depend_added_button_icon_type_custom', evercompare.option_data['added_button_icon_type'], 'custom' );

            this.HideShowField( '.depend_enable_shareable_link', evercompare.option_data['enable_shareable_link'], 'on' );

            this.HideShowField( '.depend_button_custom_style', evercompare.option_data['button_style'], 'custom' );
            this.HideShowField( '.depend_table_custom_style', evercompare.option_data['table_style'], 'custom' );

            /**
             * After Change
             */
            this.ConditionField( '.shop_btn_position select', '.depend_shop_btn_position_use_shortcode', 'use_shortcode' );
            this.ConditionField( '.shop_btn_position select', '.depend_shop_btn_position_custom_hook', 'custom_position' );

            this.ConditionField( '.product_btn_position select', '.depend_product_btn_position_use_shortcode', 'use_shortcode' );
            this.ConditionField( '.product_btn_position select', '.depend_product_btn_position_custom_hook', 'custom_position' );

            this.ConditionField( '.button_icon_type select', '.depend_button_icon_type_custom', 'custom' );
            this.ConditionField( '.added_button_icon_type select', '.depend_added_button_icon_type_custom', 'custom' );

            this.ConditionField( '.enable_shareable_link .checkbox', '.depend_enable_shareable_link', 'on', 'radio' );

            this.ConditionField( '.button_style select', '.depend_button_custom_style', 'custom' );
            this.ConditionField( '.table_style select', '.depend_table_custom_style', 'custom' );

		},

		/**
         * [MenuActive] Active first menu item
         */
        MenuActive: function(){
            if ( typeof evercompare.is_settings != "undefined" && evercompare.is_settings == 1 ){
		        $('.toplevel_page_evercompare .wp-first-item').addClass('current');
		    }
        },

        /**
         * [ConditionField]
         * @param {[String]} controller
         * @param {[String]} field
         * @param {[String]} condition_value
         * @param {String} fieldtype
         */
        ConditionField: function( controller, field, condition_value, fieldtype = 'select' ){
            $( controller ).on('change',function(){
                var change_value = '';
                if( fieldtype === 'radio' ){
                    if( $(this).is(":checked") ){
                        change_value = $(this).val();
                    }
                }else{
                    change_value = $(this).val();
                }
                EverCompareAdmin.HideShowField( field, change_value, condition_value );
            });

        },

        /**
         * [HideShowField]
         * @param {[String]} field
         * @param {[String]} current_value
         * @param {[String]} condition_value
         */
        HideShowField: function( field, current_value, condition_value ){
            if( current_value === condition_value ){
                $( field ).show();
            }else{
                $( field ).hide();
            }
        },

	};
    
    $( document ).ready( function() {
        EverCompareAdmin.init();
    });

})(jQuery);