<?php
//Add default settings for popup
require_once(ABSPATH .'wp-includes/pluggable.php');
function wpgis_plugin_settings() {
	if(!get_option('wpgis_options')) {
		add_option('wpgis_options', wpgis_defaults());
	}
}

function wpgis_defaults()
{
	$options = $_POST;
	    $update_val = array(
    	'slider_layout'   => 'horizontal',
		'slidetoshow'     => '1',
		'slidetoscroll'   => '1',
		'sliderautoplay'  => '1',
    	'arrowdisable'    => '0',
		'arrowinfinite'   => '0',
		'arrowcolor'      => '#ffffff',
		'arrowbgcolor'    => '#000000',
		'show_lightbox'   => '1',
		'show_zoom'       => '1',
    );
	return $update_val;
}

//hook to add admin menu
add_action('admin_menu','wpgis_plugin_admin_menu');
function wpgis_plugin_admin_menu()
{
	add_menu_page('wpgis Settings','WPGIS Settings','administrator','wpgis','wpgis_backend_menu','dashicons-admin-settings',59);
}

// update the wpgis options
if(isset($_POST['wpgis_update']))
{
	$nonce = $_POST['ws250nonce'];
	if (!wp_verify_nonce($nonce, 'ws250updater') ) 
	{
		return;
	}
	update_option('wpgis_options', wpgis_updates());
}

function wpgis_updates()
{
	$nonce = $_POST['ws250nonce'];
	if (!wp_verify_nonce($nonce, 'ws250updater') ) 
	{
		return;
	}
	
	$options = $_POST;
	$update_val = array(
		'slider_layout' => sanitize_text_field($options['wpgis_slider_layout']),
		'slidetoshow' =>  sanitize_text_field($options['wpgis_slidetoshow']),
		'slidetoscroll' => sanitize_text_field($options['wpgis_slidetoscroll']),
		'sliderautoplay' => sanitize_text_field($options['wpgis_sliderautoplay']),
		'arrowdisable' => sanitize_text_field($options['wpgis_arrowdisable']),
		'arrowinfinite' => sanitize_text_field($options['wpgis_arrowinfinite']),
		'arrowcolor' => sanitize_text_field($options['wpgis_arrowcolor']),
		'arrowbgcolor' => sanitize_text_field($options['wpgis_arrowbgcolor']),
		'show_lightbox' => sanitize_text_field($options['wpgis_show_lightbox']),
		'show_zoom' => sanitize_text_field($options['wpgis_show_zoom']),
    );
	return $update_val;
}


// Setting Html
function wpgis_backend_menu()
{
$options = get_option('wpgis_options'); 
//print_r($options);
?>
<style>
.wpbody
{
	background-color:#fff;
	padding:20px;
}
.form-table span
{
	color:#999;
	font-size:12px;
}
.wpigs-logo h2
{
	margin-top:0;
}
.wpbody.wpgis
{
	margin-top:20px;
}
.wp-core-ui .wpadmin-left .notice.is-dismissible
{
	margin-left:0 !important;
	margin-bottom:20px !important;
}
.wpgis_submit_btn
{
	padding-left:0 !important;
}
</style>
<div class="mainwrapper" id="wpgis_admin">
     <div class="wpbody wpgis">   
        <div class="wpadmin-left">
        	 <?php if(isset($_POST['wpgis_update'])) { ?>
        	 <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Your changes saved successfully !', 'wpgis' ); ?></p>
             </div>
             <?php } ?>
   			 <div class="wpigs-logo">
             	<h2>WPGIS Controls</h2>
             </div>    	
        </div>
    	<div id="tabs" class="clearfix wpadmin-right">
                <form method="post">
                		<input type="hidden" name="ws250nonce" value="<?php echo wp_create_nonce('ws250updater'); ?>" />
                        <div id="tabs-general">
                                <div class="admin-section">
                                    <table class="form-table wide-th">
                                            <tr>
                                                <th>
                                                   <?php _e('Slider Layout','wpgis'); ?>     
                                                </th>
                                                <td>
                                                    <select name="wpgis_slider_layout">
                                                        <option value="horizontal" <?php selected( $options['slider_layout'], 'horizontal' ); ?>><?php _e('Horizontal','wpgis'); ?></option>
                                                        <option value="left" <?php selected( $options['slider_layout'], 'left' ); ?>><?php _e('Vertical Left','wpgis'); ?></option>
                                                        <option value="right" <?php selected( $options['slider_layout'], 'right' ); ?>><?php _e('Vertical Right','wpgis'); ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                   <?php _e('Slides to Show','wpgis'); ?>     
                                                </th>
                                                <td>
                                                    <input type="number" name="wpgis_slidetoshow" value="<?php echo esc_html($options['slidetoshow']); ?>"  />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                   <?php _e('Slides to Scroll','wpgis'); ?>     
                                                </th>
                                                <td>
                                                    <input type="number" name="wpgis_slidetoscroll" value="<?php echo esc_html($options['slidetoscroll']); ?>"  />
                                                </td>
                                            </tr>
                                           	<tr>
                                                <th>
                                                   <?php _e('Slider Autoplay','wpgis'); ?><br />
                                                   <span>Default : No</span>     
                                                </th>
                                                <td>
                                                	<label class="switch">
                                                      <input type="checkbox" name="wpgis_sliderautoplay" value="1" <?php if($options['sliderautoplay']==1) { ?> checked="checked" <?php } ?>>
                                                      
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                   <?php _e('Slider Infinite Loop','wpgis'); ?><br />
                                                   <span>Default : No</span>    
                                                </th>
                                                <td>
                                                	<label class="switch">
                                                      <input type="checkbox" name="wpgis_arrowinfinite" value="1" <?php if($options['arrowinfinite']==1) { ?> checked="checked" <?php } ?>>
                                                     
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                   <?php _e('Arrow Disable','wpgis'); ?><br />
                                                   <span>Default : No</span>     
                                                </th>
                                                <td>
                                                	<label class="switch">
                                                      <input type="checkbox" name="wpgis_arrowdisable" value="1" <?php if($options['arrowdisable']==1) { ?> checked="checked" <?php } ?>>
                                                      
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                   <?php _e('Arrow Color','wpgis'); ?>     
                                                </th>
                                                <td>
                                                    <input type="text" value="<?php echo $options['arrowcolor']; ?>" name="wpgis_arrowcolor" class="my-color-field" data-default-color="#ffffff" />
                                                </td>
                                            </tr> 
                                            <tr>
                                                <th>
                                                   <?php _e('Arrow Background Color','wpgis'); ?>     
                                                </th>
                                                <td>
                                                    <input type="text" value="<?php echo $options['arrowbgcolor']; ?>" name="wpgis_arrowbgcolor" class="my-color-field" data-default-color="#000000" />
                                                </td>
                                            </tr> 
                                             <tr>
                                                <th>
                                                   <?php _e('Lightbox','wpgis'); ?><br />    
                                                   <span>Default : Yes</span>
                                                </th>
                                                <td>
                                                	<label class="switch">
                                                      <input type="checkbox" name="wpgis_show_lightbox" value="1" <?php if($options['show_lightbox']==1) { ?> checked="checked" <?php } ?>>
                                                      
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                   <?php _e('Zoom','wpgis'); ?><br />
                                                   <span>Default : Yes</span>    
                                                </th>
                                                <td>
                                                	<label class="switch">
                                                      <input type="checkbox" name="wpgis_show_zoom" value="1" <?php if($options['show_zoom']==1) { ?> checked="checked" <?php } ?>>
                                                     
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="wpgis_submit_btn">
                                                    <input type="submit" value="<?php _e('Save Settings','wpgis'); ?>" class="button-primary" id="wpgis_update" name="wpgis_update">	
                                                </td>
                                            </tr>
                                    </table>
                                </div>
                        </div>
                        
                </form>
        </div>
        <div>
        	Thank you for download WPGIS. If you found any problems in this Plugin please let us know in <a href="https://wordpress.org/support/plugin/woo-product-galllery-images-slider/reviews/">Plugin Review</a>. we will try to fix it in next Update.
        </div>
    </div>    
    
</div>

<?php
}