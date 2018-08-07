<?php
/**
* Template Name: FAQ Page
*/
get_header(); ?>

<main id="main" class="site-main col-12" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'template-parts/content', 'faq' ); ?>


	<?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php get_footer(); ?>
