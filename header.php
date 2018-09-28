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
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

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
    <div id="page" class="site container-fluid">
        <div class="row">
            <header id="masthead" class="site-header col-12" role="banner">
				<?php get_template_part( 'page-sections/header', 'section' ); ?>
			</header><!-- #masthead -->
         <?php if (is_front_page()){
            echo '<div id="content-home" class="site-content col-12">';
            }    
            else {
                echo '<div id="content" class="site-content col-12">';
            }
	     
