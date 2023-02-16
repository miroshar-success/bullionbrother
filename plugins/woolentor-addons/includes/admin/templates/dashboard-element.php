<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = Woolentor_Admin_Fields::instance()->fields()['woolentor_elements_tabs'];
    // $element_keys = array_column( $element_fields, 'name' );
    $element_keys   = Woolentor_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="woolentor_elements_tabs" class="woolentor-admin-main-tab-pane">
    <div class="woolentor-admin-main-tab-pane-inner">
        
        <!-- Header Start -->
        <div class="woolentor-admin-header">
            <div class="woolentor-admin-header-content">
                <h6 class="woolentor-admin-header-title"><?php echo esc_html__('ShopLentor Element','woolentor');?></h6>
                <p class="woolentor-admin-header-text"><?php echo esc_html__('You can enable or disable all options by one click.','woolentor');?></p>
            </div>
            <div class="woolentor-admin-header-actions">
                <button class="woolentor-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all','woolentor'); ?></button>
                <button class="woolentor-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','woolentor'); ?></button>
            </div>
        </div>
        <!-- Header End -->

        <form class="woolentor-dashboard" id="woolentor-dashboard-element-form" action="#" method="post" data-section="woolentor_elements_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>

            <!-- Elements Start -->
            <div class="woolentor-admin-switch-blocks">
                <?php
                    foreach( $element_fields as $key => $field ){
                        Woolentor_Admin_Fields_Manager::instance()->add_field( $field, 'woolentor_elements_tabs' );
                    }
                ?>
            </div>
            <!-- Elements End -->

            <!-- Footer Start -->
            <div class="woolentor-admin-footer">
                <button class="woolentor-admin-btn-save woolentor-admin-btn woolentor-admin-btn-primary hover-effect-1" disabled="disabled"><?php echo esc_html__('Save Changes','woolentor');?></button>
            </div>
            <!-- Footer End -->

        </form>

    </div>
</div>