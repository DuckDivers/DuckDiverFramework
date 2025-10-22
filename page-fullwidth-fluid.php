<?php
/**
 * Template Name: Edge to Edge Page
 *
 * @package Duck Diver Framework 1.1
 */


get_header();
?>

<div id="content-wrap" class="container-fluid full-width-container">
	<div class="row">
		<main id="main" class="site-main col p-0" role="main">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<?php get_template_part( 'template-parts/content', 'page' ); ?>
			<?php endwhile; // End of the loop. ?>
		</main><!-- #main -->
	</div>
</div>

<?php
get_footer();
