<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Duck Diver Framework 1.1
 */

$sbpos = dd_get_sidebar_position();
get_header(); ?>
<div class="container" id="content-wrap">
	<div class="row">
		<main id="single" class="single-main <?php echo esc_attr( $sbpos['main'] ); ?>" role="main">

			<?php
			while ( have_posts() ) :
				the_post();
				?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
		<?php if ( 'true' === $sbpos['showsb'] ) : ?>
			<aside class="<?php echo esc_attr( $sbpos['sb'] ); ?>" id="sidebar">
				<?php get_sidebar(); ?>
			</aside>
		<?php endif; ?>
	</div>   
</div>
<?php get_footer(); ?>
