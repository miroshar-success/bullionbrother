<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $settings_fields = Woolentor_Admin_Fields::instance()->fields()['woolentor_gutenberg_tabs']['settings'];
    $blocks_fields  = Woolentor_Admin_Fields::instance()->fields()['woolentor_gutenberg_tabs']['blocks'];

    $all_fields   = array_merge( $settings_fields, $blocks_fields );
    $element_keys = Woolentor_Admin_Fields_Manager::instance()->get_field_key( $all_fields, 'name' );
?>
<div id="woolentor_gutenberg_tabs" class="woolentor-admin-main-tab-pane">
    <div class="woolentor-admin-main-tab-pane-inner">
        <div class="woolentor-nested-tabs-area">
            <ul class="woolentor-nested-tabs">
                <li><a href="#blocks-settings" class="wlactive"><?php echo esc_html__('Blocks','woolentor');?></a></li>
                <li><a href="#general-settings"><?php echo esc_html__('Settings','woolentor');?></a></li>
            </ul>
        </div>
        
        <form class="woolentor-dashboard" id="woolentor-dashboard-settings-form" action="#" method="post" data-section="woolentor_gutenberg_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>
            <div class="woolentor-admin-settings-area">

                <!-- Blocks Start -->
                <div id="blocks-settings" class="woolentor-admin-nested-tab-pane wlactive">

                    <!-- Header Start -->
                    <div class="woolentor-admin-header woolentor-admin-header-two">
                        <div class="woolentor-admin-header-content">
                            <h6 class="woolentor-admin-header-title"><?php echo esc_html__('ShopLentor Blocks','woolentor');?></h6>
                            <p class="woolentor-admin-header-text"><?php echo esc_html__('You can enable or disable all blocks by one click.','woolentor');?></p>
                        </div>
                        <div class="woolentor-admin-header-actions">
                            <button class="woolentor-admin-btn enable" data-switch-toggle="enable" data-switch-target="element"><?php echo esc_html__('Enable all','woolentor'); ?></button>
                            <button class="woolentor-admin-btn disable" data-switch-toggle="disable" data-switch-target="element"><?php echo esc_html__('Disable all','woolentor'); ?></button>
                        </div>
                    </div>
                    <!-- Header End -->

                    <div class="woolentor-admin-switch-blocks">
                        <?php
                            foreach( $blocks_fields as $key => $field ){
                                Woolentor_Admin_Fields_Manager::instance()->add_field( $field, 'woolentor_gutenberg_tabs' );
                            }
                        ?>
                    </div>

                </div>
                <!-- Blocks End -->

                <!-- Settings Start -->
                <div id="general-settings" class="woolentor-admin-nested-tab-pane">
                    <?php
                        foreach( $settings_fields as $key => $field ){
                            Woolentor_Admin_Fields_Manager::instance()->add_field( $field, 'woolentor_gutenberg_tabs' );
                        }
                    ?>
                </div>
                <!-- Settings End -->

                <div class="woolentor-admin-footer">
                    <button class="woolentor-admin-btn-save woolentor-admin-btn woolentor-admin-btn-primary hover-effect-1" style="margin-left:auto;" disabled="disabled"><?php echo esc_html__('Save Changes','woolentor');?></button>
                </div>

            </div>
        </form>

    </div>
</div>