<?php
/**
* Template Name: Home Page
*
* @package Duck Diver Framework 1.1
*/
get_header();

get_template_part( 'template-parts/content', 'slider' ); ?>


	<div id="homepage-content" class="container-fluid">
		<main id="main" class="site-main row" role="main">
            <div class="col">
                <?php while ( have_posts() ) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    
                    <div class="entry-content">
                        <?php the_content(); ?>
                        <?php
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dd_theme' ),
                                'after'  => '</div>',
                            ) );
                        ?>
                    </div><!-- .entry-content -->
                    
                    </article><!-- #post-## -->
                <?php endwhile; // End of the loop. ?>
            </div>

		</main><!-- #main -->
	</div>
<?php if (get_theme_mod('slider_active')) {
			$delay = get_theme_mod('slider_delay'); }
		else {
			$delay = 'false';
		}
		?>


<script type="text/javascript">
	// Carousel Init
	jQuery(document).ready(function($) {
		$('.carousel').carousel({
			interval: <?php echo $delay;?>		
		});
	});
</script>
<?php get_footer(); ?>
