<?php
/**
 * Customizer Controls.
 *
 * @package WPshed Customizer Framework
 */

// User access level
$capability = 'edit_theme_options';

// Option type
$type = 'theme_mod'; // option / theme_mod

/* --- Logo -- */
// Image Upload
$options[] = array( 'title'             => __( 'Logo', 'dd_theme' ),
                    'description'       => 'Upload the Company Logo to be used instead of the Title and Tagline',
                    'section'           => 'title_tagline',
                    'id'                => 'theme_logo',
                    'default'           => '',
                    'option'            => 'image',
                    'sanitize_callback' => 'esc_url',
                    'type'              => 'control' );

$options[] = array( 'title'             => __( 'Navbar Logo', 'dd_theme' ),
                    'description'       => 'The Navbar Brand Logo for Mobile Menu',
                    'section'           => 'title_tagline',
                    'id'                => 'navbar_brand',
                    'default'           => '',
                    'option'            => 'image',
                    'sanitize_callback' => 'esc_url',
                    'type'              => 'control' );

/* Google Analytics -------------------------------------------------------------- */

$options[] = array( 'title'             => __( 'Google Analytics', 'dd_theme' ),
                    'description'       => __( 'Google Analytics', 'dd_theme' ),
                    'panel'             => '',
                    'id'                => 'theme_google_analytics',
                    'priority'          => 10,
                    'theme_supports'    => '',
                    'type'              => 'section' );

$options[] = array( 'title'             => __( 'Analytics Code', 'dd_theme' ),
                    'description'       => 'Enter the Google Analytics Tracking ID',
                    'section'           => 'theme_google_analytics',
                    'id'                => 'theme_ga_code',
                    'default'           => '',
                    'option'            => 'text',
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'control' );

/* / Google Analytics 					
---------------------------------------------------------------------------------------------------*/

/* ---------------------------------------------------------------------------------------------------
    Slider Options
--------------------------------------------------------------------------------------------------- */

$options[] = array( 'title'             => __( 'Slider Options', 'dd_theme' ),
                    'description'       => __( 'Slider Options', 'dd_theme' ),
                    'panel'             => '',
                    'id'                => 'theme_slider_options',
                    'priority'          => 15,
                    'theme_supports'    => '',
                    'type'              => 'section' );
$options[] = array( 'title'             => __( 'Slider Active', 'dd_theme' ),
                    'description'       => __('Check box to activate slideshow'),
                    'section'           => 'theme_slider_options',
                    'id'                => 'dd_slider_active',
                    'default'           => '1', // 1 for checked
                    'option'            => 'checkbox',
                    'sanitize_callback' => '',
                    'type'              => 'control' );

$options[] = array( 'title'             => __( 'Slider Delay', 'dd_theme' ),
                    'description'       => __( 'Enter the duration of each slide in miliseconds', 'dd_theme' ),
                    'section'           => 'theme_slider_options',
                    'id'                => 'dd_slider_delay',
                    'default'           => 7000,
                    'option'            => 'number',
                    'sanitize_callback' => '',
                    'type'              => 'control' );

$options[] = array( 'title'             => __( 'Slider Navs', 'dd_theme' ),
                    'description'       => __('Check box to show slider navs - dots'),
                    'section'           => 'theme_slider_options',
                    'id'                => 'dd_slider_navs',
                    'default'           => '1', // 1 for checked
                    'option'            => 'checkbox',
                    'sanitize_callback' => '',
                    'type'              => 'control' );

$options[] = array( 'title'             => __( 'Disable Slide Post Type', 'dd_theme' ),
                    'description'       => __('Check box to entirely remove the SLIDES from the Admin. This does not delete any existing slide data.'),
                    'section'           => 'theme_slider_options',
                    'id'                => 'dd_slider_cpt_enable',
                    'option'            => 'checkbox',
                    'sanitize_callback' => '',
                    'type'              => 'control' );

/* ---------------------------------------------------------------------------------------------------
    THEME OPTIONS Controls
--------------------------------------------------------------------------------------------------- */

$options[] = array( 'title'             => __( 'Theme Options', 'dd_theme' ),
                    'description'       => __( 'Additional Options for the Duck Diver Framework', 'dd_theme' ),
                    'panel'             => '',
                    'id'                => 'dd_theme_options',
                    'priority'          => 10,
                    'theme_supports'    => '',
                    'type'              => 'section' );
$options[] = array( 'title'             => __( 'Search in Menu', 'dd_theme' ),
                    'description'       => __( 'Show the Search Box in the Navigation Bar', 'dd_theme' ),
                    'section'           => 'dd_theme_options',
                    'id'                => 'navbar_search_toggle',
					'default'			=> '1',
                    'option'            => 'checkbox',
                    'sanitize_callback' => '',
                    'type'              => 'control' );
$options[] = array( 'title'             => __( 'Search in Header', 'dd_theme' ),
                    'description'       => __( 'Show the Search Box in the Header', 'dd_theme' ),
                    'section'           => 'dd_theme_options',
                    'id'                => 'header_search_toggle',
					'default'			=> '',
                    'option'            => 'checkbox',
                    'sanitize_callback' => '',
                    'type'              => 'control' );

$options[] = array ('title'             => __( 'Global Messsage', 'dd_theme' ),
                    'description'       => 'Enter the global message in header on the right',
                    'section'           => 'dd_theme_options',
                    'id'                => 'global_message',
                    'default'           => '',
                    'option'            => 'textarea',
                    'sanitize_callback' => 'esc_html',
                    'type'              => 'control' );	
$options[] = array ('title'             => __('Sidebar Position', 'dd_theme'),
                    'description'       => __('For pages with the sidebar in use, position the sidebar on the left or the right side of the content. This applies to the default template, and the blog single pages.', 'dd_theme'),
                    'section'           => 'dd_theme_options',
                    'id'                => 'sidebar_position',
                    'default'           => '2',
                    'option'            => 'radio',
                    'sanitize_callback' => '',
                    'choices'           => array(
                        '1' => __( 'Left', 'text-domain' ),
                        '2' => __( 'Right', 'text-domain' ),
                        'none' => __( 'No Sidebar', 'text-domain' ),
                    ),
                    'type'              => 'control'
                   );
					
/* Footer Section --
----------------------------------------------------------------------------------------------------*/			

$options[] = array( 'title'             => __( 'Footer Text', 'dd_theme' ),
                    'description'       => __( 'Footer Text', 'dd_theme' ),
                    'panel'             => '',
                    'id'                => 'theme_footer_text',
                    'priority'          => 200,
                    'theme_supports'    => '',
                    'type'              => 'section' );

$options[] = array( 'title'             => __( 'Footer Text', 'dd_theme' ),
                    'description'       => 'Enter the footer text/copyright message',
                    'section'           => 'theme_footer_text',
                    'id'                => 'footer_text',
                    'default'           => '',
                    'option'            => 'textarea',
                    'sanitize_callback' => 'esc_html',
                    'type'              => 'control' );


/* Footer Section --
----------------------------------------------------------------------------------------------------*/		

/* Blog Section --
----------------------------------------------------------------------------------------------------*/		

$default_blog = get_bloginfo( 'name' );

$options[] = array( 'title'             => __( 'Blog Options', 'dd_theme' ),
                    'description'       => __( 'Blog Options', 'dd_theme' ),
                    'panel'             => '',
                    'id'                => 'dd_blog_options',
                    'priority'          => 220,
                    'theme_supports'    => '',
                    'type'              => 'section' );

$options[] = array( 'title'             => __( 'Blog Title', 'dd_theme' ),
                    'description'       => 'Enter the Title of the Blog Page',
                    'section'           => 'dd_blog_options',
                    'id'                => 'dd_blog_title_h1',
                    'default'           => $default_blog . ' Blog',
                    'option'            => 'text',
                    'sanitize_callback' => 'esc_html',
                    'type'              => 'control' );


$options[] = array( 'title'             => __( 'Blog Featured Image', 'dd_theme' ),
                    'description'       => 'For the blog feed - how do you want the featured image?',
                    'section'           => 'dd_blog_options',
                    'id'                => 'dd_featured_blog_image',
                    'default'           => 'large',
                    'option'            => 'radio',
                    'sanitize_callback' => '',
                    'choices'           => array(
                        'large' => __( 'Large', 'dd_theme' ),
                        'small' => __( 'Small', 'dd_theme' ),
                        ),
                    'type'              => 'control' );

$options[] = array( 'title'             => __( 'Blog Excerpt Length', 'dd_theme' ),
                    'description'       => '',
                    'section'           => 'dd_blog_options',
                    'id'                => 'dd_blog_excerpt_length',
                    'default'           => 55,
                    'option'            => 'number',
                    'sanitize_callback' => '',
                    'input_attrs'       => array(
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ),
                    'type'              => 'control' );

$options[] = array( 'title'             => __( 'Read More Text', 'dd_theme' ),
                    'description'       => 'Enter the Text for the read more button after the blog excerpt',
                    'section'           => 'dd_blog_options',
                    'id'                => 'dd_read_more_text',
                    'default'           => 'Read More',
                    'option'            => 'text',
                    'sanitize_callback' => 'esc_html',
                    'type'              => 'control' );

$options[] = array( 'title'             => __( 'Single Post Featured Image', 'dd_theme' ),
                    'description'       => 'For the single post how do you want the featured image to appear?',
                    'section'           => 'dd_blog_options',
                    'id'                => 'dd_featured_post_image',
                    'default'           => 'large',
                    'option'            => 'radio',
                    'sanitize_callback' => '',
                    'choices'           => array(
                        'large' => __( 'Large', 'dd_theme' ),
                        'small' => __( 'Small', 'dd_theme' ),
                        'none'  => __( 'None', 'dd_theme' ),
                        ),
                    'type'              => 'control' );


/* Footer Section --
----------------------------------------------------------------------------------------------------*/		
