<?php
/**
 * Theme Includes and custom functions.
 *
 * @package Duck Diver Framework
 */

use JetBrains\PhpStorm\NoReturn;

require_once 'cpt-init.php';
	require_once 'dd-extra-widgets.php';
	require_once get_template_directory() . '/shortcodes/shortcodes.inc.php';
	require_once get_template_directory() . '/admin/shortcodes/tinymce-shortcodes.php';
	require_once get_template_directory() . '/widgets/duck-social-widget.php';

/*
*  Check if WooCommerce is Active and Activate Features.
*/
if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true ) ) {
	require_once get_template_directory() . '/inc/functions-woo.php'; // WooCommerce Functionality.
}

if ( ! function_exists( 'dd_enqueue_styles' ) ) {
	/**
	 * Enqueues theme stylesheets, including a conditional stylesheet based on theme settings and
	 * a custom stylesheet if the theme is not a child theme.
	 *
	 * @return void
	 */
	function dd_enqueue_styles() {
		$icons_css = ( get_theme_mod( 'exclude_fontawesome' ) ) ? '/css/duck-no-fontawesome.min.css' : '/css/duck.min.css';
		wp_enqueue_style( 'dd-custom-fonts', get_template_directory_uri() . $icons_css, false, '1.3' );
		if ( ! is_child_theme() ) {
			wp_enqueue_style( 'dd-custom-style', get_template_directory_URI() . '/custom.css', array(), filemtime( get_template_directory() . '/custom.css' ), false );
		}
	}
	add_action( 'wp_enqueue_scripts', 'dd_enqueue_styles', 99 );
}

if ( ! function_exists( 'load_custom_wp_admin_style' ) ) {
	/**
	 * Loads and enqueues custom stylesheets for the WordPress admin area, including a main
	 * admin-specific stylesheet and an additional font stylesheet.
	 *
	 * @return void
	 */
	function load_custom_wp_admin_style() {
			wp_register_style( 'custom_wp_admin_css', get_template_directory_URI() . '/admin/admin.css', false, '1.0.0' );
			wp_enqueue_style( 'custom_wp_admin_css' );
			wp_register_style( 'duck-fonts', get_template_directory_URI() . '/css/duck.min.css', false, '1.0.0' );
			wp_enqueue_style( 'duck-fonts' );
	}
	add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );
}

// Add Custom Image Sizes.
if ( ! function_exists( 'dd_custom_image_sizes' ) ) {
	/**
	 * Defines custom image sizes for use in the theme, including an admin thumbnail
	 * and a slider post thumbnail with specific dimensions and hard cropping.
	 *
	 * @return void
	 */
	function dd_custom_image_sizes() {
		add_image_size( 'admin_thumb', 80, 80, true ); // Featured Image for Blog.
		add_image_size( 'slider-post-thumbnail', 2000, 600, true ); // Slider Thumbnail.
		add_image_size( 'blog-archive-width', 600, 315, array( 'center', 'center' ) );
		add_image_size( 'blog-hero-image', 1200, 630, array( 'center', 'center' ) );
		add_image_size( 'mobile-slider', 500, 625, array( 'center', 'center' ) );
		add_image_size( 'medium-square', 600, 600, array( 'center', 'center' ) );
	}
}
add_action( 'after_setup_theme', 'dd_custom_image_sizes' );

add_action( 'wp_enqueue_scripts', 'dd_custom_scripts' );
/**
 * Registers and conditionally enqueues the 'magnific-popup' script.
 *
 * This function registers the 'magnific-popup' script and checks the theme
 * modification setting 'include_magnific_popup'. If the setting is enabled,
 * the script will be enqueued for use.
 *
 * @return void
 */
function dd_custom_scripts() {
		wp_register_script( 'magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	if ( get_theme_mod( 'include_magnific_popup' ) ) {
		wp_enqueue_script( 'magnific-popup' );
	}
}

/**
 * Adds a "back to top" button behavior using jQuery.
 *
 * This function defines a script that implements a "back to top" button functionality.
 * It monitors the scroll position and toggles the visibility of the button. When the button
 * is clicked, the page scrolls smoothly to the top. The script is added inline to the
 * 'bootstrap' script in WordPress.
 *
 * @return void
 */
function add_duckdiver_back_to_top() {
	/**
	 * The $script variable is used to store a script or a reference to a script.
	 *
	 * This variable can hold any type of script-related data, such as a string
	 * containing script content, a file path to a script, or other relevant
	 * information needed for script execution or processing within the application.
	 *
	 * Usage of this variable may vary depending on the context of the program.
	 * Ensure that the content or data assigned to $script conforms to the
	 * requirements of the specific implementation.
	 */
	$script = 'jQuery(window).on("scroll",function(){if(jQuery(this).scrollTop()>100){jQuery("#back-top").fadeIn("fast",function(){clearTimeout(jQuery.data(this,"scrollTimer"));jQuery.data(this,"scrollTimer",setTimeout(function(){jQuery("#back-top").delay(2e3).fadeOut("slow")},2e3))})}else{jQuery("#back-top").fadeOut()}});jQuery("#back-top a").on("click",function(){jQuery("body,html").stop(false,false).animate({scrollTop:0},800);return false});';
	wp_add_inline_script( 'bootstrap', $script );
}
add_action( 'get_footer', 'add_duckdiver_back_to_top' );

/**
 * Checks if the Contact Form 7 plugin is active. If active, it conditionally dequeues
 * Contact Form 7 scripts and styles except when the relevant shortcode is present
 * on a singular post or page. Also, adds an inline script to handle specific behaviors
 * when the form is submitted.
 *
 * @return void
 */
function check_cf7_active() {
	if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
		/**
		 * Conditionally dequeues Contact Form 7 scripts and styles from pages or posts
		 * where the Contact Form 7 shortcode is not present. If the shortcode is detected,
		 * an inline script is added to manage behavior during form submission.
		 *
		 * @return void
		 */
		function contactform_dequeue_scripts() {
			$load_scripts = false;
			if ( is_singular() ) {
				$post = get_post();
				if ( has_shortcode( $post->post_content, 'contact-form-7' ) ) {
					$load_scripts = true;
					$script       = 'jQuery(document).on("click",".wpcf7-submit",function(e){if(jQuery(".ajax-loader").hasClass("is-active")){e.preventDefault();return false}});';
					wp_add_inline_script( 'contact-form-7', $script );
				}
			}
			if ( ! $load_scripts ) {
				wp_dequeue_script( 'contact-form-7' );
				wp_dequeue_script( 'google-recaptcha' );
				wp_dequeue_style( 'contact-form-7' );
			}
		}
		add_action( 'wp_enqueue_scripts', 'contactform_dequeue_scripts', 99 );
	}
}
add_action( 'init', 'check_cf7_active' );

add_filter( 'body_class', 'dd_mobile_class' );
/**
 * Appends a class to the provided classes array based on the device type.
 *
 * @param array $classes An array of CSS class names.
 *
 * @return array The modified array of CSS class names including the device-specific class.
 */
function dd_mobile_class( $classes ) {
	$device_type = ( wp_is_mobile() ) ? 'mobile' : 'desktop';
	$classes[]   = 'is-' . $device_type;

	return $classes;
}

function dd_add_infinite_scroll() {
	if ( get_theme_mod( 'dd_use_infinite_scroll' ) ) {
		global $wp_query;
		if ( is_archive() || is_home() ) {
			wp_enqueue_script( 'infinite-scroll', get_template_directory_uri() . '/js/infinite-scroll.min.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/infinite-scroll.js' ), true );
			wp_localize_script(
				'infinite-scroll',
				'ajaxposts',
				array(
					'ajaxurl'      => site_url() . '/wp-admin/admin-ajax.php',
					'posts'        => wp_json_encode( $wp_query->query_vars ),
					'current_page' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
					'max_page'     => $wp_query->max_num_pages,
					'is_archive'   => is_archive(),
					'nonce'        => wp_create_nonce( 'ajax-nonce' ),
				)
			);
		}
	} else {
		return false;
	}
}
add_action( 'wp_enqueue_scripts', 'dd_add_infinite_scroll' );

add_action( 'wp_ajax_nopriv_loadmore', 'duck_infinite' );
add_action( 'wp_ajax_loadmore', 'duck_infinite' );

if ( ! function_exists( 'duck_infinite' ) ) {
	/**
	 * Handles the infinite scroll functionality for loading more posts via AJAX.
	 *
	 * This function verifies the AJAX nonce and checks for required POST variables.
	 * It constructs query arguments to fetch the next page of posts, iterates through
	 * the posts, and outputs each post's HTML.
	 *
	 * @return void
	 */
	#[NoReturn]
	function duck_infinite() {
		check_ajax_referer( 'ajax-nonce' );

		if ( ! isset( $_POST['query'] ) || ! isset( $_POST['page'] ) ) {
			wp_send_json_error( 'Required POST variables are missing.' );
		}

		$args                = json_decode( sanitize_text_field( wp_unslash( $_POST['query'] ) ), true );
		$args['paged']       = absint( wp_unslash( $_POST['page'] ) ) + 1;
		$args['post_status'] = 'publish';
		$args['offset']      = ( absint( wp_unslash( $_POST['page'] ) ) * 9 ) + 1;

		query_posts( $args );

		if ( have_posts() ) :

			// run the loop.
			while ( have_posts() ) :
				the_post();
				?>
			<div class="col-md-4 my-3">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'post__holder' ); ?>> <a href="<?php the_permalink(); ?>" class="archive-link">
						<?php
						if ( has_post_thumbnail() ) {
							echo '<figure class="featured-image">' . get_the_post_thumbnail( get_the_ID(), 'blog-archive-width' ) . '</figure>';
						}
						?>
						<h3 class="card__title">
							<?php the_title(); ?>
						</h3>
						<!-- Post Content -->
						<div class="post_content">
							<div class="excerpt">
								<?php
								echo wp_kses_post( dd_custom_excerpt( get_the_ID() ) );
								?>
							</div>
						</div>
					</a>
				</article>
			</div>
				<?php
			endwhile;
		endif;
		die;
	}
}
