<?php
/**
 * Template part for displaying page content in page-faq.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * Uncomment sections to use for categories
 *
 * @package Duck Diver Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<?php the_content(); ?>
		
        <?php 
		
		/* Loop Name: Faq 
		*
		* uncomment below for category sort
		*		
		*/ 
		
		// Get the FAQ Category 
	
		/* $count =1; //begin the outer loop
		$faq_category = $wpdb->get_results("SELECT `wp_terms`.`term_id`, `wp_terms`.`name`, `wp_terms`.`slug`
										FROM `wp_terms`
										WHERE `wp_terms`.`term_id` IN (SELECT `wp_term_taxonomy`.`term_taxonomy_id` 
										FROM `wp_term_taxonomy`
										WHERE `wp_term_taxonomy`.`taxonomy` = 'faq_category')
										ORDER BY `wp_terms`.`term_id`=6 DESC, `wp_terms`.`term_id`=3 DESC, `wp_terms`.`name` ASC");
		
		if ($faq_category){
		
		foreach ($faq_category as $theQuery){
			
			$thecat = $theQuery->slug;
			$catname = $theQuery->name; */
			
					//query
					$args = array(
						'post_type'        	=> 'faq',
						'showposts'        	=> -1,
						'suppress_filters' 	=> $suppress_filters,
						//'faq_category'		=> $thecat,
						);
					$faq_query = new WP_Query( $args ); ?>
			
					<?php if ( $faq_query->have_posts() ) : ?>
					<?php if (isset($catname)) echo "<h2> {$catname} </h2>"; ?>
					<div id="accordion" class="panel-group">
					<?php while ( $faq_query->have_posts() ) : $faq_query->the_post();
						$title =  get_the_title();
						$content = get_the_content();
							echo do_shortcode('[accordion title=" '.$title.' "] ' .$content. '[/accordion]'); ?>
					<?php endwhile; ?>
					
					</div>
				<?php endif;
		//}
	//}

	wp_reset_postdata();
	?>   
	</div><!-- .entry-content -->

</article><!-- #post-## -->
