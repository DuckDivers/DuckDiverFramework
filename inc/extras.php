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

function dd_get_sidebar_position( $sidebar = 'sidebar_position' ) {
	$position = get_theme_mod( $sidebar );
	$sbpos    = array();
	if ( '1' === $position ) {
		$sbpos['main']   = apply_filters( 'dd_main_width', 'col-md-9' ) . ' order-md-2';
		$sbpos['sb']     = apply_filters( 'dd_sidebar_width', 'col-md-3' ) . ' order-md-1';
		$sbpos['showsb'] = 'true';
	} elseif ( '2' === $position ) {
		$sbpos['main']   = apply_filters( 'dd_main_width', 'col-md-9' );
		$sbpos['sb']     = apply_filters( 'dd_sidebar_width', 'col-md-3' );
		$sbpos['showsb'] = 'true';
	} elseif ( 'none' === $position ) {
		$sbpos['main']   = apply_filters( 'dd_main_width', 'col-12' );
		$sbpos['sb']     = apply_filters( 'dd_sidebar_width', 'd-none' );
		$sbpos['showsb'] = 'false';
	}
	return apply_filters( 'dd_sidebar_position', $sbpos );
}
// Add Font Size to WP TinyMCE Editor.
function dd_wp_editor_fontsize_filter( $options ) {
	array_shift( $options );
	array_unshift( $options, 'fontsizeselect' );
	return $options;
}

add_filter( 'mce_buttons_2', 'dd_wp_editor_fontsize_filter' );
// Customize mce editor font sizes
if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
	function wpex_mce_text_sizes( $initArray ) {
		$initArray['fontsize_formats'] = '9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px';
		return $initArray;
	}
	add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );
}


if ( ! function_exists( 'add_slider_to_homepage' ) ) {
	/**
	 * Adds a slider to the homepage using a JavaScript-driven carousel.
	 *
	 * Initializes a carousel with customizable settings such as autoplay interval
	 * and lazy loading of images depending on the theme settings.
	 *
	 * @return void
	 */
	function add_slider_to_homepage() {
		if ( get_theme_mod( 'dd_slider_active' ) ) {
			$delay = get_theme_mod( 'dd_slider_delay' );
		} else {
			$delay = 'false';
		}
		ob_start(); ?>
		<script type="text/javascript">
			// Carousel Init
			jQuery(function ($) {
				let $slider = $('.carousel');
				$slider.carousel({
					interval: <?php echo esc_attr( $delay ); ?>
				});
				<?php if ( get_theme_mod( 'dd_slider_lazy' ) !== false ) : ?>
				$slider.on('slide.bs.carousel', function (event) {
					let target_div = event.relatedTarget;
					let target = $(target_div).next('.carousel-item').find('img').data('src');
					let mobile = $(target_div).next('.carousel-item').find('source.mobile-slider-image').data('srcset');
					if (mobile) {
						$(target_div).next('.carousel-item').find('source.mobile-slider-image').prop('srcset', mobile);
					}
					if (target) {
						$(target_div).next('.carousel-item').find('img').prop('src', target);
						$(target_div).next('.carousel-item').find('source.desktop-slider-image').prop('srcset', target);
					}
				});
				$('ol.carousel-indicators li').on('click', function(){
					let to_load    = $(this).data('slide-to');
					let image_src  = $('.carousel-item').eq(to_load).find('img').data('src');
					let mobile_src = $('.carousel-item').eq(to_load).find('source.mobile-slider-image').data('srcset');
					if (image_src) {
						$('.carousel-item').eq(to_load).find('img').prop('src', image_src);
						$('.carousel-item').eq(to_load).find('source.desktop-slider-image').prop('srcset', image_src);
					}
					if ( mobile_src ) {
						$('.carousel-item').eq(to_load).find('source.mobile-slider-image').prop('srcset', mobile_src);
					}
				});
				<?php endif; ?>
			});
		</script>
		<?php
		echo ob_get_clean();
	}
}
add_action( 'dd_homepage_scripts', 'add_slider_to_homepage', 5 );

// Add Google Rich Snippets to FAQ page
add_action( 'wp_head', 'add_faq_schema_markup' );
if ( ! function_exists( 'add_faq_schema_markup' ) ) {
	function add_faq_schema_markup() {
		global $post;
		if ( ! $post ) {
			return;
		}
		if ( get_page_template_slug( $post->ID ) === 'page-faq.php' ) {
			$args      = array(
				'post_type'      => 'faq',
				'posts_per_page' => -1,
			);
			$faq_query = new WP_Query( $args );
			if ( $faq_query->have_posts() ) :
				echo '<script type="application/ld+json">
                {
                  "@context": "https://schema.org",
                  "@type": "FAQPage",
                  "mainEntity": ';
				$json_array = array();
				while ( $faq_query->have_posts() ) :
					$faq_query->the_post();
					// The Loop
					$question     = get_the_title();
					$answer       = apply_filters( 'the_content', get_the_content() );
					$json_array[] = array(
						'@type'          => 'Question',
						'name'           => $question,
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => $answer,
						),
					);

				endwhile;
				echo json_encode( $json_array );
				echo '}</script>';
			endif;
		}
	}
}

