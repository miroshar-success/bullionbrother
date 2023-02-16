<?php
/**
 * Add extra profile fields for users in admin
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}



/**
 * Xoo_El_User_Profile Class.
 */
class Xoo_El_User_Profile {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'add_customer_meta_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'add_customer_meta_fields' ) );

		add_action( 'personal_options_update', array( $this, 'save_customer_meta_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_customer_meta_fields' ) );
	}


	/**
	 * Show Address Fields on edit user pages.
	 *
	 * @param WP_User $user
	 */
	public function add_customer_meta_fields( $user ) {
	
		$fields = xoo_el()->aff->fields->get_fields_data();

		$fields = apply_filters( 'xoo_el_user_profile_fields', $fields );

		if( empty( $fields ) ) return;

		?>


		<h2>Login/Signup Pop up fields</h2>

		<table class="form-table xoo-aff-form-table">

			<?php

			foreach ( $fields as $field_id => $field_data ) {

				//Skip if predefined field
				if( strpos( $field_id , 'xoo_el_' ) !== false ) continue;

				$args = array(
					'value' => get_user_meta( $user->ID, $field_id,true),
				);

				$args = xoo_el()->aff->fields->get_field_html_args( $field_id, $args );

				if( $args['label'] ){
					$label = $args['label'];
				}elseif ( $args['placeholder'] ) {
					$label = $args['placeholder'];
				}else{
					$label = $field_id;
				}

				unset( $args['label'] );

				echo '<tr>';

				echo '<th>'. esc_html( $label) .'</th>';

				echo '<td>';

				if( $field_data['input_type'] === 'states' ){
					$args['input_type'] = 'text';
					$args['description'] = 'Add state code';
				}

				xoo_el()->aff->fields->get_input_html( $field_id, $args );

				echo '</td>';

				echo '</tr>';
			}

			?>

		</table>

		<?php
		

	}


	/**
	 * Save Address Fields on edit user pages.
	 *
	 * @param int $user_id User ID of the user being saved
	 */
	public function save_customer_meta_fields( $user_id ) {

		$save_fields = xoo_el()->aff->fields->get_fields_data();
		if( empty( $save_fields ) ) return;

		foreach ( $save_fields as $field_id => $field_data ) {

			if( isset( $_POST[ $field_id ] ) ){
				if( is_array( $_POST[ $field_id ] ) ){
					$value = array_map( 'sanitize_text_field', $_POST[ $field_id ] );
				}
				else{
					$value = sanitize_text_field( $_POST[ $field_id ] );
				}
			}
			else{
				$value = '';
			}
			update_user_meta( $user_id, $field_id, $value );
		}
	}

	/**
	 * Get user meta for a given key, with fallbacks to core user info for pre-existing fields.
	 *
	 * @since 3.1.0
	 * @param int    $user_id User ID of the user being edited
	 * @param string $field_id     Key for user meta field
	 * @return string
	 */
	protected function get_user_meta( $user_id, $field_id ) {
		$value           = get_user_meta( $user_id, $field_id, true );
		$existing_fields = array( 'billing_first_name', 'billing_last_name' );
		if ( ! $value && in_array( $field_id, $existing_fields ) ) {
			$value = get_user_meta( $user_id, str_replace( 'billing_', '', $field_id ), true );
		} elseif ( ! $value && ( 'billing_email' === $field_id ) ) {
			$user  = get_userdata( $user_id );
			$value = $user->user_email;
		}

		return $value;
	}
}


return new Xoo_El_User_Profile();
