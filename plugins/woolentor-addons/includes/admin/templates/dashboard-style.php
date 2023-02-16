<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $element_fields = Woolentor_Admin_Fields::instance()->fields()['woolentor_style_tabs'];
    $element_keys   = Woolentor_Admin_Fields_Manager::instance()->get_field_key( $element_fields, 'name' );

?>
<div id="woolentor_style_tabs" class="woolentor-admin-main-tab-pane">
    <div class="woolentor-admin-main-tab-pane-inner">
        <form class="woolentor-dashboard" id="woolentor-dashboard-style-form" action="#" method="post" data-section="woolentor_style_tabs" data-fields='<?php echo wp_json_encode( $element_keys ); ?>'>
            <div class="woolentor-admin-options">

                <?php
                    foreach( $element_fields as $key => $field ){
                        Woolentor_Admin_Fields_Manager::instance()->add_field( $field, 'woolentor_style_tabs' );
                    }
                ?>

                <div class="woolentor-admin-option-heading">
                    <h4 class="woolentor-admin-option-heading-title"><?php echo esc_html__('Helping Screenshot','woolentor');?></h4>
                </div>
                <div class="woolentor-admin-option">
                    <img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>includes/admin/assets/images/helping-screenshot.png" alt="<?php echo esc_attr__('Helping Screenshot','woolentor'); ?>">
                </div>
                <div class="woolentor-admin-option woolentor-sticky-condition">
                    <button class="woolentor-admin-btn-save woolentor-admin-btn woolentor-admin-btn-primary hover-effect-1" style="margin-left:auto;" disabled="disabled"><?php echo esc_html__('Save Changes','woolentor');?></button>
                </div>
            </div>
        </form>
    </div>
</div>