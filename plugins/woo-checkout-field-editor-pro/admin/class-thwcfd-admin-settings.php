<?php
/**
 * The admin settings page functionality of the plugin.
 *
 * @link       https://themehigh.com
 *
 * @package    woo-checkout-field-editor-pro
 * @subpackage woo-checkout-field-editor-pro/admin
 */

if(!defined('WPINC')){ die; }

if(!class_exists('THWCFD_Admin_Settings')):

abstract class THWCFD_Admin_Settings{
	protected $page_id = '';
	protected $section_id = '';
	
	protected $tabs = '';
	protected $sections = '';

	public function __construct() {
		$this->tabs = array(
			'fields' => __('Checkout Fields', 'woo-checkout-field-editor-pro'),
			'advanced_settings' => __('Advanced Settings', 'woo-checkout-field-editor-pro'),
			'pro' => __('Premium Features', 'woo-checkout-field-editor-pro'),
			'themehigh_plugins' => __('Other Free Plugins', 'woo-checkout-field-editor-pro'),
		);		
	}
	
	public function get_tabs(){
		return $this->tabs;
	}

	public function get_current_tab(){
		return $this->page_id;
	}
	
	public function get_current_section(){
		return isset( $_GET['section'] ) ? sanitize_key( $_GET['section'] ) : $this->section_id;
	}
	
	public function render_tabs(){
		$current_tab = $this->get_current_tab();
		$tabs = $this->get_tabs();

		if(empty($tabs)){
			return;
		}
		
		echo '<h2 class="thpladmin-tabs nav-tab-wrapper woo-nav-tab-wrapper">';
		foreach( $tabs as $id => $label ){
			$active = ( $current_tab == $id ) ? 'nav-tab-active' : '';
			//$label  = esc_html__($label, 'woo-checkout-field-editor-pro');
			echo '<a class="nav-tab '.$active.'" href="'. esc_url($this->get_admin_url($id)) .'">'.$label.'</a>';
		}
		echo '</h2>';		
	}
	
	// public function render_sections() {
	// 	$current_section = $this->get_current_section();
	// 	$sections = $this->get_sections();

	// 	if(empty($sections)){
	// 		return;
	// 	}
		
	// 	$array_keys = array_keys( $sections );
		
	// 	echo '<ul class="thpladmin-sections">';
	// 	foreach( $sections as $id => $label ){
	// 		$label = wp_strip_all_tags(__($label, 'woo-checkout-field-editor-pro'));
	// 		$url = $this->get_admin_url($this->page_id, sanitize_title($id));	
	// 		echo '<li><a href="'. $url .'" class="'. ( $current_section == $id ? 'current' : '' ) .'">'. $label .'</a> '. (end( $array_keys ) == $id ? '' : '|') .' </li>';
	// 	}		
	// 	echo '</ul>';
	// }	
	
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

	public function print_notices($msg, $type='updated', $return=false){
		$notice = '<div class="thwcfd-notice '. $type .'"><p>'. $msg .'</p></div>';
		if(!$return){
			echo $notice;
		}
		return $notice;
	}
	
   /*******************************************
	*-------- HTML FORM FRAGMENTS - START -----
	*******************************************/
	
	public function render_form_element_tooltip($tooltip=''){
		$tooltip_html = '';
		
		if($tooltip){
			// $icon = THWCFD_ASSETS_URL_ADMIN.'/css/help.png';
			// $tooltip_html = '<a href="javascript:void(0)" title="'. $tooltip .'" class="thpladmin_tooltip"><img src="'. $icon .'" alt="" title=""/></a>';
		}
		?>
        <td style="width: 26px; padding:0px;"><?php esc_html($tooltip_html); ?></td>
        <?php
	}
	
	public function render_form_element_empty_cell(){
		?>
		<td width="13%">&nbsp;</td>
        <?php $this->render_form_element_tooltip(false); ?>
        <td width="34%">&nbsp;</td>
        <?php
	}
	
	public function render_form_element_h_separator($padding = 5, $colspan = 6){
		?>
        <tr><td colspan="<?php echo $colspan; ?>" style="border-bottom: 1px dashed #e6e6e6; padding-top: <?php echo $padding ?>px;"></td></tr>
        <?php
	}
	
	public function render_form_element_h_spacing($padding = 5, $colspan = 6){
		?>
        <tr><td colspan="<?php echo $colspan; ?>" style="padding-top:<?php echo $padding ?>px;"></td></tr>
        <?php
	}
	
	public function render_form_field_element($field, $atts = array(), $render_cell = true){
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_cell_props' => '',
				'input_cell_props' => '',
				'label_cell_colspan' => '',
				'input_cell_colspan' => '',
			), $atts );
		
			$ftype     = isset($field['type']) ? $field['type'] : 'text';
			$flabel    = isset($field['label']) && !empty($field['label']) ? __($field['label'], 'woo-checkout-field-editor-pro') : '';
			$sub_label = isset($field['sub_label']) && !empty($field['sub_label']) ? __($field['sub_label'], 'woo-checkout-field-editor-pro') : '';
			$tooltip   = isset($field['hint_text']) && !empty($field['hint_text']) ? __($field['hint_text'], 'woo-checkout-field-editor-pro') : '';
			
			$field_html = '';
			
			if($ftype == 'text'){
				$field_html = $this->render_form_field_element_inputtext($field, $atts);
				
			}else if($ftype == 'textarea'){
				$field_html = $this->render_form_field_element_textarea($field, $atts);
				   
			}else if($ftype == 'checkbox'){
				$field_html = $this->render_form_field_element_checkbox($field, $atts, $render_cell);   
				$flabel 	= '&nbsp;';  
			}
			
			if($render_cell){
				$required_html = isset($field['required']) && $field['required'] ? '<abbr class="required" title="required">*</abbr>' : '';
				
				$label_cell_props = !empty($args['label_cell_props']) ? $args['label_cell_props'] : '';
				$input_cell_props = !empty($args['input_cell_props']) ? $args['input_cell_props'] : '';
				
				?>
				<td <?php echo $label_cell_props ?> >
					<?php echo $flabel; echo $required_html; 
					if($sub_label){
						?>
						<br/><span class="thpladmin-subtitle"><?php echo $sub_label; ?></span>
						<?php
					}
					?>
				</td>
				<?php $this->render_form_element_tooltip($tooltip); ?>
				<td <?php echo $input_cell_props ?> ><?php echo $field_html; ?></td>
				<?php
			}else{
				echo $field_html;
			}
		}
	}

	private function prepare_form_field_props($field, $atts = array()){
		$field_props = '';
		$args = shortcode_atts( array(
			'input_width' => '',
			'input_name_prefix' => 'i_',
			'input_name_suffix' => '',
		), $atts );
		
		$ftype = isset($field['type']) ? $field['type'] : 'text';
		
		if($ftype == 'multiselect'){
			$args['input_name_suffix'] = $args['input_name_suffix'].'[]';
		}
		
		$fname  = $args['input_name_prefix'].$field['name'].$args['input_name_suffix'];
		$fvalue = isset($field['value']) ? esc_html($field['value']) : '';
		
		$input_width  = $args['input_width'] ? 'width:'.$args['input_width'].';' : '';
		$field_props  = 'name="'. $fname .'" value="'. $fvalue .'" style="'. $input_width .'"';
		$field_props .= ( isset($field['placeholder']) && !empty($field['placeholder']) ) ? ' placeholder="'.$field['placeholder'].'"' : '';
		$field_props .= ( isset($field['onchange']) && !empty($field['onchange']) ) ? ' onchange="'.$field['onchange'].'"' : '';
		
		return $field_props;
	}

	private function render_form_field_element_inputtext($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<input type="text" '. $field_props .' />';
		}
		return $field_html;
	}
	
	private function render_form_field_element_textarea($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'rows' => '5',
				'cols' => '100',
			), $atts );
		
			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<textarea '. $field_props .' rows="'.$args['rows'].'" cols="'.$args['cols'].'" >'.$fvalue.'</textarea>';
		}
		return $field_html;
	}

	private function render_form_field_element_checkbox($field, $atts = array(), $render_cell = true){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_props' => '',
				'cell_props'  => 3,
				'render_input_cell' => false,
			), $atts );
		
			$fid 	= 'a_f'. $field['name'];
			$flabel = isset($field['label']) && !empty($field['label']) ? __($field['label'], 'woo-checkout-field-editor-pro') : '';
			
			$field_props  = $this->prepare_form_field_props($field, $atts);
			$field_props .= isset($field['checked']) && $field['checked'] === 1 ? ' checked' : '';
			
			$field_html  = '<input type="checkbox" id="'. $fid .'" '. $field_props .' />';
			$field_html .= '<label for="'. $fid .'" '. $args['label_props'] .' > '. $flabel .'</label>';
		}
		if(!$render_cell && $args['render_input_cell']){
			return '<td '. $args['cell_props'] .' >'. $field_html .'</td>';
		}else{
			return $field_html;
		}
	}
   /*******************************************
	*-------- HTML FORM FRAGMENTS - END   -----
	*******************************************/
}

endif;