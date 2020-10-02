<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Duck Diver Theme
 */

$featured_size = (get_theme_mod('dd_featured_post_image')) ? get_theme_mod('dd_featured_post_image') : 'none' ;
if ($featured_size === 'large') {
    $featured_class = 'col-12';
    }
else if ($featured_size === 'small'){
    $featured_class = 'col-md-6 offset-md-3';
    }
else {
    $featured_class='d-none';}
    $post_class = ($featured_size !== 'small') ? 'col-12' : 'col-md-8';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (has_post_thumbnail()){
        echo '<div class="row"><figure class="featured-thumbnail thumbnail '.$featured_class.'">'.get_the_post_thumbnail($post->ID, 'blog-hero-image', array('class' => 'd-block mx-auto')).'</figure></div>';
		  }
    ?>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php dd_theme_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dd_theme' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php dd_theme_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
