<?php
/**
 * The template for displaying all single posts.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Duck Diver Framework 1.1
 */

$sbpos = dd_get_sidebar_position();

get_header(); ?>
<div class="container" id="content-wrap">
	<div class="row">
		<main id="main" class="single-main <?php echo esc_attr( $sbpos['main'] ); ?>" role="main">
			<?php
			echo '<div class="blog-breadcrumb">';
			echo wp_kses_post( dd_breadcrumbs( get_the_ID() ) );
			echo '</div>';
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
				// Previous/next post navigation.
				$next_post     = ( ! empty( get_next_post() ) ) ? get_next_post() : null;
				$next_thumb    = ( null !== $next_post ) ? get_the_post_thumbnail( $next_post->ID, 'medium' ) : '';
				$previous_post = get_previous_post();
				$post_nav_args = array(
					'screen_reader_text' => __( 'Continue Reading' ),
					'next_text'          => '<div class="post-nav-item">
                                                <span class="screen-reader-text">' . __( 'Next post:', 'dd-theme' ) . '</span>' . $next_thumb . '<span class="post-title">%title</span></div>',
				);
				if ( $previous_post ) {
					$post_nav_args['prev_text'] = '<div class="post-nav-item">
                                                <span class="screen-reader-text">' . __( 'Previous post:', 'dd-theme' ) . '</span>
                                                ' . get_the_post_thumbnail( $previous_post->ID, 'medium' ) . '<span class="post-title">%title</span></div>';
				}
				if ( $next_post || $previous_post ) {
					echo '<div class="post-nav">';
					echo '<div class="h3 read-more">Read More</div>';
					the_post_navigation(
						$post_nav_args
					);
					echo '</div>';
				}
			endwhile; // End of the loop.
			?>
		</main><!-- #main -->
		<?php if ( 'true' === $sbpos['showsb'] ) : ?>
			<aside class="<?php echo esc_attr( $sbpos['sb'] ); ?>" id="sidebar">
				<?php get_sidebar(); ?>
			</aside>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
