<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Duck Diver Custom
 */

get_header();

?>
	<div class="container-fluid">
		<main id="main" class="row" role="main">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<?php if ( have_posts() ) : ?>
						<header>
							<?php if ( get_theme_mod( 'dd_blog_title_h1' ) ) : ?>
								<h1 class="page-title blog-title mt-4 mb-3"><?php echo esc_html( get_theme_mod( 'dd_blog_title_h1' ) ); ?></h1>
							<?php else : ?>
								<h1><?php echo esc_html( get_bloginfo() ) . ' Blog'; ?></h1>
							<?php endif; ?>
						</header>
						<!-- .page-header -->
						<div class="row align-items-center mb-md-5" id="featured-post">
							<?php
							$latest_post = wp_get_recent_posts(
								array(
									'numberposts' => 1,
									'post_status' => 'publish',
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
									echo wp_kses_post( dd_custom_excerpt( get_the_ID() ) );
								?>
								<p class="text-center mt-3"><a href="<?php echo esc_url( get_the_permalink( $_post_id ) ); ?>" class="btn btn-primary d-inline-block">Read More</a></p>
							</div>
						</div>
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
											</a></article>
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
		if ( ! get_theme_mod( 'dd_use_infinite_scroll' ) ) {
			echo '<div class="d-flex justify-content-center">';
				understrap_pagination();
			echo '</div>';
		}
		?>
	</div>
<?php
do_action( 'dd_blog_footer' );
get_footer();
