<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

					<?php
					$the_type = get_post_type( get_the_ID() );
					if ( 'post' !== $the_type ) {
						get_template_part( 'template-parts/content-single', $the_type );
					} else {
						get_template_part( 'template-parts/content', 'single' );
					}

					?>

					<?php the_post_navigation(); ?>

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
