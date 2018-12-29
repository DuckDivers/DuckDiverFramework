<?php 
// Require Extra Files and Functions
	require_once('lessc.inc.php');
	require_once('less-compile.php'); // Less Compiler
	require_once('cpt-init.php'); 
	require_once('dd-extra-widgets.php');
	require_once('aq_resizer.php'); // Aqua Resizer
    require_once(get_template_directory() . '/shortcodes/shortcodes.inc.php');
    include_once(get_template_directory() . '/admin/shortcodes/tinymce-shortcodes.php');
    include_once(get_template_directory() . '/widgets/duck-social-widget.php');

/*
* 
*  Add To your Child Theme to Include WooCommerce Functionality 
*  >>	require_once(get_template_directory() . '/inc/functions-woo.php'); // WooCommerce Functionality
*
*/

// Enqueue Custom Style from LessCompile
function dd_enqueue_styles(){
        wp_enqueue_style('dd-custom-fonts', get_template_directory_uri() . '/css/duck.min.css');
		if (!is_child_theme()){
            wp_enqueue_style('dd-custom-style', get_template_directory_URI() . '/custom.css', array(), filemtime(get_template_directory() . '/custom.css'), false);
        }
}
add_action('wp_print_styles', 'dd_enqueue_styles', 99);
// Add Admin Style
function load_custom_wp_admin_style() {
        wp_register_style( 'custom_wp_admin_css', get_template_directory_URI() . '/admin/admin.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
        wp_register_style( 'duck-fonts', get_template_directory_URI() . '/css/duck.min.css', false, '1.0.0' );
        wp_enqueue_style('duck-fonts');
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

// Add Custom Image Sizes
add_image_size( 'admin_thumb', 80, 80, true); //Featured Image for Blog
add_image_size( 'slider-post-thumbnail', 2000, 600, true ); // Slider Thumbnail

// Enqueue Custom Scripts
add_action( 'wp_enqueue_scripts', 'dd_custom_scripts' );
function dd_custom_scripts() {
		wp_register_script( 'duck-custom', get_template_directory_uri() . '/js/duck-custom.js', array ('jquery'), filemtime(get_template_directory() . '/js/duck-custom.js') , true);
		wp_enqueue_script('duck-custom');
		wp_register_script( 'magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array ('jquery'), '1.1.0', true);
		wp_enqueue_script('magnific-popup');
}

//Only Load CF7 Scripts on CF7 Pages.
if (is_plugin_active('contact-form-7/wp-contact-form-7.php')){
    function contactform_dequeue_scripts() {
        $load_scripts = false;
        if( is_singular() ) {
            $post = get_post();
            if( has_shortcode($post->post_content, 'contact-form-7') ) {
                $load_scripts = true;
            }
        }
        if( ! $load_scripts ) {
            wp_dequeue_script( 'contact-form-7' );
            wp_dequeue_script('google-recaptcha');
            wp_dequeue_style( 'contact-form-7' );
        }
    }
add_action( 'wp_enqueue_scripts', 'contactform_dequeue_scripts', 99 );    
}