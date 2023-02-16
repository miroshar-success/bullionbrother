<?php
/**
 * Outputs the WooCommerce Zapier System Status information on the
 * WooCommerce -> Status screen.
 *
 * @since 2.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $section_title ) || ! isset( $rows ) || ! is_array( $rows ) ) {
	return;
}
?>

<table class="wc_status_table widefat" cellspacing="0">
	<thead>
	<tr>
		<th colspan="3" data-export-label="<?php echo esc_attr( $section_title ); ?>"><h2><?php echo esc_html( $section_title ); ?></h2></th>
	</tr>
	</thead>
	<tbody>
	</tr>
	<?php
	foreach ( $rows as $row ) {
		$css_class = '';
		$icon      = '';
		if ( isset( $row['success'] ) ) {
			if ( true === $row['success'] ) {
				$css_class = 'yes';
				$icon      = '<span class="dashicons dashicons-yes"></span>';
			} elseif ( false === $row['success'] ) {
				$css_class = 'error';
				$icon      = '<span class="dashicons dashicons-no-alt"></span>';
			}
		}
		?>
		<tr>
			<td data-export-label="<?php echo esc_attr( $row['name'] ); ?>"><?php echo esc_html( $row['name'] ); ?>:</td>
			<td class="help"><?php echo isset( $row['help'] ) ? wp_kses_post( wc_help_tip( $row['help'] ) ) : ''; ?></td>
			<td>
			<mark class="<?php echo esc_attr( $css_class ); ?>"><?php echo wp_kses_post( $icon ); ?> </mark><?php echo wp_kses_post( $row['note'] ); ?>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
