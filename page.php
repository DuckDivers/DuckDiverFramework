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

get_header(); ?>
<div class="container" id="content-wrap">
    <div class="row">
        <main id="single" class="single-main col-md-9" role="main">
            
            <?php while ( have_posts() ) : the_post(); ?>
            
            <?php get_template_part( 'template-parts/content', 'page' ); ?>
            
            <?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>
            
            <?php endwhile; // End of the loop. ?>
            
        </main><!-- #main -->
        <aside class="col-md-3" id="sidebar">
            <?php get_sidebar();?>
        </aside>
    </div>   
</div>
<?php get_footer(); ?>