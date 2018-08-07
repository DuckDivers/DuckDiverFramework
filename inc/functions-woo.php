<?php 
// Declare Woo Support

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

// Template Wrappers
		function dd_open_shop_content_wrappers(){
			echo '<div class="container">
					<div id="shop-wrapper" class="content-area row">
						<main id="main" class="col-sm-9" role="main">';		
				}
		function dd_after_woo_breadcrumbs(){
				echo '<div id="product-main">';
			}
		function dd_close_shop_content_wrappers(){
			echo			'</main><aside class="col-sm-3 sidebar" id="sidebar">';
								get_sidebar();
			echo			'</aside></div>
						</div>';
		}

function dd_prepare_shop_wrappers(){
	/* Woocommerce */
	remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5, 0);
//	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
	remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
	add_action('woocommerce_before_main_content', 'dd_after_woo_breadcrumbs', 40, 0);
	add_action('woocommerce_before_main_content', 'dd_open_shop_content_wrappers', 10);
	add_action('woocommerce_after_main_content', 'dd_close_shop_content_wrappers', 10);
	/* end Woocommerce */	
}
add_action('wp_head', 'dd_prepare_shop_wrappers');

		
// Change number or products per row to 4
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 4; // 4 products per row
	}
}

// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
add_filter( 'woocommerce_add_to_cart_fragments', 'cherry_child_header_add_to_cart_fragment' );

function cherry_child_header_add_to_cart_fragment( $fragments ) {
	ob_start(); ?>
    <?php if (WC()->cart->cart_contents_count == 0) : ?>
    	<span class="cart-items empty"></span>
    <?php else: ?>
		<span class="cart-items full"><?php echo WC()->cart->cart_contents_count ?></span>
    <?php endif; ?>
	<?php
	$fragments['span.cart-items'] = ob_get_clean();
	return $fragments;
}


add_filter( 'widget_title', 'cherry_child_get_cart', 10 );
function cherry_child_get_cart( $title ) {
	if ( false === strpos( $title, '%items_num%' ) ) {
		return $title;
	}
	$count = WC()->cart->cart_contents_count();
	$items_str = '<span class="cart-items>' . $count . '</span>';
	$title = str_replace( '%items_num%', $items_str, $title );
	return $title;
}
