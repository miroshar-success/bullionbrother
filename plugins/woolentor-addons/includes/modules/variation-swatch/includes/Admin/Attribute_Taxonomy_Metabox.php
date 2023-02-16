<?php
namespace Woolentor\Modules\Swatchly\Admin;

/**
 * Attribute_Taxonomy_Metabox class
 */
class Attribute_Taxonomy_Metabox{
	public $image_defaults;

	public $fields;

	public $taxnow;

    /**
     * Constructor
     */
    public function __construct() {
		$this->image_defaults = array(
			'url'         => '',
			'id'          => '',
			'width'       => '',
			'height'      => '',
			'thumbnail'   => '',
			'alt'         => '',
			'title'       => '',
			'description' => '',
		);

        $this->taxonomy	= isset( $_GET['taxonomy'] ) ? sanitize_text_field( $_GET['taxonomy'] ) : '';
		$swatch_type = $this->get_taxonomy_swatch_type( $this->taxnow );

		$prefix      			  = 'swatchly_taxonomy_meta';
		$taxonomies_obj           = wc_get_attribute_taxonomies();
		$attribute_taxonomy_slugs = array_map( function($taxonomies_obj){
    		return 'pa_'. $taxonomies_obj->attribute_name;
    	}, $taxonomies_obj);

    	$this->fields = array(
			array(
				'id'           => 'swatchly_image',
				'type'         => 'media',
				'title'        => esc_html__( 'Swatch Image', 'woolentor' ),
				'url'          => false,
				'class'        => 'swatchly_show_if_image swatchly_hide_if_select'
    		),

			array(
			    'id'    => 'swatchly_tooltip',
			    'type'  => 'select',
			    'title' => esc_html__( 'Swatch Tooltip', 'woolentor' ),
				'options' => array(
					''        => esc_html__('Use Global Setting', 'woolentor'),
					'text'    => esc_html__('Text', 'woolentor'),
					'image'   => esc_html__('Image', 'woolentor'),
					'disable' => esc_html__('Disable', 'woolentor'),
				),
			    'class' => 'swatchly_hide_if_select'
			),

			array(
				'id'         => 'swatchly_tooltip_text',
				'type'       => 'text',
				'title'      => esc_html__( 'Tooltip Text', 'woolentor' ),
				'after'      => esc_html__( 'By default, the "Attribute Name" will be shown as the tooltip. If you want custom tooltip text, put it here.', 'woolentor' ),
				'condition'  => array( 'swatchly_tooltip', '==', 'text' ),
			),

			array(
				'id'         => 'swatchly_tooltip_image',
				'type'       => 'media',
				'title'      => esc_html__( 'Tooltip Image', 'woolentor' ),
				'url'        => false,
				'condition'  => array( 'swatchly_tooltip', '==', 'image' ),
    		),

			array(
			    'id'    => 'swatchly_color',
			    'type'  => 'color',
			    'title' => esc_html__( 'Swatch Color', 'woolentor' ),
			    'class' => 'swatchly_show_if_color swatchly_hide_if_select'
			),

			array(
			    'id'    => 'swatchly_enable_multi_color',
			    'type'  => 'checkbox',
			    'title' => esc_html__( 'Enable Multi Color', 'woolentor' ),
			    'label' => esc_html__( 'By checking this will enable you to set multiple color.', 'woolentor' ),
			    'class' => 'swatchly_show_if_color swatchly_hide_if_select'
			),

			array(
                'id'         => 'swatchly_color_2',
                'type'       => 'color',
                'title'      => esc_html__( 'Swatch Color 2', 'woolentor' ),
                'condition'  => array( 'swatchly_enable_multi_color', '==', '1' ),
				'class' 	 => 'swatchly_hide_if_select'
			),
    	);

    	 // Create taxonomy meta option wrapper
    	 $this->createTaxonomyOptions( $prefix, array(
    	   'taxonomy'  => $attribute_taxonomy_slugs,
    	   'data_type' => 'unserialize', // The type of the database save options. `serialize` or `unserialize`
    	   'class'	   => 'swatchly_type_'. $swatch_type
    	 ) );
    }

	public function createTaxonomyOptions( $prefix, $args ){
		$taxnow = isset($_REQUEST['taxonomy']) ? sanitize_text_field($_REQUEST['taxonomy']) : '';
		if( !$taxnow ){
			return;
		}

		// Make custom fields for the edit and add new screen of the attributes.
		add_action( $taxnow . '_add_form_fields', array($this, 'add_screen_custom_fields_html') );
		add_action( $taxnow . '_edit_form', array($this, 'edit_screen_custom_fields_html'), 10, 2);

		// Save custom fields
		add_action( 'edited_' . $taxnow, array($this, 'save_term_meta') );
		add_action( 'created_' . $taxnow, array( $this, 'save_term_meta') );
	}

	/**
	 * It adds custom fields to the add new attribute screen
	 */
	public function add_screen_custom_fields_html( $taxonomy ){
		$swatch_type = $this->get_taxonomy_swatch_type( $taxonomy );
		$field_default = array(
			'title'	=> '',
			'type'  => '',
			'class' => '',
			'condition' => array(),
		);

		// Nonce
		wp_nonce_field( 'swatchly_save_term_meta_nonce', 'swatchly_term_meta_nonce' );
		?>
		<div class="swatchly-cs-taxonomy swatchly-cs-taxonomy-add-fields swatchly_type_<?php echo esc_attr($swatch_type); ?>">
		<?php foreach($this->fields as $field):
				$field = wp_parse_args( $field, $field_default);
				
				$data_controller = '';
				$data_condition = '';
				$data_value = '';

				if( $field['condition'] ){
					if( !empty($field['condition'][0]) ){
						$data_controller = 'data-controller='. $field['condition'][0] .'';
					}

					if( !empty($field['condition'][1]) ){
						$data_condition = 'data-condition='. $field['condition'][1] .'';
					}

					if( !empty($field['condition'][1]) ){
						$data_value = 'data-value='. $field['condition'][2] .'';
					}
				}
			?>
			<div class="swatchly-cs-field swatchly-cs-field-<?php echo esc_attr($field['type']); ?> <?php echo esc_attr($field['class']); ?>" <?php echo esc_attr($data_controller) ?> <?php echo esc_attr($data_condition) ?> <?php echo esc_attr($data_value) ?>>
				<div class="swatchly-cs-title">
					<h4><?php echo esc_html($field['title']) ?></h4>
				</div>
				
				<?php $this->field_html( 'add', $field ) ?>
			</div>
		<?php endforeach; ?>
		</div>
		
		<?php
	}

	/**
	 * It adds a new field to the edit screen of the taxonomy
	 * 
	 * @param tag The term object.
	 * @param taxonomy The taxonomy slug.
	 */
	public function edit_screen_custom_fields_html($tag, $taxonomy){
		$swatch_type = $this->get_taxonomy_swatch_type( $taxonomy );
		$tax_meta  	 = array(
			'swatchly_color'                      => get_term_meta($tag->term_id, 'swatchly_color', true),
			'swatchly_enable_multi_color'         => get_term_meta($tag->term_id, 'swatchly_enable_multi_color', true),
			'swatchly_color_2'                    => get_term_meta($tag->term_id, 'swatchly_color_2', true),
			'swatchly_image'                      => wp_parse_args( get_term_meta($tag->term_id, 'swatchly_image', true), $this->image_defaults ),
			'swatchly_tooltip'                    => get_term_meta($tag->term_id, 'swatchly_tooltip', true),
			'swatchly_tooltip_text'               => get_term_meta($tag->term_id, 'swatchly_tooltip_text', true),
			'swatchly_tooltip_image'              => wp_parse_args( get_term_meta($tag->term_id, 'swatchly_tooltip_image', true), $this->image_defaults ),
		);
		
		$swatch_type = $this->get_taxonomy_swatch_type( $taxonomy );

		$field_default = array(
			'title'	=> '',
			'type'  => '',
			'class' => '',
			'condition' => array(),
		);

		wp_nonce_field( 'swatchly_save_term_meta_nonce', 'swatchly_term_meta_nonce' );
		?>
		<div class="swatchly-cs-taxonomy swatchly-cs-taxonomy-edit-fields swatchly_type_<?php echo esc_attr($swatch_type); ?>">

		<?php foreach($this->fields as $field):
				$field = wp_parse_args( $field, $field_default);
				$data_controller = '';
				$data_condition = '';
				$data_value = '';

			if( $field['condition'] ){
				if( !empty($field['condition'][0]) ){
					$data_controller = 'data-controller='. $field['condition'][0] .'';
				}

				if( !empty($field['condition'][1]) ){
					$data_condition = 'data-condition='. $field['condition'][1] .'';
				}

				if( !empty($field['condition'][1]) ){
					$data_value = 'data-value='. $field['condition'][2] .'';
				}
			}
			?>
			<div class="swatchly-cs-field swatchly-cs-field-<?php echo esc_attr($field['type']); ?> <?php echo $field['class'];?>" <?php echo esc_attr($data_controller) ?> <?php echo esc_attr($data_condition) ?> <?php echo esc_attr($data_value) ?>>
				<div class="swatchly-cs-title">
					<h4><?php echo esc_html($field['title']) ?></h4>
				</div>

				<?php $this->field_html( 'edit', $field, $tax_meta ) ?>
			</div>
		<?php endforeach; ?>

		</div>
		<?php
	}

	public function field_html( $screen = 'add', $field = array(), $tax_meta = array() ){
		$field_value = '';
		if( $screen == 'edit' && $tax_meta ){
			$enable_multi_color = $tax_meta['swatchly_enable_multi_color'];
			$field_value 		= $tax_meta[$field['id']];

			if( $field['type'] == 'media' ){
				$field_value = $tax_meta[$field['id']];
			}
		}

		echo '<div class="swatchly-cs-fieldset">';

		if( $field['type'] == 'color' ){
			?>
				<input name="<?php echo esc_attr($field['id']) ?>" id="<?php echo esc_attr($field['id']) ?>" class=" swatchly_color_picker" data-alpha-enabled="true" type="text" value="<?php echo esc_attr($field_value) ?>" size="40">	
			<?php
		} elseif( $field['type'] == 'media' ){
			echo '<div class="swatchly-cs--preview">';
			if( $screen == 'edit' && $field_value['thumbnail'] ){
			?>
				<div class="swatchly-cs-image-preview">
					<a href="#" class="swatchly-cs--remove fas fa-times">x</a>
					<img src="<?php echo esc_attr($field_value['thumbnail']) ?>" class="watchly-cs--src">
				</div>
			<?php } ?>
			</div>
			<a href="#" class="button button-primary csf--button" data-popup_title="<?php echo esc_attr__('Select Swatch Image', 'woolentor') ?>" data-upload_button_text="<?php echo esc_attr__('Upload Image', 'woolentor') ?>" data-preview-size="thumbnail"><?php echo esc_html__('Upload', 'woolentor') ?></a>

			<?php if( $screen == 'edit' ): ?>
				<input name="<?php echo esc_attr($field['id']) ?>[url]" id="<?php echo esc_attr($field['id']) ?>" type="hidden" value="<?php echo esc_attr($field_value['url']) ?>" class="swatchly-cs--url" size="40">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[id]" value="<?php echo esc_attr($field_value['id']) ?>" class="swatchly-cs--id">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[width]" value="<?php echo esc_attr($field_value['width']) ?>" class="swatchly-cs--width">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[height]" value="<?php echo esc_attr($field_value['height']) ?>" class="swatchly-cs--height">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[thumbnail]" value="<?php echo esc_attr($field_value['thumbnail']) ?>" class="swatchly-cs--thumbnail">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[alt]" value="<?php echo esc_attr($field_value['alt']) ?>" class="swatchly-cs--alt">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[title]" value="<?php echo esc_attr($field_value['title']) ?>" class="swatchly-cs--title">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[description]" value="<?php echo esc_attr($field_value['description']) ?>" class="swatchly-cs--description">
			<?php else: ?>
				<input name="<?php echo esc_attr($field['id']) ?>[url]" id="<?php echo esc_attr($field['id']) ?>" type="hidden" value="" class="swatchly-cs--url" size="40">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[id]" value="" class="swatchly-cs--id">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[width]" value="" class="swatchly-cs--width">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[height]" value="" class="swatchly-cs--height">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[thumbnail]" value="" class="swatchly-cs--thumbnail">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[alt]" value="" class="swatchly-cs--alt">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[title]" value="" class="swatchly-cs--title">
				<input type="hidden" name="<?php echo esc_attr($field['id']) ?>[description]" value="" class="swatchly-cs--description">
			<?php endif; ?>

			
			<?php
		} elseif( $field['type'] == 'checkbox' ){
			?>
			<label class="swatchly-cs-checkbox">
				<?php if( $screen == 'add' ): ?>
					<input type="checkbox" name="<?php echo esc_attr($field['id']) ?>" class="swatchly-cs--input" data-depend-id="swatchly_enable_multi_color">
				<?php else: ?>
					<input type="checkbox" name="<?php echo esc_attr($field['id']) ?>" class="swatchly-cs--input" data-depend-id="swatchly_enable_multi_color" <?php checked($enable_multi_color, 1) ?>>
				<?php endif; ?>
				<span class="swatchly-cs--text"><?php echo esc_html('Checking this will allow you to set multiple colors.', 'woolentor') ?></span>
			</label>
			<?php
		} elseif( $field['type'] == 'select' ){
			?>
				<select name="swatchly_tooltip" data-depend-id="swatchly_tooltip">
					<?php foreach($field['options'] as $value => $label){
						echo '<option value="'. esc_attr($value) .'"'. selected($value, $field_value) .'>'. esc_html($label) .'</option>';
					} ?>
				</select>
			<?php
		} elseif( $field['type'] == 'text' ){
			?>
			<input type="text" name="swatchly_tooltip_text" value="<?php echo esc_attr($field_value) ?>" data-depend-id="swatchly_tooltip_text">
			<div class="swatchly-cs-after-text"><?php echo esc_html__('By default, the "Attribute Name" will be shown as the tooltip. If you want custom tooltip text, put it here.', 'woolentor') ?></div>
			<?php
		}

		echo '</div>';
	}

	/**
	 * It saves meta values to the database
	 * 
	 * @param term_id The ID of the term you're saving the meta for.
	 */
	public function save_term_meta( $term_id ){
		$post_data = wp_unslash($_POST);

		$nonce = sanitize_text_field($post_data['swatchly_term_meta_nonce']);
        if ( !wp_verify_nonce( $nonce, 'swatchly_save_term_meta_nonce' ) ) {
            die( esc_html__( 'No naughty business please!', 'woolentor' ) );
        }

		$tooltip        		= isset($post_data['swatchly_tooltip']) ? sanitize_text_field($post_data['swatchly_tooltip']) : '';
		$tooltip_text       	= isset($post_data['swatchly_tooltip_text']) ? sanitize_text_field($post_data['swatchly_tooltip_text']) : '';
		$tooltip_image_value	= isset($post_data['swatchly_tooltip_image']) ? $post_data['swatchly_tooltip_image'] : $this->image_defaults;
		$color_value        	= isset($post_data['swatchly_color']) ? sanitize_text_field($post_data['swatchly_color']) : '';
		$enable_multi_color 	= isset($post_data['swatchly_enable_multi_color']) ? sanitize_text_field($post_data['swatchly_enable_multi_color']) : '';
		$enable_multi_color 	= $enable_multi_color == 'on' ? '1' : '0';
		$color_value_2      	= isset($post_data['swatchly_color_2']) ? sanitize_text_field($post_data['swatchly_color_2']) : '';
		$image_value        	= isset($post_data['swatchly_image']) ? $post_data['swatchly_image'] : $this->image_defaults;

		update_term_meta(
			$term_id,
			'swatchly_tooltip',
			$tooltip
		);

		if( $tooltip == 'text' ){
			update_term_meta(
				$term_id,
				'swatchly_tooltip_text',
				$tooltip_text
			);
		}

		if( $tooltip == 'image' ){
			update_term_meta(
				$term_id,
				'swatchly_tooltip_image',
				$tooltip_image_value
			);
		}

		update_term_meta(
			$term_id,
			'swatchly_color',
			$color_value
		);

		update_term_meta(
			$term_id,
			'swatchly_enable_multi_color',
			$enable_multi_color
		);

		if( $enable_multi_color ){
			update_term_meta(
				$term_id,
				'swatchly_color_2',
				$color_value_2
			);
		}

		update_term_meta(
			$term_id,
			'swatchly_image',
			$image_value
		);
	}

    /**
     * Get swatch type of given taxonomy
     */
    public function get_taxonomy_swatch_type( $taxonomy ){
        global $wpdb;

        $attr = substr( $taxonomy, 3 );
        $attr = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s", $attr ) );
        $swatch_type = isset($attr->attribute_type) ? $attr->attribute_type : '';

        return $swatch_type;
    }
}