<div class="xoo-settings-container">

	<ul class="xoo-sc-tabs">
		<?php foreach( $tabs as $tab_id => $tab_data ): ?>
			<li data-tab="<?php echo esc_attr( $tab_id ); ?>" <?php if( $tab_data['pro'] === 'yes' ) echo 'class="xoo-as-is-pro"'; ?>><?php echo esc_html( $tab_data['title'] ); ?></li>
		<?php endforeach; ?>
	</ul>

	<form class="xoo-as-form">

		<?php foreach( $tabs as $tab_id => $tab_data ): ?>
			<div class="xoo-sc-tab-content <?php if( $tab_data['pro'] === 'yes' ) echo 'xoo-as-is-pro'; ?>" data-tab="<?php echo esc_attr( $tab_id ); ?>">
				<?php do_action( 'xoo_tab_page_start', $tab_id, $tab_data ); ?>
				<?php $adminObj->create_settings_html( $tab_id ); ?>
				<?php do_action( 'xoo_tab_page_end', $tab_id, $tab_data ); ?>
			</div>
		<?php endforeach; ?>

		<div class="xoo-sc-bottom-btns">
			<?php if( $hasPRO ): ?>
				<a class="xoo-as-pro-toggle">Show/Hide Pro options</a>
			<?php endif; ?>
			<button type="submit" class="xoo-as-form-save">Save</button>
			<a class="xoo-as-form-reset" href="<?php echo esc_url( add_query_arg( 'reset', true ) ) ?>">Reset</a>
		</div>

	</form>

</div>