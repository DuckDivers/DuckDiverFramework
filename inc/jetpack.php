<?php
/**
 * Jetpack Compatibility File.
 *
 * @link https://jetpack.me/
 *
 * @package Duck Diver Custom
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.me/support/infinite-scroll/
 */
 function dd_theme_jetpack_setup() {
 	// Add theme support for Infinite Scroll.
 	add_theme_support( 'infinite-scroll', array(
 		'container' => 'main',
 		'render'    => 'dd_theme_infinite_scroll_render',
 		'footer'    => 'page',
 	) );
} // end function dd_theme_jetpack_setup
 add_action( 'after_setup_theme', 'dd_theme_jetpack_setup' );
/**
 * Custom render function for Infinite Scroll.
 */
function dd_theme_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
		    get_template_part( 'template-parts/content', 'search' );
		else :
		    get_template_part( 'template-parts/content', get_post_format() );
		endif;
	}
} // end function dd_theme_infinite_scroll_render
