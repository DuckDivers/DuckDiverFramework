<?php
/**
 * The template for displaying archive pages.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Duck Diver Framework 1.1
 */

get_header();
?>
<div class="container-fluid">
	<main id="main" class="row" role="main">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<?php if ( have_posts() ) : ?>
						<header class="page-header">
							<?php
							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="taxonomy-description">', '</div>' );
							?>
						</header>
						<!-- .page-header -->
						<?php if ( 'post' === get_post_type() ) : ?>
						<div class="row align-items-center mb-md-5" id="featured-post">
							<?php
							$latest_post = wp_get_recent_posts(
								array(
									'numberposts' => 1,
									'post_status' => 'publish',
									'category'    => get_query_var( 'cat' ),
								)
							);
							$_post_id    = $latest_post[0]['ID'];
							?>
							<div class="col-md-6">
								<a href="<?php echo esc_url( get_the_permalink( $_post_id ) ); ?>" class="blog-featured-image">
									<?php
									dd_get_default_blog_image( $_post_id );
									?>
								</a>
							</div>
							<div class="col-md-6">
								<h2 class="text-center"><?php echo esc_attr( $latest_post[0]['post_title'] ); ?></h2>
								<?php
								echo wp_kses_post( dd_custom_excerpt( $_post_id ) );
								?>
								<p class="text-center mt-3"><a href="<?php echo esc_url( get_the_permalink( $_post_id ) ); ?>" class="btn btn-primary d-inline-block">Read More</a></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="w-100 py-5">
			<div class="container">
				<div class="row">
					<div class="col-12" id="infinite">
						<div class="row">
							<?php
							while ( have_posts() ) :
								the_post();
								?>
								<div class="col-md-4 my-3">
									<article id="post-<?php the_ID(); ?>" <?php post_class( 'post__holder' ); ?>><a
												href="<?php the_permalink(); ?>" class="archive-link">
											<?php
											dd_get_default_blog_image( get_the_ID() );
											?>
											<h3 class="card__title">
												<?php the_title(); ?>
											</h3>
											<!-- Post Content -->
											<div class="post_content">
												<div class="excerpt">
													<?php
													echo wp_kses_post( dd_custom_excerpt( get_the_ID() ) );
													?>
												</div>
											</div>
										</a>
									</article>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				</div>
			</div>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php endif; ?>
		</div>
	</main>
	<?php
	if ( ! get_theme_mod( 'dd_infinite_scroll' ) ) {
		echo '<div class="d-flex justify-content-center">';
		understrap_pagination();
		echo '</div>';
	}
	?>
</div>
<?php
do_action( 'dd_blog_footer' );
get_footer();
