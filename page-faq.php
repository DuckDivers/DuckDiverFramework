<?php
/**
 * Template Name: FAQ Page
 * @package Duck Diver Framework 1.1
 */
get_header();
?>
<div class="container" id="content-wrap">
    <div class="row">
        <main id="main" class="site-main col-12" role="main">

            <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'template-parts/content', 'faq' ); ?>


            <?php endwhile; // End of the loop. ?>

        </main>
        <!-- #main -->
    </div>
</div>
<?php get_footer(); ?>