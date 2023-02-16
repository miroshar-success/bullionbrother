<?php
namespace Woolentor\Modules\Swatchly\Admin;

/**
 * Product Metabox Class
 */
class Product_Metabox {

    /**
     * Constructor.
     */
    public function __construct() {
        // Add new tab into the WC metabox tab
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'append_product_metabox_tab' ) );

        // Add new panel to the WC metabox
        add_action( 'woocommerce_product_data_panels', array( $this, 'append_product_metabox_panel' ) );

        // Save product metabox data (non ajax)
        add_action( 'woocommerce_process_product_meta_variable', array( $this, 'save_product_metabox') );
    }

    /**
     * Metabox Tab
     */
    public function append_product_metabox_tab($tabs){
        $product_obj  = wc_get_product(get_the_id());
        $product_type = $product_obj->get_type();
        if($product_type != 'variable'){
            return $tabs;
        }

        $new_tab = array(
            'label'    => esc_html__( 'Swatches Settings', 'woolentor' ),
            'target'   => 'swatchly_swatches_product_data',
            'class'    => '',
            'priority' => 80,
        );

        $tabs[] = $new_tab;

        return $tabs;
    }

    /**
     * Metabox panel
     */
    public function append_product_metabox_panel(){
        $product_id = get_the_id();
        $product_obj  = wc_get_product($product_id);
        $product_type = $product_obj->get_type();

        if($product_type != 'variable'){
            return;
        }
        ?>

        <div id="swatchly_swatches_product_data" class="swatchly panel wc-metaboxes-wrapper hidden">
            <div class="woocommerce-message"></div>
            <?php $this->metabox_panel_inner_html( $product_id ); ?>
            <div class="toolbar">
                <button type="button" class="button swatchly_save_swatches button-primary"><?php echo esc_html__('Save Swatches', 'woolentor') ?></button>
                <button type="button" class="button swatchly_reset_to_default"><?php echo esc_html__('Reset to Default', 'woolentor') ?></button>
            </div>
        </div>

        <?php
    }

    /**
     * Save product metabox data (non ajax)
     */
    public function save_product_metabox( $product_id ){
        $product_obj  = wc_get_product($product_id);
        $product_type = $product_obj->get_type();

        if($product_type != 'variable'){
            return;
        }
        
        if(isset( $_REQUEST['swatchly_product_meta'] )){
            $meta_data = map_deep( wp_unslash( $_REQUEST['swatchly_product_meta'] ), 'sanitize_text_field' );
            update_post_meta( $product_id, '_swatchly_product_meta', $meta_data );
        }
    }

    /**
     * Metabox panel inner HTML
     */
    public static function metabox_panel_inner_html( $product_id ){
        $product_obj        = wc_get_product($product_id);
        $product_attributes = $product_obj->get_variation_attributes();
        $meta_data          = get_post_meta($product_id, '_swatchly_product_meta', true);

        $auto_convert_dropdowns_to_label           = isset($meta_data['auto_convert_dropdowns_to_label']) ? $meta_data['auto_convert_dropdowns_to_label'] : '0';
        $auto_convert_dropdowns_to_image           = isset($meta_data['auto_convert_dropdowns_to_image']) ? $meta_data['auto_convert_dropdowns_to_image'] : '0';
        $auto_convert_dropdowns_to_image_condition = isset($meta_data['auto_convert_dropdowns_to_image_condition']) ? $meta_data['auto_convert_dropdowns_to_image_condition'] : '';
        ?>
        <div class="wc-metaboxes">
            <div class="toolbar toolbar-top swatchly_auto_convert_dropdowns_to_image_<?php echo esc_attr($auto_convert_dropdowns_to_image) ?>">

                <p class="form-fieldd">
                    <input type="checkbox" class="checkbox" name="swatchly_product_meta[auto_convert_dropdowns_to_label]" id="swatchly_auto_convert_dropdowns" value="1" <?php checked('1', $auto_convert_dropdowns_to_label) ?>> 
                    <label for="swatchly_auto_convert_dropdowns"><?php esc_html_e('Auto convert Dropdowns to Label Swatch', 'woolentor') ?></label>
                </p>

                <?php if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') ): ?>
                <p class="form-fieldd">
                    <input type="checkbox" class="checkbox" name="swatchly_product_meta[auto_convert_dropdowns_to_image]" id="swatchly_auto_convert_dropdowns_image" value="1" <?php checked('1', $auto_convert_dropdowns_to_image) ?>> 
                    <label for="swatchly_auto_convert_dropdowns_image"><?php esc_html_e('Auto convert Dropdown to Image Swatch', 'woolentor') ?></label>
                </p>
                <?php else: ?>
                <p class="form-fieldd wlswatchly-pro-opacity">
                    <input type="checkbox" class="checkbox" name="swatchly_product_meta[auto_convert_dropdowns_to_image]" id="swatchly_auto_convert_dropdowns_image" value="1" <?php checked('1', $auto_convert_dropdowns_to_image) ?>> 
                    <label for="swatchly_auto_convert_dropdowns_image"><?php esc_html_e('Auto convert Dropdown to Image Swatch', 'woolentor') ?> <span class="wlswatchly-pro">Pro</span> </label>
                </p>
                <?php endif; ?>
                
                <p class="swatchly_condition swatchly_show_if_auto_convert_to_dropdown_image_1">
                    <label for="auto_convert_dropdowns_to_image_condition"><?php esc_html_e('Enable Image Type Attribute For', 'woolentor') ?></label>
                    <select class="" id="auto_convert_dropdowns_to_image_condition" name="swatchly_product_meta[auto_convert_dropdowns_to_image_condition]">
                        <option value="first_attribute" <?php selected('first_attribute', $auto_convert_dropdowns_to_image_condition) ?>><?php echo esc_html__( 'The First attribute', 'woolentor' ); ?></option>
                        <option value="maximum" <?php selected('maximum', $auto_convert_dropdowns_to_image_condition) ?>><?php echo esc_html__('The attribute with Maximum variations count', 'woolentor') ?></option>
                        <option value="minimum" <?php selected('minimum', $auto_convert_dropdowns_to_image_condition) ?>><?php echo esc_html__('The attribute with Minimum variations count', 'woolentor') ?></option>   
                    </select>
                </p>

                <div class="clear"></div>
            </div>
        <?php
        $tooltip_image_size_content = esc_html__('Place the image size name here. WordPress default image sizes are: thumbnail, medium, medium_large, large and full. Custom image size also can be used. Default is: thumbnail', 'woolentor'); 

        foreach( $product_attributes as $taxonomy => $terms ):
            $swatch_types = array('select', 'label', 'color', 'image');
            $tax_terms = $product_attributes[$taxonomy];
            $tax_name  = $taxonomy;

            if ( taxonomy_exists( $taxonomy ) ) {
                $tax_obj = get_taxonomy( $taxonomy );
                $tax_label = $tax_obj->labels->singular_name;
            } else {
                $tax_label = $taxonomy;
            }

            $swatch_type         = isset($meta_data[$tax_name]['swatch_type']) ? $meta_data[$tax_name]['swatch_type'] : '';
            
            $tooltip2            = isset($meta_data[$tax_name]['tooltip']) ? $meta_data[$tax_name]['tooltip'] : '';
            $tooltip_text2       = isset($meta_data[$tax_name]['tooltip_text']) ? $meta_data[$tax_name]['tooltip_text'] : '';
            $tooltip_image_id2   = isset($meta_data[$tax_name]['tooltip_image']) ? $meta_data[$tax_name]['tooltip_image'] : '';
            $tooltip_image_size2 = isset($meta_data[$tax_name]['tooltip_image_size']) ? $meta_data[$tax_name]['tooltip_image_size'] : '';

            $shape_style2        = isset($meta_data[$tax_name]['shape_style']) ? $meta_data[$tax_name]['shape_style'] : '';
            $enable_shape_inset2        = isset($meta_data[$tax_name]['enable_shape_inset']) ? $meta_data[$tax_name]['enable_shape_inset'] : '';
            $shape_inset_size2        = isset($meta_data[$tax_name]['shape_inset_size']) ? $meta_data[$tax_name]['shape_inset_size'] : '';
            ?>
            <div class="wc-metabox swatchly_2 swatchly_type_<?php echo esc_attr($swatch_type); ?> swatchly_tooltip_<?php echo esc_attr($tooltip2) ?> swatchly_shape_inset_<?php echo esc_attr($enable_shape_inset2) ?>">
                <h3>
                    <div class="handlediv" title="Click to toggle" aria-expanded="false"></div>
                    <strong><?php echo esc_html($tax_label) ?></strong>
                    <div class="fr">

                        <!-- Swatch type 2 -->
                        <strong><?php echo esc_html__('Swatch Type', 'woolentor') ?></strong>
                        <select class="swatchly_swatch_type swatchly_2" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][swatch_type]">
                            <option value=""><?php echo esc_html__('Inherit', 'woolentor'); ?></option>
                            <?php
                                foreach($swatch_types as $type){
                                    ?>
                                    <option value="<?php echo esc_attr($type); ?>" <?php selected($type, $swatch_type) ?>><?php echo esc_html(ucwords($type)); ?></option>
                                    <?php
                                }
                            ?>
                        </select>

                    </div>
                </h3>
                <!-- Level 2 -->
                <div class="wc-metabox-content hidden">
                    <table cellpadding="0" cellspacing="0">
                        <tbody>
                            <!-- shape style -->
                            <tr>
                                <td class="label" width="25%">
                                    <label for="swatchly_shape_style"><?php echo esc_html__('Shape Style', 'woolentor') ?></label>
                                </td>
                                <td>
                                    <select class="swatchly_shape_style" id="swatchly_shape_style" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][shape_style]">
                                        <option value=""><?php echo esc_html__('-------', 'woolentor') ?></option>
                                        <option value="squared" <?php selected('squared', $shape_style2) ?>><?php echo esc_html__('Squared', 'woolentor') ?></option>
                                        <option value="rounded" <?php selected('rounded', $shape_style2) ?>><?php echo esc_html__('Rounded', 'woolentor') ?></option>
                                        <option value="circle" <?php selected('circle', $shape_style2) ?>><?php echo esc_html__('Circle', 'woolentor') ?></option>
                                    </select>
                                </td>
                            </tr>

                            <!-- enable_shape_inset -->
                            <tr>
                                <td class="label" width="25%">
                                    <label for="swatchly_enable_shape_inset"><?php echo esc_html__('Enable Shape Inset', 'woolentor') ?></label>
                                </td>
                                <td>
                                    <select class="swatchly_enable_shape_inset" id="swatchly_enable_shape_inset" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][enable_shape_inset]">
                                        <option value=""><?php echo esc_html__('-------', 'woolentor') ?></option>
                                        <option value="enable" <?php selected('enable', $enable_shape_inset2) ?>><?php echo esc_html__('Enable', 'woolentor') ?></option>
                                        <option value="disable" <?php selected('disable', $enable_shape_inset2) ?>><?php echo esc_html__('Disable', 'woolentor') ?></option>
                                    </select>
                                </td>
                            </tr>

                            <!-- tooltip -->
                            <tr>
                                <td class="label" width="25%">
                                    <label for="swatchly_tooltip"><?php echo esc_html__('Swatch Tooltip', 'woolentor') ?></label>
                                </td>
                                <td>
                                    <select class="swatchly_tooltip" id="swatchly_tooltip" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][tooltip]">
                                        <option value=""><?php echo esc_html__('-------', 'woolentor') ?></option>
                                        <option value="text" <?php selected('text', $tooltip2) ?>><?php echo esc_html__('Text', 'woolentor') ?></option>
                                        <option value="image" <?php selected('image', $tooltip2) ?>><?php echo esc_html__('Image', 'woolentor') ?></option>
                                        <option value="disable" <?php selected('disable', $tooltip2) ?>><?php echo esc_html__('Disable', 'woolentor') ?></option>
                                    </select>
                                </td>
                            </tr>

                            <!-- tooltip_text -->
                            <tr class="swatchly_show_if_tooltip_text">
                                <td><label for="swatchly_tooltip_text"><?php echo esc_html__('Tooltip Text', 'woolentor') ?></label></td>
                                <td>
                                    <input type="text" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][tooltip_text]" id="swatchly_tooltip_text" class="short" value="<?php echo esc_attr($tooltip_text2) ?>">
                                </td>
                            </tr>

                            <!-- tooltip_image -->
                            <tr class="swatchly_show_if_tooltip_image">
                                <td><?php echo esc_html__('Tooltip Image', 'woolentor') ?></td>
                                <td>
                                    <div class="swatchly_media_field">
                                        <div class="swatchly_media_preview">
                                            <?php
                                                if( $tooltip_image_id2 ){
                                                    echo '<img src="'. wp_get_attachment_image_url($tooltip_image_id2, 'thumbnail') .'" />';
                                                }
                                            ?>
                                        </div>
                                        <div>
                                            <input type="hidden" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][tooltip_image]" class="swatchly_input" value="<?php echo esc_attr($tooltip_image_id2) ?>" />
                                            <button type="button" class="swatchly_upload_image button"><?php echo esc_html__( 'Upload/Add image', 'woolentor' ); ?></button>
                                            <button type="button" class="swatchly_remove_image button"><?php echo esc_html__( 'Remove image', 'woolentor' ); ?></button>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- tooltip_image_size -->
                            <tr class="swatchly_show_if_tooltip_image">
                                <td><label for="swatchly_tooltip_image_size"><?php echo esc_html__('Tooltip Image Size', 'woolentor') ?></label></td>
                                <td>
                                    <input type="text" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][tooltip_image_size]" id="swatchly_tooltip_image_size" class="short" value="<?php echo esc_attr($tooltip_image_size2) ?>">
                                    <?php  
                                        echo wc_help_tip( $tooltip_image_size_content );
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <?php
                                        foreach($tax_terms as $term_value):
                                            if ( taxonomy_exists( $taxonomy ) ) {
                                                $term       = get_term_by('slug', $term_value, $taxonomy);
                                                $term_id    = $term->term_id;
                                                $term_label = $term->name;
                                            } else {
                                                $term_label = $term_value; // name
                                                $term_id    = $term_value; // name
                                            }

                                            // get saved values
                                            $swatch_type        = isset($meta_data[$tax_name]['terms'][$term_id]['swatch_type']) ? $meta_data[$tax_name]['terms'][$term_id]['swatch_type'] : '';
                                            
                                            $image_id           = isset($meta_data[$tax_name]['terms'][$term_id]['image']) ? $meta_data[$tax_name]['terms'][$term_id]['image'] : '';
                                            $image_size         = isset($meta_data[$tax_name]['terms'][$term_id]['image_size']) ? $meta_data[$tax_name]['terms'][$term_id]['image_size'] : '';
                                            $tooltip            = isset($meta_data[$tax_name]['terms'][$term_id]['tooltip']) ? $meta_data[$tax_name]['terms'][$term_id]['tooltip'] : '';
                                            $tooltip_text       = isset($meta_data[$tax_name]['terms'][$term_id]['tooltip_text']) ? $meta_data[$tax_name]['terms'][$term_id]['tooltip_text'] : '';
                                            $tooltip_image_id   = isset($meta_data[$tax_name]['terms'][$term_id]['tooltip_image']) ? $meta_data[$tax_name]['terms'][$term_id]['tooltip_image'] : '';
                                            $tooltip_image_size = isset($meta_data[$tax_name]['terms'][$term_id]['tooltip_image_size']) ? $meta_data[$tax_name]['terms'][$term_id]['tooltip_image_size'] : '';
                                            
                                            $color              = isset($meta_data[$tax_name]['terms'][$term_id]['color']) ? $meta_data[$tax_name]['terms'][$term_id]['color'] : '';
                                            $enable_multi_color = isset($meta_data[$tax_name]['terms'][$term_id]['enable_multi_color']) ? $meta_data[$tax_name]['terms'][$term_id]['enable_multi_color'] : '';
                                            $color_2            = isset($meta_data[$tax_name]['terms'][$term_id]['color_2']) ? $meta_data[$tax_name]['terms'][$term_id]['color_2'] : '';
                                            ?>
                                    <div class="wc-metabox swatchly_1 swatchly_type_<?php echo esc_attr($swatch_type); ?> swatchly_tooltip_<?php echo esc_attr($tooltip) ?> swatchly_enable_multi_color_<?php echo esc_attr($enable_multi_color) ?>">
                                        <h3>
                                            <strong><?php echo esc_html($term_label) ?></strong>
                                            <div class="fr">

                                                <!-- Swatch type 1 -->
                                                <strong class="swatchly_d_none"><?php echo esc_html__('Swatch Type', 'woolentor') ?></strong>
                                                <select class="swatchly_swatch_type swatchly_1 swatchly_d_none" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][swatch_type]">
                                                    <option value=""><?php echo esc_html__('Inherit', 'woolentor'); ?></option>
                                                    <?php
                                                        $swatch_type  = isset($meta_data[$tax_name]['terms'][$term_id]['swatch_type']) ? $meta_data[$tax_name]['terms'][$term_id]['swatch_type'] : '';
                                                        unset($swatch_types[0]);

                                                        foreach($swatch_types as $type){
                                                            ?>
                                                            <option value="<?php echo esc_attr($type); ?>" <?php selected($type, $swatch_type) ?>><?php echo esc_html(ucwords($type)); ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>

                                            </div>
                                        </h3>

                                        <div class="wc-metabox-content hidden">
                                            <table cellpadding="0" cellspacing="0">
                                                <tbody>

                                                    <!-- tooltip -->
                                                    <tr>
                                                        <td class="label" width="25%">
                                                            <label for="swatchly_tooltip"><?php echo esc_html__('Swatch Tooltip', 'woolentor') ?></label>
                                                        </td>
                                                        <td>
                                                            <select class="swatchly_tooltip" id="swatchly_tooltip" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][tooltip]">
                                                                <option value=""><?php echo esc_html__('Inherit', 'woolentor') ?></option>
                                                                <option value="text" <?php selected('text', $tooltip) ?>><?php echo esc_html__('Text', 'woolentor') ?></option>
                                                                <option value="image" <?php selected('image', $tooltip) ?>><?php echo esc_html__('Image', 'woolentor') ?></option>
                                                                <option value="disable" <?php selected('disable', $tooltip) ?>><?php echo esc_html__('Disable', 'woolentor') ?></option>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <!-- tooltip_text -->
                                                    <tr class="swatchly_show_if_tooltip_text">
                                                        <td><label for="swatchly_tooltip_text"><?php echo esc_html__('Tooltip Text', 'woolentor') ?></label></td>
                                                        <td>
                                                            <input type="text" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][tooltip_text]" id="swatchly_tooltip_text" class="short" value="<?php echo esc_attr($tooltip_text) ?>">
                                                        </td>
                                                    </tr>

                                                    <!-- tooltip_image -->
                                                    <tr class="swatchly_show_if_tooltip_image">
                                                        <td><?php echo esc_html__('Tooltip Image', 'woolentor') ?></td>
                                                        <td>
                                                            <div class="swatchly_media_field">
                                                                <div class="swatchly_media_preview">
                                                                    <?php
                                                                        if( $tooltip_image_id ){
                                                                            echo '<img src="'. wp_get_attachment_image_url($tooltip_image_id, 'thumbnail') .'" />';
                                                                        }
                                                                    ?>
                                                                </div>
                                                                <div>
                                                                    <input type="hidden" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][tooltip_image]" class="swatchly_input" value="<?php echo esc_attr($tooltip_image_id) ?>" />
                                                                    <button type="button" class="swatchly_upload_image button"><?php echo esc_html__( 'Upload/Add image', 'woolentor' ); ?></button>
                                                                    <button type="button" class="swatchly_remove_image button"><?php echo esc_html__( 'Remove image', 'woolentor' ); ?></button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- tooltip_image_size -->
                                                    <tr class="swatchly_show_if_tooltip_image">
                                                        <td><label for="swatchly_tooltip_image_size"><?php echo esc_html__('Tooltip Image Size', 'woolentor') ?></label></td>
                                                        <td>
                                                            <input type="text" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][tooltip_image_size]" id="swatchly_tooltip_image_size" class="short" value="<?php echo esc_attr($tooltip_image_size) ?>">
                                                            <?php  
                                                                echo wc_help_tip( $tooltip_image_size_content );
                                                            ?>
                                                        </td>
                                                    </tr>

                                                    <!-- swatchly_color -->
                                                    <tr class="swatchly_show_if_color">
                                                        <td><?php echo esc_html__('Swatch Color', 'woolentor') ?></td>
                                                        <td><input type="text" class="swatchly_color_picker" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][color]" data-alpha-enabled="true" data-default-color="" value="<?php echo esc_attr($color) ?>" /></td>
                                                    </tr>

                                                    <!-- enable_multi_color -->
                                                    <tr class="swatchly_show_if_color">
                                                        <td><?php echo esc_html__('Enable Multi Color', 'woolentor') ?></td>
                                                        <td><input type="checkbox" class="enable_multi_color" id="enable_multi_color" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][enable_multi_color]" value="1" <?php checked( '1', $enable_multi_color ) ?>/></td>
                                                    </tr>

                                                    <!-- color_2 -->
                                                    <tr class="swatchly_show_if_enable_multi_color_1">
                                                        <td><?php echo esc_html__('Swatch Color 2', 'woolentor') ?></td>
                                                        <td><input type="text" class="swatchly_color_picker" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][color_2]" data-alpha-enabled="true" data-default-color="" value="<?php echo esc_attr($color_2) ?>" /></td>
                                                    </tr>
                                                    
                                                    <!-- swatchly_image -->
                                                    <tr class="swatchly_show_if_image">
                                                        <td><?php echo esc_html__('Swatch Image', 'woolentor') ?></td>
                                                        <td>
                                                            <div class="swatchly_media_field">
                                                                <div class="swatchly_media_preview">
                                                                    <?php
                                                                        if( $image_id ){
                                                                            echo '<img src="'. wp_get_attachment_image_url($image_id, 'thumbnail') .'" />';
                                                                        }
                                                                    ?>
                                                                </div>
                                                                <div>
                                                                    <input type="hidden" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][image]" class="swatchly_input" value="<?php echo esc_attr($image_id) ?>" />
                                                                    <button type="button" class="swatchly_upload_image button"><?php echo esc_html__( 'Upload/Add image', 'woolentor' ); ?></button>
                                                                    <button type="button" class="swatchly_remove_image button"><?php echo esc_html__( 'Remove image', 'woolentor' ); ?></button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- swatchly_image_size -->
                                                    <tr class="swatchly_show_if_image">
                                                        <td><label for="swatchly_image_size"><?php echo esc_html__('Swatch Image Size', 'woolentor') ?></label></td>
                                                        <td>
                                                            <input type="text" name="swatchly_product_meta[<?php echo esc_attr($tax_name) ?>][terms][<?php echo esc_attr($term_id) ?>][image_size]" id="swatchly_image_size" class="short" value="<?php echo esc_attr($image_size) ?>">
                                                            <?php 
                                                                echo wc_help_tip( $tooltip_image_size_content );
                                                            ?>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>                                      
                                    </div>
                                    <?php endforeach; // tax_terms ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php //endif; // is_taxonomy ?>
            <?php endforeach; // product_attributes  ?>
            </div><!-- .wc-metabox -->
        <?php
    }    
}