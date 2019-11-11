<?php 
// Parent Theme WooCommerce Functionality Pluggable Functions
// Declare Woo Support
add_action( 'after_setup_theme', 'woocommerce_support' );
if (!function_exists('woocommerce_support')){
    function woocommerce_support() {
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
}
// Bootstrap Template Wrappers
if (!function_exists('dd_open_shop_content_wrappers')){
    function dd_open_shop_content_wrappers(){
        $sbpos = dd_get_sidebar_position('wc_sidebar_position');
        echo '<div class="container">
                <div id="shop-wrapper" class="content-area row">';
                if ((true == get_theme_mod('dd_theme_disable_archive_sidebar') && !is_product()) || (true == get_theme_mod('dd_theme_disable_product_sidebar') && is_product())){
                    echo '<main id="main" class="col-12" role="main">';
                } else {
                     echo '<main id="main" class="'.$sbpos['main'].'" role="main">';
                }		
        }
}
if (!function_exists('dd_after_woo_breadcrumbs')){
        function dd_after_woo_breadcrumbs(){
            echo '<div id="product-main">';
        }
}
if (!function_exists('dd_close_shop_content_wrappers')){
		function dd_close_shop_content_wrappers(){
            $sbpos = dd_get_sidebar_position('wc_sidebar_position');
			echo			'</main>';
             if ((true == get_theme_mod('dd_theme_disable_archive_sidebar') && !is_product()) || (true == get_theme_mod('dd_theme_disable_product_sidebar') && is_product())){
                    return false;
                } else {
                     echo '<aside class="'.$sbpos['sb'].' sidebar" id="sidebar">'; dynamic_sidebar('shop-sidebar'); echo '</aside>';
                }		
            echo '</div></div>';
		}
}

if (!function_exists('dd_prepare_shop_wrappers')){
    function dd_prepare_shop_wrappers(){
        /* Woocommerce Make for Bootstrap*/
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10); 
        add_action('woocommerce_before_main_content', 'dd_after_woo_breadcrumbs', 40, 0);
        add_action('woocommerce_before_main_content', 'dd_open_shop_content_wrappers', 10);
        add_action('woocommerce_after_main_content', 'dd_close_shop_content_wrappers', 10);
        /* end Woocommerce */	
    }
}
add_action('wp_head', 'dd_prepare_shop_wrappers');

// Ensure cart contents update when products are added to the cart via AJAX
add_filter( 'woocommerce_add_to_cart_fragments', 'cherry_child_header_add_to_cart_fragment' );

if (!function_exists('cherry_child_header_add_to_cart_fragment')){
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
}

add_filter( 'widget_title', 'cherry_child_get_cart', 10 );

// Get Cart Item Counts
if (!function_exists('cherry_child_get_cart')){
    function cherry_child_get_cart( $title ) {
        if ( false === strpos( $title, '%items_num%' ) ) {
            return $title;
        }
        $count = WC()->cart->cart_contents_count();
        $items_str = '<span class="cart-items>' . $count . '</span>';
        $title = str_replace( '%items_num%', $items_str, $title );
        return $title;
    }
}

// Alter the Shop page title
add_filter( 'woocommerce_page_title', 'custom_woocommerce_page_title');
if (!function_exists('custom_woocommerce_page_title')){
    function custom_woocommerce_page_title( $page_title ) {
      if( $page_title == 'Shop' ) {
        return get_theme_mod('dd_shop_title');
      }
    }
}