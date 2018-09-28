<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Duck Diver Framework 1.1
 */

get_header(); ?>
<div class="container" id="content-wrap">
    <div class="row">
        <main id="single" class="single-main col-md-9" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="entry-content">
				 <?php /*
					  $terms = wp_get_post_terms($post->ID, 'faq_category', array("fields" => "names"));
						echo '<h3>FAQ Category: ' . $terms['0'] . '</h3>'; */?>				
                <h2><?php the_title();?></h2>
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

        </main><!-- #main -->
        <aside class="col-md-3" id="sidebar">
            <?php get_sidebar();?>
        </aside>
    </div>   
</div>
<?php get_footer(); ?>