<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Duck Diver Custom
 */

get_header(); ?>

		<main id="main" class="site-main col-md-9" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
                    <h2 style="font-size: 200px">Error 404</h2>
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'dd_theme' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of these links or try a search?', 'dd_theme' ); ?></p>

					

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
        <aside class="col-md-3" id="sidebar">
            <?php if(get_theme_mod('navbar_search_toggle') != 1){get_search_form();} ?>

					<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

					<?php if ( dd_theme_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>
					<div class="widget widget_categories">
						<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'dd_theme' ); ?></h2>
						<ul>
						<?php
							wp_list_categories( array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							) );
						?>
						</ul>
					</div><!-- .widget -->
					<?php endif; ?>

					<?php
						/* translators: %1$s: smiley */
						$archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'dd_theme' ), convert_smilies( ':)' ) ) . '</p>';
						the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
					?>

					<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
        </aside>

<?php get_footer(); ?>