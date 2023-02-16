<?php

function fca_ga_tooltip( $title = 'Tooltip', $icon = 'dashicons dashicons-editor-help' ) {
	$title = esc_attr( $title );
	return "<span class='$icon fca_ga_tooltip' title='$title'></span>";
}

//RETURN GENERIC INPUT HTML
function fca_ga_input( $name, $placeholder = '', $value = '', $type = 'text' ) {

	$name = esc_attr( $name );
	$placeholder = empty( $placeholder ) ? '' : esc_attr( $placeholder );
	$value = empty( $value ) ? '' : esc_attr( $value );

	$html = "<div class='fca-ga-field fca-ga-field-$type'>";
	
		switch ( $type ) {
			
			case 'checkbox':
				$checked = !empty( $value ) ? "checked='checked'" : '';
				
				$html .= "<div class='onoffswitch'>";
					$html .= "<input style='display:none;' type='checkbox' id='fca_ga[$name]' class='onoffswitch-checkbox fca-ga-input-$type fca-ga-$name' name='fca_ga[$name]' $checked>"; 
					$html .= "<label class='onoffswitch-label' for='fca_ga[$name]'><span class='onoffswitch-inner' data-content-on='ON' data-content-off='OFF'><span class='onoffswitch-switch'></span></span></label>";
				$html .= "</div>";
				break;
				
			case 'textarea':
				$html .= "<textarea placeholder='$placeholder' class='fca-ga-input-$type fca-ga-$name' name='fca_ga[$name]'>$value</textarea>";
				break;
			
			
			default: 
				$html .= "<input type='$type' placeholder='$placeholder' class='fca-ga-input-$type fca-ga-$name' name='fca_ga[$name]' value='$value'>";
		}
	
	$html .= '</div>';
	
	return $html;
}

//SINGLE-SELECT
function fca_ga_select_multiple( $name, $selected = array(), $options = array() ) {
	if( !is_array( $selected ) ) {
		$selected = array();
	}
	
	$html = "<select name='fca_ga[$name][]' multiple='multiple' style='width: 100%;'  class='fca_ga_multiselect'>";
		
			forEach ( $options as $key => $text ) {
				$sel = in_array( $key, $selected ) ? 'selected="selected"' : '';
				$value = esc_attr( $key );
				$text = esc_html( $text );
	
				$html .= "<option $sel value='$value'>$text</option>";
			}
		
	$html .= '</select>';
	
	return $html;
}

function fca_ga_sanitize_text_array( $array ) {
	if ( !is_array( $array ) ) {
		return sanitize_text_field( $array );
	}
	
	$new_array = array();
	
	foreach ( $array as $value ) {
		$new_array[] = sanitize_text_field( $value );
	}

	return $new_array;
}
