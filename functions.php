<?php
/**
 * Duck Diver Custom functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Duck Diver Custom
 */

if ( ! function_exists( 'dd_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function dd_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Duck Diver Custom, use a find and replace
		 * to change 'dd_theme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'dd_theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'dd_theme' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'dd_theme_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);
	}
	add_action( 'after_setup_theme', 'dd_theme_setup' );
endif; // dd_theme_setup.

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
if ( ! function_exists( 'dd_theme_widgets_init' ) ) :
	function dd_theme_widgets_init() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar', 'dd_theme' ),
				'id'            => 'sidebar-1',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
	add_action( 'widgets_init', 'dd_theme_widgets_init' );
endif;

/**
 * Enqueue scripts and styles.
 */
function dd_theme_scripts() {

	wp_enqueue_style( '_s-style', get_stylesheet_directory_uri() . '/style.min.css' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/bootstrap/js/bootstrap.bundle.min.js', array( 'jquery' ), 'v4.4.0', true );
}
add_action( 'wp_enqueue_scripts', 'dd_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
* Bootstrap integration
*/
require get_template_directory() . '/inc/functions-strap.php';

require get_template_directory() . '/inc/theme-includes.php';

require get_template_directory() . '/customizer/customizer-framework.php';

/**
 * Block the Users from exposure with the WP JSON REST API.
 */
add_filter(
	'rest_authentication_errors',
	function( $result ) {
		// If a previous authentication check was applied,
		// pass that result along without modification.
		if ( true === $result || is_wp_error( $result ) ) {
			return $result;
		}

		// No authentication has been performed yet.
		// Return an error if user is not logged in.
		if ( ! is_user_logged_in() ) {
			global $wp;
			// Get the specific route.
			if ( 'wp-json/wp/v2/users' === $wp->request ) {
				return new WP_Error(
					'rest_not_logged_in',
					__( 'You are not currently logged in.' ),
					array( 'status' => 401 )
				);
			}
		}

		return $result;
	}
);
