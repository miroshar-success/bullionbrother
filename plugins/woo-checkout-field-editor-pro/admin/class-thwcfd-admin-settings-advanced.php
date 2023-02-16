<?php
/**
 * The admin advanced settings page functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.4.4
 *
 * @package    woo-checkout-field-editor-pro
 * @subpackage woo-checkout-field-editor-pro/admin
 */

if(!defined('WPINC')){	die; }

if(!class_exists('THWCFD_Admin_Settings_Advanced')):

class THWCFD_Admin_Settings_Advanced extends THWCFD_Admin_Settings{
	protected static $_instance = null;
	protected $tabs = '';

	private $settings_fields = NULL;
	private $cell_props = array();
	private $cell_props_CB = array();
	private $cell_props_TA = array();

	public function __construct() {
		parent::__construct();
		
		$this->page_id = 'advanced_settings';
		$this->init_constants();
	}
	
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function init_constants(){
		$this->cell_props = array( 
			'label_cell_props' => 'class="label"', 
			'input_cell_props' => 'class="field"',
			'input_width' => '260px',
			'label_cell_th' => true
		);

		$this->cell_props_TA = array( 
			'label_cell_props' => 'class="label"', 
			'input_cell_props' => 'class="field"',
			'rows' => 10,
			'cols' => 100,
		);

		$this->cell_props_CB = array( 
			'label_props' => 'style="margin-right: 40px;"', 
		);
		
		$this->settings_fields = $this->get_advanced_settings_fields();
	}

	public function get_advanced_settings_fields(){
		return array(
			'enable_label_override' => array(
				'name'=>'enable_label_override', 'label'=>__('Enable label override for address fields.', 'woo-checkout-field-editor-pro'), 'type'=>'checkbox', 'value'=>'1', 'checked'=>1
			),
			'enable_placeholder_override' => array(
				'name'=>'enable_placeholder_override', 'label'=>__('Enable placeholder override for address fields.', 'woo-checkout-field-editor-pro'), 'type'=>'checkbox', 'value'=>'1', 'checked'=>1
			),
			'enable_class_override' => array(
				'name'=>'enable_class_override', 'label'=>__('Enable class override for address fields.', 'woo-checkout-field-editor-pro'), 'type'=>'checkbox', 'value'=>'1', 'checked'=>1
			),
			'enable_priority_override' => array(
				'name'=>'enable_priority_override', 'label'=>__('Enable priority override for address fields.', 'woo-checkout-field-editor-pro'), 'type'=>'checkbox', 'value'=>'1', 'checked'=>1
			),
			'enable_required_override' => array(
				'name'=>'enable_required_override', 'label'=>__('Enable required validation override for address fields.', 'woo-checkout-field-editor-pro'), 'type'=>'checkbox', 'value'=>'1', 'checked'=>1
			),
		);
	}

	public function render_page(){
		$this->render_tabs();
		$this->render_content();
	}
		
	public function save_advanced_settings($settings){
		$result = update_option(THWCFD_Utils::OPTION_KEY_ADVANCED_SETTINGS, $settings, 'no');
		return $result;
	}
	
	private function reset_settings(){
		$nonse = isset($_REQUEST['thwcfd_security_advanced_settings']) ? $_REQUEST['thwcfd_security_advanced_settings'] : false;
		$capability = THWCFD_Utils::wcfd_capability();
		if(!wp_verify_nonce($nonse, 'thwcfd_advanced_settings') || !current_user_can($capability)){
			die();
		}

		delete_option(THWCFD_Utils::OPTION_KEY_ADVANCED_SETTINGS);
		$this->print_notices(__('Settings successfully reset.', 'woo-checkout-field-editor-pro'), 'updated', false);
	}
	
	private function save_settings(){
		$nonse = isset($_REQUEST['thwcfd_security_advanced_settings']) ? $_REQUEST['thwcfd_security_advanced_settings'] : false;
		$capability = THWCFD_Utils::wcfd_capability();
		if(!wp_verify_nonce($nonse, 'thwcfd_advanced_settings') || !current_user_can($capability)){
			die();
		}
		
		$settings = array();
		
		foreach( $this->settings_fields as $name => $field ) {
			$value = '';
			
			if($field['type'] === 'checkbox'){
				$value = !empty( $_POST['i_'.$name] ) ? '1' : '';

			}else if($field['type'] === 'multiselect_grouped'){
				$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
				$value = is_array($value) ? implode(',', wc_clean(wp_unslash($value))) : wc_clean(wp_unslash($value));

			}else if($field['type'] === 'text' || $field['type'] === 'textarea'){
				$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
				$value = !empty($value) ? wc_clean( wp_unslash($value)) : '';

			}else{
				$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
				$value = !empty($value) ? wc_clean( wp_unslash($value)) : '';
			}
			
			$settings[$name] = $value;
		}
				
		$result = $this->save_advanced_settings($settings);
		if ($result == true) {
			$this->print_notices(__('Your changes were saved.', 'woo-checkout-field-editor-pro'), 'updated', false);
		} else {
			$this->print_notices(__('Your changes were not saved due to an error (or you made none!).', 'woo-checkout-field-editor-pro'), 'error', false);
		}	
	}
	
	private function render_content(){
		if(isset($_POST['reset_settings']))
			$this->reset_settings();	
			
		if(isset($_POST['save_settings']))
			$this->save_settings();

		if(isset($_POST['save_plugin_settings'])) 
			$result = $this->save_plugin_settings();
			
    	$this->render_plugin_settings();
    	$this->render_import_export_settings();
	}

	private function render_plugin_settings(){
		$settings = THWCFD_Utils::get_advanced_settings();
		?>            
        <div style="padding-left: 30px;">               
		    <form id="advanced_settings_form" method="post" action="">
                <table class="thwcfd-settings-table thpladmin-form-table">
                    <tbody>
                    <?php
                    $this->render_locale_override_settings($settings);
					?>
                    </tbody>
                </table> 
                <p class="submit">
					<input type="submit" name="save_settings" class="btn btn-small btn-primary" value="Save changes">
                    <input type="submit" name="reset_settings" class="btn btn-small" value="Reset to default" 
					onclick="return confirm(<?php _e('Are you sure you want to reset to default settings? all your changes will be deleted.', 'woo-checkout-field-editor-pro'); ?>)">
            	</p>
            	<?php wp_nonce_field( 'thwcfd_advanced_settings', 'thwcfd_security_advanced_settings' ); ?>
            </form>
    	</div>       
    	<?php
	}

	private function render_locale_override_settings($settings){
		$this->render_form_elm_row_title('Locale override settings');
		$this->render_form_elm_row_cb($this->settings_fields['enable_label_override'], $settings, true);
		$this->render_form_elm_row_cb($this->settings_fields['enable_placeholder_override'], $settings, true);
		$this->render_form_elm_row_cb($this->settings_fields['enable_class_override'], $settings, true);
		$this->render_form_elm_row_cb($this->settings_fields['enable_priority_override'], $settings, true);
		$this->render_form_elm_row_cb($this->settings_fields['enable_required_override'], $settings, true);
	}

	/************************************************
	 *-------- IMPORT & EXPORT SETTINGS - START -----
	 ************************************************/
	public function prepare_plugin_settings(){
		$settings_billing = get_option(THWCFD_Utils::OPTION_KEY_BILLING_FIELDS);
		$settings_shipping = get_option(THWCFD_Utils::OPTION_KEY_SHIPPING_FIELDS);
		$settings_additional = get_option(THWCFD_Utils::OPTION_KEY_ADDITIONAL_FIELDS);
		$settings_advanced = get_option(THWCFD_Utils::OPTION_KEY_ADVANCED_SETTINGS);

		$plugin_settings = array(
			'option_key_billing_fields' => $settings_billing,
			'option_key_shipping_fields' => $settings_shipping,
			'option_key_additional_fields' => $settings_additional,
			'option_key_advanced_settings' => $settings_advanced,
		);
		return base64_encode(json_encode($plugin_settings));
	}
	
	public function render_import_export_settings(){
		/*
		if(isset($_POST['save_plugin_settings'])) 
			$result = $this->save_plugin_settings(); 
		*/

		if(isset($_POST['import_settings'])){			   
		} 
		
		$plugin_settings = $this->prepare_plugin_settings();
		if(isset($_POST['export_settings']))
			echo $this->export_settings($plugin_settings);
		
		$imp_exp_fields = array(
			'section_import_export' => array('title'=>__('Backup and Import Settings', 'woo-checkout-field-editor'), 'type'=>'separator', 'colspan'=>'3'),
			'settings_data' => array(
				'name'=>'settings_data', 'label'=>__('Plugin Settings Data', 'woo-checkout-field-editor'), 'type'=>'textarea', 'value' => $plugin_settings,
				'sub_label'=>__('You can transfer the saved settings data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Settings".', 'woo-checkout-field-editor'),
			),
		);
		?>
		<div style="padding-left: 30px;">               
		    <form id="import_export_settings_form" method="post" action="" class="clear">
                <table class="thwcfd-settings-table">
                    <tbody>
                    <?php
                    $this->render_form_elm_row_title('Backup and Import Settings');
					$this->render_form_elm_row_ta($imp_exp_fields['settings_data']);
					?>
                    </tbody>
					<tfoot>
						<tr valign="top">
							<td colspan="2">&nbsp;</td>
							<td class="submit">
								<input type="submit" name="save_plugin_settings" class="btn btn-small btn-primary" value="<?php _e('Import Settings', 'woo-checkout-field-editor'); ?>">
								<?php wp_nonce_field( 'import_wcfd_settings', 'import_wcfd_nonce' ); ?>
							</td>
						</tr>
					</tfoot>
                </table> 
            </form>
    	</div> 
		<?php
	}
		
	public function save_plugin_settings(){

		check_admin_referer( 'import_wcfd_settings', 'import_wcfd_nonce' );

		$capability = THWCFD_Utils::wcfd_capability();
		if(!current_user_can($capability)){
			wp_die();
		}

		if(isset($_POST['i_settings_data']) && !empty($_POST['i_settings_data'])) {
			$settings_data_encoded = sanitize_textarea_field(wp_unslash($_POST['i_settings_data']));
			$base64_decoded = base64_decode($settings_data_encoded);

			if(!$this->is_json($base64_decoded,$return_data = false)){
				$this->print_notices(__('The entered import settings data is invalid. Please try again with valid data.', 'woo-extra-product-options'), 'error', false);
				return false;
			}

			// $settings = unserialize($base64_decoded, ['allowed_classes' => false]);
			$settings = json_decode($base64_decoded,true);

			if($settings){	
				foreach($settings as $key => $value){
					if($key === 'option_key_billing_fields'){
						$result = update_option(THWCFD_Utils::OPTION_KEY_BILLING_FIELDS, $value);
					}
					if($key === 'option_key_shipping_fields'){
						$result1 = update_option(THWCFD_Utils::OPTION_KEY_SHIPPING_FIELDS, $value);	
					}
					if($key === 'option_key_additional_fields'){
						$result2 = update_option(THWCFD_Utils::OPTION_KEY_ADDITIONAL_FIELDS, $value);	
					}
					if($key === 'option_key_advanced_settings'){ 
						$result3 = $this->save_advanced_settings($value);
					}						  
				}					
			}		
									
			if($result || $result1 || $result2 || $result3){
				$this->print_notices(__('Your Settings Updated.', 'woo-checkout-field-editor-pro'), 'updated', false);
				return true; 
			}else{
				$this->print_notices(__('Your changes were not saved due to an error (or you made none!).', 'woo-checkout-field-editor-pro'), 'error', false);
				return false;
			}	 			
		}
	}

	function is_json($settings,$return_data = false) {
		$data = json_decode($settings);
		return (json_last_error() == JSON_ERROR_NONE) ? ($return_data ? $data : TRUE) : FALSE;
	}

    /**********************************************
	 *-------- IMPORT & EXPORT SETTINGS - END -----
	 **********************************************/


	public function render_form_elm_row_title($title=''){
		?>
		<tr>
			<td colspan="3" class="section-title" ><?php echo $title; ?></td>
		</tr>
		<?php
	}

	private function render_form_elm_row_ta($field, $settings=false){
		if(isset($field['name'])){
			$name = $field['name'];	
		}
		if(is_array($settings) && isset($settings[$name])){
			$field['value'] = $settings[$name];
		}
		
		?>
		<tr valign="top">
			<?php $this->render_form_field_element($field, $this->cell_props_TA); ?>
		</tr>
		<?php
	}

	private function render_form_elm_row_cb($field, $settings=false, $merge_cells=false){
		$name = $field['name'];
		if(is_array($settings) && isset($settings[$name])){
			if($field['value'] === $settings[$name]){
				$field['checked'] = 1;
			}else{
				$field['checked'] = 0;
			}
		}

		if($merge_cells){
			?>
			<tr>
				<td colspan="3">
		    		<?php $this->render_form_field_element($field, $this->cell_props_CB, false); ?>
		    	</td>
		    </tr>
			<?php
		}else{
			?>
			<tr>
				<td colspan="2"></td>
				<td class="field">
		    		<?php $this->render_form_field_element($field, $this->cell_props_CB, false); ?>
		    	</td>
		    </tr>
			<?php
		}
	}
}


endif;