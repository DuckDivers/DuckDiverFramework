<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Duck Diver Framework 1.1
 */
$sbpos = dd_get_sidebar_position();
get_header(); ?>
<div class="container" id="content-wrap">
	<div class="row">
		<main id="main" class="single-main <?php echo esc_attr( $sbpos['main'] ); ?>" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'dd_theme' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<?php
					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'template-parts/content', 'search' );
					?>

				<?php endwhile; ?>

				<?php understrap_pagination(); ?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

		</main><!-- #main -->
		<?php if ( 'true' === $sbpos['showsb'] ) : ?>
			<aside class="<?php echo esc_attr( $sbpos['sb'] ); ?>" id="sidebar">
				<?php get_sidebar(); ?>
			</aside>
		<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
