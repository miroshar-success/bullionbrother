;(function($){
"use strict";

    // Active settigns menu item
    if ( typeof WishSuite.is_settings != "undefined" && WishSuite.is_settings == 1 ){
        $('.toplevel_page_wishsuite .wp-first-item').addClass('current');
    }

    // Save value
    wishsuiteConditionField( WishSuite.option_data['btn_icon_type'], 'custom', '.button_custom_icon' );
    wishsuiteConditionField( WishSuite.option_data['added_btn_icon_type'], 'custom', '.addedbutton_custom_icon' );
    wishsuiteConditionField( WishSuite.option_data['shop_btn_position'], 'use_shortcode', '.depend_shop_btn_position_use_shortcode' );
    wishsuiteConditionField( WishSuite.option_data['shop_btn_position'], 'custom_position', '.depend_shop_btn_position_custom_hook' );
    wishsuiteConditionField( WishSuite.option_data['product_btn_position'], 'use_shortcode', '.depend_product_btn_position_use_shortcode' );
    wishsuiteConditionField( WishSuite.option_data['product_btn_position'], 'custom_position', '.depend_product_btn_position_custom_hook' );
    wishsuiteConditionField( WishSuite.option_data['button_style'], 'custom', '.button_custom_style' );
    wishsuiteConditionField( WishSuite.option_data['table_style'], 'custom', '.table_custom_style' );
    wishsuiteConditionField( WishSuite.option_data['enable_social_share'], 'on', '.depend_social_share_enable' );
    wishsuiteConditionField( WishSuite.option_data['enable_login_limit'], 'on', '.depend_user_login_enable' );

    // After Select field change Condition Field
    wishsuiteChangeField( '.button_icon_type select', '.button_custom_icon', 'custom' );
    wishsuiteChangeField( '.addedbutton_icon_type select', '.addedbutton_custom_icon', 'custom' );
    wishsuiteChangeField( '.shop_btn_position select', '.depend_shop_btn_position_use_shortcode', 'use_shortcode' );
    wishsuiteChangeField( '.shop_btn_position select', '.depend_shop_btn_position_custom_hook', 'custom_position' );
    wishsuiteChangeField( '.product_btn_position select', '.depend_product_btn_position_use_shortcode', 'use_shortcode' );
    wishsuiteChangeField( '.product_btn_position select', '.depend_product_btn_position_custom_hook', 'custom_position' );
    wishsuiteChangeField( '.button_style select', '.button_custom_style', 'custom' );
    wishsuiteChangeField( '.table_style select', '.table_custom_style', 'custom' );
    wishsuiteChangeField( '.enable_social_share .checkbox', '.depend_social_share_enable', 'on', 'radio' );
    wishsuiteChangeField( '.enable_login_limit .checkbox', '.depend_user_login_enable', 'on', 'radio' );

    function wishsuiteChangeField( filedselector, selector, condition_value, fieldtype = 'select' ){
        $(filedselector).on('change',function(){
            var change_value = '';

            if( fieldtype === 'radio' ){
                if( $(this).is(":checked") ){
                    change_value = $(this).val();
                }
            }else{
                change_value = $(this).val();
            }

            wishsuiteConditionField( change_value, condition_value, selector );
        });
    }

    // Hide || Show
    function wishsuiteConditionField( value, condition_value, selector ){
        if( value === condition_value ){
            $(selector).show();
        }else{
            $(selector).hide();
        }
    }

})(jQuery);