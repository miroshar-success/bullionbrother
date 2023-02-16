<?php
/**
 * Woo Checkout Field Editor Public
 *
 * @link       https://themehigh.com
 * @since      1.3.6
 *
 * @package    woo-checkout-field-editor-pro
 * @subpackage woo-checkout-field-editor-pro/public
 */

defined( 'ABSPATH' ) || exit;

if(!class_exists('THWCFD_Public_Checkout')) :

class THWCFD_Public_Checkout {
	public function __construct() {
		
	}

	public function enqueue_styles_and_scripts() {
		if(is_checkout() || is_wc_endpoint_url('edit-address')){
			$in_footer = apply_filters( 'thwcfd_enqueue_script_in_footer', true );
			$deps = array('jquery', 'selectWoo');
			
			$debug_mode = apply_filters('thwcfd_debug_mode', false);
			$suffix = $debug_mode ? '' : '.min';
			wp_register_script('thwcfd-checkout-script', THWCFD_ASSETS_URL_PUBLIC.'js/thwcfd-public' . $suffix . '.js', $deps, THWCFD_VERSION, $in_footer);
			wp_enqueue_script('thwcfd-checkout-script');
			wp_enqueue_style('thwcfd-checkout-style', THWCFD_ASSETS_URL_PUBLIC . 'css/thwcfd-public' . $suffix . '.css', THWCFD_VERSION);

			$wcfd_var = array(
				'is_override_required' => $this->is_override_required_prop(),
			);
			wp_localize_script('thwcfd-checkout-script', 'thwcfd_public_var', $wcfd_var);
		}
	}

	public function define_public_hooks(){
		$hp_billing_fields  = apply_filters('thwcfd_billing_fields_priority', 1000);
		$hp_shipping_fields = apply_filters('thwcfd_shipping_fields_priority', 1000);
		$hp_checkout_fields = apply_filters('thwcfd_checkout_fields_priority', 1000);

		add_filter('woocommerce_enable_order_notes_field', array($this, 'enable_order_notes_field'), 1000);

		add_filter('woocommerce_get_country_locale_default', array($this, 'prepare_country_locale'));
		add_filter('woocommerce_get_country_locale_base', array($this, 'prepare_country_locale'));
		add_filter('woocommerce_get_country_locale', array($this, 'get_country_locale'));

		add_filter('woocommerce_billing_fields', array($this, 'billing_fields'), $hp_billing_fields, 2);
		add_filter('woocommerce_shipping_fields', array($this, 'shipping_fields'), $hp_shipping_fields, 2);
		add_filter('woocommerce_checkout_fields', array($this, 'checkout_fields'), $hp_checkout_fields);
		// add_filter('woocommerce_address_to_edit',array($this,'woo_address_to_edit'), 1000, 2);
		add_action('woocommerce_after_checkout_validation', array($this, 'checkout_fields_validation'), 10, 2);
		add_action('woocommerce_checkout_update_order_meta', array($this, 'checkout_update_order_meta'), 10, 2);

		add_filter('woocommerce_email_order_meta_fields', array($this, 'display_custom_fields_in_emails'), 10, 3);
		add_action('woocommerce_order_details_after_order_table', array($this, 'order_details_after_customer_details'), 20, 1);

		add_filter('woocommerce_form_field_checkboxgroup', array($this, 'woo_form_field'), 10, 4);
		add_filter('woocommerce_form_field_checkbox', array($this, 'woo_form_field'), 10, 4);
		add_filter('woocommerce_form_field_datetime_local', array($this, 'woo_form_field'), 10, 4);
		add_filter('woocommerce_form_field_date', array($this, 'woo_form_field'), 10, 4);
		add_filter('woocommerce_form_field_time', array($this, 'woo_form_field'), 10, 4);
		add_filter('woocommerce_form_field_month', array($this, 'woo_form_field'), 10, 4);
		add_filter('woocommerce_form_field_week', array($this, 'woo_form_field'), 10, 4);
		add_filter('woocommerce_form_field_url', array($this, 'woo_form_field'), 10, 4);
		add_filter('woocommerce_form_field_multiselect', array($this, 'woo_form_field'), 10, 4);
		add_filter('woocommerce_form_field_hidden', array($this, 'woo_form_field_hidden'), 10, 4);
		add_filter('woocommerce_form_field_heading', array($this, 'woo_form_field_heading'), 10, 4);
		add_filter('woocommerce_form_field_paragraph', array($this, 'woo_form_field_paragraph'), 10, 4);

	}

	/**
	 * Hide Additional Fields title if no fields available.
	 */
	public function enable_order_notes_field() {
		$additional_fields = get_option('wc_fields_additional');
		if(is_array($additional_fields)){
			$enabled = 0;
			foreach($additional_fields as $field){
				if($field['enabled']){
					$enabled++;
				}
			}
			return $enabled > 0 ? true : false;
		}
		return true;
	}

	private  function get_locale_override_value($key, $settings=false, $default=false){
		$value = '';

		if($settings){
			$value = THWCFD_Utils::get_setting_value($settings, $key);
		}else{
			$value = THWCFD_Utils::get_settings($key);
		}

		return $value === 'undefined' ? $default : $value;
	}

	public function is_override_label($settings=false){
		$override_label = $this->get_locale_override_value('enable_label_override', $settings, true);
		$override_label = $override_label ? true : false;
		return apply_filters('thwcfd_address_field_override_label', $override_label);
	}

	public function is_override_placeholder($settings=false){
		$override_ph = $this->get_locale_override_value('enable_placeholder_override', $settings, true);
		$override_ph = $override_ph ? true : false;
		return apply_filters('thwcfd_address_field_override_placeholder', $override_ph);
	}

	public function is_override_class($settings=false){
		$override_class = $this->get_locale_override_value('enable_class_override', $settings, false);
		$override_class = $override_class ? true : false;
		return apply_filters('thwcfd_address_field_override_class', $override_class);
	}

	public function is_override_priority($settings=false){
		$override_priority = $this->get_locale_override_value('enable_priority_override', $settings, true);
		$override_priority = $override_priority ? true : false;
		return apply_filters('thwcfd_address_field_override_priority', $override_priority);
	}

	public function is_override_required_prop($settings=false){
		$override_required = $this->get_locale_override_value('enable_required_override', $settings, false);
		$override_required = $override_required ? true : false;
		return apply_filters('thwcfd_address_field_override_required', $override_required);
	}
	
	public function prepare_country_locale($fields) {
		if(is_array($fields)){
			$settings = THWCFD_Utils::get_advanced_settings();

			$override_label    = $this->is_override_label($settings);
			$override_ph       = $this->is_override_placeholder($settings);
			$override_class    = $this->is_override_class($settings);
			$override_priority = $this->is_override_priority($settings);

			foreach($fields as $key => $props){
				if($override_label && isset($props['label'])){
					unset($fields[$key]['label']);
				}

				if($override_ph && isset($props['placeholder'])){
					unset($fields[$key]['placeholder']);
				}

				if($override_class && isset($props['class'])){
					unset($fields[$key]['class']);
				}
				
				if($override_priority && isset($props['priority'])){
					unset($fields[$key]['priority']);
				}
			}
		}
		return $fields;
	}

	public function get_country_locale($locale) {
		$countries = array_merge( WC()->countries->get_allowed_countries(), WC()->countries->get_shipping_countries() );
		$countries = array_keys($countries);

		if(is_array($locale) && is_array($countries)){
			foreach($countries as $country){
				if(isset($locale[$country])){
					$locale[$country] = $this->prepare_country_locale($locale[$country]);
				}
			}
		}
		return $locale;
	}
	
	public function billing_fields($fields, $country){
		if(is_wc_endpoint_url('edit-address')){
			$fields = $this->prepare_address_fields(get_option('wc_fields_billing'), $country, $fields, 'billing');
			foreach ($fields as $key => $field) {
				$value = get_user_meta(get_current_user_id(), $key , true);
				if(isset($value) && !empty($value)){
					$field['value'] = $value;
				}else{
					if(isset($field['default'])){
						$field['value'] = $field['default'];
					}else{
						$field['value'] = '';
					}
				}
				$fields[$key] = $field;
			}
			return $fields;
		}else{
			
			return $this->prepare_address_fields(get_option('wc_fields_billing'), $country, $fields, 'billing');
			
		}
	}

	// public function woo_address_to_edit($address, $load_address = 'billing'){
	// 	$fields_test = THWCFD_Utils::get_checkout_fields();
	// 	$fields = THWCFD_Utils::get_fields($load_address);
	// 	foreach ($fields as $key => $field) {
	// 		$value = get_user_meta(get_current_user_id(), $key , true);
	// 		if(isset($value) && !empty($value)){
	// 			$field['value'] = $value;
	// 		}else{
	// 			if(isset($field['default'])){
	// 				$field['value'] = $field['default'];
	// 			}else{
	// 				$field['value'] = '';
	// 			}
				
	// 		}
	// 		$fields[$key] = $field;
	// 	}
	// 	if(is_account_page()){
	// 		return $fields;
	// 	}
	// }

	public function shipping_fields($fields, $country){
		if(is_wc_endpoint_url('edit-address')){
			$fields = $this->prepare_address_fields(get_option('wc_fields_shipping'), $country, $fields, 'shipping');
			foreach ($fields as $key => $field) {
				$value = get_user_meta(get_current_user_id(), $key , true);
				if(isset($value) && !empty($value)){
					$field['value'] = $value;
				}else{
					if(isset($field['default'])){
						$field['value'] = $field['default'];
					}else{
						$field['value'] = '';
					}
				}
				$fields[$key] = $field;
			}
			return $fields;
		}else{
			
			return $this->prepare_address_fields(get_option('wc_fields_shipping'), $country, $fields, 'shipping');
		}
	}
	
	public function checkout_fields($fields) {
		$additional_fields = get_option('wc_fields_additional');

		if(is_array($additional_fields)){
			if(isset($fields['order']) && is_array($fields['order'])){
				$fields['order'] = $additional_fields + $fields['order'];
			}

			// check if order_comments is enabled/disabled
			if(isset($additional_fields['order_comments']['enabled']) && !$additional_fields['order_comments']['enabled']){
				unset($fields['order']['order_comments']);
			}
		}
				
		if(isset($fields['order']) && is_array($fields['order'])){
			$fields['order'] = $this->prepare_checkout_fields($fields['order'], false);
		}

		if(isset($fields['order']) && !is_array($fields['order'])){
			unset($fields['order']);
		}
		return $fields;
	}

	public function prepare_address_fields($fieldset, $country, $original_fieldset = false, $sname = 'billing'){
		if(is_array($fieldset) && !empty($fieldset)) {
			$locale = WC()->countries->get_country_locale();

			if(isset($locale[ $country ]) && is_array($locale[ $country ])) {
				$override_required_prop = $this->is_override_required_prop();
				$states = WC()->countries->get_states( $country );

				foreach($locale[ $country ] as $key => $value){
					$fname = $sname.'_'.$key;

					if(is_array($value) && isset($fieldset[$fname])){
						if(!$override_required_prop && isset($value['required'])){
							$fieldset[$fname]['required'] = $value['required'];
						}

						if($key === 'state'){
							if(is_array($states) && empty($states)){
								$fieldset[$fname]['hidden'] = true;
							}
						}else{
							if(isset($value['hidden'])){
								$fieldset[$fname]['hidden'] = $value['hidden'];
							}
						}
					}
				}
			}
			
			$fieldset = $this->prepare_checkout_fields($fieldset, $original_fieldset);
			return $fieldset;
		}else {
			return $original_fieldset;
		}
	}

	public function prepare_checkout_fields($fields, $original_fields) {
		if(is_array($fields) && !empty($fields)) {
			$override_required_prop = $this->is_override_required_prop();

			foreach($fields as $name => $field) {
				if(THWCFD_Utils::is_enabled($field)) {
					$new_field = false;
					$allow_override = apply_filters('thwcfd_allow_default_field_override_'.$name, false);
					
					if($original_fields && isset($original_fields[$name]) && !$allow_override){
						$new_field = $original_fields[$name];

						$class     = isset($field['class']) && is_array($field['class']) ? $field['class'] : array();
						$required  = isset($field['required']) ? $field['required'] : 0;
						$is_hidden = isset($field['hidden']) && $field['hidden'] ? true : false;

						if($is_hidden){
							$new_field['hidden'] = $field['hidden'];
							$new_field['required'] = false;
						}else{
							if($override_required_prop){
								$new_field['required'] = $required;
							}
						}

						if($override_required_prop){
							if($required){
								$class[] = 'thwcfd-required';
							}else{
								$class[] = 'thwcfd-optional';
							}
						}
						
						$new_field['label'] = isset($field['label']) ? $field['label'] : '';
						$new_field['default'] = isset($field['default']) ? $field['default'] : '';
						$new_field['placeholder'] = isset($field['placeholder']) ? $field['placeholder'] : '';
						$new_field['class'] = $class;
						$new_field['label_class'] = isset($field['label_class']) && is_array($field['label_class']) ? $field['label_class'] : array();
						$new_field['validate'] = isset($field['validate']) && is_array($field['validate']) ? $field['validate'] : array();
						$new_field['priority'] = isset($field['priority']) ? $field['priority'] : '';
					} else {
						$new_field = $field;
					}

					$type = isset($new_field['type']) ? $new_field['type'] : 'text';

					$new_field['class'][] = 'thwcfd-field-wrapper';
					$new_field['class'][] = 'thwcfd-field-'.$type;
					
					if($type === 'select' || $type === 'radio'){
						if(isset($new_field['options'])){
							$options_arr = THWCFD_Utils::prepare_field_options($new_field['options']);
							$options = array();
							foreach($options_arr as $key => $value) {
								$options[$key] = __($value, 'woo-checkout-field-editor-pro');
							}
							$new_field['options'] = $options;
						}
					}

					if(($type === 'select' || $type === 'multiselect') && apply_filters('thwcfd_enable_select2_for_select_fields', true)){
						$new_field['input_class'][] = 'thwcfd-enhanced-select';
					}
					
					if(isset($new_field['label'])){
						$new_field['label'] = __($new_field['label'], 'woo-checkout-field-editor-pro');
					}

					if(isset($new_field['placeholder'])){
						$new_field['placeholder'] = __($new_field['placeholder'], 'woo-checkout-field-editor-pro');
					}
					
					$fields[$name] = $new_field;
				}else{
					unset($fields[$name]);
				}
			}
			return $fields;
		}else {
			return $original_fields;
		}
	}

	/*************************************
	----- Validate & Update - START ------
	*************************************/
	public function checkout_fields_validation($posted, $errors){
		$checkout_fields = WC()->checkout->checkout_fields;
		
		foreach($checkout_fields as $fieldset_key => $fieldset){
			if($this->maybe_skip_fieldset($fieldset_key, $posted)){
				continue;
			}
			
			foreach($fieldset as $key => $field) {
				if(isset($posted[$key]) && !THWCFD_Utils::is_blank($posted[$key])){
					$this->validate_custom_field($key, $field, $posted, $errors);
				}
			}
		}
	}

	public function validate_custom_field($key, $field, $posted, $errors=false, $return=false){
		$err_msgs = array();
		$value = isset($posted[$key]) ? $posted[$key] : '';
		$validators = isset($field['validate']) ? $field['validate'] : '';

		if($value && is_array($validators) && !empty($validators)){
			foreach($validators as $vname){
				$err_msg = '';
				$flabel = isset($field['label']) ? $field['label'] : $key;

				if($vname === 'number'){
					if(!is_numeric($value)){
						$err_msg = sprintf( __( '<strong>%s</strong> is not a valid number.', 'woo-checkout-field-editor-pro' ), $flabel );
					}
				}else if($vname === 'url'){
					if (!filter_var($value, FILTER_VALIDATE_URL)) {
						$err_msg = sprintf( __( '<strong>%s</strong> is not a valid url.', 'woo-checkout-field-editor-pro' ), $flabel );
					}
				}
				if($err_msg){
					if($errors || !$return){
						$this->add_validation_error($err_msg, $errors);
					}
					$err_msgs[] = $err_msg;
				}
			}
		}
		return !empty($err_msgs) ? $err_msgs : false;
	}

	public function add_validation_error($msg, $errors=false){
		if($errors){
			$errors->add('validation', $msg);
		}else if(THWCFD_Utils::woo_version_check('2.3.0')){
			wc_add_notice($msg, 'error');
		} else {
			WC()->add_error($msg);
		}
	}

	public function checkout_update_order_meta($order_id, $posted){
		$types = array('billing', 'shipping', 'additional');

		foreach($types as $type){
			if($this->maybe_skip_fieldset($type, $posted)){
				continue;
			}

			$fields = THWCFD_Utils::get_fields($type);
			
			foreach($fields as $name => $field){
				if(THWCFD_Utils::is_active_custom_field($field) && isset($posted[$name]) && !THWCFD_Utils::is_wc_handle_custom_field($field)){
					$value = null;
					$type = isset($field['type']) ? $field['type'] : 'text';

					if($type == 'textarea'){
						$value =  isset($posted[$name]) ? sanitize_textarea_field($posted[$name]) : '';
					}else if($type == 'email'){
						$value =  isset($posted[$name]) ? sanitize_email($posted[$name]) : '';
					}else if(($type == 'select') || ($type == 'radio')){
						$options = isset($field['options']) ? $field['options'] : array();
						$value =  isset($posted[$name]) ? sanitize_text_field($posted[$name]) : '';
						$value = array_key_exists($value, $options) ? $value : '';
					}else if($type == 'checkboxgroup' || $type == 'multiselect'){
						$options = isset($field['options']) ? $field['options'] : array();
						$submitted_options =  isset($posted[$name]) ? $posted[$name] : array();
						if(! is_array($submitted_options)){
							$submitted_options = explode(", ", $submitted_options);
						}						
						$options_key = array_keys($options);
						if(!empty($submitted_options)){
							foreach($submitted_options as $key => $single_option){
								if(!in_array ($single_option, $options_key)){
									unset ($submitted_options[$key]);
								}
							}
						}
						if(!empty($submitted_options)){
							$value  = implode(",", $submitted_options);
						}
					}else if($type == 'checkbox'){
						$value =  isset($posted[$name]) ? sanitize_text_field($posted[$name]) : '';
						if($value){
							$value = !empty($field['default']) ? $field['default'] : $value;
						}else{
							$value = apply_filters('thwcfd_checkbox_field_off_value', $value , $name);
						}
					}else{
						$value =  isset($posted[$name]) ? sanitize_text_field($posted[$name]) : '';						
					}
					if($value){
						$result = update_post_meta($order_id, $name, $value);
					}
				}
			}
		}
	}

	private function maybe_skip_fieldset( $fieldset_key, $data ) {
        $ship_to_different_address = isset($data['ship_to_different_address']) ? $data['ship_to_different_address'] : false;
        $ship_to_destination = get_option( 'woocommerce_ship_to_destination' );

        if ( 'shipping' === $fieldset_key && ( ! $ship_to_different_address || ! WC()->cart->needs_shipping_address() ) ) {
            return  $ship_to_destination != 'billing_only' ? true : false;
        }
        return false;
    }
	
	/****************************************
	----- Display Field Values - START ------
	*****************************************/
	/**
	 * Display custom fields in emails
	 */
	public function display_custom_fields_in_emails($ofields, $sent_to_admin, $order){
		$custom_fields = array();
		$fields = THWCFD_Utils::get_checkout_fields();

		// Loop through all custom fields to see if it should be added
		foreach( $fields as $key => $field ) {
			if(isset($field['show_in_email']) && $field['show_in_email'] && !THWCFD_Utils::is_wc_handle_custom_field($field)){
				$order_id = THWCFD_Utils::get_order_id($order);
				$value = get_post_meta( $order_id, $key, true );
				
				if($value){
					$label = isset($field['label']) && $field['label'] ? $field['label'] : $key;
					//$label = esc_attr($label);
					$value = THWCFD_Utils::get_option_text($field, $value);

					$f_type = isset($field['type']) ? $field['type'] : 'text';
					$value = esc_html__($value, 'woo-checkout-field-editor-pro');
					if($f_type == 'textarea'){
						$value =  nl2br($value);
					}
					
					$custom_field = array();
					$custom_field['label'] = wp_kses_post(__($label, 'woo-checkout-field-editor-pro'));
					$custom_field['value'] = $value;
					
					$custom_fields[$key] = $custom_field;
				}
			}
		}

		return array_merge($ofields, $custom_fields);
	}	
	
	/**
	 * Display custom checkout fields on view order pages
	 */
	public function order_details_after_customer_details($order){
		$order_id = THWCFD_Utils::get_order_id($order);
		$fields = THWCFD_Utils::get_checkout_fields($order);
		if(is_array($fields) && !empty($fields)){
			$fields_html = '';
			// Loop through all custom fields to see if it should be added
			foreach($fields as $key => $field){	
				if(THWCFD_Utils::is_active_custom_field($field) && isset($field['show_in_order']) && $field['show_in_order'] && !THWCFD_Utils::is_wc_handle_custom_field($field)){
					$value = get_post_meta( $order_id, $key, true );
					if($value){
						$label = isset($field['label']) && $field['label'] ? $field['label'] : $key;
						//$label = esc_attr($label);
						$label = wp_kses_post(__($label, 'woo-checkout-field-editor-pro'));
						//$value = wptexturize($value);
						$value = THWCFD_Utils::get_option_text($field, $value);

						$f_type = isset($field['type']) ? $field['type'] : 'text';
						$value = esc_html__($value, 'woo-checkout-field-editor-pro');
						if($f_type == 'textarea'){
							$value =  nl2br($value);
						}
						
						if(is_account_page()){
							if(apply_filters( 'thwcfd_view_order_customer_details_table_view', true )){
								$fields_html .= '<tr><th>'. $label .':</th><td>'. $value .'</td></tr>';
							}else{
								$fields_html .= '<br/><dt>'. $label .':</dt><dd>'. $value .'</dd>';
							}
						}else{
							if(apply_filters( 'thwcfd_thankyou_customer_details_table_view', true )){
								$fields_html .= '<tr><th>'. $label .':</th><td>'. $value .'</td></tr>';
							}else{
								$fields_html .= '<br/><dt>'. $label .':</dt><dd>'. $value .'</dd>';
							}
						}
					}
				}
			}
			
			if($fields_html){
				do_action( 'thwcfd_order_details_before_custom_fields_table', $order ); 
				?>
				<table class="woocommerce-table woocommerce-table--custom-fields shop_table custom-fields">
					<?php
						echo $fields_html;
					?>
				</table>
				<?php
				do_action( 'thwcfd_order_details_after_custom_fields_table', $order ); 
			}
		}
	}
	/*****************************************
	----- Display Field Values - END --------
	*****************************************/


	public function woo_form_field($field, $key, $args, $value = null){

		if(is_admin()){
			return $field;
		}
		$field = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
		} else {
			$required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
		}

		if (is_string($args['label_class'])) {
			$args['label_class'] = array($args['label_class']);
		}

		if(is_null($value)){
			$value = $args['default'];
		}

		// Custom attribute handling.
		$custom_attributes = array();
		$args['custom_attributes'] = array_filter((array) $args['custom_attributes'], 'strlen');

		if ($args['maxlength']) {
			$args['custom_attributes']['maxlength'] = absint($args['maxlength']);
		}

		if (!empty($args['autocomplete'])) {
			$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
		}

		if (true === $args['autofocus']) {
			$args['custom_attributes']['autofocus'] = 'autofocus';
		}

		if ($args['description']) {
			$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
		}

		if (!empty($args['custom_attributes']) && is_array($args['custom_attributes'])) {
			foreach ($args['custom_attributes'] as $attribute => $attribute_value) {
				$custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
			}
		}

		if (!empty($args['validate'])) {
			foreach ($args['validate'] as $validate) {
				$args['class'][] = 'validate-' . $validate;
			}
		}

		//$field           = '';
		$label_id = $args['id'];
		$sort = $args['priority'] ? $args['priority'] : '';
		$field_container = '<p class="form-row %1$s" id="%2$s" data-priority="' . esc_attr($sort) . '">%3$s</p>';

		switch ($args['type']) {

			case 'multiselect':

				$field = '';

				$value = is_array($value) ? $value : array_map('trim', (array) explode(',', $value));

				if (!empty($args['options'])) {
					$field .= '<select name="' . esc_attr($key) . '[]" id="' . esc_attr($key) . '" class="select ' . esc_attr(implode(' ', $args['input_class'])) . '" multiple="multiple" ' . esc_attr(implode(' ', $custom_attributes)) . ' data-placeholder="' . esc_html__($args['placeholder'], 'woo-checkout-field-editor-pro') . '" >';
					foreach ($args['options'] as $option_key => $option_text) {
						$field .= '<option value="' . esc_attr($option_key) . '" ' . selected(in_array($option_key, $value), 1, false) . '>' . esc_html__($option_text, 'woo-checkout-field-editor-pro') . '</option>';
					}
					$field .= ' </select>';
				}

			break;

			case 'checkbox' :
				$field = '';
				if(isset($args['checked']) && $args['checked']){
					$value = 1;
				}else{
					$value = 0;
				}
				$default_value = !empty($args['default']) ? esc_attr($args['default']) : 1; 

				$field .= '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
						<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="'.$default_value.'" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';
			break;

			case 'checkboxgroup':
				$field = '';

				$value = is_array($value) ? $value : array_map('trim', (array) explode(',', $value));

				if (!empty($args['options'])) {

					$field .= ' <span class="woocommerce-multicheckbox-wrapper" ' . esc_attr(implode(' ', $custom_attributes)) . '>';

					foreach ($args['options'] as $option_key => $option_text) {
						$field .= '<label><input type="checkbox" name="' . esc_attr($key) . '[]" value="' . esc_attr($option_key) . '"' . checked(in_array($option_key, $value), 1, false) . ' /> ' . esc_html__($option_text, 'woo-checkout-field-editor-pro') . '</label>';
					}

					$field .= '</span>';
				}
			break;

			case 'datetime_local':
				$field = '';

				$field .= '<input type="datetime-local" name="' . esc_attr( $key ) . '"  id="' . esc_attr( $key ) . '" value="' . esc_attr( $value) . '" />';
			break;

			case 'date':

				$field = '';

				$field .= '<input type="date" name="' . esc_attr( $key ) . '"  id="' . esc_attr( $key ) . '" value="' . esc_attr( $value) . '" />';
			break;
			case 'time':

				$field = '';

				$field .= '<input type="time" name="' . esc_attr( $key ) . '"  id="' . esc_attr( $key ) . '" value="' . esc_attr( $value) . '" />';
			break;
			case 'month':

				$field = '';

				$field .= '<input type="month" name="' . esc_attr( $key ) . '"  id="' . esc_attr( $key ) . '" value="' . esc_attr( $value) . '" />';
			break;
			case 'week':

				$field = '';

				$field .= '<input type="week" name="' . esc_attr( $key ) . '"  id="' . esc_attr( $key ) . '" value="' . esc_attr( $value) . '" />';
			break;

			case 'url':

				$field = '';

				$field .= '<input type="url" name="' . esc_attr( $key ) . '"  id="' . esc_attr( $key ) . '" placeholder ="'.esc_attr($args['placeholder']). '" value="' . esc_attr( $value) . '" />';
			break;

			case 'file':

				$field = '';

			break;
		}

		if (!empty($field)) {
			$field_html = '';

			if ($args['label'] && 'checkbox' !== $args['type']) {
				$field_html .= '<label for="' . esc_attr($label_id) . '" class="' . esc_attr(implode(' ', $args['label_class'])) . '">' . esc_html__($args['label'], 'woo-checkout-field-editor-pro') . $required . '</label>';
			}

			$field_html .= '<span class="woocommerce-input-wrapper">' . $field;

			if ($args['description']) {
				$field_html .= '<span class="description" id="' . esc_attr($args['id']) . '-description" aria-hidden="true">' . wp_kses_post($args['description']) . '</span>';
			}

			$field_html .= '</span>';

			$container_class = esc_attr(implode(' ', $args['class']));
			$container_id = esc_attr($args['id']) . '_field';
			$field = sprintf($field_container, $container_class, $container_id, $field_html);
		}
		return $field;
	}

	public function woo_form_field_hidden($field, $key, $args, $value){
		if(is_null($value) || (is_string($value) && $value === '')){
            $value = $args['default'];
        }

		$field  = '<input type="hidden" id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" value="'. esc_attr( $value ) .'" class="'.esc_attr(implode(' ', $args['class'])).'" />';
		return $field;
	}

	public function woo_form_field_paragraph($field, $key, $args, $value){
		$args['class'][] = 'thwcfd-field-wrapper thwcfd-field-paragraph';
		
		if(isset($args['label']) && !empty($args['label'])){
			$field  = '<p class="form-row '.esc_attr(implode(' ', $args['class'])).'" id="'.esc_attr($key).'_field" >'. esc_html__($args['label'], 'woo-checkout-field-editor-pro') .'</ p >';
		}

		return $field;
	}

	public function woo_form_field_heading($field, $key, $args, $value = null){
    	$args['class'][] = 'thwcfd-field-wrapper thwcfd-field-heading';
		
		$heading_html = '';
		$field  = '';

		if(isset($args['label']) && !empty($args['label'])){
			$title_type  = isset($args['title_type']) && !empty($args['title_type']) ? $args['title_type'] : 'label';

			$heading_html .= '<'. esc_attr($title_type) .' class="'. esc_attr(implode(' ', $args['label_class'])) .'" >'. esc_html__($args['label'], 'woo-checkout-field-editor-pro') .'</'. $title_type .'>';
		}

		if(!empty($heading_html)){
			$field .= '<div class="form-row '.esc_attr(implode(' ', $args['class'])).'" id="'.esc_attr($key).'_field" data-name="'.esc_attr($key).'" >'. $heading_html .'</div>';
		}
		return $field;		
	}
	
}

endif;
