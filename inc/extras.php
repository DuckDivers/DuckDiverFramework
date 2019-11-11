<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Duck Diver Custom
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function dd_theme_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'dd_theme_body_classes' );

function dd_get_sidebar_position($sidebar = "sidebar_position"){
    $position = get_theme_mod($sidebar);
    $sbpos = array();
    if ($position == '1'){
        $sbpos['main'] = apply_filters('dd_main_width', 'col-md-9') . ' order-2';
        $sbpos['sb'] = apply_filters('dd_sidebar_width', 'col-md-3') . ' order-1';
        $sbpos['showsb'] = 'true';
    }
    elseif ($position == '2'){
        $sbpos['main'] = apply_filters('dd_main_width', 'col-md-9');
        $sbpos['sb'] = apply_filters('dd_sidebar_width', 'col-md-3');
        $sbpos['showsb'] = 'true';
    }
    elseif ($position == 'none'){
        $sbpos['main'] = apply_filters('dd_main_width', 'col-12');
        $sbpos['sb'] = apply_filters('dd_sidebar_width', 'd-none');
        $sbpos['showsb'] = 'false';
    }
    return $sbpos;
}
//Add Font Size to WP TinyMCE Editor
function dd_wp_editor_fontsize_filter( $options ) {
	array_shift( $options );
	array_unshift( $options, 'fontsizeselect');
	return $options;
}

add_filter('mce_buttons_2', 'dd_wp_editor_fontsize_filter');
// Customize mce editor font sizes
if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
    function wpex_mce_text_sizes( $initArray ){
        $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
        return $initArray;
    }
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' ); 

function add_slider_to_homepage(){
	if (get_theme_mod('dd_slider_active')) {
			$delay = get_theme_mod('dd_slider_delay'); }
		else {
			$delay = 'false';
		}
	ob_start(); ?>

	<script type="text/javascript">
		// Carousel Init
		jQuery( document ).ready( function ( $ ) {
			$( '.carousel' ).carousel( {
				interval: <?php echo $delay;?>
			} );
		} );
	</script>
	<?php echo ob_get_clean();
}
add_action('dd_homepage_scripts', 'add_slider_to_homepage', 5);
