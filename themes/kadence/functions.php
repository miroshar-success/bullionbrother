<?php
/**
 * Kadence functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kadence
 */

define( 'KADENCE_VERSION', '1.1.30' );
define( 'KADENCE_MINIMUM_WP_VERSION', '5.4' );
define( 'KADENCE_MINIMUM_PHP_VERSION', '7.2' );

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], KADENCE_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), KADENCE_MINIMUM_PHP_VERSION, '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}
// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

// Load the `kadence()` entry point function.
require get_template_directory() . '/inc/class-theme.php';

// Load the `kadence()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'Kadence\kadence' );








add_shortcode('child_category_list', 'get_child_category_list');

function get_child_category_list(){
    ob_start();
    
    // Only on product parent category pages
    if( is_product_category() ) {
        $parent = get_queried_object();

        $categories = get_term_children( $parent->term_id, 'product_cat' ); 
        if ( $categories && ! is_wp_error( $categories ) ) : 

            echo '<ul class="subcategor">';
            echo    '<li>';
            echo        '<a href="'.get_term_link ($parent->term_id, 'product_cat').'">'.$parent->name.'</a>';
            echo        '<ul class="subcategorch">';
            foreach($categories as $category) :
                        $term = get_term( $category, 'product_cat' );
                        echo '<li>';
                        echo '<a href="'.get_term_link($term).'" >';
                        echo $term->name;
                        echo '</a>';
                        echo '</li>';
            endforeach;
            echo        '</ul>';
            echo    '<li>';
            echo '</ul>';

        endif;
    }
    return ob_get_clean();
}