<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Duck Diver Custom
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="<?php echo get_theme_mod('android_theme_color');?>">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="<?php echo get_theme_mod('android_theme_color');?>">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo get_theme_mod('android_theme_color');?>">

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<script src="//cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php if( !current_user_can( 'manage_options' ) ){ // Exclude Admins from Google Analytics
    if( get_theme_mod( 'theme_ga_code' ) ) : ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_html( get_theme_mod( 'theme_ga_code' ) ); ?>"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());

              gtag('config', '<?php echo esc_html( get_theme_mod( 'theme_ga_code' ) ); ?>');
            </script>
    <?php endif;
}; ?>
<?php do_action('dd_before_header');?>
<div id="page" class="site container-fluid">
    <div class="row">

        <header id="masthead" class="<?php echo apply_filters('dd_masthead_classes', 'site-header');?>" role="banner">
            <?php get_template_part( 'page-sections/header', 'section' ); ?>
        </header><!-- #masthead -->
<?php do_action('dd_after_header');?>
        <?php if (is_front_page() && get_theme_mod('dd_slider_active')) get_template_part( 'template-parts/content', 'slider' ); ?>
<?php do_action('dd_before_main_content');?>
