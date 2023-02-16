/**
 * Page template changes will add a class to the body, and adjust the width accordingly.
 *
 * @package Genesis Block Theme
 */

/**
 * WordPress dependencies
 */
import {useEffect, useState} from '@wordpress/element';
import ReactDOM from "react-dom";

function PageTemplateUpdater( props ){

	const [prevProps, setPrevProps] = useState(props);

	function assembleClassName( pageTemplateName ) {
		pageTemplateName = pageTemplateName.replace( 'templates/', '' );
		pageTemplateName = pageTemplateName.replace( '.php', '' );
		return 'page-template-' + pageTemplateName;
	}
	
	useEffect( () => {	
		if(prevProps.template) {
			jQuery(document).ready(($) => {
				$('body').removeClass( assembleClassName( prevProps.template ) );
			});
		}
		if(props.template) {
			jQuery(document).ready(($) => {
				$('body').addClass( assembleClassName( props.template ) );
			});
		}
		
		setPrevProps( props );
	}, [props] );

	// No output needed here.
	return '';
}

const withPageTemplate = wp.compose.createHigherOrderComponent(
	wp.data.withSelect(
		select => {
			const {
				getEditedPostAttribute,
			} = select('core/editor');

			return {
				template: getEditedPostAttribute('template'),
			};
		}
	),
	'withPageTemplate'
);
const PageTemplateUpdaterHoc = withPageTemplate(PageTemplateUpdater);

ReactDOM.render(<PageTemplateUpdaterHoc />, document.getElementById('genesis-block-theme-page-template-toggle-watcher'));