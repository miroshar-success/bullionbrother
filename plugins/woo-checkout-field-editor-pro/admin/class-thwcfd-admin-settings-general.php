<?php
/**
 * Woo Checkout Field Editor Settings General
 *
 * @link       https://themehigh.com
 * @since      1.3.6
 *
 * @package    woo-checkout-field-editor-pro
 * @subpackage woo-checkout-field-editor-pro/classes
 */

defined( 'ABSPATH' ) || exit;

if(!class_exists('THWCFD_Admin_Settings_General')):

class THWCFD_Admin_Settings_General extends THWCFD_Admin_Settings{
	protected static $_instance = null;

	private $field_form = null;
	private $field_form_props = array();

	protected $tabs = '';
	protected $sections = '';

	public function __construct() {
		parent::__construct();
		$this->page_id    = 'fields';
		$this->section_id = 'billing';

		$this->tabs = array(
			'fields' => __('Checkout Fields', 'woo-checkout-field-editor-pro'),
			'advanced_settings' => __('Advanced Settings', 'woo-checkout-field-editor-pro'),
			'pro' => __('Premium Features', 'woo-checkout-field-editor-pro'),
			'themehigh_plugins' => __('Other Free Plugins', 'woo-checkout-field-editor-pro'),
		);
		$this->sections = array(
			'billing' => __('Billing Fields', 'woo-checkout-field-editor-pro'),
			'shipping' => __('Shipping Fields', 'woo-checkout-field-editor-pro'),
			'additional' => __('Additional Fields', 'woo-checkout-field-editor-pro'),
		);
	}

	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function define_admin_hooks(){
		// Show in order details page
		add_action('woocommerce_admin_order_data_after_order_details', array($this, 'order_data_after_order_details'), 20, 1);
		add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'order_data_after_billing_address'), 20, 1);
		add_action('woocommerce_admin_order_data_after_shipping_address', array($this, 'order_data_after_shipping_address'), 20, 1);
	}

	public function init(){
		$this->field_form   = new THWCFD_Admin_Form_Field();
		$this->field_form_props = $this->field_form->get_field_form_props();

		$this->render_page();
	}

	public function reset_to_default() {
		$nonse = isset($_REQUEST['thwcfd_security_manage_fields']) ? $_REQUEST['thwcfd_security_manage_fields'] : false;
		$capability = THWCFD_Utils::wcfd_capability();
		if(!wp_verify_nonce($nonse, 'thwcfd_section_fields') || !current_user_can($capability)){
			die();
		}

		delete_option('wc_fields_billing');
		delete_option('wc_fields_shipping');
		delete_option('wc_fields_additional');

		return $this->print_notices(__('Checkout fields successfully reset', 'woo-checkout-field-editor-pro'), 'updated', true);
	}

	public function render_page(){
		$this->output_tabs();
		$this->output_sections();
		$this->output_content();
	}

	public function render_checkout_fields_heading_row(){
		?>
		<th class="sort"></th>
		<th class="check-column"><input type="checkbox" style="margin:0px 4px -1px -1px;" onclick="thwcfdSelectAllCheckoutFields(this)"/></th>
		<th class="name"><?php _e('Name', 'woo-checkout-field-editor-pro'); ?></th>
		<th class="id"><?php _e('Type', 'woo-checkout-field-editor-pro'); ?></th>
		<th><?php _e('Label', 'woo-checkout-field-editor-pro'); ?></th>
		<th><?php _e('Placeholder', 'woo-checkout-field-editor-pro'); ?></th>
		<th><?php _e('Validations', 'woo-checkout-field-editor-pro'); ?></th>
        <th class="status"><?php _e('Required', 'woo-checkout-field-editor-pro'); ?></th>
		<th class="status"><?php _e('Enabled', 'woo-checkout-field-editor-pro'); ?></th>	
        <th class="action"><?php _e('Edit', 'woo-checkout-field-editor-pro'); ?></th>	
        <?php
	}
	
	public function render_actions_row($section){
		?>
        <th colspan="6">
            <button type="button" class="button button-primary" onclick="thwcfdOpenNewFieldForm('<?php echo $section; ?>')">+ <?php _e( 'Add field', 'woo-checkout-field-editor-pro' ); ?></button>
            <button type="button" class="button" onclick="thwcfdRemoveSelectedFields()"><?php _e('Remove', 'woo-checkout-field-editor-pro'); ?></button>
            <button type="button" class="button" onclick="thwcfdEnableSelectedFields()"><?php _e('Enable', 'woo-checkout-field-editor-pro'); ?></button>
            <button type="button" class="button" onclick="thwcfdDisableSelectedFields()"><?php _e('Disable', 'woo-checkout-field-editor-pro'); ?></button>
        </th>
        <th colspan="4">
        	<input type="submit" name="save_fields" class="button-primary" value="<?php _e( 'Save changes', 'woo-checkout-field-editor-pro' ) ?>" style="float:right" />
            <input type="submit" name="reset_fields" class="button" value="<?php _e( 'Reset to default fields', 'woo-checkout-field-editor-pro' ) ?>" style="float:right; margin-right: 5px;" 
			onclick="return confirm('<?php _e('Are you sure you want to reset to default fields? all your changes will be deleted.', 'woo-checkout-field-editor-pro' ); ?>')"/>
        </th>  
    	<?php 
	}

	public function output_content() {
		$section = $this->get_current_section();
		$action = isset($_POST['f_action']) ? $_POST['f_action'] : false;

		if($action === 'new')
			echo $this->save_or_update_field($section, $action);	
			
		if($action === 'edit')
			echo $this->save_or_update_field($section, $action);
		
		if(isset($_POST['save_fields']))
			echo $this->save_fields($section);

		if(isset($_POST['reset_fields']))
			echo $this->reset_to_default();

		$fields = THWCFD_Utils::get_fields($section);	
	
		?>            
        <div class="wrap woocommerce"><div class="icon32 icon32-attributes" id="icon-woocommerce"><br /></div>
		<form method="post" id="thwcfd_checkout_fields_form" action="">
        	<table id="thwcfd_checkout_fields" class="wc_gateways widefat thpladmin_fields_table" cellspacing="0">
				<thead>
                	<tr><?php $this->render_actions_row($section); ?></tr>
                	<tr><?php $this->render_checkout_fields_heading_row(); ?></tr>						
				</thead>
                <tfoot>
                	<tr><?php $this->render_checkout_fields_heading_row(); ?></tr>
					<tr><?php $this->render_actions_row($section); ?></tr>
				</tfoot>
				<tbody class="ui-sortable">
	                <?php 
					$i=0;
					foreach( $fields as $name => $field ) :
						$type = isset($field['type']) ? $field['type'] : '';
						$label = isset($field['label']) ? $field['label'] : '';
						$placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
						$validate = isset($field['validate']) ? $field['validate'] : '';
						$required = isset($field['required']) && $field['required'] ? 1 : 0;
						$enabled = isset($field['enabled']) && $field['enabled'] ? 1 : 0;
						$custom = isset($field['custom']) && $field['custom'] ? 1 : 0;

						$validate = is_array($validate) ? implode(",", $validate) : '';

						$required_status = $required ? '<span class="dashicons dashicons-yes tips" data-tip="Yes"></span>' : '-';
						$enabled_status = $enabled ? '<span class="dashicons dashicons-yes tips" data-tip="Yes"></span>' : '-';

						$props_json = htmlspecialchars($this->get_property_set_json($name, $field));
						//$options_json = isset($field['options_json']) && $field['options_json'] ? htmlspecialchars($field['options_json']) : '';

						$options_json = '';
						if($type === 'select' || $type === 'radio' || $type === 'checkboxgroup' || $type === 'multiselect'){
							$options = isset($field['options']) ? $field['options'] : '';
							$options_json = THWCFD_Utils::prepare_options_json($options);
						}
					?>
						<tr class="row_<?php echo $i; echo $enabled ? '' : ' thpladmin-disabled' ?>">
	                    	<td width="1%" class="sort ui-sortable-handle">
	                    		<input type="hidden" name="f_name[<?php echo $i; ?>]" class="f_name" value="<?php echo esc_attr($name); ?>" />
	                    		<input type="hidden" name="f_name_new[<?php echo $i; ?>]" class="f_name_new" value="" />
								<input type="hidden" name="f_order[<?php echo $i; ?>]" class="f_order" value="<?php echo $i; ?>" />
								<input type="hidden" name="f_deleted[<?php echo $i; ?>]" class="f_deleted" value="0" />
								<input type="hidden" name="f_enabled[<?php echo $i; ?>]" class="f_enabled" value="<?php echo $enabled; ?>" />
								<input type="hidden" name="f_props[<?php echo $i; ?>]" class="f_props" value='<?php echo $props_json; ?>' />
								<input type="hidden" name="f_options[<?php echo $i; ?>]" class="f_options" value='<?php echo $options_json; ?>' />
	                        </td>
	                        <td class="td_select"><input type="checkbox" name="select_field"/></td>
	                        <td class="td_name"><?php echo esc_attr( $name ); ?></td>
	                        <td class="td_type"><?php echo esc_attr($type); ?></td>
	                        <td class="td_label"><?php echo esc_html_e($label, 'woo-checkout-field-editor-pro'); ?></td>
	                        <td class="td_placeholder"><?php echo esc_html_e($placeholder, 'woo-checkout-field-editor-pro'); ?></td>
	                        <td class="td_validate"><?php echo esc_html($validate); ?></td>
	                        <td class="td_required status"><?php echo $required_status; ?></td>
	                        <td class="td_enabled status"><?php echo $enabled_status; ?></td>
	                        <td class="td_edit action">
	                        	<button type="button" class="button action-btn f_edit_btn" <?php echo($enabled ? '' : 'disabled') ?> 
	                            onclick="thwcfdOpenEditFieldForm(this, <?php echo $i; ?>)"><?php _e('Edit', 'woo-checkout-field-editor-pro'); ?></button>
	                        </td>
	                	</tr>
	                <?php 
	                	$i++; 
	                	endforeach; 
	                ?>
            	</tbody>
			</table>
			<?php wp_nonce_field( 'thwcfd_section_fields', 'thwcfd_security_manage_fields' ); ?>
        </form>
        <?php
        $this->field_form->output_field_forms();
	}

	public function get_property_set_json($name, $field){
		$json = '';
		if(is_array($field)){
			foreach($field as $pname => $pvalue){
				$pvalue = is_array($pvalue) ? implode(',', $pvalue) : $pvalue;
				$pvalue = is_string($pvalue) ? esc_attr($pvalue) : $pvalue;
				
				$field[$pname] = $pvalue;
			}

			$field['name'] = $name;
			$json = json_encode($field);
		}
		return $json;
	}

	private function save_or_update_field($section, $action) {
		$nonse = isset($_REQUEST['thwcfd_security_manage_field']) ? $_REQUEST['thwcfd_security_manage_field'] : false;
		$capability = THWCFD_Utils::wcfd_capability();
		if(!wp_verify_nonce($nonse, 'thwcfd_field_form') || !current_user_can($capability)){
			die();
		}

		try {
			$result = false;
			$fields = THWCFD_Utils::get_fields($section);
			$field = $this->prepare_field_from_posted_data($_POST);
			$this->add_wpml_support($field);
			$name = isset($field['name']) ? $field['name'] : false;

			if($name){
				if($action === 'new'){
					$priority = THWCFD_Utils::prepare_field_priority($fields, false, true);
					$field['custom'] = 1;
					$field['priority'] = $priority;
				}else{
					$oname = isset($_POST['i_oname']) ? sanitize_key($_POST['i_oname']) : false;
					if($name && $oname && $name !== $oname ){
						unset($fields[$oname]);
					}
				}

				$fields[$name] = $field;
			}
			
			$result = THWCFD_Utils::update_fields($section, $fields);
			
			if($result == true) {
				$this->print_notices(__('Your changes were saved.', 'woo-checkout-field-editor-pro' ), 'updated');
			}else {
				$this->print_notices(__('Your changes were not saved due to an error (or you made none!).', 'woo-checkout-field-editor-pro'), 'error');
			}
		} catch (Exception $e) {
			$this->print_notices(__('Your changes were not saved due to an error.', 'woo-checkout-field-editor-pro'), 'error');
		}
	}
	
	private function save_fields($section) {
		$nonse = isset($_REQUEST['thwcfd_security_manage_fields']) ? $_REQUEST['thwcfd_security_manage_fields'] : false;
		$capability = THWCFD_Utils::wcfd_capability();
		if(!wp_verify_nonce($nonse, 'thwcfd_section_fields') || !current_user_can($capability)){
			die();
		}

		try {
			$f_names = !empty( $_POST['f_name'] ) ? $_POST['f_name'] : array();
			$f_names = array_map('sanitize_key', $f_names);
			if(empty($f_names)){
				$this->print_notices(__('Your changes were not saved due to no fields found.', 'woo-checkout-field-editor-pro'), 'error');
				return;
			}
			
			$f_order   = !empty( $_POST['f_order'] ) ? $_POST['f_order'] : array();
			$f_order = array_map('absint', $f_order);
			$f_deleted = !empty( $_POST['f_deleted'] ) ? $_POST['f_deleted'] : array();
			$f_deleted = array_map('absint', $f_deleted);
			$f_enabled = !empty( $_POST['f_enabled'] ) ? $_POST['f_enabled'] : array();
			$f_enabled = array_map('absint', $f_enabled);
						
			$fields = THWCFD_Utils::get_fields($section);
			
			$max = max( array_map( 'absint', array_keys( $f_names ) ) );
			for($i = 0; $i <= $max; $i++) {
				$name = $f_names[$i];
				
				if(isset($fields[$name])){
					$is_deleted = isset($f_deleted[$i]) && $f_deleted[$i] ? true : false;

					if($is_deleted){
						unset($fields[$name]);
						continue;
					}

					$order = isset($f_order[$i]) ? $f_order[$i] : 0;
					$enabled = isset($f_enabled[$i]) ? $f_enabled[$i] : 0;
					$priority = THWCFD_Utils::prepare_field_priority($fields, $order, false);
					
					$field = $fields[$name];
					$field['priority'] = $priority;
					$field['enabled'] = $enabled;
					
					$fields[$name] = $field;
				}
			}
			$fields = THWCFD_Utils::sort_fields($fields);
			$result = THWCFD_Utils::update_fields($section, $fields);

			if($result == true) {
				$this->print_notices(__('Your changes were saved.', 'woo-checkout-field-editor-pro'), 'updated');
			}else {
				$this->print_notices(__('Your changes were not saved due to an error (or you made none!).', 'woo-checkout-field-editor-pro'), 'error');
			}
		} catch (Exception $e) {
			$this->print_notices(__('Your changes were not saved due to an error.', 'woo-checkout-field-editor-pro'), 'error');
		}
	}

	private function prepare_field_from_posted_data($posted){
		$field_props = $this->field_form_props;
		$field = array();
		
		foreach ($field_props as $pname => $prop) {
			$iname  = 'i_'.$pname;
			
			$pvalue = '';
			if($prop['type'] === 'checkbox'){
				$pvalue = isset($posted[$iname]) && $posted[$iname] ? 1 : 0;
			}else if(isset($posted[$iname])){
				//$pvalue = is_array($posted[$iname]) ? implode(',', $posted[$iname]) : trim(stripslashes($posted[$iname]));
				// $pvalue = is_array($posted[$iname]) ? $posted[$iname] : trim(stripslashes($posted[$iname]));

				if(($pname === 'type') || ($pname === 'name')){
					$pvalue = !empty($posted[$iname]) ? sanitize_key($posted[$iname]) : "";
				}else if(($pname === 'label')){
					//$pvalue = !empty($posted[$iname]) ? htmlentities(stripslashes($posted[$iname])) : "";
					$pvalue = !empty($posted[$iname]) ? wp_unslash(wp_filter_post_kses($posted[$iname])) : "";
				}else if(($pname === 'validate')){
					$pvalue = !empty($posted[$iname]) ? (array) $posted[$iname] : array();
					$pvalue = array_map( 'sanitize_key', $pvalue );
				}else if($pname === 'class'){
					//$pvalue = is_string($pvalue) ? array_map('trim', explode(',', $pvalue)) : $pvalue;
					$pvalue = !empty($posted[$iname]) ? $posted[$iname] : '';
					$pvalue = is_string($pvalue) ? preg_split('/(\s*,*\s*)*,+(\s*,*\s*)*/', $pvalue) : array();
					$pvalue = array_map('sanitize_key', $pvalue);
				}else{
					$pvalue = !empty($posted[$iname]) ? sanitize_text_field(wp_unslash($posted[$iname])) : "";
				}
			}

			$field[$pname] = $pvalue;
		}

		$type = isset($field['type']) ? $field['type'] : '';
		if(!$type){
			$type = isset($posted['i_otype']) ? sanitize_key($posted['i_otype']) : '';
			$field['type'] = $type;
		}

		$name = isset($field['name']) ? $field['name'] : '';
		if(!$name){
			$field['name'] = isset($posted['i_oname']) ? sanitize_key($posted['i_oname']) : '';
		}

		if($type === 'select' || $type === 'multiselect'){
			$field['validate'] = '';

		}else if($type === 'radio'){
			$field['validate'] = '';
			$field['placeholder'] = '';

		}else if($type === 'number'){
			$field['validate'] = array('number');

		}else if($type === 'checkbox'){
			if(isset($posted['i_default'])){
				$field['default'] = sanitize_text_field($posted['i_default']);
			}else{
				$field['default'] = '';
			}

		}

		if($type === 'select' || $type === 'radio' || $type === 'checkboxgroup' || $type === 'multiselect'){
			$options_json = isset($posted['i_options_json']) ? trim(stripslashes($posted['i_options_json'])) : '';
			$options_arr = THWCFD_Utils::prepare_options_array($options_json, $type);

			$keys = array_keys($options_arr);
			// $keys = array_map('sanitize_key', $keys);
			$keys = array_map('sanitize_text_field', $keys);

			$values = array_values($options_arr);
			$values = array_map('htmlspecialchars', $values);

			$options_arr = array_combine($keys, $values);

			$field['options'] = $options_arr;

			// // Sanitize default value same like option values
			// $default_value = isset($field['default']) ? $field['default'] : '';
			// if($default_value){
			// 	$field['default'] = sanitize_key($default_value);
			// }


		}else{
			$field['options'] = '';
		}

		$field['autocomplete'] = isset($posted['i_autocomplete']) ? sanitize_text_field($posted['i_autocomplete']) : '';
		$field['priority'] = isset($posted['i_priority']) ? absint($posted['i_priority']) : '';
		//$field['custom'] = isset($posted['i_custom']) ? $posted['i_custom'] : '';
		$field['custom'] = isset($posted['i_custom']) && $posted['i_custom'] ? 1 : 0;
		
		return $field;
	}

	/******* Display & Update Field Values *******/
	/*********************************************/
	public function order_data_after_order_details($order){
		$fields = THWCFD_Utils::get_fields('additional');
		//$this->display_fields_in_admin_order($order, $fields, '<p>&nbsp;</p>');
		$this->display_fields_in_admin_order($order, $fields, '');
	}

	public function order_data_after_billing_address($order){
		$fields = THWCFD_Utils::get_fields('billing');
		$this->display_fields_in_admin_order($order, $fields, '');
	}

	public function order_data_after_shipping_address($order){
		$fields = THWCFD_Utils::get_fields('shipping');
		$this->display_fields_in_admin_order($order, $fields, '');
	}

	public function display_fields_in_admin_order($order, $fields, $prefix_html = ''){
		if(is_array($fields)){
			$html = '';
			$order_id = THWCFD_Utils::get_order_id($order);
		
			foreach($fields as $name => $field){
				if(THWCFD_Utils::is_active_custom_field($field) && isset($field['show_in_order']) && $field['show_in_order']  && !THWCFD_Utils::is_wc_handle_custom_field($field)){
					$value = get_post_meta( $order_id, $name, true );
					if(!empty($value)){
						$value = THWCFD_Utils::get_option_text($field, $value);
						$label = isset($field['label']) && $field['label'] ? esc_html($field['label'], 'woo-checkout-field-editor-pro') : $name;
						$html .= '<p><strong>'. $label .':</strong><br/> '. wptexturize($value) .'</p>';
					}
				}
			}

			if($html){
				echo '<div style="clear:both; padding:5px 0 0;">'.$prefix_html.$html.'</div>';
			}
		}
	}

	/******* TABS & SECTIONS *******/
	/*******************************/
	public function get_current_tab(){
		return isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'fields';
	}
	
	public function get_current_section(){
		$tab = $this->get_current_tab();
		$section = '';
		if($tab === 'fields'){
			$section = isset( $_GET['section'] ) ? sanitize_key( $_GET['section'] ) : 'billing';
		}
		return $section;
	}

	public function output_tabs(){
		$current_tab = $this->get_current_tab();

		if(empty($this->tabs)){
			return;
		}
		
		echo '<h2 class="thpladmin-tabs nav-tab-wrapper woo-nav-tab-wrapper">';
		foreach( $this->tabs as $id => $label ){
			$active = ( $current_tab == $id ) ? 'nav-tab-active' : '';
			//$label  = __($label, 'woo-checkout-field-editor-pro');
			echo '<a class="nav-tab '.$active.'" href="'. esc_url($this->get_admin_url($id)) .'">'.$label.'</a>';
		}
		echo '</h2>';	
	}
	
	public function output_sections() {
		$result = false;

		$current_tab = $this->get_current_tab();
		$current_section = $this->get_current_section();

		if(empty($this->sections)){
			return;
		}
		
		$array_keys = array_keys( $this->sections );
		
		echo '<ul class="thpladmin-sections">';
		foreach( $this->sections as $id => $label ){
			// $label = __($label, 'woo-checkout-field-editor-pro');
			$url = $this->get_admin_url($current_tab, sanitize_title($id));	
			echo '<li><a href="'.esc_url($url) .'" class="'. ( $current_section == $id ? 'current' : '' ) .'">'. $label .'</a> '. (end( $array_keys ) == $id ? '' : '|') .' </li>';
		}		
		echo '</ul>';

		if($result){
			echo $result;
		}
	}	
	
	public function get_admin_url($tab = false, $section = false){
		$url = 'admin.php?page=checkout_form_designer';
		if($tab && !empty($tab)){
			$url .= '&tab='. $tab;
		}
		if($section && !empty($section)){
			$url .= '&section='. $section;
		}
		return admin_url($url);
	}

	private function add_wpml_support($field){
		$context = 'woo-checkout-field-editor-pro';
		
		$label = isset($field['label']) ? $field['label'] : '';
		if($label){
			$name = 'Field label - ' . $label;
			do_action( 'wpml_register_single_string', 'woo-checkout-field-editor-pro', $name, $label );
		}

		$placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
		if($placeholder){
			$name = 'Field placeholder - ' . $placeholder;
			do_action( 'wpml_register_single_string', 'woo-checkout-field-editor-pro', $name, $placeholder );
		}

		$options = isset($field['options']) ? $field['options'] : '';
		if($options){
			if(is_array($options)){
				$index = 0;
				foreach($options as $option_value => $option_text){
					$name = 'Field option text - ' . $option_text;
					do_action( 'wpml_register_single_string', 'woo-checkout-field-editor-pro', $name, $option_text );
					$index++;
				}
			}
		}
	}
}

endif;
