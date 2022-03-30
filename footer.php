<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Duck Diver Custom
 */

?>
<?php do_action( 'dd_before_footer' ); ?>
<footer id="colophon" class="site-footer col-12" role="contentinfo">
	<div class="container">
		<div class="row footer-widgets">
			<div class="col-md-4" data-type="dynamic-sidebar" data-sidebar-id="footer-sidebar-1">
				<?php dynamic_sidebar( 'footer-sidebar-1' ); ?>
			</div>
			<div class="col-md-4" data-type="dynamic-sidebar" data-sidebar-id="footer-sidebar-2">
				<?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
			</div>
			<div class="col-md-4" data-type="dynamic-sidebar" data-sidebar-id="footer-sidebar-3">
				<?php dynamic_sidebar( 'footer-sidebar-3' ); ?>
			</div>
		</div>
		<div id="footer-text" class="row footer-text">
			<div class="col-12">
				<?php $myfooter_text = get_theme_mod( 'footer_text' ); ?>
				<?php if ( $myfooter_text ) { ?>
					<?php echo wp_kses_post( get_theme_mod( 'footer_text' ) ); ?>
				<?php } else { ?>
					&copy;
					<?php echo esc_attr( date( 'Y' ) ); //phpcs:ignore ?>
					<span class="footer-logo">
					<?php bloginfo( 'name' ); ?>
				</span>
				<?php } ?> &bull;
				<?php esc_html_e( 'Website Created by', 'dd_theme' ); ?> <a href="https://www.duckdiverllc.com" target="_blank" class="duck"><i class="dd-duck-icon" style="vertical-align:middle"></i>Duck Diver Marketing</a>
			</div>
		</div>
	</div>
</footer> <!-- #colophon -->
<?php do_action( 'dd_after_footer' ); ?>
</div> <!-- Outer Row -->
</div> <!-- #page -->
<div id="back-top-wrapper" class="visible-desktop">
	<p id="back-top">
		<a href="#top"><span></span></a>
	</p>
</div>
<?php wp_footer(); ?>

</body>
</html>
