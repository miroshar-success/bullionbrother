/**
 * Adds a “hide title” checkbox to the Block Editor sidebar under the
 * Document sidebar in the Status & Visibility panel. Unchecked by default.
 *
 * If checked and the post is updated or published, `_genesis_block_theme_hide_title`
 * is set to true in post meta.
 *
 * @package Genesis Block Theme
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { PluginPostStatusInfo } from '@wordpress/edit-post';
import { CheckboxControl } from '@wordpress/components';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Checkbox component for the hide title option.
 *
 * @param {Object} props Component properties.
 * @return {Object} hideTitleComponent
 */
function genesisBlockThemeHideTitleComponent( { hideTitle, onUpdate } ) {
	return (
		<Fragment>
			<PluginPostStatusInfo className="edit-post-hideTitle">
				<CheckboxControl
					label={ __( 'Hide Page Title', 'genesis-block-theme' ) }
					checked={ hideTitle }
					onChange={ () => onUpdate( ! hideTitle ) }
				/>
			</PluginPostStatusInfo>
		</Fragment>
	);
}

// Retrieves meta from the Block Editor Redux store (withSelect) to set initial checkbox state.
// Persists it to the Redux store on change (withDispatch).
// Changes are only stored in the WordPress database when the post is updated.
const render = compose( [
	withSelect( ( select ) => {
		return {
			hideTitle: select( 'core/editor' ).getEditedPostAttribute( 'meta' )._genesis_block_theme_hide_title,
		};
	} ),
	withDispatch( ( dispatch, ownProps, { select } ) => ( {
		onUpdate( hideTitle ) {
			const currentMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
			const newMeta = {
				...currentMeta,
				_genesis_block_theme_hide_title: hideTitle,
			};
			dispatch( 'core/editor' ).editPost( { meta: newMeta } );
		},
	} ) ),
] )( genesisBlockThemeHideTitleComponent );

registerPlugin( 'genesis-block-theme-title-toggle', { render } );
