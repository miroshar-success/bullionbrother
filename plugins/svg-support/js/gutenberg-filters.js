"use strict"

const el = wp.element.createElement;
const withState = wp.compose.withState;
const withSelect = wp.data.withSelect;
const withDispatch = wp.data.withDispatch;

wp.hooks.addFilter(
    'editor.PostFeaturedImage',
    'bodhi-svgs-featured-image/render-inline-image-checkbox',
    wrapPostFeaturedImage
);

function wrapPostFeaturedImage( OriginalComponent ) {
    return function( props ) {
        return (
            el(
                wp.element.Fragment,
                {},
                '',
                el(
                    OriginalComponent,
                    props
                ),
                el(
                    composedCheckBox
                )
            )
        );
    }
}

class CheckBoxCustom extends React.Component {
    render() {
        const {
            meta,
            updateInlineFeaturedSvg,
        } = this.props;

        return (
            el(
                wp.components.CheckboxControl,
                {
                    label: "Render this SVG inline (Advanced)",
                    checked: meta.inline_featured_image,
                    onChange:
                        ( value ) => {
                            this.setState( { isChecked: value } );
                            updateInlineFeaturedSvg( value, meta );
                        }
                }
            )
        )
    }
}

const composedCheckBox = wp.compose.compose( [
    withState( ( value ) => { isChecked: value } ),
    withSelect( ( select ) => {
        const currentMeta = select( 'core/editor' ).getCurrentPostAttribute( 'meta' );
        const editedMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
        return {
            meta: { ...currentMeta, ...editedMeta },
        };
    } ),
    withDispatch( ( dispatch ) => ( {
        updateInlineFeaturedSvg( value, meta ) {
            meta = {
                ...meta,
                inline_featured_image: value,
            };
            dispatch( 'core/editor' ).editPost( { meta } );
        },
    } ) ),
] )( CheckBoxCustom );
