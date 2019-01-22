<?php
/**
 * Template Name: Home Page
 *
 * @package Duck Diver Framework 1.1
 */
get_header();
?>
<main id="main" class="home-main col p-0" role="main">

    <?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <div class="entry-content">
            <?php the_content(); ?>
            <?php
            wp_link_pages( array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dd_theme' ),
                'after' => '</div>',
            ) );
            ?>
        </div>
        <!-- .entry-content -->

    </article>
    <!-- #post-## -->
    <?php endwhile; // End of the loop. ?>
</main>

<?php do_action('dd_homepage_scripts');?>

<?php get_footer(); ?>
